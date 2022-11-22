<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEnumValueFromSpkApprovalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE spk_approval MODIFY COLUMN status ENUM(
        'approved_1',
        'rejected_1',
        'approved_2',
        'rejected_2',
        'approved_3',
        'rejected_3',
        'approved_4',
        'rejected_4'
        )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spk_approval', function (Blueprint $table) {
            //
        });
    }
}
