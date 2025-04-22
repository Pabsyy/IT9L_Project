<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesTransaction;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get the authenticated user
        $userInitials = strtoupper(substr($user->name, 0, 1)); // Get the first letter of the user's name
        $username = $user->username;

        $orders = SalesTransaction::with(['user', 'items.product'])
            ->latest('TransactionDate')
            ->get()
            ->map(function ($order) {
                return (object)[
                    'id' => $order->id,
                    'customer_name' => $order->user->username ?? 'Deleted User',
                    'customer_email' => $order->user->email ?? 'N/A',
                    'date' => $order->TransactionDate,
                    'items_count' => $order->items->count(),
                    'total' => $order->GrandTotal,
                    'status' => $order->Status ?? 'Processing',
                    'items' => $order->items->map(function ($item) {
                        return [
                            'product_name' => $item->product->ProductName ?? 'Unknown Product',
                            'quantity' => $item->Quantity,
                            'unit_price' => $item->UnitPrice
                        ];
                    })
                ];
            });

        // Define system status (assuming online for now)
        $systemStatus = [
            'status' => 'online',
            'color' => 'bg-green-500' // Tailwind class for green background
        ];
        // You might replace the above with actual logic to check system status

        return view('orders', compact('orders', 'userInitials', 'username', 'systemStatus'));
    }

    public function show(SalesTransaction $order)
    {
        return response()->json([
            'id' => $order->SalesTransactionID,
            'customer_name' => $order->user->username ?? 'Deleted User',
            'customer_email' => $order->user->email ?? 'N/A',
            'date' => $order->TransactionDate,
            'items_count' => $order->items ? $order->items->count() : 0,
            'total' => number_format($order->GrandTotal, 2),
            'status' => $order->Status ?? 'Processing'
        ]);
    }
}
