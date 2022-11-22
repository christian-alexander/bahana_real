<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusApprovalToForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sptt', function (Blueprint $table) {
            $table->enum('status_approval', ['approved_diserahkan_oleh', 
            'approved_penerima',
            'rejected_diserahkan_oleh',
            'rejected_penerima'])->nullable()->after('status');
        });
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->enum('status_approval', ['approved_pelaksana', 
            'approved_diperiksa',
            'rejected_pelaksana',
            'rejected_diperiksa'])->nullable()->after('status');
        });
        Schema::table('laporan_penangguhan_pekerjaan', function (Blueprint $table) {
            $table->enum('status_approval', ['approved_pelaksana', 
            'approved_diperiksa',
            'rejected_pelaksana',
            'rejected_diperiksa'])->nullable()->after('status');
        });
        Schema::table('laporan_perbaikan_kerusakan', function (Blueprint $table) {
            $table->enum('status_approval', ['approved_pembuat', 
            'approved_diperiksa',
            'rejected_pembuat',
            'rejected_diperiksa'])->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form', function (Blueprint $table) {
            //
        });
    }
}
