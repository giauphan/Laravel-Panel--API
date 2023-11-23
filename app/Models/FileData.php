<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileData extends Model
{
    protected $fillable = ['business_code', 'has_business_code', 'Data','type_data'];
}
