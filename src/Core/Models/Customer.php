<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Lunar\Base\Casts\AsAttributeData;
use Lunar\Base\Traits\LogsActivity;
use Lunar\Models\Language;
use XtendLunar\Features\NotifyTimeline\Concerns\HasModelNotification;

class Customer extends \Lunar\Models\Customer
{
    use HasModelNotification;
    use LogsActivity;
    use SoftDeletes;
    use Notifiable;

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'attribute_data' => AsAttributeData::class,
        'legacy_data' => AsCollection::class,
        'dob' => 'date:Y-m-d',
        'meta' => 'object',
    ];

    protected static function booted(): void
    {
        static::created(function (self $customer) {
            $customer->notify($this->customerNotification($customer));
        });
    }

    /**
     * Return the language relationship.
     *
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
