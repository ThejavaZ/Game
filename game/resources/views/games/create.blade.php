@extends('games.form')
@section('formName')
    Crear
@endsection
@section('action')
    action = "{{route('games.store')}}"
@endsection