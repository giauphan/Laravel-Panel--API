<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileViewResource;
use App\Models\FileData;
use App\Models\MultiDatabase;
use App\Service\MultiMigrationService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class FileViewDriverController extends Controller
{
    public function index(Request $request)
    {
        $filesByDatabase = collect();
        $database_name = MultiDatabase::all();

        $filesByDatabase = $database_name->map(function ($database) {
            return [
                'business_code' => $database->database,
                'has_database_name' => $database->has_database_name,
                'type_data' => 'folder',
                'created_at' => Carbon::parse($database->created_at)->format('--M d.Y'),
            ];
        });

        $perPage = 10; // Set your desired items per page
        $currentPage = request('page', 1);
        $paginatedData = array_slice($filesByDatabase->toArray(), ($currentPage - 1) * $perPage, $perPage);
        $filesByDatabase = new LengthAwarePaginator($paginatedData, count($filesByDatabase), $perPage, $currentPage, ['path' => $request->url()]);

        return view('storage.index', [
            'storages' => $filesByDatabase,
        ]);
    }

    public function show(string $folder, Request $request)
    {

        $preview = $request->input('preview');
        $database_name = MultiDatabase::query()
            ->where('has_database_name', $folder)->first();
        $database_id = $database_name->id;

        MultiMigrationService::switchToMulti($database_name);
        $files = FileData::query()
            ->select('business_code', 'has_business_code', 'type_data')
            ->paginate()
            ->withQueryString();

        $preview_file = FileData::query()
            ->when($preview, function (Builder $query) use ($preview) {
                $query->where('has_business_code', $preview);
            })
            ->firstOrFail();

        MultiMigrationService::disconnectFromMulti();

        return view('storage.index', [
            'storages' => FileViewResource::collection($files),
            'preview' => new FileViewResource($preview_file),
        ]);
    }
}
