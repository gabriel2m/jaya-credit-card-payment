<?php

namespace Tests\Feature\Api\Payments;

use App\Models\Payer;
use App\Models\PayerIdentification;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_needs_valid_token(): void
    {
        $this
            ->postJson(
                route('payments.store'),
                headers: ['Authorization' => 'Bearer not-valid-token']
            )
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_needs_request_body(): void
    {
        Passport::actingAs(User::factory()->create());

        $this
            ->post(route('payments.store'))
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidData
     */
    public function test_needs_valid_data(string $attr, mixed $value): void
    {
        Passport::actingAs(User::factory()->create());

        $data = $this->newPaymentData()->toArray();
        data_set($data, $attr, $value);

        $this
            ->postJson(route('payments.store'), $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseEmpty(Payment::class);
        $this->assertDatabaseEmpty(Payer::class);
        $this->assertDatabaseEmpty(PayerIdentification::class);
    }

    public static function invalidData()
    {
        return [
            'transaction_amount empty' => ['transaction_amount', ''],
            'transaction_amount string' => ['transaction_amount', 'invalid'],
            'transaction_amount too low' => ['transaction_amount', 0],
            'transaction_amount too big' => ['transaction_amount', PHP_INT_MAX],
            'transaction_amount too many decimals' => ['transaction_amount', '0.001'],
            'installments empty' => ['installments', ''],
            'installments string' => ['installments', 'invalid'],
            'installments too low' => ['installments', 0],
            'installments too big' => ['installments', 1000],
            'token empty' => ['token', ''],
            'token invalid' => ['token', ':'],
            'token too long' => ['token', str_repeat('a', 256)],
            'payment_method_id empty' => ['payment_method_id', ''],
            'payment_method_id too long' => ['payment_method_id', str_repeat('a', 256)],
            'payer empty' => ['payer', ''],
            'payer string' => ['payer', 'invalid'],
            'payer.email empty' => ['payer.email', ''],
            'payer.email invalid' => ['payer.email', 'invalid'],
            'payer.email too long' => ['payer.email', str_repeat('a', 255).fake()->email()],
            'payer.identification.type empty' => ['payer.identification.type', ''],
            'payer.identification.type invalid' => ['payer.identification.type', 'invalid-type'],
            'payer.identification.number empty' => ['payer.identification.number', ''],
            'payer.identification.number invalid' => ['payer.identification.number', '12345678900'],
        ];
    }

    public function test_can_creat_a_payment(): void
    {
        Passport::actingAs(User::factory()->create());

        $data = $this->newPaymentData();

        $response = $this
            ->postJson(route('payments.store'), $data->toArray())
            ->assertStatus(Response::HTTP_CREATED);

        $payment = Payment::first();

        $response->assertExactJson(
            $payment->setVisible([
                'id',
                'created_at',
            ])->toArray()
        );

        $this->assertPaymentData($payment, $data);
    }
}
