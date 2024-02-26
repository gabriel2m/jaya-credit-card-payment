<?php

namespace App\Models;

use App\Casts\Money;
use App\Enums\PaymentStatus;
use Brick\Money\Money as BrickMoney;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property BrickMoney $transaction_amount
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
        'transaction_amount' => Money::class,
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'status' => PaymentStatus::class,
    ];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $this->attributes();
        parent::__construct($attributes);
    }

    /**
     * Default values for attributes.
     */
    protected function attributes(): array
    {
        return [
            'status' => PaymentStatus::PENDING->value,
            'notification_url' => config('app.notification_url'),
        ];
    }

    public function payer(): BelongsTo
    {
        return $this->belongsTo(Payer::class);
    }
}
