<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovedByHrdToLeaveDinasLuarKotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_dinas_luar_kotas', function (Blueprint $table) {
            $table->tinyInteger('is_approved_hrd')->after('biaya')->nullable();
            $table->string('approved_by')->after('is_approved_hrd')->nullable();
            $table->dateTime('approved_at')->after('approved_by')->nullable();
            $table->string('rejected_by')->after('approved_at')->nullable();
            $table->dateTime('rejected_at')->after('rejected_by')->nullable();
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
