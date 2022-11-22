<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->string('pp_id')->nullable();
            $table->enum('mt_or_spob',['mt','spob']);
            $table->string('no')->nullable();
            $table->text('keperluan')->nullable();
            $table->date('tanggal')->nullable();
            $table->enum('status',['pending','onprogress','done'])->default('pending');
            $table->tinyInteger('verif_spv')->default(0);
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->longText('json_cabang')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('spk');
    }
}
