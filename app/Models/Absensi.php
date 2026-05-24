<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis'; // sesuaikan nama tabel di database
    
    protected $fillable = [
        'guru_id',
        'tanggal',
        'status',
        // kolom lainnya...
    ];
}