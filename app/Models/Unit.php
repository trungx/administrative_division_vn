<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'name',
        'name_with_type',
        'code',
        'parent_code',
        'path',
        'path_with_type',
        'slug',
        'type',
        'level',
    ];
}
