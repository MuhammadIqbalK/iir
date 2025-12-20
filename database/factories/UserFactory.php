<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'userid' => $this->faker->unique()->userName, // primary key custom
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // default password
            'groupid' => $this->faker->randomElement(['ADMIN', 'USER', 'GUEST']),
            'statuslogin' => $this->faker->randomElement(['0', '1']),
            'menusecurity' => null,
            'lastmoduser' => null,
            'lastmoddate' => now(),
            'properties' => null,
            'remember_token' => Str::random(10),
        ];
    }
}
