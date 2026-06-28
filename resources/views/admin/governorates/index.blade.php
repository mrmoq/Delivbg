@extends('layouts.master')
@section('title', 'المحافظات')

@section('content')
@include('components.breadcrumb', ['title' => 'المحافظات', 'li_1' => 'لوحة التحكم'])

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">قائمة المحافظات</h5>
        <a href="{{ route('admin.governorates.create') }}" class="btn btn-primary btn-sm">إضافة محافظة</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم (عربي)</th>
                        <th>الاسم (إنجليزي)</th>
                        <th>عدد المناطق</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($governorates as $governorate)
                        <tr>
                            <td>{{ $governorate->id }}</td>
                            <td>{{ $governorate->name_ar }}</td>
                            <td>{{ $governorate->name_en }}</td>
                            <td>{{ $governorate->areas_count }}</td>
                            <td>{{ $governorate->is_active ? 'مفعّل' : 'معطّل' }}</td>
                            <td>
                                <a href="{{ route('admin.governorates.areas.index', $governorate) }}" class="btn btn-sm btn-soft-info">المناطق</a>
                                <a href="{{ route('admin.governorates.edit', $governorate) }}" class="btn btn-sm btn-soft-primary">تعديل</a>
                                <form action="{{ route('admin.governorates.destroy', $governorate) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف المحافظة؟')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-soft-danger">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('components.admin-pagination', ['paginator' => $governorates])
    </div>
</div>
@endsection
