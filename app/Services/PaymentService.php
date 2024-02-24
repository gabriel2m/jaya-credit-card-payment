<?php

namespace App\Services;

use App\Contracts\Services\PaymentService as PaymentServiceContract;
use App\Models\Payer;
use App\Models\PayerIdentification;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService implements PaymentServiceContract
{
    /**
     * Store a newly created Payment in storage.
     */
    public function create(array $data): Payment
    {
        $data = collect($data)->dot();

        return DB::transaction(function () use ($data) {
            return Payment::create(
                $data->only([
                    'transaction_amount',
                    'installments',
                    'token',
                    'payment_method_id',
                ])->merge([
                    'payer_id' => Payer::create([
                        'email' => $data->get('payer.email'),
                        'payer_identification_id' => PayerIdentification::create([
                            'type' => $data->get('payer.identification.type'),
                            'number' => $data->get('payer.identification.number'),
                        ])->id,
                    ])->id,
                ])->toArray()
            );
        });
    }
}
