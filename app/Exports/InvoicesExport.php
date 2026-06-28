<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InvoicesExport implements FromView
{
    public function __construct(protected array $filters = []) {}

    public function view(): View
    {
        $invoices = Payment::query()
            ->with(['order.school', 'order.governorate', 'order.area'])
            ->when(! empty($this->filters['status']), function ($q) {
                if ($this->filters['status'] === 'paid') {
                    $q->where('status', true);
                } elseif ($this->filters['status'] === 'failed') {
                    $q->where('status', false);
                }
            })
            ->latest()
            ->get();

        return view('admin.exports.invoices', compact('invoices'));
    }
}
