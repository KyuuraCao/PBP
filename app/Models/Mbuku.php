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
        'kode_buku', 'judul_buku', 'pengarang', 'penerbit', 
        'tahun_terbit', 'isbn', 'kategori_id', 'rak_id',
        'jumlah_halaman', 'stok', 'status'
    ];

    // FIXED: Relationship dengan kategori
    public function kategori()
    {
        return $this->belongsTo(Mkategori::class, 'kategori_id', 'id');
    }

    // Relationship dengan rak
    public function rak()
    {
        return $this->belongsTo(Mrak::class, 'rak_id', 'id');
    }
}