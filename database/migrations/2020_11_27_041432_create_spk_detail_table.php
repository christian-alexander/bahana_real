<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpkDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spk_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('spk_id');
            $table->string('barang_id')->nullable();
            $table->string('barang_etc')->nullable();
            $table->integer('barang_diminta');
            $table->integer('barang_disetujui');
            $table->text('ket');
            $table->longText('json_barang');
            $table->softDeletes();
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
        Schema::dropIfExists('spk_detail');
    }
}
