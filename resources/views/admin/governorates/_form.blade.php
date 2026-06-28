<div class="mb-3">
    <label class="form-label">الاسم (عربي)</label>
    <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $governorate->name_ar ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">الاسم (إنجليزي)</label>
    <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $governorate->name_en ?? '') }}">
</div>
<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $governorate->is_active ?? true))>
    <label class="form-check-label" for="is_active">مفعّل</label>
</div>
