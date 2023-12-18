<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadFileRequest;
use App\Models\MultiDatabase;
use App\Settings\SettingServerStorage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UploadFile extends Controller
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

    public function index(UploadFileRequest $request)
    {
        $validator = $request->validated();

        $fileApi = new FileUploadController();

        $file = $request->file('files');
        $fileContents = file_get_contents($file->path());
        $fileName = $validator['file_name'] ?? $file->getClientOriginalName();
        $fileType = $file->getClientMimeType();

        $encodedData = base64_encode($fileContents);

        $hashedFileName = Hash::make($fileName);

        $totalSize = $this->calculateTotalSize();

        $record = null;
        $record = $totalSize + strlen($encodedData) / (1024 * 1024) <= $this->limitDatabaseMd
            ? $fileApi->handleSingleDatabase($fileName, $hashedFileName, $encodedData, $fileType)
            : $fileApi->handleMultiDatabase($fileName, $hashedFileName, $encodedData, $this->databaseNameStorage, $fileType);

        if ($record == null) {
            return back()->with('error', 'The files have a duplicate business_code.');
        }

        $this->generateShareLink($record);

        return back()->with('succes', 'upload success');
    }

    private function calculateTotalSize()
    {
        $migration = MultiDatabase::where('status', 1)->first();

        return DB::table('information_schema.tables')
            ->selectRaw('ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb')
            ->where('table_schema', $migration ? $migration->database : config('database.connections.mysql.database'))
            ->first()->size_mb;
    }

    private function generateShareLink($record)
    {
        $share = route('preview', ['id' => $record->has_business_code]);
        $migration = MultiDatabase::where('status', 1)
            ->orderBy('id', 'desc')
            ->first();

        if ($migration) {
            $share .= '&&DatabaseID='.$migration->id;
        }

        return $share;
    }
}
