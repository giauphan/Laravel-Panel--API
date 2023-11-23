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
    public function __invoke(Request $request)
    {
        if ($request->has('id')) {
            $file = null;
            $id = $request->input('id');
            if ($request->has('DatabaseID')) {
                $migration = MultiDatabase::findOrFail($request->input('DatabaseID'));
                MultiMigrationService::switchToMulti($migration);
                $file = FileData::query()
                    ->where('has_business_code', 'like', "%$id%")->firstOrFail();
                MultiMigrationService::disconnectFromMulti();
            } else {
                $file = FileData::query()
                    ->where('has_business_code', 'like', "%$id%")->firstOrFail();
            }
            if ($file) {
                $decodedData = base64_decode($file->DataBcdn);
                $filename = ! empty($file->business_code) ? $file->business_code : $file->has_business_code;
                $response = Response::make($decodedData, 200);
                $response->header('Content-Disposition', "attachment; filename= $filename.$file->type_data");
                $response->header('Content-Type', 'application/pdf');

                return $response;
            }

            return abort(404);
        }
    }
}
