<?php

namespace Xtend\Extensions\Lunar\Core\PaymentTypes;

use Lunar\Base\DataTransferObjects\PaymentAuthorize;
use Lunar\Base\DataTransferObjects\PaymentCapture;
use Lunar\Base\DataTransferObjects\PaymentRefund;
use Lunar\Models\Transaction;
use Lunar\PaymentTypes\AbstractPayment;

class Payzen extends AbstractPayment
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): PaymentAuthorize
    {
        if (! $this->order) {
            if (! $this->order = $this->cart->order) {
                $this->order = $this->cart->getManager()->createOrder();
            }
        }

        // ...

        return new PaymentAuthorize(true);
    }

    /**
     * {@inheritDoc}
     */
    public function refund(Transaction $transaction, int $amount = 0, $notes = null): PaymentRefund
    {
        // ...
        return new PaymentRefund(true);
    }

    /**
     * {@inheritDoc}
     */
    public function capture(Transaction $transaction, $amount = 0): PaymentCapture
    {
        // ...
        return new PaymentCapture(true);
    }
}
