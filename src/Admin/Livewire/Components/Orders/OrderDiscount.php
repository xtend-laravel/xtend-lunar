<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Orders;

use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Models\Order;

class OrderDiscount extends Component
{
    use Notifies;

    protected static $overrideComponentAlias = 'discount';

    /**
     * The amount to discount.
     *
     * @var int
     */
    public $amount = 0;

    /**
     * Confirm the discount.
     *
     * @var string
     */
    public bool $confirmed = false;

    /**
     * Any notes for the doscount.
     *
     * @var string
     */
    public string $notes = '';

    /**
     * The instance of the order to discount.
     *
     * @var Order
     */
    public Order $order;

    /**
     * The discount error message.
     *
     * @var bool
     */
    public string $discountError = '';

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:1',
            'confirmed' => 'required',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Action the discount.
     *
     * @return void
     */
    public function discount()
    {
        $this->discountError = '';

        $this->validate();

        $this->amount = $this->availableToRefund / 100;
        $this->notes = '';
        $this->confirmed = false;

        $this->notify(
            message: 'Refund successful',
        );
    }

    /**
     * Cancel the discount.
     *
     * @return void
     */
    public function cancel()
    {
        $this->amount = 0;
        $this->notes = '';
        $this->confirmed = false;

        $this->emit('cancelRefund');
    }

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('adminhub::livewire.components.orders.discount')
            ->layout('adminhub::layouts.base');
    }
}
