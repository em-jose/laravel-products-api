<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\BasicSeeder;

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
            BasicSeeder::class
        ]);
    }
}
