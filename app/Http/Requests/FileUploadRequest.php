<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file_name' => ['string', 'max:255'],
            'file_contents' => ['string'],
            'file_type' => ['string'],
            'file' => ['file', 'mimes:pdf,img'],
        ];
    }
}
