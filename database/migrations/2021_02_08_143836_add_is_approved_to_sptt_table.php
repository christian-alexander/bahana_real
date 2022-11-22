<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsApprovedToSpttTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sptt', function (Blueprint $table) {
            $table->tinyInteger('is_penerima_oleh')->default(0)->after('signature_diserahkan_oleh');
            $table->dateTime('approved_penerima_at')->after('is_penerima_oleh')->nullable();
            $table->dateTime('rejected_penerima_at')->after('approved_penerima_at')->nullable();
            $table->text('rejected_penerima_reason')->after('rejected_penerima_at')->nullable();
            $table->tinyInteger('is_diserahkan_oleh')->default(0)->after('rejected_penerima_reason');
            $table->dateTime('approved_diserahkan_oleh_at')->after('is_diserahkan_oleh')->nullable();
            $table->dateTime('rejected_diserahkan_oleh_at')->after('approved_diserahkan_oleh_at')->nullable();
            $table->text('rejected_diserahkan_oleh_reason')->after('rejected_diserahkan_oleh_at')->nullable();
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
