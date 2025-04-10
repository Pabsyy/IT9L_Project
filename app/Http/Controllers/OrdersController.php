<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesTransaction;

class OrdersController extends Controller
{
    public function index()
    {
        // Fetch all orders
        $orders = SalesTransaction::all();

        return view('orders', compact('orders'));
    }
}
