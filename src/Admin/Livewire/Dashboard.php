<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire;

use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\DB;
use Lunar\Models\Customer;
use Lunar\Models\CustomerGroup;
use Lunar\Models\Order;
use Xtend\Extensions\Filament\Notifications\Notification;

class Dashboard extends \Lunar\Hub\Http\Livewire\Dashboard
{
    public function mount()
    {
        $this->range['from'] = $this->range['from'] ?? now()->startOfMonth()->format('Y-m-d');
        $this->range['to'] = $this->range['too'] ?? now()->endOfWeek()->format('Y-m-d');
    }

    /**
     * Return computed property for customer group orders.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCustomerGroupOrdersProperty()
    {
        $userModel = config('auth.providers.users.model');

        $ordersTable = (new Order())->getTable();
        $usersTable = (new $userModel())->getTable();
        $customer = (new Customer());
        $customersTable = $customer->getTable();
        $customerUserTable = $customer->users()->getTable();
        $customerCustomerGroupTable = $customer->customerGroups()->getTable();

        $orders = DB::connection((new Order())->getConnectionName())
            ->table($ordersTable, 'o')
            ->selectRaw('
                ccg.customer_group_id,
                count(o.id) as order_count
            ')->leftJoin(
                DB::raw("{$usersTable} u"),
                'o.user_id',
                '=',
                'u.id'
            )->leftJoin(
                DB::RAW("{$customerUserTable} cu"),
                'cu.user_id',
                '=',
                'u.id'
            )->leftJoin(
                DB::RAW("{$customersTable} c"),
                'cu.customer_id',
                '=',
                'c.id'
            )->leftJoin(
                DB::RAW("{$customerCustomerGroupTable} ccg"),
                'c.id',
                '=',
                'ccg.customer_id'
            )->whereBetween('placed_at', [
                now()->parse($this->range['from']),
                now()->parse($this->range['to']),
            ])->groupBy('ccg.customer_group_id')
            ->get();

        $customerGroups = CustomerGroup::get();

        $labels = $customerGroups->map(
            fn (CustomerGroup $group) => $group->translate('name')
        )->toArray();

        $series = collect();

        foreach ($customerGroups as $group) {
            // Find our counts...
            $data = $orders->filter(function ($row) use ($group) {
                if ($group->default && ! $row->customer_group_id) {
                    return true;
                }

                return $group->id == $row->customer_group_id;
            });

            $series->push($data->sum('order_count'));
        }

        return collect([
            'chart' => [
                'type' => 'donut',
                'toolbar' => [
                    'show' => false,
                ],
                'height' => '100%',
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'series' => $series->toArray(),
            'labels' => $labels,
            'legend' => [
                'position' => 'bottom',
            ],
        ]);
    }

    public function render()
    {
        // @todo - this is temporary before we implement tests.
        // $order = Order::find(rand(700, 800));
        // $order->notify(
        //     Notification::make()
        //         ->success()
        //         ->system()
        //         ->title('New order placed successfully!')
        //         ->body("**{$order->customer->fullName}** ordered **{$order->lines->count()}** products.")
        //         ->actions([
        //             Action::make('view')
        //               ->button()
        //               ->url(route('hub.orders.show', ['order' => $order])),
        //         ])
        //         ->toDatabase()
        // );

        return view('adminhub::livewire.dashboard');
    }
}
