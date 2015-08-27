<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Class AclRolePermissions
 */
class AclRolePermissions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acl_role_permissions', function ($table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('role_id')->unsigned();
            $table->integer('permission_id')->unsigned();
            $table->unique(['role_id', 'permission_id']);

            $table->foreign('role_id')
                ->references('id')->on('acl_roles')
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->references('id')->on('acl_permissions')
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
        Schema::dropIfExists('acl_role_permissions');
    }
}
