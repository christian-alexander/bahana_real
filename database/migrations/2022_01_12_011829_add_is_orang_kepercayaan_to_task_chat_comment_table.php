<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsOrangKepercayaanToTaskChatCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_chat_comment', function (Blueprint $table) {
            $table->tinyInteger('is_orang_kepercayaan')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_chat_comment', function (Blueprint $table) {
            //
        });
    }
}
