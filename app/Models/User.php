<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // optional if using Sanctum

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nip',
        'password',
        'job_title',
        'email',
        'phone',
        'role_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays and JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'boolean',
    ];

    /**
     * Automatically hash passwords when setting.
     */
    public function setPasswordAttribute($value)
    {
        // Avoid double-hashing
        if (!empty($value) && strlen($value) < 60) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    /**
     * Relationship: a user belongs to a role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Custom accessor for role name (optional convenience).
     */
    public function getRoleNameAttribute()
    {
        return $this->role ? $this->role->name : '-';
    }

    /**
     * Get the field name used for authentication.
     * (We use `nip` instead of `email` for login)
     */
    public function getAuthIdentifierName()
    {
        return 'nip';
    }
}
