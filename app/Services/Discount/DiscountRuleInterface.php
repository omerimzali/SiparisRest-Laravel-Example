<?php

namespace App\Services\Discount;

use App\Models\Order;

interface DiscountRuleInterface
{
    /**
     * İndirim uygula ve sonucu döndür.
     * @param Order $order
     * @param array $context
     * @return array ["discountReason" => string, "discountAmount" => float, "subtotal" => float] veya null
     */
    public function apply(Order $order, array $context = []);
}
