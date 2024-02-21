<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property float $transaction_amount
 * @property int $installments
 * @property string $token
 * @property string $payment_method_id
 * @property string $payer_id
 * @property Payer $payer
 * @property string $notification_url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $status
 */
class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'transaction_amount',
        'installments',
        'token',
        'payment_method_id',
        'payer_id',
        'notification_url',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'status' => PaymentStatus::class,
    ];

    protected $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $this->attributes();
        parent::__construct($attributes);
    }

    public function attributes(): array
    {
        return [
            'status' => PaymentStatus::PENDING->value,
            'notification_url' => config('app.notification_url'),
        ];
    }

    public function payer(): HasOne
    {
        return $this->hasOne(Payer::class);
    }
}
