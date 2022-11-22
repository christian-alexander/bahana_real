<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInputPoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('input_po', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sub_company_id')->nullable();
            $table->integer('kapal_id')->nullable();
            $table->string('no_po')->nullable();
            $table->string('nama_customer')->nullable();
            $table->string('wilayah')->nullable();
            $table->string('jenis_kegiatan')->nullable();
            $table->date('tanggal_po')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('jenis_produk')->nullable();
            $table->integer('qty')->nullable();
            $table->string('no_sao')->nullable();
            $table->text('image')->nullable();
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
        Schema::dropIfExists('input_po');
    }
}
