<?php

use Illuminate\Support\Facades\Route;
use Xtend\Extensions\Lunar\Admin\Livewire\Pages\ProductFeatures\FeaturesIndex;

/**
 * Product features routes.
 */
Route::group([
    'middleware' => 'can:settings:core',
    'prefix' => 'product',
], function () {
    Route::get('features', FeaturesIndex::class)->name('hub.product-features.index');
});
