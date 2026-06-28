<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BASMA GRADS — طلب توصيل</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    @include('partials.frontend-styles')
</head>
<body class="min-h-screen text-slate-800 antialiased" style="background-color: #182e65;">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
        <div class="mb-8 text-center">
            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="BASMA GRADS" class="mx-auto h-20 w-auto sm:h-24">
        </div>

        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl ring-1 ring-slate-200/70 sm:p-8">
            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pr-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('orders.store') }}" method="POST" class="space-y-5">
                @csrf

                <x-dropdown-select id="school_id" name="school_id" label="المدرسة" placeholder="اختر المدرسة" required>
                    <option value="">اختر المدرسة</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" @selected(old('school_id') == $school->id)>{{ $school->name_ar }}</option>
                    @endforeach
                </x-dropdown-select>

                <div>
                    <label for="customer_name" class="mb-1.5 block text-sm font-medium text-slate-700">الاسم</label>
                    <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required
                        placeholder="الاسم المكتوب علي الدرع"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-[#182e65] focus:outline-none focus:ring-2 focus:ring-[#182e65]/20">
                </div>

                <div>
                    <label for="customer_phone" class="mb-1.5 block text-sm font-medium text-slate-700">رقم التليفون</label>
                    @php
                        $phoneDigits = old('customer_phone');
                        if ($phoneDigits && str_starts_with($phoneDigits, '+965')) {
                            $phoneDigits = substr($phoneDigits, 4);
                        }
                    @endphp
                    <div dir="ltr" class="flex overflow-hidden rounded-xl border border-slate-300 shadow-sm focus-within:border-[#182e65] focus-within:ring-2 focus-within:ring-[#182e65]/20">
                        <span class="flex shrink-0 items-center border-r border-slate-300 bg-slate-50 px-4 text-sm font-semibold text-slate-600">+965</span>
                        <input type="tel" id="customer_phone" name="customer_phone" value="{{ $phoneDigits }}"
                            required placeholder="XXXXXXXX" inputmode="numeric"
                            maxlength="8" minlength="8" pattern="[0-9]{8}"
                            class="min-w-0 flex-1 border-0 px-4 py-3 text-sm text-left placeholder:text-slate-400 focus:outline-none focus:ring-0">
                    </div>
                    <p class="mt-1 text-xs text-slate-500">أدخل 8 أرقام فقط بدون رمز الدولة</p>
                </div>

                <div class="border-t border-slate-100 pt-5">
                    <h2 class="mb-4 text-sm font-semibold text-slate-900">تفاصيل عنوان التوصيل</h2>

                    <div class="space-y-4">
                        <x-dropdown-select id="governorate_id" name="governorate_id" label="المحافظة" placeholder="اختر المحافظة" required>
                            <option value="">اختر المحافظة</option>
                            @foreach($governorates as $governorate)
                                <option value="{{ $governorate->id }}" @selected(old('governorate_id') == $governorate->id)>{{ $governorate->name_ar }}</option>
                            @endforeach
                        </x-dropdown-select>

                        <x-dropdown-select id="area_id" name="area_id" label="المنطقة" placeholder="اختر المنطقة" required disabled>
                            <option value="">اختر المنطقة</option>
                        </x-dropdown-select>

                        <div>
                            <label for="block" class="mb-1.5 block text-sm font-medium text-slate-700">القطعة</label>
                            <input type="text" id="block" name="block" value="{{ old('block') }}"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-[#182e65] focus:outline-none focus:ring-2 focus:ring-[#182e65]/20">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="street" class="mb-1.5 block text-sm font-medium text-slate-700">الشارع</label>
                                <input type="text" id="street" name="street" value="{{ old('street') }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-[#182e65] focus:outline-none focus:ring-2 focus:ring-[#182e65]/20">
                            </div>
                            <div>
                                <label for="avenue" class="mb-1.5 block text-sm font-medium text-slate-700">جادة</label>
                                <input type="text" id="avenue" name="avenue" value="{{ old('avenue') }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-[#182e65] focus:outline-none focus:ring-2 focus:ring-[#182e65]/20">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="building" class="mb-1.5 block text-sm font-medium text-slate-700">بناية / منزل</label>
                                <input type="text" id="building" name="building" value="{{ old('building') }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-[#182e65] focus:outline-none focus:ring-2 focus:ring-[#182e65]/20">
                            </div>
                            <div>
                                <label for="floor" class="mb-1.5 block text-sm font-medium text-slate-700">الدور</label>
                                <input type="text" id="floor" name="floor" value="{{ old('floor') }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-[#182e65] focus:outline-none focus:ring-2 focus:ring-[#182e65]/20">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-[#182e65]/10 px-4 py-4 flex items-center justify-between ring-1 ring-[#182e65]/15">
                    <span class="text-sm font-medium text-[#182e65]">رسوم التوصيل</span>
                    <span id="delivery-fee-display" class="text-lg font-bold text-[#182e65]">{{ number_format($defaultFee, 3) }} د.ك</span>
                </div>

                <button type="submit"
                    class="w-full rounded-xl bg-[#182e65] px-4 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:bg-[#122348] focus:outline-none focus:ring-2 focus:ring-[#182e65]/40 focus:ring-offset-2">
                    الدفع عبر KNET
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const governorateSelect = document.getElementById('governorate_id');
            const areaSelect = document.getElementById('area_id');
            const feeDisplay = document.getElementById('delivery-fee-display');
            const defaultFee = {{ $defaultFee }};
            const oldAreaId = @json(old('area_id'));
            const oldGovernorateId = @json(old('governorate_id'));

            const choicesConfig = (placeholder, search = false) => ({
                searchEnabled: search,
                itemSelectText: '',
                shouldSort: false,
                placeholder: true,
                placeholderValue: placeholder,
                noResultsText: 'لا توجد نتائج',
                noChoicesText: 'لا توجد خيارات',
                allowHTML: false,
                position: 'bottom',
            });

            const schoolChoices = new Choices('#school_id', choicesConfig('اختر المدرسة', true));
            const governorateChoices = new Choices('#governorate_id', choicesConfig('اختر المحافظة'));
            let areaChoices = new Choices('#area_id', choicesConfig('اختر المنطقة'));
            let areaFees = {};

            areaChoices.disable();

            function setAreaPlaceholder() {
                areaChoices.clearStore();
                areaChoices.setChoices([
                    { value: '', label: 'اختر المنطقة', placeholder: true },
                ], 'value', 'label', true);
            }

            async function loadAreas(governorateId, selectedAreaId = null) {
                setAreaPlaceholder();
                areaChoices.disable();
                areaFees = {};
                feeDisplay.textContent = defaultFee.toFixed(3) + ' د.ك';

                if (!governorateId) return;

                const response = await fetch(`/governorates/${governorateId}/areas`);
                const areas = await response.json();

                const choices = [
                    { value: '', label: 'اختر المنطقة', placeholder: true },
                    ...areas.map(area => {
                        areaFees[String(area.id)] = parseFloat(area.delivery_fee);

                        return {
                            value: String(area.id),
                            label: area.name_ar,
                            selected: selectedAreaId && String(selectedAreaId) === String(area.id),
                        };
                    }),
                ];

                areaChoices.setChoices(choices, 'value', 'label', true);
                areaChoices.enable();
                updateFee();
            }

            function updateFee() {
                const fee = areaFees[areaSelect.value] ?? defaultFee;
                feeDisplay.textContent = fee.toFixed(3) + ' د.ك';
            }

            governorateSelect.addEventListener('change', () => loadAreas(governorateSelect.value));
            areaSelect.addEventListener('change', updateFee);

            if (oldGovernorateId) {
                loadAreas(oldGovernorateId, oldAreaId);
            }

            const phoneInput = document.getElementById('customer_phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', () => {
                    phoneInput.value = phoneInput.value.replace(/\D/g, '').slice(0, 8);
                });
            }
        });
    </script>
</body>
</html>
