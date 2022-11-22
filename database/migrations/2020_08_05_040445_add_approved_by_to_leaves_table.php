<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovedByToLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leaves', function (Blueprint $table) {
            //
            \DB::statement("ALTER TABLE `leaves` CHANGE `status` `status` ENUM('pending', 'approved_atasan_satu', 'approved_atasan_dua','rejected_atasan_satu','rejected_atasan_dua') NOT NULL DEFAULT 'pending';");
            $table->tinyInteger('is_final')->after('status')->default(0);
            $table->text('approved_by')->after('is_final')->nullable();
            $table->text('rejected_by')->after('approved_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            //
        });
    }
}
