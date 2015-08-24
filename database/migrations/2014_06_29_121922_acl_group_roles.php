<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Class AclGroupRoles
 */
class AclGroupRoles extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acl_group_roles', function ($table) {
            $table->integer('group_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->primary(['group_id', 'role_id']);
            $table->timestamps();

            $table->foreign('role_id')
                ->references('id')->on('acl_roles')
                ->onDelete('cascade');

            $table->foreign('group_id')
                ->references('id')->on('acl_groups')
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
        Schema::dropIfExists('acl_group_roles');
    }
}
