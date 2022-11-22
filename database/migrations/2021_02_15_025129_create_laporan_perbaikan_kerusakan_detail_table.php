<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanPerbaikanKerusakanDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_perbaikan_kerusakan_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lpk_id');
            $table->string('nama_bagian_dan_posisi_dikapal')->nullable();
            $table->string('uraian_pekerjaan')->nullable();
            $table->string('suku_cadang')->nullable();
            $table->integer('jumlah_satuan')->nullable();
            $table->string('nomor_suku_cadang')->nullable();
            $table->string('hasil_perbaikan')->nullable();
            $table->timestamps();

            $table->foreign('lpk_id')
                ->references('id')
                ->on('laporan_perbaikan_kerusakan')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laporan_perbaikan_kerusakan_detail');
    }
}
