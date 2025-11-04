<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pinjam_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pinjam');
            $table->unsignedBigInteger('id_buku');
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['pinjam', 'kembali'])->default('pinjam');
            $table->timestamps();
            
            $table->foreign('id_pinjam')->references('id')->on('pinjam')->onDelete('cascade');
            $table->foreign('id_buku')->references('id')->on('buku')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pinjam_detail');
    }
};