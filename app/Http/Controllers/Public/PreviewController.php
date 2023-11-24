<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FileData;
use App\Models\MultiDatabase;
use App\Service\MultiMigrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PreviewController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('id')) {
            $id = $request->input('id');
            $file = $this->findFileById($request, $id);

            if ($file) {
                $decodedData = base64_decode($file->Data);

                $filename = $this->getFilename($file);
                if ($file->type_data === 'image/png' || $file->type_data === 'application/pdf') {
                    $headers = [
                        'Content-Disposition' => 'inline; filename="'.$filename.'"',
                    ];
                } else {
                    $headers = [
                        'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"',
                    ];
                }

                if ($file->type_data === 'image/png' || $file->type_data == "png") {
                    $headers['Content-Type'] = 'image/png';
                } elseif ($file->type_data === 'image/svg') {
                    $headers['Content-Type'] = 'image/svg+xml';
                } else {
                    $headers['Content-Type'] = 'application/pdf';
                }

                return Response::make($decodedData, 200, $headers);
            }

            // return abort(404);
        }
    }

    private function findFileById(Request $request, $id)
    {

        if ($request->has('DatabaseID')) {
            $migration = MultiDatabase::findOrFail($request->input('DatabaseID'));
            MultiMigrationService::switchToMulti($migration);
            $fileQuery = FileData::query()->where('has_business_code', 'like', "%$id%");
            $file = $fileQuery->firstOrFail();

        } else {
            $fileQuery = FileData::query()->where('has_business_code', 'like', "%$id%");
            $file = $fileQuery->firstOrFail();
        }

        return $file;
    }

    private function getFilename($file)
    {
        return ! empty($file->business_code) ? $file->business_code : $file->has_business_code;
    }
}
