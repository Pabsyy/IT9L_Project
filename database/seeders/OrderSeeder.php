<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\SalesTransaction;
use App\Models\SalesTransactionItem;
use App\Models\Address;
use App\Models\InventoryMovement;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    protected $paymentMethods = ['cash_on_delivery', 'credit_card', 'bank_transfer', 'gcash'];
    protected $orderStatuses = ['pending', 'processing', 'paid', 'shipped', 'delivered', 'cancelled'];
    
    public function run(): void
    {
        // Disable model events to avoid caching issues
        Model::unsetEventDispatcher();
        
        $users = User::where('role', 'customer')->get();
        $products = Product::all()->keyBy('id');
        $productStocks = $products->pluck('stock', 'id')->toArray();
        
        // Create orders over the last 6 months
        $startDate = now()->subMonths(6)->startOfMonth();
        $endDate = now();
        
        // Calculate the number of days between start and end dates
        $daysBetween = $endDate->diffInDays($startDate);
        
        // Prepare batch arrays
        $orders = [];
        $orderItems = [];
        $inventoryMovements = [];
        $statusHistories = [];
        $productUpdates = [];
        
        // Generate 200 orders
        for ($i = 0; $i < 200; $i++) {
            // Distribute orders evenly across the date range
            $daysToAdd = floor(($i / 200) * $daysBetween);
            $orderDate = $startDate->copy()->addDays($daysToAdd);
            
            $user = $users->random();
            
            // Get or create user's address
            $address = $this->getOrCreateAddress($user);
            
            // Determine order status based on date
            $status = $this->determineOrderStatus($orderDate);
            
            // Calculate initial totals
            $itemCount = rand(1, 5); // 1-5 items per order
            $orderProducts = $products->random($itemCount);
            $validProducts = [];
            $subtotal = 0;
            
            // Calculate initial subtotal and validate stock
            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $productId = $product->id;
                
                // Check if we have enough stock
                if (isset($productStocks[$productId]) && $productStocks[$productId] >= $quantity) {
                    $subtotal += $quantity * $product->price;
                    $validProducts[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'unit_price' => $product->price,
                        'subtotal' => $quantity * $product->price
                    ];
                    
                    // Update our local stock tracking
                    if ($status !== 'cancelled') {
                        $productStocks[$productId] -= $quantity;
                    }
                }
            }
            
            // Skip if no valid products
            if (empty($validProducts)) {
                continue;
            }
            
            // Calculate totals
            $tax = $subtotal * 0.12;
            $shippingFee = rand(0, 1) ? rand(50, 250) : 0;
            $discount = rand(0, 100) < 30 ? $subtotal * (rand(5, 15) / 100) : 0;
            $grandTotal = $subtotal + $tax + $shippingFee - $discount;
            
            // Calculate delivery dates based on status
            $paidAt = null;
            $shippedAt = null;
            $deliveredAt = null;
            $cancelledAt = null;
            
            if ($status !== 'pending' && $status !== 'cancelled') {
                $paidAt = $orderDate->copy()->addHours(rand(1, 24));
            }
            
            if (in_array($status, ['shipped', 'delivered'])) {
                $shippedAt = $paidAt->copy()->addDays(rand(1, 2));
            }
            
            if ($status === 'delivered') {
                $deliveredAt = $shippedAt->copy()->addDays(rand(1, 2));
            }
            
            if ($status === 'cancelled') {
                $cancelledAt = $orderDate->copy()->addHours(rand(1, 48));
            }
            
            // Create order data
            $orderId = '#ORD-' . uniqid();
            $orders[] = [
                'order_id' => $orderId,
                'user_id' => $user->id,
                'reference_number' => 'REF-' . Str::random(10),
                'customer_name' => $user->first_name . ' ' . $user->last_name,
                'customer_email' => $user->email,
                'contact_number' => $user->contact_number ?? fake()->phoneNumber(),
                'shipping_address' => $this->formatAddress($address),
                'billing_address' => $this->formatAddress($address),
                'delivery_method' => rand(0, 1) ? 'pickup' : 'delivery',
                'payment_method' => $this->paymentMethods[array_rand($this->paymentMethods)],
                'payment_status' => $status === 'cancelled' ? 'cancelled' : ($status === 'pending' ? 'pending' : 'paid'),
                'order_status' => $status,
                'transaction_date' => $orderDate,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_fee' => $shippingFee,
                'discount' => $discount,
                'grand_total' => $grandTotal,
                'paid_at' => $paidAt,
                'shipped_at' => $shippedAt,
                'delivered_at' => $deliveredAt,
                'cancelled_at' => $cancelledAt,
                'created_at' => $orderDate,
                'updated_at' => $orderDate
            ];
            
            $lastOrderId = count($orders);
            
            // Prepare order items and inventory movements
            foreach ($validProducts as $item) {
                $orderItems[] = [
                    'sales_transaction_id' => $lastOrderId,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                    'tax_rate' => 0.12,
                    'tax_amount' => $item['subtotal'] * 0.12,
                    'sku' => $item['product']->sku,
                    'product_snapshot' => json_encode([
                        'name' => $item['product']->name,
                        'sku' => $item['product']->sku,
                        'price' => $item['product']->price,
                        'category' => $item['product']->category->name,
                        'brand' => $item['product']->brand->name,
                    ]),
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate
                ];
                
                if ($status !== 'cancelled') {
                    $inventoryMovements[] = [
                        'product_id' => $item['product']->id,
                        'user_id' => $user->id,
                        'type' => 'sale',
                        'quantity' => -$item['quantity'],
                        'reference_number' => $orders[$lastOrderId - 1]['reference_number'],
                        'moved_at' => $orderDate,
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate
                    ];
                    
                    // Track product updates
                    $productKey = $item['product']->id;
                    if (!isset($productUpdates[$productKey])) {
                        $productUpdates[$productKey] = [
                            'sales' => 0,
                            'stock' => 0,
                            'last_movement_at' => $orderDate
                        ];
                    }
                    $productUpdates[$productKey]['sales'] += $item['quantity'];
                    $productUpdates[$productKey]['stock'] -= $item['quantity'];
                }
            }
            
            // Prepare order status history
            $statusHistories = array_merge(
                $statusHistories,
                $this->prepareStatusHistory($lastOrderId, $status, $orderDate)
            );
        }
        
        // Execute all database operations in a transaction
        DB::transaction(function () use ($orders, $orderItems, $inventoryMovements, $statusHistories, $productUpdates) {
            // Bulk insert orders
            DB::table('sales_transactions')->insert($orders);
            
            // Get the actual order IDs
            $orderIdMap = DB::table('sales_transactions')
                ->whereIn('order_id', array_column($orders, 'order_id'))
                ->pluck('id', 'order_id')
                ->toArray();
            
            // Update order IDs in related records
            foreach ($orderItems as &$item) {
                $item['sales_transaction_id'] = $orderIdMap[$orders[$item['sales_transaction_id'] - 1]['order_id']];
            }
            foreach ($statusHistories as &$history) {
                $history['sales_transaction_id'] = $orderIdMap[$orders[$history['sales_transaction_id'] - 1]['order_id']];
            }
            
            // Bulk insert related records
            foreach (array_chunk($orderItems, 500) as $chunk) {
                DB::table('sales_transaction_items')->insert($chunk);
            }
            
            foreach (array_chunk($inventoryMovements, 500) as $chunk) {
                DB::table('inventory_movements')->insert($chunk);
            }
            
            foreach (array_chunk($statusHistories, 500) as $chunk) {
                DB::table('order_status_history')->insert($chunk);
            }
            
            // Bulk update products
            foreach ($productUpdates as $productId => $updates) {
                DB::table('products')
                    ->where('id', $productId)
                    ->update([
                        'sales' => DB::raw('sales + ' . $updates['sales']),
                        'stock' => DB::raw('GREATEST(0, stock + (' . $updates['stock'] . '))'),
                        'last_movement_at' => $updates['last_movement_at']
                    ]);
            }
        });
    }
    
    protected function getOrCreateAddress($user)
    {
        $address = Address::where('user_id', $user->id)
            ->where('is_default', true)
            ->first() ?? 
            Address::where('user_id', $user->id)->inRandomOrder()->first();
            
        if (!$address) {
            $address = Address::create([
                'user_id' => $user->id,
                'name' => 'Home',
                'type' => 'home',
                'street_address' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->state(),
                'postal_code' => fake()->postcode(),
                'country' => 'Philippines',
                'is_default' => true,
                'phone_number' => $user->contact_number ?? fake()->phoneNumber(),
                'additional_info' => 'Main residence',
            ]);
        }
        
        return $address;
    }
    
    protected function determineOrderStatus($orderDate)
    {
        // Make 80% of all orders delivered
        if (rand(1, 100) <= 80) {
            return 'delivered';
        }
        
        $daysSinceOrder = now()->diffInDays($orderDate);
        
        if ($daysSinceOrder < 1) {
            return array_rand(array_flip(['pending', 'processing']));
        } elseif ($daysSinceOrder < 2) {
            return array_rand(array_flip(['processing', 'paid', 'shipped']));
        } else {
            return array_rand(array_flip(['shipped', 'cancelled']));
        }
    }
    
    protected function formatAddress($address)
    {
        return implode(', ', array_filter([
            $address->street_address,
            $address->city,
            $address->state,
            $address->postal_code,
            $address->country
        ]));
    }
    
    protected function prepareStatusHistory($orderId, $finalStatus, $orderDate)
    {
        $statuses = [];
        $currentDate = $orderDate;
        
        // Add initial pending status
        $statuses[] = [
            'sales_transaction_id' => $orderId,
            'status' => 'pending',
            'comment' => 'Order placed',
            'created_at' => $currentDate,
            'updated_at' => $currentDate
        ];
        
        if ($finalStatus !== 'pending') {
            $currentDate = $currentDate->copy()->addHours(rand(1, 24));
            $statuses[] = [
                'sales_transaction_id' => $orderId,
                'status' => 'processing',
                'comment' => 'Order confirmed and processing started',
                'created_at' => $currentDate,
                'updated_at' => $currentDate
            ];
        }
        
        if (in_array($finalStatus, ['paid', 'shipped', 'delivered'])) {
            $currentDate = $currentDate->copy()->addHours(rand(1, 24));
            $statuses[] = [
                'sales_transaction_id' => $orderId,
                'status' => 'paid',
                'comment' => 'Payment received and verified',
                'created_at' => $currentDate,
                'updated_at' => $currentDate
            ];
        }
        
        if (in_array($finalStatus, ['shipped', 'delivered'])) {
            $currentDate = $currentDate->copy()->addDays(rand(1, 3));
            $statuses[] = [
                'sales_transaction_id' => $orderId,
                'status' => 'shipped',
                'comment' => 'Order shipped to customer',
                'created_at' => $currentDate,
                'updated_at' => $currentDate
            ];
        }
        
        if ($finalStatus === 'delivered') {
            $currentDate = $currentDate->copy()->addDays(rand(1, 3));
            $statuses[] = [
                'sales_transaction_id' => $orderId,
                'status' => 'delivered',
                'comment' => 'Order successfully delivered',
                'created_at' => $currentDate,
                'updated_at' => $currentDate
            ];
        }
        
        if ($finalStatus === 'cancelled') {
            $currentDate = $orderDate->copy()->addHours(rand(1, 48));
            $statuses[] = [
                'sales_transaction_id' => $orderId,
                'status' => 'cancelled',
                'comment' => 'Order cancelled by customer',
                'created_at' => $currentDate,
                'updated_at' => $currentDate
            ];
        }
        
        return $statuses;
    }
} 