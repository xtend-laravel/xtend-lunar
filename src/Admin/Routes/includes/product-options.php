<?php

use Illuminate\Support\Facades\Route;
use Xtend\Extensions\Lunar\Admin\Livewire\Pages\ProductOptions\ProductOptionsIndex;

/**
 * Product features routes.
 */
Route::group([
    'middleware' => 'can:settings:core',
    'prefix' => 'product',
], function () {
    Route::get('product-options', ProductOptionsIndex::class)->name('hub.product-options.index');
});
