<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mpinjam_detail extends Model
{
    use HasFactory;

    protected $table = 'pinjam_detail';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_pinjam',
        'id_buku',
        'tanggal_kembali',
        'status'
    ];

    protected $casts = [
        'tanggal_kembali' => 'date',
    ];

    public function pinjam()
    {
        return $this->belongsTo(Mpinjam::class, 'id_pinjam', 'id');
    }

    public function buku()
    {
        return $this->belongsTo(Mbuku::class, 'id_buku', 'id');
    }
}