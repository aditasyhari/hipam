<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Pembayaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pelanggan')->references('id')->on('user');
            $table->foreignId('id_petugas')->references('id')->on('user')->nullable();
            $table->string('nama');
            $table->string('tlp');
            $table->string('alamat');
            $table->string('bukti')->nullable();
            $table->enum('status', ['pending', 'waiting', 'reject', 'success'])->default('pending');
            $table->boolean('read')->default(false);
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
        Schema::dropIfExists('pembayaran');
    }
}
