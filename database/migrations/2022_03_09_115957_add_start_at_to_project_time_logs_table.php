<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartAtToProjectTimeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_time_logs', function (Blueprint $table) {
            $table->dateTime('start_at')->nullable();
            $table->dateTime('start_at_gmt')->nullable();
            $table->dateTime('stop_at')->nullable();
            $table->dateTime('stop_at_gmt')->nullable();
            $table->dateTime('done_at')->nullable();
            $table->dateTime('done_at_gmt')->nullable();
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
