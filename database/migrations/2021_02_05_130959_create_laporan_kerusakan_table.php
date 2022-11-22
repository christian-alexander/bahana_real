<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanKerusakanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_kerusakan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nomor')->nullable();
            $table->string('nama_kapal')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('bagian_kapal')->nullable();
            $table->string('pelaksana')->nullable();
            $table->string('diperiksa')->nullable();
            $table->string('mengetahui_1')->nullable();
            $table->string('mengetahui_2')->nullable();
            $table->text('note')->nullable();
            $table->longText('signature_applicant')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('laporan_kerusakan');
    }
}
