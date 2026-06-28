<table>
    <thead>
        <tr>
            <th>رقم الطلب</th>
            <th>الاسم</th>
            <th>التليفون</th>
            <th>المدرسة</th>
            <th>المحافظة</th>
            <th>المنطقة</th>
            <th>المبلغ</th>
            <th>الحالة</th>
            <th>التاريخ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->formatted_phone ?? '—' }}</td>
                <td>{{ $order->school?->name_ar }}</td>
                <td>{{ $order->governorate?->name_ar }}</td>
                <td>{{ $order->area?->name_ar }}</td>
                <td>{{ number_format($order->total_amount, 3) }}</td>
                <td>{{ $order->status_label }}</td>
                <td>{{ $order->created_at?->format('Y-m-d H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
