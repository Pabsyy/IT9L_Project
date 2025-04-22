<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesTransaction;
use Illuminate\Support\Facades\Auth;

// AnalyticsController handles analytics-related requests
class AnalyticsController extends Controller
{
    // Index method to display the analytics dashboard
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
        // Get the first letter of the user's name
        $userInitials = strtoupper(substr($user->name, 0, 1));
        // Get the username
        $username = $user->username;

        // Fetch sales data grouped by date
        $salesData = SalesTransaction::selectRaw('DATE(TransactionDate) as date, SUM(GrandTotal) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Load the analytics view with sales data and user information
        return view('analytics', compact('salesData', 'userInitials', 'username'));
    }
}

// © 2025 — Authored by Ryan S Pabiran. All rights reserved. GitHub: https://github.com/Pabsyy
