<?php

use \Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Schema;

/**
 * Class AclGroupPermissions
 */
class AclGroupPermissions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acl_group_permissions', function ($table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('group_id')->unsigned();
            $table->integer('permission_id')->unsigned();
            $table->unique(['group_id', 'permission_id']);

            $table->foreign('group_id')
                ->references('id')->on('acl_groups')
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
        Schema::dropIfExists('acl_group_permissions');
    }
}
