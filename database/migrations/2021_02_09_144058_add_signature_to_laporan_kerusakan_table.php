<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignatureToLaporanKerusakanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->longText('signature_approval_diperiksa')->nullable()->after('is_mengetahui_2');
            $table->longText('signature_approval_mengetahui_1')->nullable()->after('signature_approval_diperiksa');
            $table->longText('signature_approval_mengetahui_2')->nullable()->after('signature_approval_mengetahui_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            //
        });
    }
}
