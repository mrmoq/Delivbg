@extends('layouts.master')
@section('title', 'الإعدادات')
@section('content')
@include('components.breadcrumb', ['title' => 'الإعدادات', 'li_1' => 'لوحة التحكم'])
<div class="card"><div class="card-body">
<form action="{{ route('admin.settings.update') }}" method="POST">@csrf @method('PUT')
<div class="mb-3"><label class="form-label">رسوم التوصيل الافتراضية (د.ك)</label><input type="number" step="0.001" min="0" name="default_delivery_fee" class="form-control" value="{{ old('default_delivery_fee', $defaultDeliveryFee) }}" required></div>
<button class="btn btn-primary">حفظ</button>
</form></div></div>
@endsection
