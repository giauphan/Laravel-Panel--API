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
                $headers = $this->getHeaders($file);

                return Response::make($decodedData, 200, $headers);
            }
        }
    }

    private function findFileById(Request $request, $id)
    {
        if ($request->has('DatabaseID')) {
            $migration = MultiDatabase::findOrFail($request->input('DatabaseID'));
            MultiMigrationService::switchToMulti($migration);
        }
        $fileQuery = FileData::query()->where('has_business_code', 'like', "%$id%");
        $file = $fileQuery->firstOrFail();

        if ($request->has('DatabaseID')) {
            MultiMigrationService::disconnectFromMulti();
        }

        return $file;
    }

    private function getHeaders($file)
    {
        $filename = $this->getFilename($file);
        $headers = [
            'Content-Disposition' => $this->getContentDisposition($file, $filename),
            'Content-Type' => $this->getContentType($file),
        ];

        return $headers;
    }

    private function getContentDisposition($file, $filename)
    {
        $disposition = $file->type_data === 'image/png' || $file->type_data === 'application/pdf'
            ? 'inline; filename="'.$filename.'"'
            : 'inline; filename="'.$filename.'.pdf"';

        return $disposition;
    }

    private function getContentType($file)
    {
        $contentTypes = [
            'image/png' => 'image/png',
            'image/svg' => 'image/svg+xml',
            'application/pdf' => 'application/pdf',
            'png' => 'image/png',
        ];

        return $contentTypes[$file->type_data == 'pdf' ? 'application/pdf' : $file->type_data] ?? 'application/octet-stream';
    }

    private function getFilename($file)
    {
        return $file->business_code ?: $file->has_business_code;
    }
}
