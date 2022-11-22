<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSbpbbmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbpbbm', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('office_id');
            $table->string('bagian')->nullable();
            $table->string('no_jurnal')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('jam')->nullable();
            $table->string('rob_awal')->nullable();
            $table->string('rob_akhir')->nullable();
            $table->string('port_lokasi')->nullable();
            $table->longText('pemakaian_json')->nullable();
            $table->longText('table_json')->nullable();

            $table->enum('status',['pending','onprogress','done'])->default('pending');
            $table->string('status_approval')->nullable();
            $table->string('pembuat')->nullable();
            $table->string('menyaksikan')->nullable();
            $table->string('mengetahui_1')->nullable();
            $table->string('diperiksa')->nullable();
            $table->string('penerima')->nullable();
            $table->tinyInteger('is_pembuat')->default(0);
            $table->tinyInteger('is_menyaksikan')->default(0);
            $table->tinyInteger('is_mengetahui_1')->default(0);
            $table->tinyInteger('is_diperiksa')->default(0);
            $table->tinyInteger('is_penerima')->default(0);
            $table->dateTime('approved_pembuat_at')->nullable();
            $table->dateTime('approved_menyaksikan_at')->nullable();
            $table->dateTime('approved_mengetahui_1_at')->nullable();
            $table->dateTime('approved_diperiksa_at')->nullable();
            $table->dateTime('approved_penerima_at')->nullable();
            $table->longText('signature_approval_pembuat')->nullable();
            $table->longText('signature_approval_menyaksikan')->nullable();
            $table->longText('signature_approval_mengetahui_1')->nullable();
            $table->longText('signature_approval_diperiksa')->nullable();
            $table->longText('signature_approval_penerima')->nullable();
            $table->dateTime('rejected_pembuat_at')->nullable();
            $table->dateTime('rejected_menyaksikan_at')->nullable();
            $table->dateTime('rejected_mengetahui_1_at')->nullable();
            $table->dateTime('rejected_diperiksa_at')->nullable();
            $table->dateTime('rejected_penerima_at')->nullable();
            $table->text('rejected_pembuat_reason')->nullable();
            $table->text('rejected_menyaksikan_reason')->nullable();
            $table->text('rejected_mengetahui_1_reason')->nullable();
            $table->text('rejected_diperiksa_reason')->nullable();
            $table->text('rejected_penerima_reason')->nullable();
            
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
        Schema::dropIfExists('sbpbbm');
    }
}
