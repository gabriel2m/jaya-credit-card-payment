<?php

namespace Tests\Feature\Services;

use App\Contracts\Services\PaymentService;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_creat_a_payment(): void
    {
        $data = $this->newPaymentData();

        app(PaymentService::class)->create($data->toArray());

        $this->assertPaymentData(Payment::first(), $data);
    }
}
