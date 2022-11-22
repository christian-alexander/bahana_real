<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpkApprovalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spk_approval', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('spk_id');
            $table->enum('tipe',['approved','rejected']);
            $table->enum('status',[
                'approved_1',
                'rejected_1',
                'approved_2',
                'rejected_2',
                'approved_3',
                'rejected_3',
                ]);
            $table->string('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->string('rejected_by')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->string('rejected_reason')->nullable();
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
        Schema::dropIfExists('spk_approval');
    }
}
