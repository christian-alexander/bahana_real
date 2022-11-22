<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->unsignedBigInteger('leave_id');
            // $table->unsignedBigInteger('triggered_by');
            $table->integer('leave_id')->unsigned();
            $table->integer('triggered_by')->unsigned();
            $table->string('event');
            $table->timestamps();

            // $table->foreign('leave_id')
            //     ->references('id')
            //     ->on('leaves')
            //     ->onDelete('cascade');
            // $table->foreign('triggered_by')
            //     ->references('id')
            //     ->on('users')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_activities');
    }
}
