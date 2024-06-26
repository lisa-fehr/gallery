<?php

namespace LisaFehr\Gallery\Database\Seeders;

use LisaFehr\Gallery\Models\UberGallery;
use LisaFehr\Gallery\Models\UberTagAssoc;
use LisaFehr\Gallery\Models\UberTags;
use Illuminate\Database\Seeder;

class TagAssocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UberTagAssoc::truncate();

        $california2014 = UberTags::where('name', 'California2014')->first();

        $images = [
            ['JapanLA_1'],
            ['JapanLA_2'],
            ['JapanLA_3'],
            ['JapanLA_4'],
            ['Aquarium_1'],
            ['Aquarium_2'],
            ['Aquarium_3'],
            ['Aquarium_4'],
            ['Aquarium_5'],
            ['Aquarium_7'],
            ['Aquarium_8'],
        ];

        collect($images)->each(function ($item) use ($california2014) {
            $image = UberGallery::where('img', $item)->first();
            UberTagAssoc::create([
                'tag_id' => $california2014->id,
                'image_id' => $image->id,
            ]);
        });
    }
}
