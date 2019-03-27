<?php

use Illuminate\Database\Seeder;

class UnionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Union::class, 200)->create();
    }
}
