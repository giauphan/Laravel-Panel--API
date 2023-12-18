<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [

            'files' => ['required', 'file', 'mimes:pdf,png,jpg,svg', 'max:2048'],
        ];
    }
}
