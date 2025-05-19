<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'unit_cost',
        'total_cost',
        'reference_number',
        'batch_number',
        'notes',
        'moved_at'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'moved_at' => 'datetime'
    ];

    /**
     * Get the product associated with the movement.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedUnitCost()
    {
        return '₱' . number_format($this->unit_cost ?? 0, 2);
    }

    public function getFormattedTotalCost()
    {
        return '₱' . number_format($this->total_cost ?? 0, 2);
    }

    public function getTypeLabel()
    {
        return match($this->type) {
            'purchase' => 'Stock In',
            'sale' => 'Sale',
            'damage' => 'Damage/Loss',
            'return' => 'Return',
            'adjustment' => 'Adjustment',
            default => ucfirst($this->type)
        };
    }

    public function getTypeColor()
    {
        return match($this->type) {
            'purchase' => 'green',
            'sale' => 'blue',
            'damage' => 'red',
            'return' => 'yellow',
            'adjustment' => 'purple',
            default => 'gray'
        };
    }
} 