@extends('layouts.dashboard')

@section('title', 'Kasir Dashboard')

@section('header_title', 'Kasir Dashboard')

@section('sidebar_menu')
    <a href="{{ route('kasir.dashboard') }}" class="nav-link {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid"></i>
        <span>Beranda</span>
    </a>
    
    <div class="sidebar-heading">Transaksi</div>
    
    <a href="{{ route('kasir.transactions') }}" class="nav-link {{ request()->routeIs('kasir.transactions') ? 'active' : '' }}">
        <i class="bi bi-receipt"></i>
        <span>Semua Transaksi</span>
    </a>
    <a href="{{ route('kasir.rentals.index') }}" class="nav-link {{ request()->routeIs('kasir.rentals.*') ? 'active' : '' }}">
        <i class="bi bi-list-check"></i>
        <span>Daftar Sewa</span>
        @php
            $waitingCount = \App\Models\Rental::where('status', 'menunggu_konfirmasi')->count();
        @endphp
        @if($waitingCount > 0)
            <span class="badge bg-warning ms-auto">{{ $waitingCount }}</span>
        @endif
    </a>
    <a href="{{ route('kasir.deliveries.index') }}" class="nav-link {{ request()->routeIs('kasir.deliveries.*') ? 'active' : '' }}">
        <i class="bi bi-truck"></i>
        <span>Pengantaran</span>
        @php
            $pendingDeliveryCount = \App\Models\Rental::where('status', 'menunggu_pengantaran')->count();
        @endphp
        @if($pendingDeliveryCount > 0)
            <span class="badge bg-info ms-auto">{{ $pendingDeliveryCount }}</span>
        @endif
    </a>

    <div class="sidebar-heading">Pembayaran</div>

    <a href="{{ route('kasir.payments') }}" class="nav-link {{ request()->routeIs('kasir.payments') ? 'active' : '' }}">
        <i class="bi bi-credit-card"></i>
        <span>Semua Pembayaran</span>
    </a>
    <a href="{{ route('kasir.fines') }}" class="nav-link {{ request()->routeIs('kasir.fines') ? 'active' : '' }}">
        <i class="bi bi-exclamation-triangle"></i>
        <span>Denda Kerusakan</span>
        @php
            $pendingFines = \App\Models\DamageReport::where('fine_paid', true)->whereNull('kasir_confirmed_at')->count();
        @endphp
        @if($pendingFines > 0)
            <span class="badge bg-danger ms-auto">{{ $pendingFines }}</span>
        @endif
    </a>

    <div class="sidebar-heading">Laporan</div>

    <a href="{{ route('kasir.daily-report') }}" class="nav-link {{ request()->routeIs('kasir.daily-report') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i>
        <span>Laporan Harian</span>
    </a>
@endsection

@section('content')
    @yield('kasir_content')
@endsection
