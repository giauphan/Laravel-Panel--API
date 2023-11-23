<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\FileData
 */
class FileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'business_code' => $this->business_code,
            'has_business_code' => $this->has_business_code,
            'Data' => $this->Data,
            'url_preview' => route('preview', ['id' => $this->has_business_code]),
        ];
    }
}
