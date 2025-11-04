<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mpinjam extends Model
{
    use HasFactory;

    protected $table = 'pinjam';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'no_pinjam',
        'id_anggota',
        'tanggal_pinjam',
        'batas_pinjam',
        'status'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'batas_pinjam' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(Manggota::class, 'id_anggota', 'id');
    }

    public function details()
    {
        return $this->hasMany(Mpinjam_detail::class, 'id_pinjam', 'id');
    }
}