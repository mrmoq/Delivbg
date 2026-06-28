@extends('layouts.master')
@section('title', 'الفواتير')
@section('content')
@include('components.breadcrumb', ['title' => 'الفواتير', 'li_1' => 'لوحة التحكم'])
<div class="card"><div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
<h5 class="card-title mb-0">الفواتير</h5>
<a href="{{ route('admin.invoices.export', request()->query()) }}" class="btn btn-success btn-sm">تصدير Excel</a>
</div><div class="card-body">
<form class="row g-2 mb-3" method="GET">
<div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="بحث..." value="{{ request('search') }}"></div>
<div class="col-md-3"><select name="status" class="form-select"><option value="">الكل</option><option value="paid" @selected(request('status')==='paid')>مدفوع</option><option value="failed" @selected(request('status')==='failed')>فشل</option></select></div>
<div class="col-md-2"><button class="btn btn-primary w-100">بحث</button></div>
</form>
<table class="table table-bordered align-middle"><thead><tr><th>رقم الطلب</th><th>العميل</th><th>المبلغ</th><th>معاملة KNET</th><th>الحالة</th><th></th></tr></thead>
<tbody>@foreach($invoices as $invoice)<tr><td>{{ $invoice->order?->order_number }}</td><td>{{ $invoice->order?->customer_name }}</td><td>{{ number_format($invoice->price,3) }} د.ك</td><td>{{ $invoice->transaction_id ?? '—' }}</td><td>{{ $invoice->status_label }}</td><td>@if($invoice->order)<a href="{{ route('admin.orders.show', $invoice->order) }}" class="btn btn-sm btn-soft-primary">عرض</a>@endif</td></tr>@endforeach</tbody></table>
@include('components.admin-pagination', ['paginator' => $invoices])
</div></div>
@endsection
