<?php

namespace LisaFehr\Gallery\Database\Seeders;

use Illuminate\Database\Seeder;

class GalleryDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            GallerySeeder::class,
            TagSeeder::class,
            TagAssocSeeder::class,
        ]);
    }
}
