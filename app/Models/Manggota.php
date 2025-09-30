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
        'foto'
    ];
}