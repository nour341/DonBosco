<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $items = [
            ['id' => 1, 'name' => 'Food'],            // الطعام
            ['id' => 2, 'name' => 'Equipment'],       // المعدات
            ['id' => 3, 'name' => 'Salaries Wages'],  // أجور الرواتب
        ];

        // Insert the items into the database
        Item::insert($items);
    }
}
