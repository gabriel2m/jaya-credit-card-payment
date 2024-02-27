<?php

namespace Tests\Feature\Services;

use App\Contracts\Services\PaymentService;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    public PaymentService $paymentService;

    public function setUp(): void
    {
        parent::setUp();
        $this->paymentService = app(PaymentService::class);
    }

    public function test_can_creat_a_payment(): void
    {
        $data = $this->newPaymentData();

        $this->paymentService->create($data->toArray());

        $this->assertPaymentData(Payment::first(), $data);
    }

    public function test_can_confirm_a_payment(): void
    {
        $payment = Payment::factory()->create();

        $this->paymentService->confirm($payment);

        $this->assertDatabaseHas($payment->getTable(), [
            'id' => $payment->id,
            'status' => PaymentStatus::PAID->value,
        ]);
    }
}
