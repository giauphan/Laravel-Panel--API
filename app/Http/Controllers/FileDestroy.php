<?php

namespace App\Http\Controllers;

use App\Models\FileData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileDestroy extends Controller
{
    /**
     * Handle the incoming request.
     */
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

        $file = FileData::findOrFail($id);
        $file->delete();

        return response()->json([
            'status' => 200
        ]);
    }
}
