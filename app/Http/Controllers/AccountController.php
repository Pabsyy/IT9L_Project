<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesTransaction;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $orders = SalesTransaction::where('user_id', $user->id)
                      ->orderBy('created_at', 'desc')
                      ->get();
        $wishlist = $user->wishlists()->with('product')->get();
                      
        return view('Customer.views.account.dashboard', compact('user', 'orders', 'wishlist'));
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = SalesTransaction::where('user_id', $user->id)
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
                      
        return view('Customer.views.account.orders', compact('orders'));
    }
}
