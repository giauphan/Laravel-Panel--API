<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FileData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileDestroy extends Controller
{

    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_id' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ], 422);
        }
        $id = $request->input('file_id');

        $file = FileData::find($id);

        if (!$file) {
            return response()->json([
                'status' => 404,
                'error' => 'file not Found',
            ]);
        }

        $file->delete();

        return response()->json([
            'status' => 200,
        ]);
    }
}
