<?php

use Lunar\Base\OrderReferenceGenerator;

return [
    /*
    |--------------------------------------------------------------------------
    | Order Reference Generator
    |--------------------------------------------------------------------------
    |
    | Here you can specify how you want your order references to be generated
    | when you create an order from a cart.
    |
    */
    'reference_generator' => OrderReferenceGenerator::class,
    /*
    |--------------------------------------------------------------------------
    | Draft Status
    |--------------------------------------------------------------------------
    |
    | When a draft order is created from a cart, we need an initial status for
    | the order that's created. Define that here, it can be anything that would
    | make sense for the store you're building.
    |
    */
    'draft_status' => 'awaiting-payment',
    'statuses' => [
        'awaiting-payment' => [
            'label' => 'Awaiting Payment',
            'color' => '#848a8c',
            'mailers' => [],
            'notifications' => [
                \App\Notifications\OrderStatusAwaitingPayment::class,
            ],
        ],
        'processing' => [
            'label' => 'Processing',
            'color' => '#ff8c00',
            'mailers' => [],
            'notifications' => [
                \App\Notifications\OrderStatusProcessing::class,
            ],
        ],
        'payment-received' => [
            'label' => 'Payment Received',
            'color' => '#6a67ce',
            'mailers' => [],
            'notifications' => [
                \App\Notifications\OrderStatusPaymentReceived::class,
            ],
        ],
        'dispatched' => [
            'label' => 'Dispatched',
            'color' => '#8a2be2',
            'mailers' => [],
            'notifications' => [
                \App\Notifications\OrderStatusDispatched::class,
            ],
        ],
        'delivered' => [
            'label' => 'Delivered',
            'color' => '#108510',
            'mailers' => [],
            'notifications' => [
                \App\Notifications\OrderStatusDelivered::class,
            ],
        ],
        'cancelled' => [
            'label' => 'Cancelled',
            'color' => '#dc143c',
            'mailers' => [],
            'notifications' => [
                \App\Notifications\OrderStatusCancelled::class,
            ],
        ],
        'refunded' => [
            'label' => 'Refunded',
            'color' => '#dc143c',
            'mailers' => [],
            'notifications' => [
                \App\Notifications\OrderStatusRefunded::class,
            ],
        ],
        'error' => [
            'label' => 'Payment error',
            'color' => '#dc143c',
            'mailers' => [],
            'notifications' => [
                \App\Notifications\OrderStatusPaymentError::class,
            ],
        ],
    ],
];
