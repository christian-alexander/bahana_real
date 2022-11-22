<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRuteAwalLeaveDinasLuarKotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('leave_dinas_luar_kotas', function (Blueprint $table) {
            $table->string('rute_awal')->after('leave_id')->nullable();
            $table->string('rute_akhir')->after('rute_awal')->nullable();
            $table->string('alasan')->after('rute_akhir')->nullable();
            $table->integer('biaya')->after('alasan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
