<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRencanaPelayananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rencana_pelayanan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('input_po_id')->nullable();
            $table->integer('kapal_id')->nullable();
            $table->date('tanggal_rencana_bunker')->nullable();
            $table->string('nama_oob')->nullable();
            $table->string('no_rfb')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rencana_pelayanan');
    }
}
