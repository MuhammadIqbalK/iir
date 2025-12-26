<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IncomingPartReport;

class IncomingPartReportSeeder extends Seeder
{
    public function run(): void
    {
        $itemncOptions = [
            '4206 136 58776',
            '4206 136 58777',
            '4206 136 58778',
            '4206 136 58779',
        ];

        $supplierOptions = [
            'SUP001',
            'SUP002',
            'SUP003',
            'SUP004',
            'SUP005',
            'SUP006',
            'SUP007',
            'SUP008',
            'SUP009',
            'SUP010',
        ];

        $data = [];

        for ($i = 1; $i <= 20; $i++) {
            $data[] = [
                'iirdate' => now()->addDays($i)->format('Y-m-d'),
                'itemnc' => $itemncOptions[array_rand($itemncOptions)], // acak dari daftar
                'nodoc' => rand(100000000, 999999999),
                'quantity' => rand(100, 1200),
                'samplesize' => rand(50, 150),
                'gilevel' => rand(1, 3),
                'examiner_id' => rand(1, 20),
                'start' => sprintf("%02d:%02d", rand(8, 15), rand(0, 59)),
                'end' => sprintf("%02d:%02d", rand(9, 17), rand(0, 59)),
                'duration' => sprintf("%02d:%02d", rand(0, 2), rand(0, 59)),
                'supplier_code' => $supplierOptions[array_rand($supplierOptions)],
                'option' => rand(0,1) ? 'Local' : 'Import',
                'batch' => 1,
                'status' => rand(0,1) ? 'N' : 'Y',
            ];
        }

        foreach ($data as $item) {
            IncomingPartReport::create($item);
        }
    }
}
