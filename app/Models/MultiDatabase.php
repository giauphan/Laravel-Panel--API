<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiDatabase extends Model
{
    use HasFactory;

    protected $fillable = ['host', 'has_database_name','database', 'username', 'password', 'port', 'status'];
}
