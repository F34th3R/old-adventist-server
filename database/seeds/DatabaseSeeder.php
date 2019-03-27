<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            UnionsTableSeeder::class,
            GroupsTableSeeder::class,
            ChurchesTableSeeder::class,
            DepartmentsTableSeeder::class,
            ImagesTableSeeder::class,
            AdvertisementsTableSeeder::class,
        ]);
    }
}
