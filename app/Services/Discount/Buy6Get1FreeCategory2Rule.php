<?php

namespace App\Services\Discount;

use App\Models\Order;

class Buy6Get1FreeCategory2Rule implements DiscountRuleInterface
{
    public function apply(Order $order, array $context = [])
    {
        $discount = 0;
        foreach ($order->orderItems as $item) {
            if ($item->product && $item->product->category == 2 && $item->quantity >= 6) {
                $freeCount = intdiv($item->quantity, 6);
                $discount += $freeCount * $item->unit_price;
            }
        }
        if ($discount > 0) {
            $subtotal = round($order->total - $discount, 2);
            return [
                'discountReason' => 'BUY_5_GET_1',
                'discountAmount' => (string) round($discount, 2),
                'subtotal' => (string) $subtotal,
            ];
        }
        return null;
    }
}
