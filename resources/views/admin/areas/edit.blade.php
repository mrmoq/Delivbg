@extends('layouts.master')
@section('title', 'تعديل منطقة')

@section('content')
@include('components.breadcrumb', ['title' => 'تعديل منطقة', 'li_1' => 'لوحة التحكم'])

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.governorates.areas.update', [$governorate, $area]) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.areas._form', ['area' => $area])
            <button type="submit" class="btn btn-primary">تحديث</button>
        </form>
    </div>
</div>
@endsection
