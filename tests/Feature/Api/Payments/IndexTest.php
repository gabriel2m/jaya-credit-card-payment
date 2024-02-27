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

class IndexTest extends TestCase
{
    use RefreshDatabase,
        TestNeedsValidToken;

    public function test_can_return_payments_paginated_list(): void
    {
        Passport::actingAs(User::factory()->create());

        $perPage = Payment::make()->getPerPage();

        Payment::factory($perPage + 1)->create();

        $this
            ->getJson(route('payments.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => PaymentResource::collection(
                    Payment::query()->take($perPage)->get()
                )->resolve(),
                'meta' => [],
                'links' => [],
            ]);
    }
}
