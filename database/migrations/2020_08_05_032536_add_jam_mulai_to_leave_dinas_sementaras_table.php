<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJamMulaiToLeaveDinasSementarasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_dinas_sementaras', function (Blueprint $table) {
            $table->string('start_hour')->after('leave_id')->nullable();
            $table->string('end_hour')->after('start_hour')->nullable();
            $table->string('destination')->after('end_hour')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_dinas_sementaras', function (Blueprint $table) {
            //
        });
    }
}
