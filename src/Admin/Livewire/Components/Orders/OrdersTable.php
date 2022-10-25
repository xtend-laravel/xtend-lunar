<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Orders;

use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
//use Lunar\Hub\Tables\Columns\OrderStatusColumn;
use Illuminate\Support\Collection;
use Livewire\Component;
use Lunar\Hub\Exporters\OrderExporter;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Models\Country;
use Lunar\Models\Currency;
use Lunar\Models\Customer;
use Lunar\Models\Order;
use Lunar\Models\OrderAddress;

class OrdersTable extends Component implements Tables\Contracts\HasTable
{
    use Notifies;
    use Tables\Concerns\InteractsWithTable;

    /**
     * Restrict records to a customer.
     *
     * @var Customer|null
     */
    public ?Customer $customer = null;

    /**
     * Whether to show filters.
     *
     * @var bool
     */
    public bool $filterable = true;

    /**
     * {@inheritDoc}
     */
    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 20, 50, 100];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableQuery(): Builder
    {
        $query = Order::query();

        if ($this->customer) {
            $query = Order::whereCustomerId($this->customer->id);
        }

        return $query;
    }

    /**
     * {@inheritDoc}
     */
    // protected function applySearchToTableQuery(Builder $query): Builder
    // {
    //     if (filled($searchQuery = $this->getTableSearchQuery())) {
    //         $query->whereIn('id', Order::search($searchQuery)->keys());
    //     }
    //
    //     return $query;
    // }

    /**
     * {@inheritDoc}
     */
    protected function getTableColumns(): array
    {
        $prefix = config('getcandy.database.table_prefix');

        return [
            TextColumn::make('id')->visible(auth()->user()->admin)->sortable()->searchable(),
            TextColumn::make('reference')->sortable(),
            BadgeColumn::make('new_client')
                ->label('New client')
                ->colors([
                    'success' => fn ($state, $record): bool => $this->isNewClient($record) === 'YES',
                    'warning' => fn ($state, $record): bool => $this->isNewClient($record) === 'NO',
                ])
                ->getStateUsing(fn (Order $record) => $this->isNewClient($record)),

            TextColumn::make('billingAddress.fullName')
                ->label('Customer')
                ->sortable(query: function (Builder $query, string $direction) use ($prefix): Builder {
                  return $query->orderBy(
                      OrderAddress::select('first_name')
                          ->whereColumn('order_id', $prefix.'orders.id')
                          ->where('type', 'billing'), $direction);
                })
                ->url(fn (Order $record): string => route('hub.customers.show', ['customer' => $record->customer->id ?? 0])),
            TextColumn::make('billingAddress.country.name')
                ->getStateUsing(fn (Order $record) => $record->billingAddress?->country?->emoji.' '.$record->billingAddress?->country?->name ?? '')
                ->sortable('name', function (Builder $query, string $direction) use ($prefix): Builder {
                  return $query->orderBy(
                      OrderAddress::select('country_id')
                          ->whereColumn('order_id', $prefix.'orders.id')
                          ->where('type', 'billing'), $direction);
                })
                ->label('Country'),
            BadgeColumn::make('total')
                ->label('Sales')
                ->sortable()
                ->colors(['success' => static fn ($state, $record): bool => collect($record->legacy_data)->get('valid') ?? false])
                ->getStateUsing(fn (Order $record) => price((int) $record->total->value, Currency::getDefault())->formatted()),

            TextColumn::make('payment_method')
                ->sortable()
                ->getStateUsing(fn (Order $record) => $record->transactions->first()->driver ?? '--'),

            TextColumn::make('lines_count')
                ->counts('lines')
                ->hidden(false)
                ->sortable()
                ->getStateUsing(fn (Order $record) => $record->lines->count()),
            BadgeColumn::make('status')
                ->label('Status')
                ->color(static function (Order $record): string {
                    $statuses = config('lunar.orders.statuses');
                    $color = $statuses[$record->status]['color'] ?? '#7C7C7C';

                    return 'bg-['.$color.'] text-white';
                })
                ->sortable()
                ->getStateUsing(fn (Order $record) => ucwords($record->status)),
            TextColumn::make('placed_at')->dateTime()->sortable(),
        ];
    }

    protected function isNewClient(Order|Model $order): string
    {
        return $order?->customer?->orders?->count() === 1 ? 'YES' : 'NO';
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableRecordUrlUsing(): Closure
    {
        return fn (Order $record): string => route('hub.orders.show', ['order' => $record]);
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableActions(): array
    {
        return [
            Action::make('invoice')
                  ->label(false)
                  ->icon('heroicon-o-document-text')
                  ->url(fn (Order $record): string => route('hub.orders.show', ['order' => $record])),
            Action::make('delivery_slip')
                  ->label(false)
                  ->icon('heroicon-o-truck')
                  ->url(fn (Order $record): string => route('hub.orders.show', ['order' => $record])),
            Action::make('view')
                  ->label(false)
                  ->icon('heroicon-o-eye')
                  ->url(fn (Order $record): string => route('hub.orders.show', ['order' => $record])),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableBulkActions(): array
    {
        $statuses = collect(config('getcandy.orders.statuses', []))->mapWithKeys(function ($status, $key) {
            return [$key => $status['label'] ?? $status];
        });

        return [
            BulkAction::make('export')->action(function (Collection $records) {
                return $this->exportUsing(
                    OrderExporter::class,
                    $records->pluck('id')->toArray()
                );
            })->deselectRecordsAfterCompletion(),
            BulkAction::make('updateStatus')
                      ->action(function (Collection $records, array $data): void {
                          Order::whereIn('id', $records->pluck('id')->toArray())->update([
                              'status' => $data['status'],
                          ]);
                      })
                      ->form([
                          Select::make('status')
                                ->label('Status')
                                ->options($statuses->toArray())
                                ->required(),
                      ])->deselectRecordsAfterCompletion(),
        ];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Tables\Filters\Layout::AboveContent;
    }

    protected function getTableFiltersFormColumns(): int | array
    {
        return match ($this->getTableFiltersLayout()) {
            Layout::AboveContent, Layout::BelowContent => [
                'sm' => 2,
                'lg' => 3,
                'xl' => 4,
                '2xl' => 3,
            ],
            default => 1,
        };
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableFilters(): array
    {
        if (! $this->filterable) {
            return [];
        }

        $statuses = config('getcandy.orders.statuses', []);

        return [

            Filters\Filter::make('customer_filters')
                  ->form([
                      Grid::make()
                          ->schema([
                              TextInput::make('reference'),
                              Select::make('new_client')
                                    ->label('New client')
                                    ->options([
                                        'YES' => 'Yes',
                                        'NO' => 'No',
                                    ])
                                    ->required(),
                              TextInput::make('full_name'),
                          ])->columns(3),
                  ])
                  ->query(function (Builder $query, array $data): Builder {
                      return $query
                          ->when(
                              $data['reference'] ?? null,
                              fn (Builder $query, $reference): Builder => $query->where('reference', 'like', '%'.$reference.'%'))
                          ->when(
                              $data['full_name'] ?? null,
                              fn (Builder $query, $fullName): Builder => $query->whereHas('billingAddress',
                                  fn (Builder $query) => $query->where('first_name', 'like', '%'.$fullName.'%')->orWhere('last_name', 'like', '%'.$fullName.'%')
                              )
                          )
                          ->when(
                              $data['last_name'] ?? null,
                              fn (Builder $query, $lastName): Builder => $query->whereHas('billingAddress',
                                  fn (Builder $query) => $query->where('last_name', 'like', '%'.$lastName.'%'))
                          )
                          ->when($data['new_client'] === 'YES', fn (Builder $query) => $query->has('customer.orders', 1))
                          ->when($data['new_client'] === 'NO', fn (Builder $query) => $query->has('customer.orders', '>', 1));
                  }),

            Filters\Filter::make('general_filters')
                  ->form([
                      Grid::make()
                          ->columns(3)
                          ->schema([
                              Select::make('country')->options(
                                  Country::distinct()->pluck('name', 'id')->filter(function ($name, $id) {
                                      return OrderAddress::where('type', 'shipping')->pluck('country_id')->contains($id);
                                  })
                              ),
                              Select::make('payment')->options([
                                  'payzen' => 'Payzen',
                                  'paypal' => 'PayPal',
                                  'ps_wirepayment' => 'Wire',
                              ]),
                              Select::make('status')->options(
                                  Order::distinct()->pluck('status')->mapWithKeys(function ($status) use ($statuses) {
                                      return [
                                          $status => $statuses[$status]['label'] ?? $status,
                                      ];
                                  }),
                              ),
                          ]),
                  ])->query(function (Builder $query, array $data): Builder {
                      return $query->when(
                          $data['country'] ?? null,
                          fn (Builder $query, $countryId): Builder => $query->whereHas('billingAddress', fn (Builder $query) => $query->where('country_id', $countryId))
                      )->when(
                          $data['payment'] ?? null,
                          fn (Builder $query, $payment): Builder => $query->whereHas('transactions', fn (Builder $query) => $query->where('driver', $payment))
                      )->when(
                          $data['status'] ?? null,
                          fn (Builder $query, $status): Builder => $query->where('status', $status)
                      );
                  }),

            Filters\Filter::make('placed_at')
                  ->form([
                      Grid::make([
                          'default' => 2,
                          'sm' => 2,
                          'md' => 2,
                      ])
                          ->schema([
                              DatePicker::make('created_from')
                                        ->placeholder(fn ($state): string => 'Dec 18, '.now()->subYear()->format('Y')),
                              DatePicker::make('created_until')
                                        ->placeholder(fn ($state): string => now()->format('M d, Y')),
                          ]),
                  ])
                  ->query(function (Builder $query, array $data): Builder {
                      return $query
                          ->when(
                              $data['created_from'] ?? null,
                              fn (Builder $query, $date): Builder => $query->whereDate('placed_at', '>=', $date),
                          )
                          ->when(
                              $data['created_until'] ?? null,
                              fn (Builder $query, $date): Builder => $query->whereDate('placed_at', '<=', $date),
                          );
                  }),
        ];
    }

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('adminhub::livewire.components.tables.base-table')
            ->layout('adminhub::layouts.base');
    }
}
