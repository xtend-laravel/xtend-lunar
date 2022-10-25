<?php

use Illuminate\Support\Facades\Route;
use Xtend\Extensions\Lunar\Admin\Livewire\Pages\Carts\CartShow;
use Xtend\Extensions\Lunar\Admin\Livewire\Pages\Carts\CartsIndex;

/**
 * Channel routes.
 */
Route::group([
    'middleware' => 'can:catalogue:manage-orders',
], function () {
    Route::get('/', CartsIndex::class)->name('hub.carts.index');

    Route::group([
        'prefix' => '{cart}',
    ], function () {
        Route::get('/', CartShow::class)->name('hub.carts.show');
    });
});
