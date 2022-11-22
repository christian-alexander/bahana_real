<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleFinishFollowupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_finish_followups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('schedule_finish_id');
            $table->unsignedBigInteger('schedule_finish_invitation_id');
            $table->date('date');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('schedule_finish_id')
                ->references('id')
                ->on('schedule_finishes')
                ->onDelete('cascade');

            $table->foreign('schedule_finish_invitation_id')
                ->references('id')
                ->on('schedule_finish_invitations')
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
        Schema::dropIfExists('schedule_finish_followups');
    }
}
