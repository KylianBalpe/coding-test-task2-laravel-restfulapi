<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            "name" => "John Doe",
            "email" => "admin@example.com",
            "password" => bcrypt("password"),
            "role" => "user",
        ];
    }

    public function admin(): static
    {
        return $this->state([
            "role" => "admin",
        ]);
    }
}
