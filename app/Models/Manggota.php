<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manggota extends Model
{
    protected $table = 'anggota';
    protected $primaryKey = 'id'; // Harus ada
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = [
        'id_anggota',
        'nama',
        'jenis_kelamin',
        'alamat',
        'nomor_hp',
        'email',
        'status',
        'pendidikan_terakhir',
        'pekerjaan',
        'instansi',
        'tanggal_daftar',
        'berlaku_hingga',
        'foto'
    ];

    // Cast untuk tipe data yang sesuai
    protected $casts = [
        'tanggal_daftar' => 'date',
        'berlaku_hingga' => 'date',
    ];

    // Default values
    protected $attributes = [
        'status' => 'Aktif',
    ];
}