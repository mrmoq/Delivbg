@extends('layouts.master')
@section('title', 'إضافة منطقة')

@section('content')
@include('components.breadcrumb', ['title' => 'إضافة منطقة', 'li_1' => 'لوحة التحكم'])

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.governorates.areas.store', $governorate) }}" method="POST">
            @csrf
            @include('admin.areas._form')
            <button type="submit" class="btn btn-primary">حفظ</button>
        </form>
    </div>
</div>
@endsection
