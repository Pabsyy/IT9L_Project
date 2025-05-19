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
                      
        return view('account.dashboard', compact('user', 'orders'));
    }
}
