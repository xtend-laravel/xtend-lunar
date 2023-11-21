<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Lunar\Base\Casts\AsAttributeData;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductVariant extends \Lunar\Models\ProductVariant
{
    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'requires_shipping' => 'bool',
        'attribute_data' => AsAttributeData::class,
        'legacy_data' => AsCollection::class,
    ];

    /**
     * {@inheritDoc}
     */
    protected static function booting(): void
    {
        static::updated(function (ProductVariant $productVariant) {
            $product = $productVariant->product;
            $product->stock = $product->variants->sum('stock');
            $product->saveQuietly();
        });
    }

    public function getThumbnail(): ?Media
    {
        return $this->images->first(function ($media) {
            return (bool) $media->pivot->primary;
        }) ?: $this->product->thumbnail;
    }
}
