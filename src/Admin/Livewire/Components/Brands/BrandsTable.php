<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Brands;

use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Models\Brand;

class BrandsTable extends Component implements Tables\Contracts\HasTable
{
    use Notifies;
    use Tables\Concerns\InteractsWithTable;

    /**
     * {@inheritDoc}
     */
    protected function getTableQuery(): Builder
    {
        return Brand::query();
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\ImageColumn::make('thumbnail')
                ->getStateUsing(function (Brand $record) {
                    return $record->thumbnail->getUrl('small');
                })
                ->size(120)
                ->label('Image'),
            Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('products_count')
                      ->getStateUsing(fn (Brand $record): string => $record->products->count() ?? '--'),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ActionGroup::make([
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\EditAction::make()->url(fn (Brand $record): string => route('hub.brands.show', ['brand' => $record])),
            ]),
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
