<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Notifikasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pelanggan')->references('id')->on('user');
            $table->foreignId('id_petugas')->references('id')->on('user')->nullable();
            $table->text('pesan')->nullable();
            $table->enum('type', ['keluhan', 'pembayaran']);
            $table->boolean('read_petugas')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifikasi');
    }
}
