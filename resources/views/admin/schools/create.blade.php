@extends('layouts.master')
@section('title', 'إضافة مدرسة')
@section('content')
@include('components.breadcrumb', ['title' => 'إضافة مدرسة', 'li_1' => 'لوحة التحكم'])
<div class="card"><div class="card-body">
<form action="{{ route('admin.schools.store') }}" method="POST">@csrf @include('admin.schools._form')<button class="btn btn-primary">حفظ</button></form>
</div></div>
@endsection
