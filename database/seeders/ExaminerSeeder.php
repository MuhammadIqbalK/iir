<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExaminerSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            ['name' => 'Dedi D'],
            ['name' => 'Banu'],
            ['name' => 'Tri Sani'],
            ['name' => 'Ahmad Syarifudin'],
            ['name' => 'Irwan Sofyan KB'],
            ['name' => 'Yuli W'],
            ['name' => 'Sarwono'],
            ['name' => 'Endang S'],
            ['name' => 'Suyanto'],
            ['name' => 'Anji'],
            ['name' => 'Syafri'],
            ['name' => 'Agus Kusnadi'],
            ['name' => 'Hendi'],
            ['name' => 'Bambang'],
            ['name' => 'Henyanto'],
        ];

        foreach ($names as $name) {
            \App\Models\Examiner::insert([
                'name' => $name['name'],
            ]);
        }
    }
}
