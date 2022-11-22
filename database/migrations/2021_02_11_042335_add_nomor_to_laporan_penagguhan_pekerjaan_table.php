<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNomorToLaporanPenagguhanPekerjaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laporan_penangguhan_pekerjaan', function (Blueprint $table) {
            $table->string('nomor')->after('laporan_kerusakan_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laporan_penangguhan_pekerjaan', function (Blueprint $table) {
            //
        });
    }
}
