<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckerAtToProjectTimeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_time_logs', function (Blueprint $table) {
            $table->dateTime('checker_at')->nullable();
            $table->dateTime('checker_at_gmt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_time_logs', function (Blueprint $table) {
            //
        });
    }
}
