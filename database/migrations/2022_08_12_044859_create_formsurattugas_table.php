<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsurattugasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_surat_tugas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('no')->nullable();
            $table->integer('subcompany_id')->nullable();
            $table->integer('user_id')->nullable()->description('gak tau ini untuk apa, tapi ada di form');
            $table->integer('team_id')->nullable();
            $table->text('keperluan')->nullable();
            $table->string('jabatan_satu')->nullable();
            $table->string('nik_satu')->nullable();
            $table->string('jabatan_dua')->nullable();
            $table->string('nik_dua')->nullable();
            $table->string('nama_bertugas')->nullable();
            $table->string('rute_awal')->nullable();
            $table->string('rute_akhir')->nullable();
            $table->string('tanggal_mulai')->nullable();
            $table->string('tanggal_selesai')->nullable();
            $table->string('estimasi_biaya')->nullable();
            $table->string('acc_atasan_1')->nullable();
            $table->string('acc_atasan_2')->nullable();



            $table->enum('status',['pending','onprogress','done'])->default('pending');
            $table->string('status_approval')->nullable();
            $table->string('pembuat')->nullable();
            $table->string('penerima')->nullable();
            $table->string('mengetahui_1')->nullable();
            $table->string('mengetahui_2')->nullable();
            $table->tinyInteger('is_pembuat')->default(0);
            $table->tinyInteger('is_penerima')->default(0);
            $table->tinyInteger('is_mengetahui_1')->default(0);
            $table->tinyInteger('is_mengetahui_2')->default(0);
            $table->dateTime('approved_pembuat_at')->nullable();
            $table->dateTime('approved_penerima_at')->nullable();
            $table->dateTime('approved_mengetahui_1_at')->nullable();
            $table->dateTime('approved_mengetahui_2_at')->nullable();
            $table->longText('signature_approval_pembuat')->nullable();
            $table->longText('signature_approval_penerima')->nullable();
            $table->longText('signature_approval_mengetahui_1')->nullable();
            $table->longText('signature_approval_mengetahui_2')->nullable();
            $table->dateTime('rejected_pembuat_at')->nullable();
            $table->dateTime('rejected_penerima_at')->nullable();
            $table->dateTime('rejected_mengetahui_1_at')->nullable();
            $table->dateTime('rejected_mengetahui_2_at')->nullable();
            $table->text('rejected_pembuat_reason')->nullable();
            $table->text('rejected_penerima_reason')->nullable();
            $table->text('rejected_mengetahui_1_reason')->nullable();
            $table->text('rejected_mengetahui_2_reason')->nullable();
            
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
        Schema::dropIfExists('form_surat_tugas');
    }
}
