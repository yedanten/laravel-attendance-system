<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNamedLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('named_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('workid');
            $table->string('class');
            $table->string('subject');
            $table->string('area');
            $table->integer('No');
            $table->integer('stuid');
            $table->tinyInteger('attend');
            $table->bigInteger('time');
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
        //
    }
}
