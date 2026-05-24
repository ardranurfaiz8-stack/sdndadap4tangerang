<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke masing-masing role
    public function guru()
    {
        return $this->hasOne(Guru::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function kepalaSekolah()
    {
        return $this->hasOne(KepalaSekolah::class);
    }
}