<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    // Custom primary key
    protected $primaryKey = 'userid';
    public $incrementing = false; // karena primary key bukan auto-increment
    protected $keyType = 'string';

    // Tabel yang digunakan (opsional jika default 'users')
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'userid',
        'password',
        'firstname',
        'lastname',
        'groupid',
        'statuslogin',
        'menusecurity',
        'lastmoduser',
        'lastmoddate',
        'email',
        'email_verified_at',
        'properties',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'lastmoddate' => 'datetime',
        'properties' => 'array',
    ];

    /**
     * Automatically hash password when setting it.
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
}
