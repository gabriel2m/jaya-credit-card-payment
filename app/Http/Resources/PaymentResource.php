<?php

namespace App\Http\Resources;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var Payment
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return collect(
            $this->resource->setVisible([
                'id',
                'status',
                'transaction_amount',
                'installments',
                'token',
                'payment_method_id',
                'notification_url',
                'created_at',
                'updated_at',
            ])
        )->put(
            'payer',
            collect(
                $this->resource->payer->setVisible([
                    'entity_type',
                    'type',
                    'email',
                ])
            )->put(
                'identification',
                $this->resource->payer->payer_identification->setVisible([
                    'type',
                    'number',
                ])
            )
        )->toArray();
    }
}
