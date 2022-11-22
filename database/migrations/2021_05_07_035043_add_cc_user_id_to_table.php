<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCcUserIdToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sbpbbm', function (Blueprint $table) {
            $table->longText('cc_user_id')->nullable();
        });
        Schema::table('permintaan_dana', function (Blueprint $table) {
            $table->longText('cc_user_id')->nullable();
        });
        Schema::table('internal_memo', function (Blueprint $table) {
            $table->longText('cc_user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table', function (Blueprint $table) {
            //
        });
    }
}
