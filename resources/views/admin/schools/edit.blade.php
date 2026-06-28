@extends('layouts.master')
@section('title', 'تعديل مدرسة')
@section('content')
@include('components.breadcrumb', ['title' => 'تعديل مدرسة', 'li_1' => 'لوحة التحكم'])
<div class="card"><div class="card-body">
<form action="{{ route('admin.schools.update', $school) }}" method="POST">@csrf @method('PUT') @include('admin.schools._form', ['school' => $school])<button class="btn btn-primary">تحديث</button></form>
</div></div>
@endsection
