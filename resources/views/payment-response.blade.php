<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>نتيجة الدفع | BASMA GRADS</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
    @include('partials.frontend-styles')
</head>
<body class="payment-page">
    <div class="payment-page__container">
        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="BASMA GRADS" class="payment-page__logo">

        <div class="payment-card">
            <div class="payment-card__status {{ ($status ?? false) ? 'payment-card__status--success' : 'payment-card__status--failed' }}">
                @if($status ?? false)
                    <div class="payment-card__icon payment-card__icon--success">✓</div>
                    <h1 class="payment-card__title payment-card__title--success">تم الدفع بنجاح</h1>
                    <p class="payment-card__subtitle payment-card__subtitle--success">شكراً لك، تم استلام طلبك وسيتم التوصيل قريباً</p>
                @else
                    <div class="payment-card__icon payment-card__icon--failed">✕</div>
                    <h1 class="payment-card__title payment-card__title--failed">فشل الدفع</h1>
                    <p class="payment-card__subtitle payment-card__subtitle--failed">لم تتم عملية الدفع. يرجى المحاولة مرة أخرى.</p>
                @endif
            </div>

            @if($payment?->order)
                @php $order = $payment->order; @endphp

                <div class="payment-card__section">
                    <h2 class="payment-card__heading">ملخص الطلب</h2>
                    <div class="payment-details payment-details--grid">
                        <div>
                            <div class="payment-detail__label">رقم الطلب</div>
                            <div class="payment-detail__value">{{ $order->order_number }}</div>
                        </div>
                        <div>
                            <div class="payment-detail__label">تاريخ الطلب</div>
                            <div class="payment-detail__value">{{ $order->created_at?->format('Y/m/d') }}<br><span class="payment-detail__value--muted">{{ $order->created_at?->format('h:i A') }}</span></div>
                        </div>
                        <div>
                            <div class="payment-detail__label">حالة الطلب</div>
                            <div class="payment-detail__value">
                                <span class="payment-badge {{ ($status ?? false) ? 'payment-badge--success' : 'payment-badge--failed' }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="payment-card__section">
                    <h2 class="payment-card__heading">تفاصيل الدفع</h2>
                    <div class="payment-details">
                        <div class="payment-row">
                            <div class="payment-detail__label">وسيلة الدفع</div>
                            <div class="payment-detail__value">{{ $paymentMethod ?? 'KNET' }}</div>
                        </div>
                        <div class="payment-row">
                            <div class="payment-detail__label">رسوم التوصيل</div>
                            <div class="payment-detail__value">{{ number_format($order->delivery_fee, 3) }} د.ك</div>
                        </div>
                        <div class="payment-total">
                            <span class="payment-total__label">الإجمالي</span>
                            <span class="payment-total__amount">{{ number_format($payment->price, 3) }} د.ك</span>
                        </div>
                        @if($payment->transaction_id)
                            <div class="payment-row">
                                <div class="payment-detail__label">رقم المعاملة</div>
                                <div class="payment-detail__value payment-detail__value--mono">{{ $payment->transaction_id }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="payment-card__footer">
                <a href="{{ route('home') }}" class="payment-btn">
                    {{ ($status ?? false) ? 'العودة للرئيسية' : 'إعادة المحاولة' }}
                </a>
            </div>
        </div>

        <p class="payment-page__copy">© {{ date('Y') }} BASMA GRADS</p>
    </div>
</body>
</html>
