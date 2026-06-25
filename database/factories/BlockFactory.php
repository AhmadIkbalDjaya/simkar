<?php

namespace Database\Factories;

use App\Enums\BlockStatus;
use App\Models\Block;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Block>
 */
class BlockFactory extends Factory
{
    public function definition(): array
    {
        $code = strtoupper(fake()->unique()->bothify('BLK-##'));

        return [
            'code' => $code,
            'name' => 'Blok '.$code,
            'status' => BlockStatus::Active,
        ];
    }
}
