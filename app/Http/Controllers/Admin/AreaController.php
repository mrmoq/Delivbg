<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Governorate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AreaController extends Controller
{
    public function index(Governorate $governorate): View
    {
        $areas = $governorate->areas()->latest()->paginate(15);

        return view('admin.areas.index', compact('governorate', 'areas'));
    }

    public function create(Governorate $governorate): View
    {
        return view('admin.areas.create', compact('governorate'));
    }

    public function store(Request $request, Governorate $governorate): RedirectResponse
    {
        $validated = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'delivery_fee' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $governorate->areas()->create($validated);

        return redirect()->route('admin.governorates.areas.index', $governorate)->with('success', 'تمت إضافة المنطقة.');
    }

    public function edit(Governorate $governorate, Area $area): View
    {
        return view('admin.areas.edit', compact('governorate', 'area'));
    }

    public function update(Request $request, Governorate $governorate, Area $area): RedirectResponse
    {
        $validated = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'delivery_fee' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $area->update($validated);

        return redirect()->route('admin.governorates.areas.index', $governorate)->with('success', 'تم تحديث المنطقة.');
    }

    public function destroy(Governorate $governorate, Area $area): RedirectResponse
    {
        $area->delete();

        return redirect()->route('admin.governorates.areas.index', $governorate)->with('success', 'تم حذف المنطقة.');
    }
}
