<div class="shadow sm:rounded-md">
    <div class="flex-col space-y-4 rounded-t-xl bg-white">

        <header class="flex items-center gap-1 rounded-t-xl bg-[#353F4F] p-3">
            <x-go-info-16 class="h-6 w-6 text-[#CFA55B]" />
            <span class="ml-2 text-sm font-semibold text-white">
                {{ __('adminhub::partials.products.basic-information.heading') }}
            </span>
        </header>

        <div class="space-y-4 p-6">
            <x-hub::input.group :label="__('adminhub::inputs.brand.label')" for="brand">
                <x-hub::input.select id="brand" wire:model="product.brand_id">
                    <option>{{ __('adminhub::components.brands.choose_brand_default_option') }}</option>
                    @foreach ($this->brands as $brand)
                        <option value="{{ $brand->id }}" wire:key="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </x-hub::input.select>
            </x-hub::input.group>

            <x-hub::input.text id="productType" wire:model="product.product_type_id" type="hidden" />

            <x-hub::input.group :label="__('adminhub::inputs.tags.label')" for="tags">
                <x-hub::input.tags id="tags" wire:model="tags" />
            </x-hub::input.group>
        </div>
    </div>
</div>
