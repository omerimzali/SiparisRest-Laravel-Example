<?php

namespace App\Services\Discount;

use App\Models\Order;

class TenPercentOverThousandRule implements DiscountRuleInterface
{
    public function apply(Order $order, array $context = [])
    {
        if ($order->total > 1000) {
            $discount = round($order->total * 0.10, 2);
            $subtotal = round($order->total - $discount, 2);
            return [
                'discountReason' => '10_PERCENT_OVER_1000',
                'discountAmount' => (string) $discount,
                'subtotal' => (string) $subtotal,
            ];
        }
        return null;
    }
}
