<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveAccomodationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_accomodations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('leave_dinas_luar_kotas_id');
            $table->text('note')->nullable();
            $table->text('file')->nullable();
            $table->timestamps();

            $table->foreign('leave_dinas_luar_kotas_id')
                ->references('id')
                ->on('leave_dinas_luar_kotas')
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
        Schema::dropIfExists('leave_accomodations');
    }
}
