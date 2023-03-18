<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Settings\Shippings\Tables;

use Filament\Tables;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use XtendLunar\Features\ShippingProviders\Models\ShippingOption;

class ListShippingOptions extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected function getTableQuery(): Builder
    {
        return ShippingOption::query();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('identifier')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('wsCode')
                ->sortable()
                ->searchable(),
            Tables\Columns\IconColumn::make('is_enabled')
                ->boolean()
                ->trueIcon('heroicon-o-badge-check')
                ->falseIcon('heroicon-o-x-circle'),
            Tables\Columns\TextColumn::make('order')
                ->sortable(),
        ];
    }

    public function render(): View
    {
        return view('adminhub::livewire.components.settings.shippings.tables.list-shipping-options');
    }
}
