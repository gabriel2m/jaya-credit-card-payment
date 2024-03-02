<?php

namespace Tests\Feature\Api\Payments;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CancelTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_valid_token(): void
    {
        $this
            ->delete(
                route('payments.cancel', Payment::factory()->create()),
                headers: ['Authorization' => 'Bearer not-valid-token']
            )
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_needs_valid_id(): void
    {
        $this
            ->delete(route('payments.cancel', 'invalid'))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_can_cancel_a_payment(): void
    {
        Passport::actingAs(User::factory()->create());

        $payment = Payment::factory()->create();

        $this
            ->delete(route('payments.cancel', $payment))
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas($payment->getTable(), [
            'id' => $payment->id,
            'status' => PaymentStatus::CANCELED->value,
        ]);
    }
}
