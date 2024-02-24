<?php

namespace App\Contracts\Services;

use App\Models\Payment;

interface PaymentService
{
    public function create(array $data): Payment;
}
