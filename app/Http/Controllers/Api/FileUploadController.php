<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FileData;
use App\Models\MultiDatabase;
use App\Service\MultiMigrationService;
use App\Settings\SettingServerStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    private string $databaseNameStorage;

    private string $limitDatabaseMd;

    public function __construct()
    {
        $settings = new SettingServerStorage();
        if ($settings->server_name == 'storage') {
            $this->databaseNameStorage = $settings->database_name;
            $this->limitDatabaseMd = $settings->limit_database_mb;
        }
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_name' => ['string', 'max:255'],
            'file_contents' => ['string'],
            'file_type' => ['string'],
            'files' => ['file', 'mimes:pdf,png,jpg,svg'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ], 422);
        }

        if ($request->hasFile('files') && $request->file('files')->isValid()) {
            $file = $request->file('files');
            $fileContents = file_get_contents($file->path());
            $fileName = $file->getClientOriginalName();
            $fileType = $file->getClientMimeType();
        } elseif ($request->filled('file_contents')) {
            // Option 2: Send file content as base64
            $fileContents = base64_decode($request->input('file_contents'));
            $fileName = $request->input('file_name') ?? 'file';
            $fileType = $request->input('file_type') ?? 'application/octet-stream';
        } else {
            return response()->json([
                'status' => 422,
                'errors' => ['files' => ['The files field is required.']],
                'message' => 'Validation failed',
            ], 422);
        }

        $encodedData = base64_encode($fileContents);

        if (! $encodedData) {
            return response()->json([
                'status' => 400,
                'errors' => 'The files or file base64 field is required.',
                'message' => 'Validation failed',
            ], 400);
        }
        $hashedFileName = Hash::make($fileName);

        $migration = MultiDatabase::where('status', 1)->first();
        $totalSize = DB::table('information_schema.tables')
            ->selectRaw('ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb')
            ->where('table_schema', $migration ? $migration->database : config('database.connections.mysql.database'))
            ->first()->size_mb;

        $record = null;
        $record = $totalSize + strlen($encodedData) / (1024 * 1024) <= $this->limitDatabaseMd
            ? $this->handleSingleDatabase($fileName, $hashedFileName, $encodedData, $fileType)
            : $this->handleMultiDatabase($fileName, $hashedFileName, $encodedData, $this->databaseNameStorage, $fileType);

        $share = route('preview', ['id' => $record->has_business_code]);
        $migration = MultiDatabase::where('status', 1)
            ->orderBy('id', 'desc')
            ->first();

        if ($migration) {
            $share .= '&&DatabaseID='.$migration->id;
        }

        return response()->json([
            'id' => $record->id,
            'has_business_code' => $record->has_business_code,
            'url_preview' => $share,
        ]);
    }

    private function handleMultiDatabase($fileName, $hashedFileName, $encodedData, $databaseName, $fileType)
    {
        $record_id = null;
        // 1. Get the latest record
        $newRecord = MultiDatabase::orderBy('id', 'desc')->first();
        // 2. Update all records with status = 1 to status = 0
        MultiDatabase::where('status', 1)->update(['status' => 0]);

        // 3. Use the obtained id to construct the new database name
        $newDatabaseName = $databaseName.'_bcdnscanner_'.($newRecord ? ($newRecord->id + 1) : 1);

        // 4. Ensure the new database name is unique
        $database_multi = MultiDatabase::updateOrCreate(
            ['database' => $newDatabaseName],
            [
                'host' => '127.0.0.1',
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'port' => env('DB_PORT'),
                'status' => 1,
            ]
        );

        Artisan::call('create:db', ['databaseName' => $database_multi->database]);

        // 5. Switch to the newly created database
        $migration = MultiDatabase::where('status', 1)->first();
        MultiMigrationService::switchToMulti($migration);

        // 6. Run the multi migration command
        Artisan::call('multi:migration');

        // 7. Update or create data in the DataBcdn table
        $record_id = FileData::updateOrCreate(
            ['business_code' => $fileName],
            [
                'has_business_code' => (string) $hashedFileName,
                'Data' => $encodedData,
                'type_data' => $fileType,
            ]
        );

        // 8. Disconnect from the multi database
        MultiMigrationService::disconnectFromMulti();

        return $record_id;
    }

    private function handleSingleDatabase($fileName, $hashedFileName, $encodedData, $fileType)
    {
        $record_id = null;
        $migration = MultiDatabase::where('status', 1)->first();

        if ($migration) {
            MultiMigrationService::switchToMulti($migration);
            $record_id = FileData::updateOrCreate(
                ['business_code' => $fileName],
                [
                    'has_business_code' => (string) $hashedFileName,
                    'Data' => $encodedData,
                    'type_data' => $fileType,
                ]
            );

            MultiMigrationService::disconnectFromMulti();
        } else {
            $record_id = FileData::updateOrCreate(
                ['business_code' => $fileName],
                [
                    'has_business_code' => (string) $hashedFileName,
                    'Data' => $encodedData,
                    'type_data' => $fileType,
                ]
            );
        }

        return $record_id;
    }
}
