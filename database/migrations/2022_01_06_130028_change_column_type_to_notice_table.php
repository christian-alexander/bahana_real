<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTypeToNoticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notices', function (Blueprint $table) {
            // if (Schema::hasForeign('notices', 'team_id')){
            //     $table->dropForeign(['team_id']);
            // }
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            if (Schema::hasColumn('notices', 'team_id'))
            {
                $table->dropColumn('team_id');
            }
            if (Schema::hasColumn('notices', 'sub_company_id'))
            {
                $table->dropColumn('sub_company_id');
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });
        Schema::table('notices', function (Blueprint $table) {
            $table->longText('team_id')->nullable();
            $table->longText('sub_company_id')->nullable();
            // DB::statement('ALTER TABLE notices MODIFY team_id LONGTEXT;');
            // DB::statement('ALTER TABLE notices MODIFY sub_company_id LONGTEXT;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notice', function (Blueprint $table) {
            //
        });
    }
}
