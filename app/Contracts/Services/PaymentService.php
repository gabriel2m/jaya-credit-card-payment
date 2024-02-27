<?php

namespace App\Contracts\Services;

use App\Models\Payment;

interface PaymentService
{
    /**
     * Store a newly created payment in storage.
     */
    public function create(array $data): Payment;

    /**
     * Set the payment as paid in storage.
     */
    public function confirm(Payment $payment): bool;

    /**
     * Set the payment as canceled in storage.
     */
    public function cancel(Payment $payment): bool;
}
