<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemncSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = rand(1,9);
        
        $items = [
        [
            'item12nc' => '4206 136 58775', 
            'partname' => 'Mail Interface Turbo', 
            'type' => 'Turbo Module',        // sub-jenis / spesifikasi
            'whq' => 1000, 
            'unit' => 'Pcs', 
            'category' => $category,
        ],
        [
            'item12nc' => '4206 136 58776', 
            'partname' => 'Hydraulic Control Valve', 
            'type' => 'Valve Type X', 
            'whq' => 500, 
            'unit' => 'Pcs', 
            'category' => $category,
        ],
        [
            'item12nc' => '4206 136 58777', 
            'partname' => 'Electronic Sensor Module', 
            'type' => 'Sensor Module V2', 
            'whq' => 750, 
            'unit' => 'Pcs', 
            'category' => $category,
        ],
        [
            'item12nc' => '4206 136 58778', 
            'partname' => 'Cooling Fan Assembly', 
            'type' => 'Fan 12V', 
            'whq' => 1200, 
            'unit' => 'Pcs', 
            'category' => $category,
        ],
        [
            'item12nc' => '4206 136 58779', 
            'partname' => 'Power Supply Unit', 
            'type' => 'PSU 500W', 
            'whq' => 300, 
            'unit' => 'Pcs', 
            'category' => $category,
        ],
    ];


        foreach ($items as $item) {
            \App\Models\Itemnc::create($item);
        }
    }
}
