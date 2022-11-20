<nav class="flex space-x-4" aria-label="Tabs">
  <a href="{{ route('hub.shippings.shipping-zones.index') }}"
    @class([
      'px-3 py-2 text-sm font-medium rounded-md' => true,
      'bg-white shadow' => request()->route()->getName() == 'hub.shippings.shipping-zones.index',
      'hover:text-gray-700 text-gray-500' =>  request()->route()->getName() != 'hub.shippings.shipping-zones.index'
    ])
  >
    Shipping Zones
  </a>

  <a href="{{ route('hub.shippings.shipping-locations.index') }}"
    @class([
      'px-3 py-2 text-sm font-medium rounded-md' => true,
      'bg-white shadow' => request()->route()->getName() == 'hub.shippings.shipping-locations.index',
      'hover:text-gray-700 text-gray-500' =>  request()->route()->getName() != 'hub.shippings.shipping-locations.index'
    ])
  >
    Shipping Locations
  </a>

  <a href="{{ route('hub.shippings.shipping-options.index') }}"
    @class([
      'px-3 py-2 text-sm font-medium rounded-md' => true,
      'bg-white shadow' => request()->route()->getName() == 'hub.shippings.shipping-options.index',
      'hover:text-gray-700 text-gray-500' =>  request()->route()->getName() != 'hub.shippings.shipping-options.index'
    ])
  >
    Shipping Options
  </a>
</nav>
