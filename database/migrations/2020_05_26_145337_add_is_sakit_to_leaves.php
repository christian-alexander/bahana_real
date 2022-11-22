<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSakitToLeaves extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->string('is_sakit')->default('0')->after('reason');
            $table->text('surat_keterangan_sakit')->nullable()->after('is_sakit');

            $table->integer('budget_keuangan')->default(0)->after('surat_keterangan_sakit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
