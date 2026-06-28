@extends('layouts.master')

@section('title', $order->order_number)

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            لوحة التحكم
        @endslot
        @push('breadcrumb_additions')
            <li class="breadcrumb-item">
                <a href="{{ route('admin.orders.index') }}">الطلبات</a>
            </li>
        @endpush
        @slot('title', $order->order_number)
    @endcomponent

    <div class="row justify-content-center" id="printPage">
        <div class="col-auto px-3">
            <div class="invoice-page-wrap">
                <div class="card border-0 shadow-sm w-auto">
                    <div class="card-body p-4 p-lg-5">
                        @include('admin.orders._order_body', ['order' => $order, 'paymentMethod' => $paymentMethod])
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap invoice-print-actions mt-3 mb-4 d-print-none">
                @include('admin.orders._status_dropdown', [
                    'order' => $order,
                    'buttonClass' => 'btn',
                    'showIcon' => true,
                ])
                <button type="button" class="btn btn-primary invoice-print-btn" onclick="preparePrint(); window.print();">
                    <i class="ri-printer-line align-middle me-1"></i> طباعة
                </button>
            </div>
        </div>
    </div>
@endsection

@section('css')
    @include('admin.orders._order_detail_styles')
@endsection

@section('script')
<script>
    function updateOrderStatus(orderId, status) {
        document.getElementById('order-status-input-' + orderId).value = status;
        document.getElementById('order-status-form-' + orderId).submit();
    }

    function preparePrint() {
        var c = document.querySelector('.customizer-setting');
        if (c) c.remove();
        var m = document.getElementById('msgs');
        if (m) m.style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.querySelector('.invoice-print-btn');
        if (btn) btn.addEventListener('click', preparePrint);

        @if(request()->boolean('print'))
        preparePrint();
        window.print();
        @endif
    });
</script>
@endsection
