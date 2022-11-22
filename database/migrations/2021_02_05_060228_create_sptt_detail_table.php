<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpttDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sptt_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sptt_id');
            $table->text('uraian');
            $table->string('satuan');
            $table->integer('jumlah');
            $table->timestamps();

            $table->foreign('sptt_id')
                ->references('id')
                ->on('sptt')
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
        Schema::dropIfExists('sptt_detail');
    }
}
