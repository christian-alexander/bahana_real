<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJsonToClusterWorkingHourTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cluster_working_hours', function (Blueprint $table) {
            //
            $table->enum('type', ['daily', 'shift'])->after('company_id')->default('daily');
            $table->text('json')->after('end_hour')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cluster_working_hour', function (Blueprint $table) {
            //
        });
    }
}
