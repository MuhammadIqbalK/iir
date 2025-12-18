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
            ['item12nc' => '4206 136 58775', 'partname' => 'Mail Interface Turbo', 'quality' => 'A', 'quantity' => 1000],
            ['item12nc' => '4206 136 58776', 'partname' => 'Hydraulic Control Valve', 'quality' => 'B', 'quantity' => 500],
            ['item12nc' => '4206 136 58777', 'partname' => 'Electronic Sensor Module', 'quality' => 'A', 'quantity' => 750],
            ['item12nc' => '4206 136 58778', 'partname' => 'Cooling Fan Assembly', 'quality' => 'A', 'quantity' => 1200],
            ['item12nc' => '4206 136 58779', 'partname' => 'Power Supply Unit', 'quality' => 'C', 'quantity' => 300],
        ];

        foreach ($items as $item) {
            \App\Models\Itemnc::create($item);
        }
    }
}
