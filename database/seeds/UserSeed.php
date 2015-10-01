<?php use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'username' => 'user',
            'email' => 'user@user.fr',
            'password' => \Hash::make('user')
        ]);

        \App\User::create([
            'username' => 'admin',
            'email' => 'admin@admin.fr',
            'password' => \Hash::make('admin')
        ]);
    }
}
