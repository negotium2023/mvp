<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('id_number')->nullable();
            $table->string('company')->nullable();
            $table->string('company_registration_number')->nullable();
            $table->string('email');
            $table->string('contact')->nullable();
            $table->string('cif_code')->nullable();//Remember to limit from the front-end

            $table->integer('referrer_id')->unsigned()->nullable();
            $table->integer('introducer_id')->unsigned();
            $table->integer('office_id')->unsigned();
            $table->integer('process_id')->unsigned();
            $table->integer('step_id')->unsigned()->default(1);

            $table->dateTime('completed_at')->nullable();
            $table->boolean('is_progressing')->default(true);
            $table->dateTime('not_progressing_date')->nullable();
            $table->boolean('is_qa')->default(true);
            $table->dateTime('qa_date')->nullable();
            $table->boolean('needs_approval')->default(false);

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('referrer_id')->references('id')->on('referrers');
            $table->foreign('introducer_id')->references('id')->on('users');
            $table->foreign('office_id')->references('id')->on('offices');
            $table->foreign('process_id')->references('id')->on('processes');
//            $table->foreign('step_id')->references('id')->on('steps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
