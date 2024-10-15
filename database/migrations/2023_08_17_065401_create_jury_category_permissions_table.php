<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJuryCategoryPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jury_category_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->integer('cat_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('jury_category_permissions', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cat_id')->references('id')->on('cats');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jury_category_permissions');
    }
}
