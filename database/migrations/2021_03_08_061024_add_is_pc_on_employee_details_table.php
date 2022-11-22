<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPcOnEmployeeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('employee_details', function (Blueprint $table) {
            // $table->tinyInteger('is_pc')->default(0)->after('is_atasan')->comment('for dek');
            $table->tinyInteger('is_pe')->default(0)->after('is_pc')->comment('for mesin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
