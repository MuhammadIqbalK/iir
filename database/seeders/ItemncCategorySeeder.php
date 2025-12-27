<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemncCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['id' => 0, 'description' => 'FG Purchase'],
            ['id' => 1, 'description' => 'FG Own Product'],
            ['id' => 2, 'description' => 'Component RM Local'],
            ['id' => 3, 'description' => 'Component RM Import'],
            ['id' => 4, 'description' => 'Service Spareparts'],
            ['id' => 5, 'description' => 'Plastic Parts'],
            ['id' => 6, 'description' => 'Common'],
            ['id' => 8, 'description' => 'BOM (Sell Price fr Comp)'],
            ['id' => 9, 'description' => 'BOM (Sell Price fr Item)']
        ];

        foreach ($categories as $category) {
            DB::table('itemncs_category')->insert([
                'id' => $category['id'],
                'description' => $category['description'],
            ]);
        }
    }
}
