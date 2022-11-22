<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanPenangguhanPekerjaanDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_penangguhan_pekerjaan_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lpp_id');
            $table->string('item_pekerjaan')->nullable();
            $table->string('posisi')->nullable();
            $table->string('alasan_penangguhan')->nullable();
            $table->string('target_perbaikan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('lpp_id')
                ->references('id')
                ->on('laporan_penangguhan_pekerjaan')
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
        Schema::dropIfExists('laporan_penangguhan_pekerjaan_detail');
    }
}
