<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\FileData;
use App\Models\MultiDatabase;
use App\Service\MultiMigrationService;
use Illuminate\Http\Request;

class FileViewDriverController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index(Request $request)
    {
        $filesByDatabase = collect();
        $database_name = MultiDatabase::all();

        foreach ($database_name as $key => $database) {
            $filesByDatabase[$key] = [
                [
                    'business_code' => $database->database,
                    'has_database_name' => $database->has_database_name,
                    'type_data' => 'folder',
                    'created_at' => $database->created_at,
                ],
            ];
        }

        return view('storage.index', [
            'storages' => $filesByDatabase,
        ]);
    }

    public function show(string $folder)
    {
        $filesByDatabase = collect();
        $database_name = MultiDatabase::query()
            ->where('has_database_name', $folder)->first();

        MultiMigrationService::switchToMulti($database_name);
        $files = collect(FileData::query()->Limit(100)->get());
        $filesByDatabase = $files->map(function ($value) {
            return [
                [
                    'business_code' => $value->business_code,
                    'type_data' => $value->type_data,
                    'created_at' => $value->created_at,
                ],
            ];
        })->toArray();
        MultiMigrationService::disconnectFromMulti();

        return view('storage.index', [
            'storages' => $filesByDatabase,
        ]);
    }
}
