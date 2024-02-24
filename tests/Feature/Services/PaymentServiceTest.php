<?php

namespace Tests\Feature\Services;

use App\Contracts\Services\PaymentService;
use App\Models\Payer;
use App\Models\PayerIdentification;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_creat_a_payment(): void
    {
        $payment_attrs = [
            'transaction_amount',
            'installments',
            'token',
            'payment_method_id',
        ];
        $payer_attrs = [
            'email',
        ];
        $payer_identification_attrs = [
            'type',
            'number',
        ];

        $data = collect(
            Payment::factory()
                ->makeOne(['payer_id' => null])
                ->setVisible($payment_attrs)
                ->toArray()
        )->put(
            'payer',
            collect(
                Payer::factory()
                    ->makeOne(['payer_identification_id' => null])
                    ->only($payer_attrs)
            )->put(
                'identification',
                PayerIdentification::factory()
                    ->makeOne()
                    ->setVisible($payer_identification_attrs)
                    ->toArray()
            )
        );

        app(PaymentService::class)->create($data->toArray());

        /** @var Payment */
        $payment = Payment::first();
        $this->assertEquals(
            $payment->setVisible($payment_attrs)->toArray(),
            $data->only($payment_attrs)->toArray()
        );
        $this->assertEquals(
            $payment->payer->setVisible($payer_attrs)->toArray(),
            $data->get('payer')->only($payer_attrs)->toArray()
        );
        $this->assertEquals(
            $payment->payer->payer_identification->setVisible($payer_identification_attrs)->toArray(),
            $data->get('payer')->get('identification')
        );
    }
}
