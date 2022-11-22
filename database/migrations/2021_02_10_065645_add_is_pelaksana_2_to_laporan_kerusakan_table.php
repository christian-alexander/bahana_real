<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPelaksana2ToLaporanKerusakanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->dateTime('rejected_pelaksana_at')->after('rejected_diperiksa_reason')->nullable();
            $table->text('rejected_pelaksana_reason')->after('rejected_pelaksana_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            //
        });
    }
}
