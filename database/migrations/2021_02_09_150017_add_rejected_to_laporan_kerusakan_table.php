<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRejectedToLaporanKerusakanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->dateTime('approved_mengetahui_1_at')->after('approved_diperiksa_at')->nullable();
            $table->dateTime('approved_mengetahui_2_at')->after('approved_mengetahui_1_at')->nullable();
            $table->dateTime('rejected_mengetahui_1_at')->after('approved_mengetahui_2_at')->nullable();
            $table->text('rejected_mengetahui_1_reason')->after('rejected_mengetahui_1_at')->nullable();
            $table->dateTime('rejected_mengetahui_2_at')->after('rejected_mengetahui_1_reason')->nullable();
            $table->text('rejected_mengetahui_2_reason')->after('rejected_mengetahui_2_at')->nullable();
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
