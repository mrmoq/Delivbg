@extends('layouts.master')
@section('title', 'تعديل محافظة')

@section('content')
@include('components.breadcrumb', ['title' => 'تعديل محافظة', 'li_1' => 'لوحة التحكم'])

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.governorates.update', $governorate) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.governorates._form', ['governorate' => $governorate])
            <button type="submit" class="btn btn-primary">تحديث</button>
        </form>
    </div>
</div>
@endsection
