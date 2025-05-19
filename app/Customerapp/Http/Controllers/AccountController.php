<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->UserID)
                      ->orderBy('created_at', 'desc')
                      ->get();
                      
        return view('account.dashboard', compact('user', 'orders'));
    }
}
