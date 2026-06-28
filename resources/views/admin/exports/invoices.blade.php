<table>
    <thead>
        <tr>
            <th>رقم الطلب</th>
            <th>العميل</th>
            <th>المدرسة</th>
            <th>المبلغ</th>
            <th>معاملة KNET</th>
            <th>Track ID</th>
            <th>الحالة</th>
            <th>التاريخ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->order?->order_number }}</td>
                <td>{{ $invoice->order?->customer_name }}</td>
                <td>{{ $invoice->order?->school?->name_ar }}</td>
                <td>{{ number_format($invoice->price, 3) }}</td>
                <td>{{ $invoice->transaction_id }}</td>
                <td>{{ $invoice->payment_token }}</td>
                <td>{{ $invoice->status_label }}</td>
                <td>{{ $invoice->created_at?->format('Y-m-d H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
