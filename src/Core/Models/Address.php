<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lunar\Base\Traits\HasPersonalDetails;

class Address extends \Lunar\Models\Address
{
    use HasPersonalDetails;
    use SoftDeletes;

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'legacy_data' => AsCollection::class,
        'shipping_default' => 'boolean',
        'billing_default' => 'boolean',
    ];

    /**
     * Format address.
     *
     * @todo Replace this collection with address formatter class.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function formatted(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => collect([
                $attributes['line_one'],
                $attributes['line_two'],
                $attributes['city'],
                $attributes['postcode'],
                $this->country->name,
            ])->join(' '),
        );
    }
}
