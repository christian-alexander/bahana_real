<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCcUserIdToInputPoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('input_po', function (Blueprint $table) {
            $table->longText('cc_user_id')->nullable();
        });
        Schema::table('rencana_pelayanan', function (Blueprint $table) {
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
        // code
    }
}
