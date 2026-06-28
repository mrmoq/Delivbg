@extends('layouts.master')
@section('title', 'إضافة محافظة')

@section('content')
@include('components.breadcrumb', ['title' => 'إضافة محافظة', 'li_1' => 'لوحة التحكم'])

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.governorates.store') }}" method="POST">
            @csrf
            @include('admin.governorates._form')
            <button type="submit" class="btn btn-primary">حفظ</button>
        </form>
    </div>
</div>
@endsection
