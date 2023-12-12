<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'has_database_name' => $this->has_database_name,
            'business_code' => $this->business_code,
            'type_data' => $this->type_data,
            'created_at' => Carbon::parse($this->created_at)->format('--M d.Y'),
        ];
    }
}
