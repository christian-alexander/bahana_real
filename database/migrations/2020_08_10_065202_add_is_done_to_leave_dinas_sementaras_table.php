<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDoneToLeaveDinasSementarasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_dinas_sementaras', function (Blueprint $table) {
            $table->tinyInteger('is_done')->after('destination')->default(0);
            $table->dateTime('done_at')->after('is_done')->nullable();
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
