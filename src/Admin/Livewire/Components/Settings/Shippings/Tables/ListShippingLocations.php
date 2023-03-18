<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Settings\Shippings\Tables;

use Filament\Tables;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use XtendLunar\Features\ShippingProviders\Models\ShippingLocation;

class ListShippingLocations extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected function getTableQuery(): Builder
    {
        return ShippingLocation::query();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('zone.name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('parent.name')
                ->sortable(),
            Tables\Columns\TextColumn::make('code')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('type')
                ->sortable(),
            Tables\Columns\TextColumn::make('name')
                ->sortable(),
        ];
    }

    public function render(): View
    {
        return view('adminhub::livewire.components.settings.shippings.tables.list-shipping-locations');
    }
}
