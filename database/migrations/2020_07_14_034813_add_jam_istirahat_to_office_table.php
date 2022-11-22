<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJamIstirahatToOfficeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office', function (Blueprint $table) {
            $table->string('jam_istirahat_awal')->nullable()->after('longitude');
            $table->string('jam_istirahat_akhir')->nullable()->after('jam_istirahat_awal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('office', function (Blueprint $table) {
            //
        });
    }
}
