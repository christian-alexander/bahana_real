<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpkActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spk_activity', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('spk_id');
            $table->string('triggered_by');
            $table->string('activity');
            $table->timestamps();

            $table->foreign('spk_id')
                ->references('id')
                ->on('spk')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spk_activity');
    }
}
