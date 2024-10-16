<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirstRoundEvaluationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('first_round_evaluation', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->integer('jury_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->integer('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('first_round_evaluation', function($table) {
            $table->foreign('jury_id')->references('id')->on('users');

        });

        Schema::table('first_round_evaluation', function($table) {
            $table->foreign('project_id')->references('id')->on('projects');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('first_round_evaluation');
    }
}
