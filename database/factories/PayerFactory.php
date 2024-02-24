<?php

namespace Database\Factories;

use App\Enums\PayerEntityType;
use App\Enums\PayerType;
use App\Models\PayerIdentification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payer>
 */
class PayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'entity_type' => fake()->randomElement(PayerEntityType::cases()),
            'type' => fake()->randomElement(PayerType::cases()),
            'email' => fake()->email(),
            'payer_identification_id' => PayerIdentification::factory(),
        ];
    }
}
