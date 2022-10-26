<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Lunar\Base\Casts\AsAttributeData;
use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;
use Lunar\Models\ProductVariant;
use XtendLunar\Features\ProductFeatures\Models\ProductFeatureValue;

class Product extends \Lunar\Models\Product
{
    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'attribute_data' => AsAttributeData::class,
        'legacy_data' => AsCollection::class,
    ];

    /**
     * Define which attributes should be
     * fillable during mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'attribute_data',
        'legacy_data',
        'product_type_id',
        'status',
    ];

    /**
     * Return the product collections relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function collections()
    {
        $prefix = config('lunar.database.table_prefix');

        return $this->belongsToMany(Collection::class, $prefix.'collection_product')
            ->using(CollectionProduct::class)
            ->withPivot(['position'])->withTimestamps();
    }

    public function categoryCollection()
    {
        return $this->hasOneThrough(
            related: Collection::class,
            through: CollectionProduct::class,
            firstKey: 'product_id',
            secondKey: 'id',
            localKey: 'id',
            secondLocalKey: 'collection_id'
        )->where('lunar_collections.collection_group_id', CollectionGroup::whereHandle('categories')->first()->id);
    }

    public function primaryCategory(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'primary_category_id');
    }

    /**
     * Return the product base variant relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function baseVariant()
    {
        return $this->hasOne(ProductVariant::class);
    }

    public function featureValues(): BelongsToMany
    {
        // @todo - this is a hack to get the feature values to work best to move this into a trait on the product-feature package
        return $this->belongsToMany(
            related: ProductFeatureValue::class,
            table: 'lunar_product_feature_value_product',
            foreignPivotKey: 'lunar_product_id',
            relatedPivotKey: 'lunar_product_feature_value_id',
        )->withTimestamps();
    }
}
