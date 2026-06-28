<form id="order-status-form-{{ $order->id }}" action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="d-none">
    @csrf @method('PUT')
    <input type="hidden" name="status" id="order-status-input-{{ $order->id }}">
</form>
<div class="dropdown {{ $dropdownClass ?? 'd-inline-block' }}">
    <button class="btn {{ $buttonClass ?? 'btn-sm' }} {{ $order->status_color_class }} dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        @if($showIcon ?? false)
            <i class="ri-exchange-line align-middle me-1"></i>
        @endif
        {{ $order->status_label }}
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        @foreach(\App\Models\Order::FULFILLMENT_STATUSES as $status)
            <li>
                <button type="button"
                    class="dropdown-item d-flex align-items-center gap-2 {{ $order->status === $status ? 'active' : '' }}"
                    onclick="updateOrderStatus({{ $order->id }}, '{{ $status }}')">
                    <span class="status-dot {{ \App\Models\Order::statusColorClass($status) }}"></span>
                    {{ \App\Models\Order::STATUS_LABELS[$status] }}
                </button>
            </li>
        @endforeach
    </ul>
</div>
