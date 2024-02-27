<?php

namespace Tests\Feature\Api\Payments;

use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\Atoms\TestNeedsValidToken;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase,
        TestNeedsValidToken;

    public function test_needs_valid_id(): void
    {
        $this
            ->getJson(route('payments.show', 'invalid'))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_can_return_a_payment(): void
    {
        Passport::actingAs(User::factory()->create());

        $payment = Payment::factory()->create();

        $this
            ->getJson(route('payments.show', $payment))
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => PaymentResource::make($payment)->resolve(),
            ]);
    }
}
