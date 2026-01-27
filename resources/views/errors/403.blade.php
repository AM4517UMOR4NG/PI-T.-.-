@extends('errors.layout')

@section('title', 'Access Forbidden')
@section('code', '403')
@section('message', 'Access Denied')
@section('description', 'You do not have the necessary clearance to access this sector.')
@section('color', 'danger')
@section('icon')
    <i class="bi bi-shield-lock-fill"></i>
@endsection
