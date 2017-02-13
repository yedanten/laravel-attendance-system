<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigInteger('stuid');
            $table->string('username');
            $table->string('password');
            $table->string('sex');
            $table->string('department');
            $table->string('major');
            $table->string('class');
            $table->string('email')->nullable();
            $table->primary('stuid');
            $table->index('department');
            $table->index('major');
            $table->index('class');
            $table->rememberToken();
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
