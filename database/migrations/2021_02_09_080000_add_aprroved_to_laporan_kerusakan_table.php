<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAprrovedToLaporanKerusakanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->tinyInteger('is_diperiksa')->default(0)->after('mengetahui_2');
            $table->tinyInteger('is_mengetahui_1')->default(0)->after('is_diperiksa');
            $table->tinyInteger('is_mengetahui_2')->default(0)->after('is_mengetahui_1');
            $table->dateTime('approved_diperiksa_at')->after('is_mengetahui_2')->nullable();
            $table->dateTime('rejected_diperiksa_at')->after('approved_diperiksa_at')->nullable();
            $table->text('rejected_diperiksa_reason')->after('rejected_diperiksa_at')->nullable();
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
