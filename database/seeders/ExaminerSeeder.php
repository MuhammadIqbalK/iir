<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExaminerSeeder extends Seeder
{
    public function run(): void
    {
        $examiners = [
            ['name' => 'Budi Santoso',      'employee_id' => 'EMP001', 'email' => 'budi.santoso@example.com'],
            ['name' => 'Siti Aisyah',       'employee_id' => 'EMP002', 'email' => 'siti.aisyah@example.com'],
            ['name' => 'Andi Pratama',      'employee_id' => 'EMP003', 'email' => 'andi.pratama@example.com'],
            ['name' => 'Dewi Lestari',      'employee_id' => 'EMP004', 'email' => 'dewi.lestari@example.com'],
            ['name' => 'Rizky Kurniawan',   'employee_id' => 'EMP005', 'email' => 'rizky.kurniawan@example.com'],
            ['name' => 'Ayu Permata',       'employee_id' => 'EMP006', 'email' => 'ayu.permata@example.com'],
            ['name' => 'Fajar Hidayat',     'employee_id' => 'EMP007', 'email' => 'fajar.hidayat@example.com'],
            ['name' => 'Nabila Putri',      'employee_id' => 'EMP008', 'email' => 'nabila.putri@example.com'],
            ['name' => 'Agus Setiawan',     'employee_id' => 'EMP009', 'email' => 'agus.setiawan@example.com'],
            ['name' => 'Rina Handayani',    'employee_id' => 'EMP010', 'email' => 'rina.handayani@example.com'],
            ['name' => 'Dedi Saputra',      'employee_id' => 'EMP011', 'email' => 'dedi.saputra@example.com'],
            ['name' => 'Fitri Rahmawati',   'employee_id' => 'EMP012', 'email' => 'fitri.rahmawati@example.com'],
            ['name' => 'Hendra Wijaya',     'employee_id' => 'EMP013', 'email' => 'hendra.wijaya@example.com'],
            ['name' => 'Intan Maharani',    'employee_id' => 'EMP014', 'email' => 'intan.maharani@example.com'],
            ['name' => 'Arif Nugroho',      'employee_id' => 'EMP015', 'email' => 'arif.nugroho@example.com'],
            ['name' => 'Putri Amelia',      'employee_id' => 'EMP016', 'email' => 'putri.amelia@example.com'],
            ['name' => 'Bayu Ramadhan',     'employee_id' => 'EMP017', 'email' => 'bayu.ramadhan@example.com'],
            ['name' => 'Lia Oktaviani',     'employee_id' => 'EMP018', 'email' => 'lia.oktaviani@example.com'],
            ['name' => 'Eko Susanto',       'employee_id' => 'EMP019', 'email' => 'eko.susanto@example.com'],
            ['name' => 'Maya Sari',          'employee_id' => 'EMP020', 'email' => 'maya.sari@example.com'],
        ];

        foreach ($examiners as $examiner) {
            \App\Models\Examiner::create($examiner);
        }
    }
}
