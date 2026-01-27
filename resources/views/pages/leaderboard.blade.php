@extends('pelanggan.layout')

@section('title', 'Leaderboard')
@section('header_title', 'Top Users Ranking')

@section('pelanggan_content')
<div class="container-fluid p-0">
    <!-- Category Navigation -->
    <ul class="nav nav-pills nav-fill mb-4 gap-2 p-1 bg-white rounded-4 shadow-sm" id="leaderboardTabs" role="tablist" style="max-width: 1000px; margin: 0 auto;">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill fw-bold py-2" id="ps-tab" data-bs-toggle="pill" data-bs-target="#ps-content" type="button" role="tab" aria-selected="true">
                <i class="bi bi-controller me-2"></i>Unit PS
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-bold py-2" id="game-tab" data-bs-toggle="pill" data-bs-target="#game-content" type="button" role="tab" aria-selected="false">
                <i class="bi bi-disc me-2"></i>Games
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-bold py-2" id="acc-tab" data-bs-toggle="pill" data-bs-target="#acc-content" type="button" role="tab" aria-selected="false">
                <i class="bi bi-headset me-2"></i>Aksesoris
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-bold py-2" id="sultan-tab" data-bs-toggle="pill" data-bs-target="#sultan-content" type="button" role="tab" aria-selected="false">
                <i class="bi bi-crown me-2"></i>Sultan
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="leaderboardTabContent">
        
        <!-- Top PS Renters Tab -->
        <div class="tab-pane fade show active" id="ps-content" role="tabpanel" tabindex="0">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold d-flex align-items-center">
                                <span class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    <i class="bi bi-controller"></i>
                                </span>
                                Jawara Rental PS
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($topPsRenters as $index => $user)
                                    <div class="list-group-item border-bottom-0 d-flex align-items-center px-4 py-3">
                                        <div class="me-3 position-relative text-center" style="min-width: 40px;">
                                            <div class="rounded-circle fw-bold d-flex align-items-center justify-content-center mx-auto shadow-sm" 
                                                 style="width: 32px; height: 32px; 
                                                 @if($index == 0) background: #FFD700; color: #fff; border: 2px solid #fff;
                                                 @elseif($index == 1) background: #C0C0C0; color: #fff; border: 2px solid #fff;
                                                 @elseif($index == 2) background: #CD7F32; color: #fff; border: 2px solid #fff;
                                                 @else background: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6;
                                                 @endif">
                                                {{ $index + 1 }}
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <div class="me-3">
                                                @if($user->avatar)
                                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle object-fit-cover shadow-sm" width="50" height="50">
                                                @else
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-primary fs-5" style="width: 50px; height: 50px;">
                                                       {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold text-dark fs-5">{{ $user->name }}</h6>
                                                <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Total Sewa Unit</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-4 py-2 fs-6">
                                                {{ $user->total_rents }}x <span class="d-none d-sm-inline">Sewa</span>
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486777.png" alt="Empty" width="80" class="mb-3 opacity-25">
                                        <p class="text-muted fw-semibold">Belum ada data untuk ditampilkan.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Game Renters Tab -->
        <div class="tab-pane fade" id="game-content" role="tabpanel" tabindex="0">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold d-flex align-items-center">
                                <span class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    <i class="bi bi-disc"></i>
                                </span>
                                Gamers Sejati
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($topGameRenters as $index => $user)
                                     <div class="list-group-item border-bottom-0 d-flex align-items-center px-4 py-3">
                                        <div class="me-3 position-relative text-center" style="min-width: 40px;">
                                            <div class="rounded-circle fw-bold d-flex align-items-center justify-content-center mx-auto shadow-sm" 
                                                 style="width: 32px; height: 32px; 
                                                 @if($index == 0) background: #FFD700; color: #fff; border: 2px solid #fff;
                                                 @elseif($index == 1) background: #C0C0C0; color: #fff; border: 2px solid #fff;
                                                 @elseif($index == 2) background: #CD7F32; color: #fff; border: 2px solid #fff;
                                                 @else background: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6;
                                                 @endif">
                                                {{ $index + 1 }}
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <div class="me-3">
                                                @if($user->avatar)
                                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle object-fit-cover shadow-sm" width="50" height="50">
                                                @else
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-primary fs-5" style="width: 50px; height: 50px;">
                                                       {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold text-dark fs-5">{{ $user->name }}</h6>
                                                <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Total Sewa Game</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-4 py-2 fs-6">
                                                {{ $user->total_rents }}x <span class="d-none d-sm-inline">Sewa</span>
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486777.png" alt="Empty" width="80" class="mb-3 opacity-25">
                                        <p class="text-muted fw-semibold">Belum ada data untuk ditampilkan.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Accessory Renters Tab -->
        <div class="tab-pane fade" id="acc-content" role="tabpanel" tabindex="0">
             <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold d-flex align-items-center">
                                <span class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    <i class="bi bi-headset"></i>
                                </span>
                                Equipment Master
                            </h5>
                        </div>
                        <div class="card-body p-0">
                             <div class="list-group list-group-flush">
                                @forelse($topAccRenters as $index => $user)
                                     <div class="list-group-item border-bottom-0 d-flex align-items-center px-4 py-3">
                                        <div class="me-3 position-relative text-center" style="min-width: 40px;">
                                            <div class="rounded-circle fw-bold d-flex align-items-center justify-content-center mx-auto shadow-sm" 
                                                 style="width: 32px; height: 32px; 
                                                 @if($index == 0) background: #FFD700; color: #fff; border: 2px solid #fff;
                                                 @elseif($index == 1) background: #C0C0C0; color: #fff; border: 2px solid #fff;
                                                 @elseif($index == 2) background: #CD7F32; color: #fff; border: 2px solid #fff;
                                                 @else background: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6;
                                                 @endif">
                                                {{ $index + 1 }}
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <div class="me-3">
                                                @if($user->avatar)
                                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle object-fit-cover shadow-sm" width="50" height="50">
                                                @else
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-primary fs-5" style="width: 50px; height: 50px;">
                                                       {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold text-dark fs-5">{{ $user->name }}</h6>
                                                <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Total Sewa Aksesoris</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-4 py-2 fs-6">
                                                {{ $user->total_rents }}x <span class="d-none d-sm-inline">Sewa</span>
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                         <img src="https://cdn-icons-png.flaticon.com/512/7486/7486777.png" alt="Empty" width="80" class="mb-3 opacity-25">
                                        <p class="text-muted fw-semibold">Belum ada data untuk ditampilkan.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Spenders (Sultans) Tab -->
        <div class="tab-pane fade" id="sultan-content" role="tabpanel" tabindex="0">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="card border-0 shadow-sm bg-gradient text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                         <!-- Background Texture -->
                         <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: radial-gradient(circle at 10% 20%, rgba(255,255,255,0.1) 0%, transparent 20%); z-index: 0;"></div>
                         
                         <div class="card-header border-bottom border-white border-opacity-25 py-3 position-relative z-1" style="background: transparent !important;">
                            <h5 class="mb-0 fw-bold d-flex align-items-center text-white">
                                <span class="bg-warning bg-opacity-25 text-warning rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    <i class="bi bi-crown"></i>
                                </span>
                                Sultan PlayStation (Top Spenders)
                            </h5>
                        </div>
                        <div class="card-body p-0 position-relative z-1">
                            <div class="list-group list-group-flush bg-transparent">
                                @forelse($topSpenders as $index => $user)
                                    <div class="list-group-item bg-transparent border-bottom border-white border-opacity-10 d-flex align-items-center px-4 py-3">
                                        <div class="me-3 position-relative text-center" style="min-width: 40px;">
                                             <div class="rounded-circle fw-bold d-flex align-items-center justify-content-center mx-auto shadow-sm" 
                                                 style="width: 32px; height: 32px; 
                                                 background: {{ $index == 0 ? '#FFD700' : ($index == 1 ? '#C0C0C0' : ($index == 2 ? '#CD7F32' : 'rgba(255,255,255,0.2)')) }}; 
                                                 color: {{ $index < 3 ? '#fff' : '#fff' }}; font-weight: 900;">
                                                {{ $index + 1 }}
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <div class="me-3">
                                                @if($user->avatar)
                                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle object-fit-cover border border-2 border-warning shadow-sm" width="50" height="50">
                                                @else
                                                    <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center fw-bold text-white border border-2 border-warning" style="width: 50px; height: 50px;">
                                                       {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold text-white fs-5">{{ $user->name }}</h6>
                                                <small class="text-white text-opacity-75 text-uppercase fw-semibold" style="font-size: 0.75rem;">Total Transaksi</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-warning text-dark fw-bold rounded-pill px-4 py-2 fs-6">
                                                Rp {{ number_format($user->total_spent, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5 text-white text-opacity-75">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486777.png" alt="Empty" width="80" class="mb-3 opacity-50" style="filter: brightness(0) invert(1);">
                                        <p class="fw-semibold">Belum ada data sultan.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<style>
    /* Tab Styling */
    .nav-pills .nav-link {
        color: var(--text-muted);
        background-color: transparent;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    
    .nav-pills .nav-link:hover {
        background-color: var(--bg-light);
        color: var(--text-primary);
    }
    
    .nav-pills .nav-link.active {
        background-color: var(--bs-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    
    [data-theme="dark"] .nav-pills {
        background-color: var(--card-bg) !important;
    }
    
    [data-theme="dark"] .nav-pills .nav-link {
        color: var(--text-muted);
    }
    
    [data-theme="dark"] .nav-pills .nav-link.active {
        background-color: var(--bs-primary);
        color: white !important;
    }
    
    /* Animation for tabs */
    .tab-pane {
        transition: opacity 0.3s ease;
    }
</style>
@endsection
