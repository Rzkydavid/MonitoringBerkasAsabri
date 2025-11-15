<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    protected $fillable = [
        'role_id',
        'name',
    ];

    /**
     * A privilege belongs to a role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
