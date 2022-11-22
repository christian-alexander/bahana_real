<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sptt', function (Blueprint $table) {
            $table->enum('status', ['pending', 'onprogress','done'])->default('pending')->after('updated_by');
        });
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->enum('status', ['pending', 'onprogress','done'])->default('pending')->after('updated_by');
        });
        Schema::table('laporan_penangguhan_pekerjaan', function (Blueprint $table) {
            $table->enum('status', ['pending', 'onprogress','done'])->default('pending')->after('updated_by');
        });
        Schema::table('laporan_perbaikan_kerusakan', function (Blueprint $table) {
            $table->enum('status', ['pending', 'onprogress','done'])->default('pending')->after('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sptt', function (Blueprint $table) {
            //
        });
    }
}
