<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusBerkas extends Model
{
    protected $table = 'status_berkas';

    protected $fillable = [
        'status_terkini',
        'next_step',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
