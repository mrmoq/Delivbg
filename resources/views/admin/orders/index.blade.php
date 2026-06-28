@extends('layouts.master')
@section('title', 'الطلبات')
@section('content')
@include('components.breadcrumb', ['title' => 'الطلبات', 'li_1' => 'لوحة التحكم'])
<div class="card"><div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
<h5 class="card-title mb-0">الطلبات المدفوعة</h5>
<a href="{{ route('admin.orders.export', request()->query()) }}" class="btn btn-success btn-sm">تصدير Excel</a>
</div><div class="card-body">
<form class="row g-2 mb-3" method="GET">
<div class="col-md-3">
    <input type="text" name="search" class="form-control" placeholder="بحث بالاسم أو رقم الطلب..." value="{{ request('search') }}">
</div>
<div class="col-md-3">
    <select name="school_id" class="form-select">
        <option value="">كل المدارس</option>
        @foreach($schools as $school)
            <option value="{{ $school->id }}" @selected(request('school_id') == $school->id)>{{ $school->name_ar }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-3">
    <select name="area_id" class="form-select">
        <option value="">كل المناطق</option>
        @foreach($areas as $area)
            <option value="{{ $area->id }}" @selected(request('area_id') == $area->id)>{{ $area->name_ar }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-2"><button class="btn btn-primary w-100">بحث</button></div>
</form>

<form method="POST" action="{{ route('admin.orders.bulk-update-status') }}" id="bulk-actions-form">
    @csrf
    <div class="d-flex flex-wrap align-items-center gap-2 mb-3 p-3 rounded bg-light border" id="bulk-actions-bar">
        <div class="form-check mb-0">
            <input class="form-check-input" type="checkbox" id="select-all-orders">
            <label class="form-check-label" for="select-all-orders">تحديد الكل</label>
        </div>
        <span class="text-muted" id="selected-count">0 محدد</span>
        <select name="status" id="bulk-status-select" class="form-select form-select-sm" style="width:auto; min-width:10rem">
            <option value="">تغيير الحالة إلى...</option>
            @foreach(\App\Models\Order::FULFILLMENT_STATUSES as $status)
                <option value="{{ $status }}">{{ \App\Models\Order::STATUS_LABELS[$status] }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-sm btn-primary" id="bulk-apply-btn" disabled>تطبيق</button>
        <button type="submit"
            class="btn btn-sm btn-soft-secondary"
            id="bulk-print-btn"
            disabled
            formaction="{{ route('admin.orders.bulk-print') }}"
            formtarget="_blank"
            formnovalidate>
            <i class="ri-printer-line align-middle me-1"></i> طباعة
        </button>
    </div>
</form>

    <div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th class="text-center" style="width:42px"></th>
                <th>رقم الطلب</th>
                <th>الاسم</th>
                <th>المدرسة</th>
                <th>المنطقة</th>
                <th class="text-center">المبلغ</th>
                <th class="text-center">الحالة</th>
                <th class="text-center">إجراءات</th>
            </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
        <tr>
            <td class="text-center">
                <input type="checkbox" form="bulk-actions-form" name="order_ids[]" value="{{ $order->id }}" class="form-check-input order-checkbox">
            </td>
            <td>{{ $order->order_number }}</td>
            <td>{{ $order->customer_name }}</td>
            <td>{{ $order->school?->name_ar }}</td>
            <td>{{ $order->area?->name_ar }}</td>
            <td class="text-center">{{ number_format($order->total_amount, 3) }} د.ك</td>
            <td class="text-center">
                @include('admin.orders._status_dropdown', ['order' => $order])
            </td>
            <td class="text-center">
                @include('admin.orders._actions_dropdown', ['order' => $order])
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>

@include('components.admin-pagination', ['paginator' => $orders])
</div></div>
@endsection

@section('script')
<script>
    function updateOrderStatus(orderId, status) {
        document.getElementById('order-status-input-' + orderId).value = status;
        document.getElementById('order-status-form-' + orderId).submit();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all-orders');
        const checkboxes = document.querySelectorAll('.order-checkbox');
        const countEl = document.getElementById('selected-count');
        const applyBtn = document.getElementById('bulk-apply-btn');
        const printBtn = document.getElementById('bulk-print-btn');
        const statusSelect = document.getElementById('bulk-status-select');
        const bulkForm = document.getElementById('bulk-actions-form');

        function updateBulkUi() {
            const checked = document.querySelectorAll('.order-checkbox:checked');
            const count = checked.length;
            countEl.textContent = count + ' محدد';
            applyBtn.disabled = count === 0 || !statusSelect.value;
            printBtn.disabled = count === 0;
            selectAll.checked = count > 0 && count === checkboxes.length;
            selectAll.indeterminate = count > 0 && count < checkboxes.length;
        }

        selectAll.addEventListener('change', function () {
            checkboxes.forEach(function (cb) {
                cb.checked = selectAll.checked;
            });
            updateBulkUi();
        });

        checkboxes.forEach(function (cb) {
            cb.addEventListener('change', updateBulkUi);
        });

        statusSelect.addEventListener('change', updateBulkUi);

        bulkForm.addEventListener('submit', function (e) {
            const submitter = e.submitter;
            const checked = document.querySelectorAll('.order-checkbox:checked');

            if (checked.length === 0) {
                e.preventDefault();
                alert('يرجى تحديد طلب واحد على الأقل.');
                return;
            }

            if (submitter && submitter.id === 'bulk-apply-btn' && !statusSelect.value) {
                e.preventDefault();
                alert('يرجى اختيار الحالة الجديدة.');
            }
        });

        updateBulkUi();
    });
</script>
@endsection
