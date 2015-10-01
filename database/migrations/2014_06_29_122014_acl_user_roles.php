<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Class AclUserRoles
 */
class AclUserRoles extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acl_user_roles', function ($table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->timestamps();
            $table->primary(['user_id', 'role_id']);

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')->on('acl_roles')
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
        Schema::dropIfExists('acl_user_roles');
    }

}
