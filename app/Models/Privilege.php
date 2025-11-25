<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Privilege extends Model
{
    use HasFactory;

    protected $table = 'privileges';

    protected $fillable = [
        'name',
    ];

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_privilege',      // pivot table
            'privilege_name',      // pivot column referring to this model
            'role_id',             // pivot column referring to Role model
            'name',                // local key on Privilege model
            'id'                   // local key on Role model
        );
    }
}
