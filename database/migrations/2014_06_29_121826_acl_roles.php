<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class AclRoles
 */
class AclRoles extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acl_roles', function ($table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->enum('filter', array('A', 'D', 'R'));

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
        Schema::dropIfExists('acl_roles');
    }
}
