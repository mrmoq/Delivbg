<div class="mb-3">
    <label class="form-label">الاسم (عربي)</label>
    <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $area->name_ar ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">الاسم (إنجليزي)</label>
    <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $area->name_en ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">رسوم التوصيل (د.ك)</label>
    <input type="number" step="0.001" min="0" name="delivery_fee" class="form-control" value="{{ old('delivery_fee', $area->delivery_fee ?? 3) }}" required>
</div>
<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="area_is_active" @checked(old('is_active', $area->is_active ?? true))>
    <label class="form-check-label" for="area_is_active">مفعّل</label>
</div>
