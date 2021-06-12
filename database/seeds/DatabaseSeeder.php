<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        DB::table('users')->delete();
        User::create(array(
            'name'     => 'jeri yulfiranda',
            'username' => 'jyr',
            'email'    => 'jyr@gmail.com    ',
            'password' => Hash::make('admin'),
        ));
    }
}
