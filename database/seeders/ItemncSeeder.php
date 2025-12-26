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
        
        $items = [
        [
            'item12nc' => '4206 136 58775', 
            'partname' => 'Mail Interface Turbo', 
            'type' => 'Turbo Module',        // sub-jenis / spesifikasi
            'quality' => 'A', 
            'quantity' => 1000, 
            'unit' => 'Pcs', 
            'status' => 'Active',
        ],
        [
            'item12nc' => '4206 136 58776', 
            'partname' => 'Hydraulic Control Valve', 
            'type' => 'Valve Type X', 
            'quality' => 'B', 
            'quantity' => 500, 
            'unit' => 'Pcs', 
            'status' => 'Active',
        ],
        [
            'item12nc' => '4206 136 58777', 
            'partname' => 'Electronic Sensor Module', 
            'type' => 'Sensor Module V2', 
            'quality' => 'A', 
            'quantity' => 750, 
            'unit' => 'Pcs', 
            'status' => 'Active',
        ],
        [
            'item12nc' => '4206 136 58778', 
            'partname' => 'Cooling Fan Assembly', 
            'type' => 'Fan 12V', 
            'quality' => 'A', 
            'quantity' => 1200, 
            'unit' => 'Pcs', 
            'status' => 'Active',
        ],
        [
            'item12nc' => '4206 136 58779', 
            'partname' => 'Power Supply Unit', 
            'type' => 'PSU 500W', 
            'quality' => 'C', 
            'quantity' => 300, 
            'unit' => 'Pcs', 
            'status' => 'Active',
        ],
    ];


        foreach ($items as $item) {
            \App\Models\Itemnc::create($item);
        }
    }
}
