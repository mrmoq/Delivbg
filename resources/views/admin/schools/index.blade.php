@extends('layouts.master')
@section('title', 'المدارس')

@section('content')
@include('components.breadcrumb', ['title' => 'المدارس', 'li_1' => 'لوحة التحكم'])

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">قائمة المدارس</h5>
        <a href="{{ route('admin.schools.create') }}" class="btn btn-primary btn-sm">إضافة مدرسة</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered align-middle">
            <thead><tr><th>#</th><th>الاسم (عربي)</th><th>الحالة</th><th>إجراءات</th></tr></thead>
            <tbody>
                @foreach($schools as $school)
                    <tr>
                        <td>{{ $school->id }}</td>
                        <td>{{ $school->name_ar }}</td>
                        <td>{{ $school->is_active ? 'مفعّل' : 'معطّل' }}</td>
                        <td>
                            <a href="{{ route('admin.schools.edit', $school) }}" class="btn btn-sm btn-soft-primary">تعديل</a>
                            <form action="{{ route('admin.schools.destroy', $school) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف المدرسة؟')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-soft-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include('components.admin-pagination', ['paginator' => $schools])
    </div>
</div>
@endsection
