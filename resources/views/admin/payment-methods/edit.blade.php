@extends('layouts.master')
@section('title', 'تعديل إعدادات KNET')
@section('content')
@include('components.breadcrumb', ['title' => 'تعديل KNET', 'li_1' => 'لوحة التحكم'])
<div class="card"><div class="card-body">
<form action="{{ route('admin.payment-methods.update', $paymentMethod) }}" method="POST">@csrf @method('PUT')
<div class="mb-3"><label class="form-label">اسم الطريقة</label><input type="text" name="name" class="form-control" value="{{ old('name', $paymentMethod->name) }}" required></div>
<div class="mb-3"><label class="form-label">Transport ID</label><input type="text" name="knet_transport_id" class="form-control" value="{{ old('knet_transport_id', $paymentMethod->knet_transport_id) }}"></div>
<div class="mb-3"><label class="form-label">Transport Password</label><input type="text" name="knet_transport_password" class="form-control" value="{{ old('knet_transport_password', $paymentMethod->knet_transport_password) }}"></div>
<div class="mb-3"><label class="form-label">Resource Key <small class="text-muted">(16 حرف)</small></label><input type="text" name="knet_resource_key" class="form-control" maxlength="16" minlength="16" value="{{ old('knet_resource_key', $paymentMethod->knet_resource_key) }}" required></div>
<div class="mb-3"><label class="form-label">Test URL</label><input type="url" name="test_url" class="form-control" value="{{ old('test_url', $paymentMethod->test_url) }}"></div>
<div class="mb-3"><label class="form-label">Live URL</label><input type="url" name="live_url" class="form-control" value="{{ old('live_url', $paymentMethod->live_url) }}"></div>
<div class="mb-3"><label class="form-label">الوضع</label><select name="mode" class="form-select"><option value="test_mode" @selected(old('mode', $paymentMethod->mode) === 'test_mode')>Test</option><option value="live_mode" @selected(old('mode', $paymentMethod->mode) === 'live_mode')>Live</option></select></div>
<div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="status" value="1" @checked(old('status', $paymentMethod->status))><label class="form-check-label">تفعيل</label></div>
<button class="btn btn-primary">حفظ</button>
</form></div></div>
@endsection
