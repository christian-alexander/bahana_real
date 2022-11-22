<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpkPerformanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spk_performance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('spk_id');
            $table->unsignedInteger('user_id');
            $table->text('desc')->nullable();
            $table->integer('point');
            $table->timestamps();
            
            $table->foreign('spk_id')
                ->references('id')
                ->on('spk')
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
        Schema::dropIfExists('spk_performance');
    }
}
