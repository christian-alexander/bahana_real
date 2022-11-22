<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanPerbaikanKerusakanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_perbaikan_kerusakan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('laporan_kerusakan_id');
            $table->string('nomor')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('bagian_kapal')->nullable();
            $table->string('pembuat')->nullable();
            $table->string('diperiksa')->nullable();
            $table->string('mengetahui_1')->nullable();
            $table->string('mengetahui_2')->nullable();
            $table->tinyInteger('is_pembuat')->default(0);
            $table->tinyInteger('is_diperiksa')->default(0);
            $table->tinyInteger('is_mengetahui_1')->default(0);
            $table->tinyInteger('is_mengetahui_2')->default(0);
            $table->dateTime('approved_pembuat_at')->nullable();
            $table->dateTime('approved_diperiksa_at')->nullable();
            $table->dateTime('approved_mengetahui_1_at')->nullable();
            $table->dateTime('approved_mengetahui_2_at')->nullable();
            $table->longText('signature_approval_pembuat')->nullable();
            $table->longText('signature_approval_diperiksa')->nullable();
            $table->longText('signature_approval_mengetahui_1')->nullable();
            $table->longText('signature_approval_mengetahui_2')->nullable();
            $table->dateTime('rejected_pembuat_at')->nullable();
            $table->dateTime('rejected_diperiksa_at')->nullable();
            $table->dateTime('rejected_mengetahui_1_at')->nullable();
            $table->dateTime('rejected_mengetahui_2_at')->nullable();
            $table->text('rejected_pembuat_reason')->nullable();
            $table->text('rejected_diperiksa_reason')->nullable();
            $table->text('rejected_mengetahui_1_reason')->nullable();
            $table->text('rejected_mengetahui_2_reason')->nullable();
            $table->text('note')->nullable();
            
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('laporan_kerusakan_id')
                ->references('id')
                ->on('laporan_kerusakan')
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
        Schema::dropIfExists('laporan_perbaikan_kerusakan');
    }
}
