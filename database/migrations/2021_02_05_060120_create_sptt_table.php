<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpttTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sptt', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nomor')->nullable();
            $table->string('mt_spob')->nullable();
            $table->string('posisi_kapal')->nullable();
            $table->date('tanggal')->nullable();
            $table->text('note')->nullable();
            $table->string('penerima')->nullable();
            $table->string('diserahkan_oleh')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->longText('signature_diserahkan_oleh')->nullable();
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
        Schema::dropIfExists('sptt');
    }
}
