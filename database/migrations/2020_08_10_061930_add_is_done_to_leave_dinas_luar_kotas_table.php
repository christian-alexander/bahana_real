<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDoneToLeaveDinasLuarKotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_dinas_luar_kotas', function (Blueprint $table) {
            $table->tinyInteger('is_done')->after('biaya')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_dinas_luar_kotas', function (Blueprint $table) {
            //
        });
    }
}
