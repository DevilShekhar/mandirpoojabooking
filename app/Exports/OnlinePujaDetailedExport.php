<?php

namespace App\Exports;

use App\Models\OnlinePujaOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OnlinePujaDetailedExport implements FromCollection, WithHeadings, WithMapping
{
    protected $requestData;
    protected $totalAmount = 0;
    protected $totalBookingsCount = 0;

    public function __construct($requestData)
    {
        $this->requestData = $requestData;
    }

    public function collection()
    {
        $query = OnlinePujaOrder::with(['customer', 'puja.category']);

        // Date Range Filter
        if (!empty($this->requestData['from_date']) && !empty($this->requestData['to_date'])) {
            $query->whereBetween('created_at', [
                $this->requestData['from_date'] . ' 00:00:00', 
                $this->requestData['to_date'] . ' 23:59:59'
            ]);
        }

        // Specific Online Puja Filter
        if (!empty($this->requestData['online_puja_id'])) {
            $query->where('online_puja_id', $this->requestData['online_puja_id']);
        }

        // Category Filter
        if (!empty($this->requestData['online_puja_cat_id'])) {
            $catId = $this->requestData['online_puja_cat_id'];
            $query->whereHas('puja', function($subQ) use ($catId) {
                $subQ->where('online_puja_cat_id', $catId)
                     ->orWhere('category_id', $catId);
            });
        }

        // Booking Status Filter (applies to online_puja_orders table)
        if (!empty($this->requestData['booking_status'])) {
            $query->where('booking_status', $this->requestData['booking_status']);
        }

        // Puja Status Filter (applies to related online_pujas table via 'puja' relation)
        if (isset($this->requestData['status']) && $this->requestData['status'] !== '') {
            $statusVal = $this->requestData['status'];
            $query->whereHas('puja', function($subQ) use ($statusVal) {
                $subQ->where('status', $statusVal);
            });
        }

        $records = $query->latest()->get();

        // Calculate totals for summary reference if needed
        $this->totalBookingsCount = $records->count();
        $this->totalAmount = $records->sum('amount');

        return $records;
    }

    public function headings(): array
    {
        return [
            'Order Number',
            'Customer Name',
            'Customer Phone',
            'Puja Title',
            'Category',
            'Package Name',
            'Booking Status',
            'Amount (₹)',
            'Booking Date',
        ];
    }

    public function map($row): array
    {
        $pujaTitle = $row->puja->title ?? 'N/A';
        $categoryName = $row->puja->category->name ?? $row->category_name ?? 'N/A';

        return [
            $row->order_number ?? 'N/A',
            $row->customer_name ?? ($row->customer->name ?? 'N/A'),
            $row->customer_phone ?? ($row->customer->phone ?? 'N/A'),
            $pujaTitle,
            $categoryName,
            $row->package_name ?? 'N/A',
            ucfirst($row->booking_status ?? 'N/A'),
            number_format($row->amount ?? 0, 2, '.', ''),
            $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : 'N/A',
        ];
    }
}