<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Feather',
            'email' => 'f@f.com',
            'code' => 'S3117',
            'role_id' => 1,
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(20),
        ]);
//        User::create([
//            'name' => 'Guest',
//            'email' => 'g@f.com',
//            'code' => 'S000002',
//            'role_id' => 2,
//            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
//            'remember_token' => str_random(20),
//        ]);

//        factory(App\User::class, 600)->create();
    }
}
