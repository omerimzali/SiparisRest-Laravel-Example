<?php

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Siparişleri listele
    public function index()
    {
        $orders = Order::with(['customer', 'orderItems.product'])->get();
        return response()->json($orders);
    }

    // Sipariş oluştur
    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();
        $customer = Customer::find($validated['customerId']);
        $items = $validated['items'];

        DB::beginTransaction();
        try {
            $total = 0;
            $orderItems = [];
            foreach ($items as $item) {
                $product = Product::find($item['productId']);
                if (!$product || $product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'error' => 'Stok yetersiz: Ürün ID ' . $item['productId']
                    ], 400);
                }
                $lineTotal = $product->price * $item['quantity'];
                $total += $lineTotal;
                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total' => $lineTotal,
                ];
            }
            $order = Order::create([
                'customer_id' => $customer->id,
                'total' => $total,
            ]);
            foreach ($orderItems as $orderItem) {
                $order->orderItems()->create($orderItem);
                // Stok düş
                $product = Product::find($orderItem['product_id']);
                $product->stock -= $orderItem['quantity'];
                $product->save();
            }
            DB::commit();
            return response()->json($order->load(['orderItems.product', 'customer']), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Sipariş sil
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(['message' => 'Sipariş silindi']);
    }

    // İndirim hesapla
    public function calculateDiscount($orderId)
    {
        $order = Order::with(['orderItems.product'])->findOrFail($orderId);
        $calculator = new \App\Services\Discount\DiscountCalculator([
            new \App\Services\Discount\Buy6Get1FreeCategory2Rule(),
            new \App\Services\Discount\TenPercentOverThousandRule(),
            new \App\Services\Discount\TwentyPercentCheapestCategory1Rule(),
        ]);
        $result = $calculator->calculate($order);
        return response()->json($result);
    }
}
