<?php

namespace Database\Factories;

use App\Enums\GenderType;
use App\Enums\InmateStatus;
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

        $admissionDate = fake()->dateTimeBetween('-2 years', '-1 month');
        $placementDate = fake()->dateTimeBetween($admissionDate, 'now');
        $expirationDate = fake()->dateTimeBetween('now', '+3 years');

        return [
            'registration_number' => 'NPI-'.fake()->unique()->numerify('#####'),
            'name' => $gender === GenderType::Male ? fake()->name('male') : fake()->name('female'),
            'gender' => $gender,
            'crime_type' => fake()->randomElement([
                'Pencurian',
                'Narkotika',
                'Penipuan',
                'Penganiayaan',
                'Korupsi',
            ]),
            'admission_date' => $admissionDate,
            'placement_date' => $placementDate,
            'expiration_date' => $expirationDate,
            'status' => InmateStatus::Active,
            'current_room_id' => null,
        ];
    }
}
