<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'userid' => 'TESTUSER01',
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'password' => bcrypt('secret'), // password bisa diubah sesuai kebutuhan
        ]);

        $this->call([
            SupplierSeeder::class,
            ExaminerSeeder::class,
            ItemncSeeder::class,
            IncomingPartReportSeeder::class,
        ]);
    }
}
