<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddButuhAkomodasiToLeaveDinasLuarKotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_dinas_luar_kotas', function (Blueprint $table) {
            if (!Schema::hasColumn('leave_dinas_luar_kotas', 'butuh_akomodasi')) {
                $table->tinyInteger('butuh_akomodasi')->after('leave_id')->default(0);
            }
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
