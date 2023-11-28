<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\FileUploadController;
use App\Models\MultiDatabase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FileUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = 'file:upload {filename} {fileType} {fileContent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle()
    {

        $fileuploadcontroller = new FileUploadController();
        $limitDatabaseMd = 100;
        // Previous code
        $filename = $this->argument('filename');
        $fileType = $this->argument('fileType');
        $fileContent = $this->argument('fileContent');

        // New code
        if ($filename == null || $fileType == null || $fileContent == null) {
            $this->error('Missing required arguments: filename, fileType, fileContent');

            return;
        }

        $fileName = $filename;
        $fileType = $fileType;
        $fileContents = file_get_contents($fileContent);

        $encodedData = base64_encode($fileContents);
        $hashedFileName = Hash::make($fileName);

        $migration = MultiDatabase::where('status', 1)->first();
        $totalSize = DB::table('information_schema.tables')
            ->selectRaw('ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb')
            ->where('table_schema', $migration ? $migration->database : config('database.connections.mysql.database'))
            ->first()->size_mb;

        $record = null;
        $record = $totalSize + strlen($encodedData) / (1024 * 1024) <= $limitDatabaseMd
            ? $fileuploadcontroller->handleSingleDatabase($fileName, $hashedFileName, $encodedData, $fileType)
            : $fileuploadcontroller->handleMultiDatabase($fileName, $hashedFileName, $encodedData, $fileType);

        if ($record == null) {
            $this->error('Duplicate record');

            return;
        }

        $share = route('preview', ['id' => $record->has_business_code]);
        $migration = MultiDatabase::where('status', 1)
            ->orderBy('id', 'desc')
            ->first();

        if ($migration) {
            $share .= '&&DatabaseID='.$migration->id;
        }
        dump($share);
    }
}
