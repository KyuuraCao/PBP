<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('kode_buku', 50)->unique();
            $table->string('judul_buku', 255);
            $table->string('pengarang', 255)->nullable();
            $table->string('penerbit', 255)->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->string('isbn', 50)->nullable();
            $table->string('kategori', 100)->nullable();
            $table->integer('jumlah_halaman')->nullable();
            $table->integer('stok')->default(0);
            $table->enum('status', ['Ada', 'Dipinjam', 'Hilang'])->default('Ada');
            $table->timestamps();
            
            // Index
            $table->index('kode_buku');
            $table->index('judul_buku');
            $table->index('status');
            $table->index('kategori');
        });
    }

    public function down()
    {
        Schema::dropIfExists('buku');
    }
};