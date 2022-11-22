<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedInteger('pic');
            $table->string('subject');
            $table->string('description')->nullable();
            $table->date('date');
            $table->string('time');
            $table->enum('status', ['unfinished', 'finished'])->default('unfinished');
            $table->timestamps();

            $table->foreign('pic')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('parent_id')
                ->references('id')
                ->on('schedules')
                ->onDelete('cascade');
        });

        Schema::create('schedules_invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('schedules_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('schedules_id')
                ->references('id')
                ->on('schedules')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('schedules');
    }
}
