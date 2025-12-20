<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_name' => 'PT Sumber Makmur Abadi',
                'supplier_code' => 'SUP001',
                'contact_person' => 'John Coyote',
                'email' => 'john@acme.com',
                'phone' => '555-0101',
                'address' => '123 Desert Road',
                'status' => 'Active',
            ],
            [
                'supplier_name' => 'PT Global Teknologi Nusantara',
                'supplier_code' => 'SUP002',
                'contact_person' => 'Hank Scorpio',
                'email' => 'hank@globex.com',
                'phone' => '555-0102',
                'address' => '456 Volcano Lair',
                'status' => 'Active',
            ],
            [
                'supplier_name' => 'PT Pangan Sejahtera Indonesia',
                'supplier_code' => 'SUP003',
                'contact_person' => 'Richard Thornhill',
                'email' => 'richard@soylent.com',
                'phone' => '555-0103',
                'address' => '789 Green Avenue',
                'status' => 'Inactive',
            ],
            [
                'supplier_name' => 'PT Solusi Digital Mandiri',
                'supplier_code' => 'SUP004',
                'contact_person' => 'Bill Lumbergh',
                'email' => 'bill@initech.com',
                'phone' => '555-0104',
                'address' => '101 Cubicle Way',
                'status' => 'Active',
            ],
            [
                'supplier_name' => 'PT Bio Farma Utama',
                'supplier_code' => 'SUP005',
                'contact_person' => 'Albert Wesker',
                'email' => 'albert@umbrella.com',
                'phone' => '555-0105',
                'address' => '666 Raccoon City',
                'status' => 'Blocked',
            ],
            [
                'supplier_name' => 'PT Industri Baja Nusantara',
                'supplier_code' => 'SUP006',
                'contact_person' => 'Pepper Potts',
                'email' => 'pepper@stark.com',
                'phone' => '555-0106',
                'address' => '10880 Malibu Point',
                'status' => 'Active',
            ],
            [
                'supplier_name' => 'PT Properti Cipta Sejahtera',
                'supplier_code' => 'SUP007',
                'contact_person' => 'Lucius Fox',
                'email' => 'lucius@wayne.com',
                'phone' => '555-0107',
                'address' => '1007 Mountain Drive',
                'status' => 'Active',
            ],
            [
                'supplier_name' => 'PT Sistem Otomasi Indonesia',
                'supplier_code' => 'SUP008',
                'contact_person' => 'Miles Dyson',
                'email' => 'miles@cyberdyne.com',
                'phone' => '555-0108',
                'address' => '18144 El Camino Real',
                'status' => 'Inactive',
            ],
            [
                'supplier_name' => 'PT Dinamika Energi Nasional',
                'supplier_code' => 'SUP009',
                'contact_person' => 'Nina Sharp',
                'email' => 'nina@massivedynamic.com',
                'phone' => '555-0109',
                'address' => '655 18th St',
                'status' => 'Active',
            ],
            [
                'supplier_name' => 'PT Riset Teknologi Terapan',
                'supplier_code' => 'SUP010',
                'contact_person' => 'Cave Johnson',
                'email' => 'cave@aperture.com',
                'phone' => '555-0110',
                'address' => '4000 Upper Michigan',
                'status' => 'Active',
            ],
        ];

        foreach ($data as $item) {
            \App\Models\Supplier::create([
                'supplier_name' => $item['supplier_name'],
                'supplier_code' => $item['supplier_code'],
                'contact_person' => $item['contact_person'],
                'email' => $item['email'],
                'phone' => $item['phone'],
                'address' => $item['address'],
                'status' => $item['status'] ?? 'Active',
            ]);
        }
    }
}
