<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanKerusakanDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_kerusakan_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('laporan_kerusakan_id');
            $table->string('posisi_di_kapal');
            $table->integer('jumlah_kerusakan');
            $table->text('uraian_kerusakan');
            $table->text('analisis_kerusakan');
            $table->text('usaha_penanggulangan');
            $table->text('hal_yang_perlu_ditindak_lanjuti');
            $table->timestamps();

            $table->foreign('laporan_kerusakan_id')
                ->references('id')
                ->on('laporan_kerusakan')
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
        Schema::dropIfExists('laporan_kerusakan_detail');
    }
}
