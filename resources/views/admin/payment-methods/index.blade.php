@extends('layouts.master')
@section('title', 'إعدادات الدفع')
@section('content')
@include('components.breadcrumb', ['title' => 'إعدادات الدفع', 'li_1' => 'لوحة التحكم'])
<div class="card"><div class="card-body">
<table class="table table-bordered align-middle"><thead><tr><th>الاسم</th><th>الوضع</th><th>الحالة</th><th></th></tr></thead>
<tbody>@foreach($paymentMethods as $method)<tr><td>{{ $method->name }}</td><td>{{ $method->mode === 'live_mode' ? 'Live' : 'Test' }}</td><td>{{ $method->status_label }}</td><td><a href="{{ route('admin.payment-methods.edit', $method) }}" class="btn btn-sm btn-primary">تعديل</a></td></tr>@endforeach</tbody></table>
@include('components.admin-pagination', ['paginator' => $paymentMethods])
</div></div>
@endsection
