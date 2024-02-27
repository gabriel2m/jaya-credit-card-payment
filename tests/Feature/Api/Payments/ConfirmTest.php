<?php

namespace Tests\Feature\Api\Payments;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ConfirmTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_valid_token(): void
    {
        $this
            ->patchJson(
                route('payments.confirm', Payment::factory()->create()),
                headers: ['Authorization' => 'Bearer not-valid-token']
            )
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_needs_valid_id(): void
    {
        $this
            ->patchJson(route('payments.confirm', 'invalid'))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider invalidData
     */
    public function test_needs_valid_data(array $data): void
    {
        Passport::actingAs(User::factory()->create());

        $payment = Payment::factory()->create();
        $old = $payment->toArray();

        $this
            ->patchJson(route('payments.confirm', $payment), $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $new = $payment->refresh()->toArray();
        $this->assertEquals($old, $new);
    }

    public static function invalidData()
    {
        return [
            'status empty' => [['status' => '']],
            'status invalid' => [['status' => PaymentStatus::CANCELED->value]],
        ];
    }

    public function test_can_confirm_a_payment(): void
    {
        Passport::actingAs(User::factory()->create());

        $payment = Payment::factory()->create();
        $data = [
            'status' => PaymentStatus::PAID->value,
        ];

        $this
            ->patchJson(route('payments.confirm', $payment), $data)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $data['id'] = $payment->id;

        $this->assertDatabaseHas($payment->getTable(), $data);
    }
}
