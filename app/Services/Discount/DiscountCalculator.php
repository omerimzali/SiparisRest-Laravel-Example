<?php

namespace App\Services\Discount;

use App\Models\Order;

class DiscountCalculator
{
    /** @var DiscountRuleInterface[] */
    protected $rules = [];

    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }

    public function calculate(Order $order)
    {
        $discounts = [];
        $subtotal = $order->total;
        $context = [];
        foreach ($this->rules as $rule) {
            $result = $rule->apply($order, array_merge($context, ['subtotal' => $subtotal]));
            if ($result) {
                $discounts[] = $result;
                $subtotal = $result['subtotal'];
            }
        }
        $totalDiscount = round($order->total - $subtotal, 2);
        return [
            'orderId' => $order->id,
            'discounts' => $discounts,
            'totalDiscount' => (string) $totalDiscount,
            'discountedTotal' => (string) $subtotal,
        ];
    }
}
