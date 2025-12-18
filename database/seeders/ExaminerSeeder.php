<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExaminerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $examiners = [
            ['name' => 'John Doe', 'employee_id' => 'EMP001', 'email' => 'john.doe@example.com'],
            ['name' => 'Sarah Miller', 'employee_id' => 'EMP002', 'email' => 'sarah.miller@example.com'],
            ['name' => 'David Wilson', 'employee_id' => 'EMP003', 'email' => 'david.wilson@example.com'],
            ['name' => 'Emily Carter', 'employee_id' => 'EMP004', 'email' => 'emily.carter@example.com'],
            ['name' => 'James Anderson', 'employee_id' => 'EMP005', 'email' => 'james.anderson@example.com'],
            ['name' => 'Anna White', 'employee_id' => 'EMP006', 'email' => 'anna.white@example.com'],
            ['name' => 'Mark Taylor', 'employee_id' => 'EMP007', 'email' => 'mark.taylor@example.com'],
            ['name' => 'Rachel Adams', 'employee_id' => 'EMP008', 'email' => 'rachel.adams@example.com'],
            ['name' => 'Steven Brown', 'employee_id' => 'EMP009', 'email' => 'steven.brown@example.com'],
            ['name' => 'Linda Harris', 'employee_id' => 'EMP010', 'email' => 'linda.harris@example.com'],
            ['name' => 'Paul Young', 'employee_id' => 'EMP011', 'email' => 'paul.young@example.com'],
            ['name' => 'Nancy Hall', 'employee_id' => 'EMP012', 'email' => 'nancy.hall@example.com'],
            ['name' => 'Kevin Lewis', 'employee_id' => 'EMP013', 'email' => 'kevin.lewis@example.com'],
            ['name' => 'Diana Walker', 'employee_id' => 'EMP014', 'email' => 'diana.walker@example.com'],
            ['name' => 'Brian King', 'employee_id' => 'EMP015', 'email' => 'brian.king@example.com'],
            ['name' => 'Olivia Perez', 'employee_id' => 'EMP016', 'email' => 'olivia.perez@example.com'],
            ['name' => 'Jason Moore', 'employee_id' => 'EMP017', 'email' => 'jason.moore@example.com'],
            ['name' => 'Cynthia Reed', 'employee_id' => 'EMP018', 'email' => 'cynthia.reed@example.com'],
            ['name' => 'George Collins', 'employee_id' => 'EMP019', 'email' => 'george.collins@example.com'],
            ['name' => 'Melissa Ward', 'employee_id' => 'EMP020', 'email' => 'melissa.ward@example.com'],
        ];

        foreach ($examiners as $examiner) {
            \App\Models\Examiner::create($examiner);
        }
    }
}
