<?php

namespace Docs;

use App\Enums\PayerEntityType;
use App\Enums\PayerIdentificationType;
use App\Enums\PayerType;
use App\Enums\PaymentStatus;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\OpenApi(
    openapi: '3.0.0',
    info: new OA\Info(
        version: '1.0.0',
        description: 'Rest API to credit card payments management',
        title: 'Rest API'
    ),
    servers: [new OA\Server(url: '/rest')]
), OA\Get(
    path: '/payments',
    tags: ['payments'],
    description: 'Payments paginated list',
    responses: [
        new OA\Response(response: Response::HTTP_OK, description: 'OK', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/PaymentResource')),
            new OA\Property(property: 'links', type: 'object', properties: [
                new OA\Property(property: 'first', type: 'string', example: 'https://host-url/rest/payments?page=1'),
                new OA\Property(property: 'last', type: 'string', example: 'https://host-url/rest/payments?page=2'),
                new OA\Property(property: 'prev', type: 'string', example: null),
                new OA\Property(property: 'next', type: 'string', example: 'https://host-url/rest/payments?page=2'),
            ]),
            new OA\Property(property: 'meta', type: 'object', properties: [
                new OA\Property(property: 'path', type: 'string', example: 'https://host-url/rest/payments'),
                new OA\Property(property: 'current_page', type: 'integer', example: 1),
                new OA\Property(property: 'last_page', type: 'integer', example: 2),
                new OA\Property(property: 'per_page', type: 'integer', example: 15),
                new OA\Property(property: 'from', type: 'integer', example: 1),
                new OA\Property(property: 'to', type: 'integer', example: 15),
                new OA\Property(property: 'total', type: 'integer', example: 21),
                new OA\Property(
                    property: 'links',
                    type: 'array',
                    items: new OA\Items(type: 'object', properties: [
                        new OA\Property(property: 'url', type: 'string'),
                        new OA\Property(property: 'label', type: 'string'),
                        new OA\Property(property: 'active', type: 'boolean'),
                    ]),
                    example: [
                        ['url' => null, 'label' => '&laquo; Previous', 'active' => false],
                        ['url' => 'http://localhost/rest/payments?page=1', 'label' => '1', 'active' => true],
                        ['url' => 'http://localhost/rest/payments?page=2', 'label' => 'Next &raquo;', 'active' => false],
                    ],
                ),
            ]),
        ])),
    ]
), OA\Post(
    path: '/payments',
    tags: ['payments'],
    description: 'Create a payment',
    requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StorePaymentRequest')),
    responses: [
        new OA\Response(response: Response::HTTP_CREATED, description: 'payment created', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'id', type: 'string', example: '84e8adbf-1a14-403b-ad73-d78ae19b59bf'),
            new OA\Property(property: 'created_at', type: 'string', example: '2024-01-10'),
        ])),
        new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'payment not provided in the request body', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'message', type: 'string', example: 'payment not provided in the request body'),
        ])),
        new OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'invalid payment provided', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'message', type: 'string', example: 'The transaction amount field is required. (and 1 more errors)'),
            new OA\Property(
                property: 'errors',
                type: 'object',
                properties: [new OA\Property(type: 'array', description: 'attibute name', items: new OA\Items(type: 'string', description: 'error message'))],
                example: [
                    'transaction_amount' => ['The transaction amount field is required.'],
                    'installments' => ['The installments field is required.'],
                ]
            ),
        ])),
    ]
), OA\Get(
    path: '/payments/{id}',
    parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: '84e8adbf-1a14-403b-ad73-d78ae19b59bf')],
    tags: ['payments'],
    description: 'Payment details',
    responses: [
        new OA\Response(response: Response::HTTP_OK, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/PaymentResource')),
        new OA\Response(response: '404', description: 'payment not found with the specified id', content: new OA\JsonContent(
            properties: [new OA\Property(property: 'message', type: 'string', default: 'Payment not found with the specified id')]
        )),
    ]
), OA\Delete(
    path: '/payments/{id}',
    parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: '84e8adbf-1a14-403b-ad73-d78ae19b59bf')],
    tags: ['payments'],
    description: 'Cancel payment',
    responses: [
        new OA\Response(response: Response::HTTP_NO_CONTENT, description: 'canceled'),
        new OA\Response(response: '404', description: 'payment not found with the specified id', content: new OA\JsonContent(
            properties: [new OA\Property(property: 'message', type: 'string', default: 'Payment not found with the specified id')]
        )),
    ]
), OA\Patch(
    path: '/payments/{id}',
    parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: '84e8adbf-1a14-403b-ad73-d78ae19b59bf')],
    tags: ['payments'],
    description: 'Confirm payment',
    requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ConfirmPaymentRequest')),
    responses: [
        new OA\Response(response: Response::HTTP_NO_CONTENT, description: 'confirmed'),
        new OA\Response(response: '404', description: 'payment not found with the specified id', content: new OA\JsonContent(
            properties: [new OA\Property(property: 'message', type: 'string', default: 'Payment not found with the specified id')]
        )),
    ]
), OA\Schema(schema: 'PaymentResource', properties: [
    new OA\Property(property: 'id', type: 'string', example: '84e8adbf-1a14-403b-ad73-d78ae19b59bf'),
    new OA\Property(property: 'status', type: 'string', enum: PaymentStatus::class),
    new OA\Property(property: 'transaction_amount', type: 'number', example: 245.90),
    new OA\Property(property: 'installments', type: 'integer', example: 3),
    new OA\Property(property: 'token', type: 'string', example: 'ae4e50b2a8f3h6d9f2c3a4b5d6e7f8g9'),
    new OA\Property(property: 'payment_method_id', type: 'string', example: 'master'),
    new OA\Property(property: 'notification_url', type: 'string', example: 'https://webhook.site/unique-r'),
    new OA\Property(property: 'created_at', type: 'string', example: '2024-01-10'),
    new OA\Property(property: 'updated_at', type: 'string', example: '2024-01-11'),
    new OA\Property(property: 'payer', type: 'object', properties: [
        new OA\Property(property: 'entity_type', type: 'string', enum: PayerEntityType::class),
        new OA\Property(property: 'type', type: 'string', enum: PayerType::class),
        new OA\Property(property: 'email', type: 'string', example: 'example_random@gmail.com'),
        new OA\Property(property: 'identification', type: 'object', properties: [
            new OA\Property(property: 'type', type: 'string', enum: PayerIdentificationType::class),
            new OA\Property(property: 'number', type: 'string', example: '12345678909'),
        ]),
    ]),
]), OA\Schema(schema: 'StorePaymentRequest', required: ['transaction_amount', 'installments', 'token', 'payment_method_id', 'payer'], properties: [
    new OA\Property(property: 'transaction_amount', type: 'number', example: 245.90),
    new OA\Property(property: 'installments', type: 'integer', example: 3),
    new OA\Property(property: 'token', type: 'string', example: 'ae4e50b2a8f3h6d9f2c3a4b5d6e7f8g9'),
    new OA\Property(property: 'payment_method_id', type: 'string', example: 'master'),
    new OA\Property(property: 'payer', type: 'object', required: ['email', 'identification'], properties: [
        new OA\Property(property: 'email', type: 'string', example: 'example_random@gmail.com'),
        new OA\Property(property: 'identification', type: 'object', required: ['type', 'number'], properties: [
            new OA\Property(property: 'type', type: 'string', enum: PayerIdentificationType::class),
            new OA\Property(property: 'number', type: 'string', example: '12345678909'),
        ]),
    ]),
]), OA\Schema(schema: 'ConfirmPaymentRequest', required: ['status'], properties: [
    new OA\Property(property: 'status', type: 'string', enum: [PaymentStatus::PAID->value]),
])]
class OpenApiSpec
{
}
