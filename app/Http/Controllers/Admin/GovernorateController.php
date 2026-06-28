<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GovernorateController extends Controller
{
    public function index(): View
    {
        $governorates = Governorate::query()->withCount('areas')->latest()->paginate(15);

        return view('admin.governorates.index', compact('governorates'));
    }

    public function create(): View
    {
        return view('admin.governorates.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Governorate::query()->create($validated);

        return redirect()->route('admin.governorates.index')->with('success', 'تمت إضافة المحافظة بنجاح.');
    }

    public function edit(Governorate $governorate): View
    {
        return view('admin.governorates.edit', compact('governorate'));
    }

    public function update(Request $request, Governorate $governorate): RedirectResponse
    {
        $validated = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $governorate->update($validated);

        return redirect()->route('admin.governorates.index')->with('success', 'تم تحديث المحافظة بنجاح.');
    }

    public function destroy(Governorate $governorate): RedirectResponse
    {
        $governorate->delete();

        return redirect()->route('admin.governorates.index')->with('success', 'تم حذف المحافظة.');
    }
}
