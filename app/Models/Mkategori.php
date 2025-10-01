<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mkategori extends Model
{
    use HasFactory;
    
    protected $table = 'kategori_buku';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'kode',
        'nama_kategori',
        'deskripsi'
    ];

    /**
     * Relasi ke tabel buku
     * Satu kategori bisa memiliki banyak buku
     */
    public function buku()
    {
        return $this->hasMany(Mbuku::class, 'kategori', 'nama_kategori');
    }

    /**
     * Scope untuk mencari kategori berdasarkan kode
     */
    public function scopeByKode($query, $kode)
    {
        return $query->where('kode', $kode);
    }

    /**
     * Scope untuk mencari kategori berdasarkan nama
     */
    public function scopeByNama($query, $nama)
    {
        return $query->where('nama_kategori', 'LIKE', "%{$nama}%");
    }
}