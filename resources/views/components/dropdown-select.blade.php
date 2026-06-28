@props([
    'id',
    'name',
    'label',
    'required' => false,
    'disabled' => false,
    'placeholder' => 'اختر',
])

<div {{ $attributes->merge(['class' => 'dropdown-field']) }}>
    <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-slate-700">
        {{ $label }}
    </label>
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        data-placeholder="{{ $placeholder }}"
        class="dropdown-select-input w-full"
    >
        {{ $slot }}
    </select>
</div>
