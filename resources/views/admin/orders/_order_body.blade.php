@php
    $payment = $order->payment;
    $paymentMethodName = $paymentMethod ?? 'KNET';
@endphp

<div class="basma-inv">
    <header class="basma-inv__header">
        <div class="basma-inv__brand">
            <img src="{{ asset('assets/images/invoices.png') }}" alt="BASMA GRADS">
        </div>
        <div class="basma-inv__meta">
            <div class="basma-inv__meta-col">
                <span>تاريخ الطلب</span>
                <strong>{{ $order->created_at?->format('d M Y') }}</strong>
            </div>
            <div class="basma-inv__meta-vr d-none d-sm-block"></div>
            <div class="basma-inv__meta-col">
                <span>رقم الطلب</span>
                <strong>{{ $order->order_number }}</strong>
            </div>
        </div>
    </header>

    <div class="basma-inv__info">
        <div>
            <h6>بيانات العميل</h6>
            <dl>
                <dt>الاسم</dt>
                <dd>{{ $order->customer_name }}</dd>
                <dt>التليفون</dt>
                <dd dir="ltr" style="text-align: right">{{ $order->formatted_phone ?? '—' }}</dd>
                <dt>المدرسة</dt>
                <dd>{{ $order->school?->name_ar ?? '—' }}</dd>
                <dt>الحالة</dt>
                <dd>
                    <span class="badge {{ $order->status_color_class }}">
                        {{ $order->status_label }}
                    </span>
                </dd>
            </dl>
        </div>
        <div>
            <h6>عنوان التوصيل</h6>
            <p class="basma-inv__delivery-dd">{{ $order->full_address ?: '—' }}</p>
        </div>
    </div>

    <div class="basma-inv__table-wrap">
        <table class="basma-inv__table">
            <thead>
                <tr>
                    <th style="width:48px">#</th>
                    <th>الوصف</th>
                    <th class="text-end">المبلغ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>رسوم توصيل الدرع — {{ $order->order_number }}</td>
                    <td class="text-end">{{ number_format($order->delivery_fee, 3) }} د.ك</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="basma-inv__bottom">
        <div class="basma-inv__pay">
            <h4>تفاصيل الدفع</h4>
            @if($payment)
                <p><strong>وسيلة الدفع:</strong> {{ $paymentMethodName }}</p>
                <p><strong>حالة الدفع:</strong> {{ $payment->status_label }}</p>
                @if($payment->transaction_id)
                    <p><strong>رقم المعاملة:</strong> {{ $payment->transaction_id }}</p>
                @endif
                <p><strong>وقت الدفع:</strong> {{ $payment->created_at?->format('Y-m-d H:i:s') ?? '—' }}</p>
            @else
                <p>لم يتم الدفع بعد.</p>
            @endif
        </div>
        <div class="basma-inv__sum">
            <div class="basma-inv__sum-line basma-inv__sum-line--head">
                <span>رسوم التوصيل</span>
                <span>{{ number_format($order->delivery_fee, 3) }} د.ك</span>
            </div>
            <div class="basma-inv__total-box">
                <span>الإجمالي</span>
                <span>{{ number_format($order->total_amount, 3) }} د.ك</span>
            </div>
        </div>
    </div>
</div>
