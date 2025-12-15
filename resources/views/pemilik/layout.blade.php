@extends('layouts.dashboard')

@section('title', 'Pemilik Dashboard')

@section('header_title', 'Panel Pemilik')

@section('sidebar_menu')
    <a href="{{ route('dashboard.pemilik') }}" class="nav-link {{ request()->routeIs('dashboard.pemilik') ? 'active' : '' }}">
        <i class="bi bi-grid"></i>
        <span>Beranda</span>
    </a>
    <div class="sidebar-heading">Laporan</div>
    <a href="{{ route('pemilik.status_produk') }}" class="nav-link {{ request()->routeIs('pemilik.status_produk') ? 'active' : '' }}">
        <i class="bi bi-controller"></i>
        <span>Status Produk</span>
    </a>
    <a href="{{ route('pemilik.laporan_transaksi') }}" class="nav-link {{ request()->routeIs('pemilik.laporan_transaksi') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-bar-graph"></i>
        <span>Laporan Transaksi</span>
    </a>
    <a href="{{ route('pemilik.laporan_pendapatan') }}" class="nav-link {{ request()->routeIs('pemilik.laporan_pendapatan') ? 'active' : '' }}">
        <i class="bi bi-currency-dollar"></i>
        <span>Laporan Pendapatan</span>
    </a>
    <div class="sidebar-heading">Kerusakan</div>
    <a href="{{ route('pemilik.damage-reports') }}" class="nav-link {{ request()->routeIs('pemilik.damage-reports') ? 'active' : '' }}">
        <i class="bi bi-exclamation-triangle"></i>
        <span>Laporan Kerusakan</span>
        @php
            $pendingDamageCount = \App\Models\RentalItem::where('condition', 'rusak')->count();
        @endphp
        @if($pendingDamageCount > 0)
            <span class="badge bg-danger ms-auto">{{ $pendingDamageCount }}</span>
        @endif
    </a>
@endsection

@section('content')
    @yield('owner_content')
@endsection
