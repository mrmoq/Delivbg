@extends('layouts.master')

@section('title', 'طباعة الطلبات')

@section('content')
    <div class="d-print-none text-center mb-3">
        <button type="button" class="btn btn-primary" onclick="preparePrint(); window.print();">
            <i class="ri-printer-line align-middle me-1"></i> طباعة ({{ $orders->count() }})
        </button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-soft-secondary ms-2">رجوع</a>
    </div>

    <div id="printPage">
        @foreach($orders as $order)
            <div class="bulk-print-item {{ ! $loop->last ? 'bulk-print-item--break' : '' }}">
                <div class="row justify-content-center">
                    <div class="col-auto px-3">
                        <div class="invoice-page-wrap">
                            <div class="card border-0 shadow-sm w-auto">
                                <div class="card-body p-4 p-lg-5">
                                    @include('admin.orders._order_body', ['order' => $order, 'paymentMethod' => $paymentMethod])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('css')
    @include('admin.orders._order_detail_styles')
    <style>
        .bulk-print-item--break {
            page-break-after: always;
            break-after: page;
            margin-bottom: 2rem;
        }
        @media print {
            .bulk-print-item--break {
                margin-bottom: 0;
            }
        }
    </style>
@endsection

@section('script')
<script>
    function preparePrint() {
        var c = document.querySelector('.customizer-setting');
        if (c) c.remove();
        var m = document.getElementById('msgs');
        if (m) m.style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        preparePrint();
        window.print();
    });
</script>
@endsection
