<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormpermintaandanasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_permintaan_dana', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('no')->nullable();
            $table->integer('subcompany_id')->nullable();
            $table->integer('user_id')->nullable()->description('gak tau ini untuk apa, tapi ada di form');
            $table->date('tanggal')->nullable();
            $table->text('keperluan')->nullable();
            $table->string('nominal')->nullable();
            $table->integer('team_id')->nullable();
            $table->text('note')->nullable();
            $table->string('terbilang')->nullable();
            $table->string('unsur_pph')->nullable();
            $table->string('nominal_pph')->nullable();
            $table->string('approval_pajak')->nullable();
            $table->string('diperiksa_1')->nullable();
            $table->string('mengetahui')->nullable();
            $table->string('disetujui_1')->nullable();
            $table->text('image')->nullable();

            $table->enum('status',['pending','onprogress','done'])->default('pending');
            $table->string('status_approval')->nullable();
            $table->string('pembuat')->nullable();
            $table->string('disetujui')->nullable();
            $table->string('mengetahui_1')->nullable();
            $table->string('diperiksa')->nullable();
            $table->tinyInteger('is_pembuat')->default(0);
            $table->tinyInteger('is_disetujui')->default(0);
            $table->tinyInteger('is_mengetahui_1')->default(0);
            $table->tinyInteger('is_diperiksa')->default(0);
            $table->dateTime('approved_pembuat_at')->nullable();
            $table->dateTime('approved_disetujui_at')->nullable();
            $table->dateTime('approved_mengetahui_1_at')->nullable();
            $table->dateTime('approved_diperiksa_at')->nullable();
            $table->longText('signature_approval_pembuat')->nullable();
            $table->longText('signature_approval_disetujui')->nullable();
            $table->longText('signature_approval_mengetahui_1')->nullable();
            $table->longText('signature_approval_diperiksa')->nullable();
            $table->dateTime('rejected_pembuat_at')->nullable();
            $table->dateTime('rejected_disetujui_at')->nullable();
            $table->dateTime('rejected_mengetahui_1_at')->nullable();
            $table->dateTime('rejected_diperiksa_at')->nullable();
            $table->text('rejected_pembuat_reason')->nullable();
            $table->text('rejected_disetujui_reason')->nullable();
            $table->text('rejected_mengetahui_1_reason')->nullable();
            $table->text('rejected_diperiksa_reason')->nullable();
            
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
        Schema::dropIfExists('form_permintaan_dana');
    }
}
