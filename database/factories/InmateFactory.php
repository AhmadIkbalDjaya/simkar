<?php

namespace Database\Factories;

use App\Enums\GenderType;
use App\Models\Inmate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inmate>
 */
class InmateFactory extends Factory
{
    public function definition(): array
    {
        $gender = fake()->randomElement(GenderType::cases());

        return [
            'registration_number' => 'NPI-'.fake()->unique()->numerify('#####'),
            'name' => $gender === GenderType::Male ? fake()->name('male') : fake()->name('female'),
            'gender' => $gender,
            'current_room_id' => null,
        ];
    }
}
