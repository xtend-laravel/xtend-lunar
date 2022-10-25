<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Customers;

use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Models\Currency;
use Lunar\Models\Customer;
use Lunar\Models\Order;

class CustomersTable extends Component implements Tables\Contracts\HasTable
{
    use Notifies;
    use Tables\Concerns\InteractsWithTable;

    /**
     * {@inheritDoc}
     */
    public function isTableSearchable(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableQuery(): Builder
    {
        return Customer::query();
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
    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Customer::search($searchQuery)->keys());
        }

        return $query;
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableColumns(): array
    {
        $prefix = config('getcandy.database.table_prefix');

        return [
            TextColumn::make('id')->sortable()->searchable(),
            TextColumn::make('title')->sortable(),
            //TextColumn::make('fullName')->url(fn (Customer $record): string => route('hub.customers.show', ['customer' => $record])),
            TextColumn::make('first_name')->sortable(),
            TextColumn::make('last_name')->sortable(),
            TextColumn::make('email')->sortable(),
            BadgeColumn::make('total')
                      ->label('Sales')
                      ->colors([
                          'success' => static fn ($state, $record): bool => (int) $record->orders()->sum('total'),
                      ])
                      ->sortable(query: function (Builder $query, string $direction) use ($prefix): Builder {
                          return $query->orderBy(
                              Order::selectRaw('sum(total)')
                                   ->whereColumn('customer_id', $prefix.'customers.id'), $direction);
                      })
                      ->getStateUsing(fn (Customer $record) => price((int) $record->orders()->sum('total'), Currency::getDefault())->formatted()),
            BooleanColumn::make('newsletter')->getStateUsing(fn (Customer $record) => $record->newsletter ?? false)->sortable(),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableRecordUrlUsing(): Closure
    {
        return fn (Customer $record): string => route('hub.customers.show', ['customer' => $record]);
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableActions(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableBulkActions(): array
    {
        return [];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Tables\Filters\Layout::AboveContent;
    }

    protected function getTableFiltersFormColumns(): int | array
    {
        return match ($this->getTableFiltersLayout()) {
            Layout::AboveContent, Layout::BelowContent => [
                'sm' => 3,
            ],
            default => 1,
        };
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableFilters(): array
    {
        return [
            Filters\Filter::make('fullName')
                  ->form([
                      Grid::make()
                          ->schema([
                              Select::make('title')
                                    ->options([
                                        'Mr.' => 'Mr.',
                                        'Mrs.' => 'Mrs.',
                                    ])
                                    ->required(),
                              TextInput::make('first_name'),
                              TextInput::make('last_name'),
                          ])->columns(3),
                  ])
                  ->query(function (Builder $query, array $data): Builder {
                      return $query
                          ->when(
                              $data['title'] ?? null,
                              fn (Builder $query, $title): Builder => $query->where('title', $title))
                          ->when(
                              $data['first_name'] ?? null,
                              fn (Builder $query, $firstName): Builder => $query->where('first_name', 'like', '%'.$firstName.'%'))
                          ->when(
                              $data['last_name'] ?? null,
                              fn (Builder $query, $lastName): Builder => $query->where('last_name', 'like', '%'.$lastName.'%'));
                  }),
            Filters\Filter::make('sales_newsletter')
                  ->form([
                      Grid::make()
                          ->schema([
                              TextInput::make('email'),
                              TextInput::make('sales_from'),
                              TextInput::make('sales_to'),
                          ])->columns(3),
                  ])
                  ->query(function (Builder $query, array $data): Builder {
                      if (is_numeric($data['sales_from'])) {
                          $data['sales_from'] = (int) number_format($data['sales_from'] ?? 0, 2, '', '');
                      }
                      if (is_numeric($data['sales_to'])) {
                          $data['sales_to'] = (int) number_format($data['sales_to'], 2, '', '');
                      }

                      return $query
                          ->when($data['email'] ?? null, fn (Builder $query, $email) => $query->where('email', 'like', '%'.$email.'%'))
                          ->when($data['sales_from'] ?? null, fn (Builder $query, $sales): Builder => $query->whereHas('orders', fn (Builder $query) => $query->havingRaw('sum(total) >= ?', [$sales])->groupBy('id')))
                          ->when($data['sales_to'] ?? null, fn (Builder $query, $sales): Builder => $query->whereHas('orders', fn (Builder $query) => $query->havingRaw('sum(total) <= ?', [$sales])->groupBy('id')));
                  }),
            Filters\Filter::make('created_at')
                  ->form([
                      Grid::make()
                          ->schema([
                              Select::make('newsletter')
                                    ->options([
                                        'YES' => 'Yes',
                                        'NO' => 'No',
                                    ]),
                              DatePicker::make('created_from')
                                        ->placeholder(fn ($state): string => 'Dec 18, '.now()->subYear()->format('Y')),
                              DatePicker::make('created_until')
                                        ->placeholder(fn ($state): string => now()->format('M d, Y')),
                          ])->columns(3),
                  ])
                  ->query(function (Builder $query, array $data): Builder {
                      return $query
                          ->when($data['newsletter'] === 'YES', fn (Builder $query) => $query->where('newsletter', 1))
                          ->when($data['newsletter'] === 'NO', fn (Builder $query) => $query->where('newsletter', 0))
                          ->when(
                              $data['created_from'] ?? null,
                              fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                          )
                          ->when(
                              $data['created_until'] ?? null,
                              fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                          );
                  }),
        ];
    }

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('adminhub::livewire.components.tables.base-table')
            ->layout('adminhub::layouts.base');
    }
}
