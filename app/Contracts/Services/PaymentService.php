<?php

namespace App\Contracts\Services;

use App\Models\Payment;

interface PaymentService
{
    /**
     * Store a newly created Payment in storage.
     */
    public function create(array $data): Payment;

    /**
     * Update the specified resource in storage.
     */
    public function update(Payment $payment, array $data): bool;
}
