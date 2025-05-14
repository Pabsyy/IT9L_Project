<?php

namespace App\Exports;

use App\Models\SalesTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return SalesTransaction::all();
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Customer Name',
            'Customer Email',
            'Date',
            'Items',
            'Total',
            'Status'
        ];
    }

    public function map($order): array
    {
        return [
            '#ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
            $order->customer_name,
            $order->customer_email,
            $order->created_at->format('M d, Y'),
            $order->items->count() . ' items',
            '$' . number_format($order->grand_total, 2),
            ucfirst($order->status)
        ];
    }
}
