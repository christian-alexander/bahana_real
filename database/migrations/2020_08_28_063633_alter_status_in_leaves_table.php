<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStatusInLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leaves', function (Blueprint $table) {
            \DB::statement("ALTER TABLE `leaves` CHANGE `status` `status` ENUM('pending', 'approved_atasan_satu', 'approved_atasan_dua','approved_atasan_tiga','rejected_atasan_satu','rejected_atasan_dua','rejected_atasan_tiga') NOT NULL DEFAULT 'pending';");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            //
        });
    }
}
