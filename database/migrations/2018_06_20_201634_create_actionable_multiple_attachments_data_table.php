<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionableMultipleAttachmentsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actionable_multiple_attachments_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id')->unsigned();
            $table->string('email')->nullable();
            $table->integer('actionable_ma_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('duration')->unsigned();
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('templates');
            $table->foreign('actionable_ma_id')->references('id')->on('actionable_multiple_attachments');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actionable_multiple_attachments_data');
    }
}
