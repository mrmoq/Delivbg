<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InvoicesExport;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $invoices = Payment::query()
            ->with(['order.school', 'order.governorate', 'order.area'])
            ->when($request->filled('status'), function ($q) use ($request) {
                if ($request->status === 'paid') {
                    $q->where('status', true);
                } elseif ($request->status === 'failed') {
                    $q->where('status', false);
                }
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($query) use ($search) {
                    $query->where('transaction_id', 'like', "%{$search}%")
                        ->orWhere('payment_token', 'like', "%{$search}%")
                        ->orWhereHas('order', fn ($o) => $o->where('order_number', 'like', "%{$search}%")
                            ->orWhere('customer_name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.invoices.index', compact('invoices'));
    }

    public function export(Request $request): BinaryFileResponse
    {
        return Excel::download(new InvoicesExport($request->all()), 'invoices-'.now()->format('Y-m-d').'.xlsx');
    }
}
