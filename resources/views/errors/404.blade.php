@extends('errors.layout')

@section('title', 'Page Not Found')
@section('code', '404')
@section('message', 'Lost in Space?')
@section('description', 'The page you are looking for seems to have vanished into a black hole.')
@section('color', 'warning')
@section('icon')
    <i class="bi bi-geo-alt-fill"></i>
@endsection
