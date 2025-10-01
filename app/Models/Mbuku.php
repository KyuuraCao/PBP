<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mbuku extends Model
{
    use HasFactory;
    
    protected $table = 'buku';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'kode_buku',
        'judul_buku',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'kategori',
        'jumlah_halaman',
        'stok',
        'status'
    ];
}   