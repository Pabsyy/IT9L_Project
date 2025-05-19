<?php

namespace App\Customerapp\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $fillable = [
        'UserID',
        'total',
        'status',
        'reference_number',
        'notes'
    ];

    // If your primary key is different from 'id'
    protected $primaryKey = 'id';

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Get the inventory movements associated with this order
     */
    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class, 'reference_number', 'reference_number');
    }

    /**
     * Process the order and create inventory movements
     */
    public function processOrder()
    {
        try {
            DB::beginTransaction();

            // Generate a unique reference number if not exists
            if (!$this->reference_number) {
                $this->reference_number = 'ORD-' . str_pad($this->id, 8, '0', STR_PAD_LEFT);
                $this->save();
            }

            // Process each order item
            foreach ($this->items as $item) {
                // Create inventory movement for the sale
                InventoryMovement::create([
                    'product_id' => $item->product_id,
                    'user_id' => $this->UserID,
                    'type' => 'sale',
                    'quantity' => -$item->quantity, // Negative for sales
                    'unit_cost' => $item->product->average_cost ?? 0,
                    'total_cost' => ($item->product->average_cost ?? 0) * $item->quantity,
                    'reference_number' => $this->reference_number,
                    'notes' => 'Order #' . $this->id,
                    'moved_at' => now()
                ]);

                // Update product stock
                $item->product->update([
                    'stock' => $item->product->stock - $item->quantity,
                    'last_movement_at' => now()
                ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Order processing failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if all items in the order have sufficient stock
     */
    public function hasStockForAllItems()
    {
        foreach ($this->items as $item) {
            if (!$item->product->hasStockFor($item->quantity)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the total quantity of items in the order
     */
    public function getTotalQuantity()
    {
        return $this->items->sum('quantity');
    }

    /**
     * Get the formatted total with currency symbol
     */
    public function getFormattedTotal()
    {
        return '₱' . number_format($this->total, 2);
    }

    /**
     * Get the status badge details
     */
    public function getStatusBadge()
    {
        return match($this->status) {
            'completed' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'ri-checkbox-circle-line'],
            'processing' => ['class' => 'bg-blue-100 text-blue-800', 'icon' => 'ri-loader-4-line'],
            'cancelled' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'ri-close-circle-line'],
            default => ['class' => 'bg-yellow-100 text-yellow-800', 'icon' => 'ri-time-line']
        };
    }
}
