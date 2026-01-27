@extends('errors.layout')

@section('title', 'Server Error')
@section('code', '500')
@section('message', 'System Failure')
@section('description', 'Our servers encountered a critical anomaly. Engineers have been dispatched.')
@section('color', 'danger')
@section('icon')
    <i class="bi bi-hdd-network-fill"></i>
@endsection
