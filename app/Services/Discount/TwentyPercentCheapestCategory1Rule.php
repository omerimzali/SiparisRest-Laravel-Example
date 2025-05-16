<?php

namespace App\Services\Discount;

use App\Models\Order;

class TwentyPercentCheapestCategory1Rule implements DiscountRuleInterface
{
    public function apply(Order $order, array $context = [])
    {
        $category1Items = [];
        foreach ($order->orderItems as $item) {
            if ($item->product && $item->product->category == 1) {
                $category1Items[] = $item;
            }
        }
        if (count($category1Items) >= 2) {
            $cheapest = collect($category1Items)->sortBy('unit_price')->first();
            $discount = round($cheapest->unit_price * 0.20, 2);
            $subtotal = round($order->total - $discount, 2);
            return [
                'discountReason' => '20_PERCENT_ON_CHEAPEST_CAT1',
                'discountAmount' => (string) $discount,
                'subtotal' => (string) $subtotal,
            ];
        }
        return null;
    }
}
