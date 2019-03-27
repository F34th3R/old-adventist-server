<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'feather',
        ]);
        Role::create([
            'name' => 'guest',
        ]);
        Role::create([
            'name' => 'union',
        ]);
        Role::create([
            'name' => 'group',
        ]);
        Role::create([
            'name' => 'church',
        ]);
    }
}
