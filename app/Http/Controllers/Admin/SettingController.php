<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $defaultDeliveryFee = Setting::getValue('default_delivery_fee', 3);

        return view('admin.settings.edit', compact('defaultDeliveryFee'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'default_delivery_fee' => ['required', 'numeric', 'min:0'],
        ]);

        Setting::setValue('default_delivery_fee', $validated['default_delivery_fee']);

        return redirect()->route('admin.settings.edit')->with('success', 'تم حفظ الإعدادات.');
    }
}
