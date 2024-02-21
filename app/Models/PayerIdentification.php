<?php

namespace App\Models;

use App\Enums\PayerIdentificationType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $type
 * @property string $number
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class PayerIdentification extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'type',
        'number',
    ];

    protected $casts = [
        'type' => PayerIdentificationType::class,
    ];
}
