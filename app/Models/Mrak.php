<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mrak extends Model
{
    use HasFactory;
    
    protected $table = 'rak';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'kode_rak',
        'keterangan'
    ];

    // Relasi ke buku
    public function buku()
    {
        return $this->hasMany(Mbuku::class, 'rak_id', 'id');
    }
}