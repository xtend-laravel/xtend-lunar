<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Lunar\Base\BaseModel;
use Lunar\Base\Traits\HasMacros;
use Lunar\Base\Traits\HasTranslations;
use Lunar\Base\Traits\Searchable;
use Lunar\Database\Factories\ProductFeatureFactory;

/**
 * Class ProductFeature.
 *
 * @property string $name
 * @property string $handle
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ProductFeature extends BaseModel
{
    use HasFactory;
    use HasTranslations;
    use Searchable;
    use HasMacros;

    /**
     * Define our base filterable attributes.
     *
     * @var array
     */
    protected $filterable = [];

    /**
     * Define our base sortable attributes.
     *
     * @var array
     */
    protected $sortable = [
        'name',
    ];

    /**
     * Define which attributes should be cast.
     *
     * @var array
     */
    protected $casts = [
        'name' => AsCollection::class,
        'legacy_data' => AsCollection::class,
    ];

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return config('scout.prefix').'product_features';
    }

    /**
     * Return a new factory instance for the model.
     *
     * @return \Lunar\Database\Factories\ProductFeatureFactory
     */
    protected static function newFactory(): ProductFeatureFactory
    {
        return ProductFeatureFactory::new();
    }

    public function getNameAttribute($value)
    {
        return json_decode($value);
    }

    protected function setNameAttribute($value)
    {
        $this->attributes['name'] = json_encode($value);
    }

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the product features values.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values(): HasMany
    {
        return $this->hasMany(ProductFeatureValue::class)->orderBy('position');
    }
}
