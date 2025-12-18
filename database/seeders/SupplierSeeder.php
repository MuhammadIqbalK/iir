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
                'supplierName' => 'Acme Corp',
                'supplierCode' => 'SUP001',
                'contactPerson' => 'John Coyote',
                'email' => 'john@acme.com',
                'phone' => '555-0101',
                'address' => '123 Desert Road',
                'status' => 'Active',
            ],
            [
                'supplierName' => 'Globex Corporation',
                'supplierCode' => 'SUP002',
                'contactPerson' => 'Hank Scorpio',
                'email' => 'hank@globex.com',
                'phone' => '555-0102',
                'address' => '456 Volcano Lair',
                'status' => 'Active',
            ],
            [
                'supplierName' => 'Soylent Corp',
                'supplierCode' => 'SUP003',
                'contactPerson' => 'Richard Thornhill',
                'email' => 'richard@soylent.com',
                'phone' => '555-0103',
                'address' => '789 Green Avenue',
                'status' => 'Inactive',
            ],
            [
                'supplierName' => 'Initech',
                'supplierCode' => 'SUP004',
                'contactPerson' => 'Bill Lumbergh',
                'email' => 'bill@initech.com',
                'phone' => '555-0104',
                'address' => '101 Cubicle Way',
                'status' => 'Active',
            ],
            [
                'supplierName' => 'Umbrella Corp',
                'supplierCode' => 'SUP005',
                'contactPerson' => 'Albert Wesker',
                'email' => 'albert@umbrella.com',
                'phone' => '555-0105',
                'address' => '666 Raccoon City',
                'status' => 'Blocked',
            ],
            [
                'supplierName' => 'Stark Industries',
                'supplierCode' => 'SUP006',
                'contactPerson' => 'Pepper Potts',
                'email' => 'pepper@stark.com',
                'phone' => '555-0106',
                'address' => '10880 Malibu Point',
                'status' => 'Active',
            ],
            [
                'supplierName' => 'Wayne Enterprises',
                'supplierCode' => 'SUP007',
                'contactPerson' => 'Lucius Fox',
                'email' => 'lucius@wayne.com',
                'phone' => '555-0107',
                'address' => '1007 Mountain Drive',
                'status' => 'Active',
            ],
            [
                'supplierName' => 'Cyberdyne Systems',
                'supplierCode' => 'SUP008',
                'contactPerson' => 'Miles Dyson',
                'email' => 'miles@cyberdyne.com',
                'phone' => '555-0108',
                'address' => '18144 El Camino Real',
                'status' => 'Inactive',
            ],
            [
                'supplierName' => 'Massive Dynamic',
                'supplierCode' => 'SUP009',
                'contactPerson' => 'Nina Sharp',
                'email' => 'nina@massivedynamic.com',
                'phone' => '555-0109',
                'address' => '655 18th St',
                'status' => 'Active',
            ],
            [
                'supplierName' => 'Aperture Science',
                'supplierCode' => 'SUP010',
                'contactPerson' => 'Cave Johnson',
                'email' => 'cave@aperture.com',
                'phone' => '555-0110',
                'address' => '4000 Upper Michigan',
                'status' => 'Active',
            ],
        ];

        foreach ($data as $item) {
            \App\Models\Supplier::create([
                'supplierName' => $item['supplierName'],
                'supplierCode' => $item['supplierCode'],
                'contactPerson' => $item['contactPerson'],
                'email' => $item['email'],
                'phone' => $item['phone'],
                'address' => $item['address'],
                'status' => $item['status'] ?? 'Active',
            ]);
        }
    }
}
