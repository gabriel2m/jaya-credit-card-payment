<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Payer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_amount' => fake()->randomFloat(nbMaxDecimals: 2, min: 0.01, max: max_amount_float_value()),
            'installments' => fake()->numberBetween(1, 999),
            'token' => fake()->regexify('[A-Za-z0-9]{32}'),
            'payment_method_id' => fake()->creditCardType(),
            'payer_id' => Payer::factory(),
            'status' => fake()->randomElement(PaymentStatus::cases()),
        ];
    }
}
