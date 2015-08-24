<?php use Illuminate\Database\Seeder;

class PermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Permission::create([
            'permission' => 'create',
            'action' => 'create',
            'description' => 'Create new item'
        ]);

        \App\Permission::create([
            'permission' => 'list',
            'action' => 'list',
            'description' => 'List items'
        ]);

        \App\Permission::create([
            'permission' => 'show',
            'action' => 'show',
            'description' => 'Show item'
        ]);

        \App\Permission::create([
            'permission' => 'edit',
            'action' => 'edit',
            'description' => 'Edit item'
        ]);

        \App\Permission::create([
            'permission' => 'delete',
            'action' => 'delete',
            'description' => 'Delete item'
        ]);
    }
}
