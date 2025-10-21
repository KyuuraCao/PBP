<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rak', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rak', 50)->unique();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rak');
    }
};