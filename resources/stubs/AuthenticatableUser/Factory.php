<?php

namespace Database\Factories;

use App\Models\Dummy;
use Illuminate\Database\Eloquent\Factories\Factory;

class DummyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dummy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->numerify('dummy00####'),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'display_name' => $this->faker->name(),
            'is_enabled' => true,
        ];
    }
}
