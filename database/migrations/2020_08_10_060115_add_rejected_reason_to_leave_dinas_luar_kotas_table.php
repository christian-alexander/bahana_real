<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRejectedReasonToLeaveDinasLuarKotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_dinas_luar_kotas', function (Blueprint $table) {
            $table->text('rejected_reason')->after('rejected_at')->nullable();
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
