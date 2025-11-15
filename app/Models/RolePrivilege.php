<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePrivilege extends Model
{
    protected $fillable = ['role_id', 'privilege_name'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
