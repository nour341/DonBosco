<?php

namespace Database\Seeders;

use App\Models\Center;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $centers =  [
            [
            "id"=> 1,
            "name"=> "Jaramana",
            "address"=> "damascus",
            "image_path"=> "images/Center/1714725245.pexels-elti-meshau-107925-333850.jpg",
            "country_id"=> 213
        ],
        [
            "id"=> 2,
            "name"=> "Aleppo1",
            "address"=> "Aleppo",
            "image_path"=> "images/Center/1714725318.pexels-elti-meshau-107925-333850.jpg",
            "country_id"=> 213
        ]
        ];
        Center::insert($centers);
    }
}
