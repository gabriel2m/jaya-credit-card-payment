<?php

namespace App\Models;

use App\Enums\PayerEntityType;
use App\Enums\PayerType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $entity_type
 * @property string $type
 * @property string $email
 * @property string $payer_identification_id
 * @property PayerIdentification $payer_identification
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Payer extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'entity_type',
        'type',
        'email',
        'payer_identification_id',
    ];

    protected $casts = [
        'entity_type' => PayerEntityType::class,
        'type' => PayerType::class,
    ];

    protected $attributes = [
        'entity_type' => PayerEntityType::INDIVIDUAL->value,
        'type' => PayerType::CUSTOMER->value,
    ];

    public function payerIdentification(): HasOne
    {
        return $this->hasOne(PayerIdentification::class);
    }
}
