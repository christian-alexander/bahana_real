<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveIjinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_ijins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('leave_id');
            $table->tinyInteger('is_sakit')->default(0);
            $table->text('surat_keterangan_sakit')->nullable();
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
        Schema::dropIfExists('leave_ijins');
    }
}
