<?php

namespace Tests\Feature\Api\Payments;

use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_valid_token(): void
    {
        $this
            ->get(
                route('payments.show', Payment::factory()->create()),
                headers: ['Authorization' => 'Bearer not-valid-token']
            )
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_needs_valid_id(): void
    {
        $this
            ->get(route('payments.show', 'invalid'))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_can_return_a_payment(): void
    {
        Passport::actingAs(User::factory()->create());

        $payment = Payment::factory()->create();

        $this
            ->get(route('payments.show', $payment))
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => PaymentResource::make($payment)->resolve(),
            ]);
    }
}
