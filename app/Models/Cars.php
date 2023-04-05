<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cars extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'financed',
        'files',
        'sold',
        'visible',
        'created_by',
    ];

    protected $casts = [
        'financed' => 'boolean',
        'sold' => 'boolean',
        'visible' => 'boolean',
    ];
}
