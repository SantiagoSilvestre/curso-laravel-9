@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('content')
    <h1 class="text-2xl font-semibold leading-tigh py-2">Novo usuário</h1>

    @include('includes.validations_forms')

    <form action="{{route('users.store')}}" method="post" enctype="multipart/form-data">
        @include('users._partials.form')
    </form>


@endsection