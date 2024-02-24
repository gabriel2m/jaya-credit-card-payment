<?php

namespace Database\Factories;

use App\Enums\PayerIdentificationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PayerIdentification>
 */
class PayerIdentificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $type = fake()->randomElement(PayerIdentificationType::cases()),
            'number' => match ($type) {
                PayerIdentificationType::CPF => fake()->cpf(),
                default => null,
            },
        ];
    }
}
