<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JsonOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(base_path('orders.json'));
$orders = json_decode($json, true);

foreach ($orders as $order) {
    $createdOrder = \App\Models\Order::updateOrCreate(
        ['id' => $order['id']],
        [
            'customer_id' => $order['customer_id'],
            'total' => $order['total'],
            'created_at' => $order['created_at'],
        ]
    );
    if (!empty($order['items'])) {
        foreach ($order['items'] as $item) {
            \App\Models\OrderItem::updateOrCreate(
                [
                    'order_id' => $createdOrder->id,
                    'product_id' => $item['product_id'],
                ],
                [
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['total'],
                ]
            );
        }
    }
}
    }
}
