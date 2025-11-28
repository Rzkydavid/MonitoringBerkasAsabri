<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKlaim extends Model
{
    protected $table = 'jenis_klaim';

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
