<?php

namespace Tests;

use App\Models\Payer;
use App\Models\PayerIdentification;
use App\Models\Payment;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Collection;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public array $payment_data_attrs = [
        'transaction_amount',
        'installments',
        'token',
        'payment_method_id',
    ];

    public array $payer_data_attrs = [
        'email',
    ];

    public array $payer_identification_data_attrs = [
        'type',
        'number',
    ];

    public function newPaymentData(): Collection
    {
        return collect(
            Payment::factory()
                ->makeOne(['payer_id' => null])
                ->setVisible($this->payment_data_attrs)
        )->put(
            'payer',
            collect(
                Payer::factory()
                    ->makeOne(['payer_identification_id' => null])
                    ->only($this->payer_data_attrs)
            )->put(
                'identification',
                PayerIdentification::factory()
                    ->makeOne()
                    ->setVisible($this->payer_identification_data_attrs)
            )
        );
    }

    public function assertPaymentData(Payment $payment, Collection $data): void
    {
        $this->assertEquals(
            $payment->setVisible($this->payment_data_attrs)->toArray(),
            $data->only($this->payment_data_attrs)->toArray()
        );
        $this->assertEquals(
            $payment->payer->setVisible($this->payer_data_attrs)->toArray(),
            $data->get('payer')->only($this->payer_data_attrs)->toArray()
        );
        $this->assertEquals(
            $payment->payer->payer_identification->setVisible($this->payer_identification_data_attrs)->toArray(),
            $data->get('payer')->get('identification')->toArray()
        );
    }
}
