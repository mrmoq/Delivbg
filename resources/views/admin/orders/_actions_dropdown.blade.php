<div class="dropdown d-inline-block">
    <button class="btn btn-sm btn-soft-primary dropdown-toggle px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="إجراءات">
        <span class="visually-hidden">إجراءات</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="{{ route('admin.orders.show', $order) }}">
                <i class="ri-eye-line align-middle me-1"></i> عرض
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('admin.orders.show', ['order' => $order, 'print' => 1]) }}" target="_blank">
                <i class="ri-printer-line align-middle me-1"></i> طباعة
            </a>
        </li>
    </ul>
</div>
