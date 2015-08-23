<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AclUsers extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('username', 255)->unique();
            $table->string('email', 255);
            $table->string('password', 255);
            $table->string('group_id', 255)->nullable();
            $table->string('remember_token', 150)->nullable();

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
        Schema::dropIfExists('users');
    }

}
