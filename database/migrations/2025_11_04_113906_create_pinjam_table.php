<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pinjam', function (Blueprint $table) {
            $table->id();
            $table->string('no_pinjam', 50)->unique();
            $table->unsignedBigInteger('id_anggota');
            $table->date('tanggal_pinjam');
            $table->date('batas_pinjam');
            $table->enum('status', ['pinjam', 'sebagian', 'selesai'])->default('pinjam');
            $table->timestamps();
            
            $table->foreign('id_anggota')->references('id')->on('anggota')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pinjam');
    }
};