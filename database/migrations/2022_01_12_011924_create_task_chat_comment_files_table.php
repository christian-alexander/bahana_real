<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskChatCommentFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_chat_comment_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('task_chat_comment_id');
            $table->string('filename')->nullable();
            $table->text('hashname')->nullable();
            $table->text('url_file')->nullable();
            $table->integer('size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_chat_comment_files');
    }
}
