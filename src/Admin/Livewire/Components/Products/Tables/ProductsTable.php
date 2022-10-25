<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Products\Tables;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Models\Brand;
use Lunar\Models\Price;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;

class ProductsTable extends Component implements Tables\Contracts\HasTable
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
        return Product::query();
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
            $query->whereIn('id', Product::search($searchQuery)->keys());
        }

        return $query;
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableColumns(): array
    {
        // Product::each(function (Product $product) {
        //     $product->primary_category_id = $product->categoryCollection->id ?? null;
        //     $product->update();
        // });

        return [
            Tables\Columns\ImageColumn::make('thumbnail')
                ->getStateUsing(function (Product $record) {
                    if ($record->thumbnail) {
                        return $record->thumbnail->getUrl('small');
                    }
                    $variant = $record->variants->first(function ($variant) {
                        return $variant->thumbnail;
                    });

                    return $variant?->thumbnail?->getUrl('small');
                })
                ->size(70)
                ->label('Image'),
            TextColumn::make('name')
                ->getStateUsing(fn (Product $record): string => $record->translateAttribute('name') ?? '--')
                ->sortable('primaryCategory.name', fn ($query, $direction) => $query->orderBy('attribute_data->name->value', $direction)),
            TextColumn::make('primaryCategory.name')
                ->getStateUsing(fn (Product $record): string => $record->primaryCategory?->translateAttribute('name') ?? '--')
                ->sortable('primaryCategory.name', fn ($query, $direction) => $query->orderBy('attribute_data->name->value', $direction)),
            TextColumn::make('brand.name')->sortable('brand.name'),
            TextColumn::make('price')
                ->getStateUsing(fn (Product $record): string => $record->baseVariant?->basePrices?->first()?->price->formatted ?? '--')
                ->sortable('price', fn (Builder $query, $direction) => $query->orderBy(
                    Price::select('price')
                         ->whereColumn('priceable_id', 'variant_default_id')
                         ->where('priceable_type', ProductVariant::class), $direction)
                ),
            TextColumn::make('sku')
                      ->label('SKU')
                      ->getStateUsing(fn (Product $record): string => $record?->baseVariant?->sku ?? '--'),
            TextColumn::make('quantity')
                      ->getStateUsing(fn (Product $record): string => $record?->variants?->sum('unit_quantity') ?? '--'),
            $this->statusColumn(),
        ];
    }

    /**
     * Return a column for status.
     *
     * @param  string  $column
     * @return BadgeColumn
     */
    public function statusColumn($column = 'status')
    {
        return BadgeColumn::make($column)
            ->enum([
                'unpublished' => __('adminhub::global.draft'),
                'published' => __('adminhub::global.published'),
            ])->colors([
                'danger' => 'unpublished',
                'success' => 'published',
            ]);
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ActionGroup::make([
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\EditAction::make()->url(fn (Product $record): string => route('hub.products.show', ['product' => $record])),
            ]),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableBulkActions(): array
    {
        return [
            BulkAction::make('delete')
            ->action(fn (Collection $records) => $records->each->delete()),
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
                '2xl' => 2,
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
            Filters\Filter::make('sales_newsletter')
                ->form([
                    Grid::make()
                        ->schema([
                            TextInput::make('sku'),
                            TextInput::make('name'),
                            TextInput::make('price_from'),
                            TextInput::make('price_to'),
                        ])->columns(4),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    if (is_numeric($data['price_from'])) {
                        $data['price_from'] = (int) number_format($data['price_from'] ?? 0, 2, '', '');
                    }
                    if (is_numeric($data['price_to'])) {
                        $data['price_to'] = (int) number_format($data['price_to'], 2, '', '');
                    }

                    return $query
                        ->when(filled($data['sku']), fn (Builder $query, $fullName): Builder => $query->whereHas('baseVariant',
                            fn (Builder $query) => $query->where('sku', 'like', '%'.$data['sku'].'%')
                        ))
                        ->when($data['name'] ?? null, fn (Builder $query, $name) => $query->where('attribute_data->name->value', 'like', '%'.$name.'%'))
                        ->when($data['price_from'] ?? null, fn (Builder $query, $from): Builder => $query->whereHas('baseVariant.basePrices', fn (Builder $query) => $query->whereRaw('price >= ?', [$from])->groupBy('id')))
                        ->when($data['price_to'] ?? null, fn (Builder $query, $to): Builder => $query->whereHas('baseVariant.basePrices', fn (Builder $query) => $query->whereRaw('price <= ?', [$to])->groupBy('id')));
                }),
            Filters\Filter::make('brand_status_trashed')
                 ->form([
                     Grid::make()
                         ->schema([
                             Select::make('primary_category')->options(
                                 \Lunar\Models\Collection::where('type', 'category')
                                     ->get()
                                     ->filter(fn ($primaryCategory) => Product::where('primary_category_id', $primaryCategory->id)->exists())
                                     ->mapWithKeys(fn ($collection) => [$collection->id => $collection->translateAttribute('name')])
                             ),
                             Select::make('brand')->relationship('brand', 'name')->options(Brand::all()->pluck('name', 'id')),
                             $this->statusFilter(),
                             //$this->trashedFilter(),
                         ])->columns(3),
                 ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['primary_category'] ?? null, fn (Builder $query, $primaryCategory) => $query->where('primary_category_id', $primaryCategory))
                        ->when($data['brand'] ?? null, fn (Builder $query, $brand) => $query->where('brand_id', $brand))
                        ->when($data['status'] ?? null, fn (Builder $query, $status) => $query->where('status', $status));
                    //->when($data['trashed'] ?? null, fn (Builder $query, $trashed) => $trashed ? $query->onlyTrashed() : $query->withoutTrashed());
                }),
        ];
    }

    /**
     * Return a status filter for the table.
     *
     * @param  string  $column
     * @return \Filament\Tables\Filters\SelectFilter
     */
    public function statusFilter($column = 'status')
    {
        return Select::make($column)
            ->options([
                'published' => __('adminhub::global.published'),
                'draft' => __('adminhub::global.draft'),
            ]);
    }

    public function trashedFilter()
    {
        return Select::make('trashed')->options([
            '0' => __('adminhub::tables.without_trashed'),
            '1' => __('adminhub::tables.with_trashed'),
        ]);
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
