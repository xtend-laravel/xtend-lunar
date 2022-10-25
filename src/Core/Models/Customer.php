<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Lunar\Base\Casts\AsAttributeData;
use Lunar\Base\Traits\LogsActivity;
use Lunar\Models\Language;
use Xtend\Extensions\Filament\Notifications\Notification;

class Customer extends \Lunar\Models\Customer
{
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
            $customer->notify(
                Notification::make()
                    ->success()
                    ->system()
                    ->id(Str::random())
                    ->title('New customer registered successfully!')
                    ->body('System detected new customer registration named **'.$customer->fullName.'**.')
                    ->actions([
                        Action::make('view')
                              ->button()
                              ->url(route('hub.customers.show', ['customer' => $customer])),
                    ])
                    ->toDatabase()
            );
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
