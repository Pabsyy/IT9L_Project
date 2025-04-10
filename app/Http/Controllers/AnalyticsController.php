<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesTransaction;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Fetch sales data grouped by date
        $salesData = SalesTransaction::selectRaw('DATE(TransactionDate) as date, SUM(GrandTotal) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('analytics', compact('salesData'));
    }
}
