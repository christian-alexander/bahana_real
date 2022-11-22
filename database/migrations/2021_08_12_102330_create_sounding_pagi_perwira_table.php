<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoundingPagiPerwiraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sounding_pagi_perwira', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('office_id');
            $table->date('tanggal')->nullable();
            $table->string('bagian')->nullable();
            $table->string('no_jurnal')->nullable();
            $table->string('lokasi')->nullable();
            $table->longText('table_json')->nullable();

            $table->enum('status',['pending','onprogress','done'])->default('pending');
            $table->string('status_approval')->nullable();
            $table->string('pembuat')->nullable();
            $table->string('menyetujui')->nullable();
            $table->tinyInteger('is_pembuat')->default(0);
            $table->tinyInteger('is_menyetujui')->default(0);
            $table->dateTime('approved_pembuat_at')->nullable();
            $table->dateTime('approved_menyetujui_at')->nullable();
            $table->longText('signature_approval_pembuat')->nullable();
            $table->longText('signature_approval_menyetujui')->nullable();
            $table->dateTime('rejected_pembuat_at')->nullable();
            $table->dateTime('rejected_menyetujui_at')->nullable();
            $table->text('rejected_pembuat_reason')->nullable();
            $table->text('rejected_menyetujui_reason')->nullable();
            $table->longText('cc_user_id')->nullable();
            
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('sounding_pagi_perwira');
    }
}
