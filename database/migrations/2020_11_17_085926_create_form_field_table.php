<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_field', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('form_id');
            $table->string('label')->nullable();
            $table->string('field_name');
            $table->string('field_type');
            $table->string('dropdown_table_name')->nullable();
            $table->string('dropdown_table_value')->nullable();
            $table->string('dropdown_table_label')->nullable();
            $table->text('dropdown_option')->nullable();
            $table->string('field_default_value')->nullable();
            $table->tinyInteger('nullable')->default(1);
            $table->tinyInteger('pk')->default(0);
            $table->string('reference_table_name')->nullable();
            $table->string('reference_field_name')->nullable();
            $table->timestamps();

            $table->foreign('form_id')
                ->references('id')
                ->on('form')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_field');
    }
}
