@extends('layouts.master')
@section('title', 'مناطق '.$governorate->name_ar)

@section('content')
@include('components.breadcrumb', ['title' => 'مناطق '.$governorate->name_ar, 'li_1' => 'لوحة التحكم'])

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">مناطق {{ $governorate->name_ar }}</h5>
        <a href="{{ route('admin.governorates.areas.create', $governorate) }}" class="btn btn-primary btn-sm">إضافة منطقة</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم (عربي)</th>
                        <th>رسوم التوصيل</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($areas as $area)
                        <tr>
                            <td>{{ $area->id }}</td>
                            <td>{{ $area->name_ar }}</td>
                            <td>{{ number_format($area->delivery_fee, 3) }} د.ك</td>
                            <td>{{ $area->is_active ? 'مفعّل' : 'معطّل' }}</td>
                            <td>
                                <a href="{{ route('admin.governorates.areas.edit', [$governorate, $area]) }}" class="btn btn-sm btn-soft-primary">تعديل</a>
                                <form action="{{ route('admin.governorates.areas.destroy', [$governorate, $area]) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف المنطقة؟')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-soft-danger">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('components.admin-pagination', ['paginator' => $areas])
    </div>
</div>
@endsection
