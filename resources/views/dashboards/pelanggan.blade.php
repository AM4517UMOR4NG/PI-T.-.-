@extends('pelanggan.layout')

@section('pelanggan_content')
<div class="container-fluid">
    <!-- Hero Section -->
    <!-- Hero Section -->
    <!-- Hero Section -->
    <div class="text-center py-5 mb-5 rounded-4 position-relative overflow-hidden hero-card" style="border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
        <!-- Animated Background Elements -->
        <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 0;">
            <div class="position-absolute rounded-circle" style="width: 300px; height: 300px; background: rgba(255, 255, 255, 0.1); filter: blur(60px); top: -50px; left: -50px;"></div>
            <div class="position-absolute rounded-circle" style="width: 250px; height: 250px; background: rgba(255, 255, 255, 0.1); filter: blur(60px); bottom: -50px; right: -50px;"></div>
        </div>

        <div class="position-relative z-1 p-4">
            <h2 class="fw-bold display-5 mb-3 typing-text-container" style="color: white; text-shadow: 0 2px 10px rgba(0,0,0,0.3); font-family: 'Outfit', sans-serif;">
                <span id="typing-heading"></span><span class="cursor-blink">|</span>
            </h2>
            <p class="lead mb-0 typing-desc-container" style="max-width: 600px; margin: 0 auto; color: rgba(255,255,255,0.9); font-weight: 300; min-height: 1.5em;">
                <span id="typing-desc"></span>
            </p>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700&display=swap');

        .hero-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
            /* Light Mode: Blue Gradient */
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
        }
        
        /* Dark Mode: Deep Purple/Dark Gradient */
        [data-theme="dark"] .hero-card {
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
        }

        .hero-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.4);
        }
        .cursor-blink {
            animation: blink 1s step-end infinite;
            color: #fff;
            font-weight: 100;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Typing effect code...
            const headingText = "{{ __('dashboard.welcome_user', ['name' => Auth::user()->name]) }}";
             // existing code...
             // ...
             // Auto-generated snippet shortened for brevity, I will include the full typing effect script preservation + new widgets
             // Wait, I should not delete the script. I will just insert the widgets ABOVE the hero section.
        });
    </script>
    
    <!-- Dashboard Stats Widgets -->
    <div class="row g-4 mb-5">
        <!-- Active Rentals -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm overflow-hidden card-hover-lift" style="border-radius: 16px;">
                <div class="card-body position-relative p-4">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="bi bi-controller display-4 text-primary"></i>
                    </div>
                    <div class="d-flex flex-column h-100">
                        <div class="mb-3">
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                <i class="bi bi-joystick me-1"></i> Aktif
                            </span>
                        </div>
                        <h2 class="display-5 fw-bold mb-1 text-dark">{{ $activeRentals }}</h2>
                        <p class="text-muted mb-0 small text-uppercase fw-bold tracking-wider">Sedang Disewa</p>
                        @if($activeRentals > 0)
                            <div class="mt-3 pt-3 border-top border-light">
                                <a href="{{ route('pelanggan.rentals.index') }}" class="text-decoration-none small fw-bold text-primary d-flex align-items-center">
                                    Lihat Detail <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                            </div>
                        @else
                            <div class="mt-3 pt-3 border-top border-light opacity-0">
                                <span class="small">&nbsp;</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm overflow-hidden card-hover-lift" style="border-radius: 16px;">
                <div class="card-body position-relative p-4">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="bi bi-credit-card display-4 text-warning"></i>
                    </div>
                    <div class="d-flex flex-column h-100">
                        <div class="mb-3">
                            <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2">
                                <i class="bi bi-hourglass-split me-1"></i> Pending
                            </span>
                        </div>
                        <h2 class="display-5 fw-bold mb-1 text-dark">{{ $pendingPaymentsCount }}</h2>
                        <p class="text-muted mb-0 small text-uppercase fw-bold tracking-wider">Menunggu Bayar</p>
                        @if($pendingPaymentsCount > 0)
                            <div class="mt-3 pt-3 border-top border-light">
                                <a href="{{ route('pelanggan.rentals.index') }}" class="text-decoration-none small fw-bold text-warning d-flex align-items-center">
                                    Bayar Sekarang <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                            </div>
                        @else
                             <div class="mt-3 pt-3 border-top border-light opacity-0">
                                <span class="small">&nbsp;</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm overflow-hidden card-hover-lift" style="border-radius: 16px;">
                <div class="card-body position-relative p-4">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="bi bi-clock-history display-4 text-success"></i>
                    </div>
                    <div class="d-flex flex-column h-100">
                        <div class="mb-3">
                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i> Total
                            </span>
                        </div>
                        <h2 class="display-5 fw-bold mb-1 text-dark">{{ $totalTransactions }}</h2>
                        <p class="text-muted mb-0 small text-uppercase fw-bold tracking-wider">Riwayat Sewa</p>
                        <div class="mt-3 pt-3 border-top border-light">
                             <a href="{{ route('pelanggan.rentals.index') }}" class="text-decoration-none small fw-bold text-success d-flex align-items-center">
                                Lihat Riwayat <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Spent -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm overflow-hidden card-hover-lift" style="border-radius: 16px;">
                <div class="card-body position-relative p-4">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="bi bi-wallet2 display-4 text-info"></i>
                    </div>
                    <div class="d-flex flex-column h-100">
                        <div class="mb-3">
                            <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-2">
                                <i class="bi bi-graph-up me-1"></i> Pengeluaran
                            </span>
                        </div>
                        <h4 class="fw-bold mb-1 text-dark text-truncate" title="Rp {{ number_format($totalSpent, 0, ',', '.') }}">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h4>
                        <p class="text-muted mb-0 small text-uppercase fw-bold tracking-wider">Total Belanja</p>
                        <div class="mt-3 pt-3 border-top border-light opacity-0">
                            <span class="small">&nbsp;</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const headingText = "{{ __('dashboard.welcome_user', ['name' => Auth::user()->name]) }}";
            
            // Array of sentences to rotate
            const descSentences = [
                "{{ __('dashboard.hero_description') }}",
                "Sewa PlayStation 5 dan 4 dengan harga terbaik.",
                "Koleksi game terlengkap siap untuk dimainkan.",
                "Proses sewa cepat, mudah, dan terpercaya.",
                "Rasakan sensasi gaming tanpa batas."
            ];
            
            const headingElement = document.getElementById('typing-heading');
            const descElement = document.getElementById('typing-desc');
            const cursorElement = document.querySelector('.cursor-blink');
            
            let headingIndex = 0;
            let descIndex = 0; // Index of the current sentence
            let charIndex = 0; // Index of the character in the current sentence
            let isDeleting = false;
            
            function typeHeading() {
                if (headingIndex < headingText.length) {
                    headingElement.textContent += headingText.charAt(headingIndex);
                    headingIndex++;
                    setTimeout(typeHeading, 50);
                } else {
                    // Start typing description after heading is done
                    setTimeout(typeDesc, 300);
                }
            }
            
            function typeDesc() {
                const currentSentence = descSentences[descIndex % descSentences.length];
                
                if (isDeleting) {
                    if (charIndex > 0) {
                        descElement.textContent = currentSentence.substring(0, charIndex - 1);
                        charIndex--;
                        setTimeout(typeDesc, 30); // Deleting speed
                    } else {
                        isDeleting = false;
                        descIndex++; // Move to next sentence
                        setTimeout(typeDesc, 500); // Pause before typing next
                    }
                } else {
                    if (charIndex < currentSentence.length) {
                        descElement.textContent = currentSentence.substring(0, charIndex + 1);
                        charIndex++;
                        setTimeout(typeDesc, 50); // Typing speed
                    } else {
                        isDeleting = true;
                        setTimeout(typeDesc, 2000); // Pause before deleting
                    }
                }
            }
            
            // Start animation
            setTimeout(typeHeading, 500);
        });
    </script>

    <!-- Unit PS Section -->
    <section class="mb-5">
        <div class="position-relative mb-4">
            <div class="text-center mb-3">
                <h4 class="fw-bold d-inline-block">
                    <i class="bi bi-controller me-2"></i>{{ __('dashboard.unit_ps_title') }}
                </h4>
            </div>
            <div class="position-absolute end-0 top-50 translate-middle-y">
                <a href="{{ route('pelanggan.unitps.index') }}" class="btn btn-sm rounded-pill px-3" style="color: #0652DD; border: 1px solid #0652DD; background-color: transparent;" onmouseover="this.style.backgroundColor='#0652DD'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#0652DD';">{{ __('dashboard.view_all') }}</a>
            </div>
        </div>

        <div class="mt-4 row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
            @forelse($unitps as $unit)
                @php
                    $modelKey = $unit->model;
                    if (!$modelKey) {
                        $name = strtoupper($unit->name ?? '');
                        if (str_contains($name, 'PS5')) $modelKey = 'PS5';
                        elseif (str_contains($name, 'PS3')) $modelKey = 'PS3';
                        else $modelKey = 'PS4';
                    }
                @endphp
                <div class="col">
                    <div class="card h-100 shadow-sm position-relative card-blue-left" style="border-radius: 16px;">
                        <div class="position-absolute" style="top: 12px; left: 12px; z-index: 10;">
                            <span class="d-flex align-items-center justify-content-center fw-bold" style="background: #0652DD; color: #fff; width: 44px; height: 44px; border-radius: 50%; font-size: 0.7rem; box-shadow: 0 3px 10px rgba(6,82,221,0.4);">
                                {{ $modelKey }}
                            </span>
                        </div>
                        <div class="position-relative" style="height: 200px; overflow: hidden;">
                            @if($unit->foto)
                                <img src="{{ str_starts_with($unit->foto, 'http') ? $unit->foto : asset('storage/' . $unit->foto) }}"
                                     alt="{{ $unit->name }}"
                                     class="w-100 h-100 object-fit-cover"
                                     style="transition: transform 0.3s ease;">
                            @else
                                <img src="https://placehold.co/400x300/F5F6FA/222222?text={{ urlencode($unit->model) }}"
                                     alt="{{ $unit->name }}"
                                     class="w-100 h-100 object-fit-cover"
                                     style="transition: transform 0.3s ease;">
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="text-center mb-3">
                                <h5 class="card-title fw-bold mb-1 text-brand-main">{{ $unit->name }}</h5>
                                <p class="mb-1 text-muted" style="font-size: 1rem; font-weight: 500;">{{ $unit->brand }}</p>
                                @php
                                    $stok = $unit->stock ?? 0;
                                @endphp
                                @if($stok > 0)
                                    <div class="mb-2 text-muted" style="font-size: 1rem; font-weight: 500;">
                                        {{ __('dashboard.available', ['count' => $stok]) }}
                                    </div>
                                @else
                                    <div class="mb-2 text-muted" style="font-size: 1rem; font-weight: 500;">
                                        {{ __('dashboard.out_of_stock') }}
                                    </div>
                                @endif
                                <div class="fw-bold text-brand-main">Rp {{ number_format($unit->price_per_hour, 0, ',', '.') }}<span class="small fw-normal text-muted">{{ __('dashboard.per_hour') }}</span></div>
                            </div>
                            <div class="mt-auto">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary add-to-cart-btn"
                                        data-type="unitps"
                                        data-id="{{ $unit->id }}"
                                        data-name="{{ $unit->name }}"
                                        data-price="{{ $unit->price_per_hour }}"
                                        data-price_type="per_jam">
                                        <i class="bi bi-cart"></i>
                                    </button>
                                    <a href="{{ route('pelanggan.rentals.create') }}?type=unitps&id={{ $unit->id }}" class="btn btn-sm btn-primary flex-grow-1">
                                        {{ __('dashboard.rent') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info border-0" style="background-color: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.2); color: #67e8f9;">
                                        <i class="bi bi-info-circle me-2"></i>{{ __('dashboard.no_units_available') }}
                                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Games Section -->
    <section class="mb-5">
        <div class="position-relative mb-4">
            <div class="text-center mb-3">
                <h4 class="fw-bold d-inline-block">
                    <i class="bi bi-disc me-2"></i>{{ __('dashboard.game_title') }}
                </h4>
            </div>
            <div class="position-absolute end-0 top-50 translate-middle-y">
                <a href="{{ route('pelanggan.games.index') }}" class="btn btn-sm rounded-pill px-3" style="color: #0652DD; border: 1px solid #0652DD; background-color: transparent;" onmouseover="this.style.backgroundColor='#0652DD'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#0652DD';">{{ __('dashboard.view_all') }}</a>
            </div>
        </div>

        <div class="mt-4 row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
            @forelse($games as $game)
                @php
                    $platformKey = $game->platform ?? 'PS4';
                @endphp
                <div class="col">
                    <div class="card h-100 shadow-sm position-relative card-blue-left" style="border-radius: 16px;">
                        <div class="position-absolute" style="top: 12px; left: 12px; z-index: 10;">
                            <span class="d-flex align-items-center justify-content-center fw-bold" style="background: #0652DD; color: #fff; width: 44px; height: 44px; border-radius: 50%; font-size: 0.7rem; box-shadow: 0 3px 10px rgba(6,82,221,0.4);">
                                {{ $platformKey }}
                            </span>
                        </div>
                        <div class="position-relative" style="height: 200px; overflow: hidden;">
                            @if($game->gambar)
                                <img src="{{ str_starts_with($game->gambar, 'http') ? $game->gambar : asset('storage/' . $game->gambar) }}"
                                     alt="{{ $game->judul }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <img src="https://placehold.co/300x400/F5F6FA/222222?text={{ urlencode($game->judul) }}" alt="{{ $game->judul }}" class="w-100 h-100 object-fit-cover">
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="text-center mb-3">
                                <h5 class="card-title fw-bold mb-1 text-truncate text-brand-main">{{ $game->judul }}</h5>
                                <p class="mb-1 text-muted" style="font-size: 1rem; font-weight: 500;">{{ $game->platform }} • {{ $game->genre }}</p>
                                @php
                                    $stok = $game->stok ?? 0;
                                @endphp
                                @if($stok > 0)
                                    <div class="mb-2 text-muted" style="font-size: 1rem; font-weight: 500;">
                                        {{ __('dashboard.available', ['count' => $stok]) }}
                                    </div>
                                @else
                                    <div class="mb-2 text-muted" style="font-size: 1rem; font-weight: 500;">
                                        {{ __('dashboard.out_of_stock') }}
                                    </div>
                                @endif
                                <div class="fw-bold text-brand-main">Rp {{ number_format($game->harga_per_hari, 0, ',', '.') }}<span class="small fw-normal text-muted">{{ __('dashboard.per_day') }}</span></div>
                            </div>
                            <div class="mt-auto">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary add-to-cart-btn"
                                        data-type="game"
                                        data-id="{{ $game->id }}"
                                        data-name="{{ $game->judul }}"
                                        data-price="{{ $game->harga_per_hari }}"
                                        data-price_type="per_hari">
                                        <i class="bi bi-cart"></i>
                                    </button>
                                    <a href="{{ route('pelanggan.rentals.create') }}?type=game&id={{ $game->id }}" class="btn btn-sm btn-primary flex-grow-1">
                                        {{ __('dashboard.rent') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info border-0" style="background-color: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.2); color: #67e8f9;">
                                        <i class="bi bi-info-circle me-2"></i>{{ __('dashboard.no_games_available') }}
                                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Accessories Section -->
    <section class="mb-5">
        <div class="position-relative mb-4">
            <div class="text-center mb-3">
                <h4 class="fw-bold d-inline-block">
                    <i class="bi bi-headset me-2"></i>{{ __('dashboard.accessory_title') }}
                </h4>
            </div>
            <div class="position-absolute end-0 top-50 translate-middle-y">
                <a href="{{ route('pelanggan.accessories.index') }}" class="btn btn-sm rounded-pill px-3" style="color: #0652DD; border: 1px solid #0652DD; background-color: transparent;" onmouseover="this.style.backgroundColor='#0652DD'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#0652DD';">{{ __('dashboard.view_all') }}</a>
            </div>
        </div>

        <div class="mt-4 row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
            @forelse($accessories as $acc)
                @php
                    $jenisKey = $acc->jenis ?? 'Acc';
                    $iconMap = [
                        'Headset' => 'headset',
                        'Controller' => 'controller',
                        'Kabel' => 'plug',
                        'Charger' => 'battery-charging',
                        'Adapter' => 'usb-plug',
                        'Tas' => 'bag',
                    ];
                    $iconName = $iconMap[$jenisKey] ?? 'gear';
                @endphp
                <div class="col">
                    <div class="card h-100 shadow-sm position-relative card-blue-left" style="border-radius: 16px;">
                        <div class="position-absolute" style="top: 12px; left: 12px; z-index: 10;">
                            <span class="d-flex align-items-center justify-content-center" style="background: #0652DD; color: #fff; width: 44px; height: 44px; border-radius: 50%; font-size: 1.1rem; box-shadow: 0 3px 10px rgba(6,82,221,0.4);">
                                <i class="bi bi-{{ $iconName }}"></i>
                            </span>
                        </div>
                        <div class="position-relative" style="height: 200px; overflow: hidden;">
                            @if($acc->gambar)
                                <img src="{{ str_starts_with($acc->gambar, 'http') ? $acc->gambar : asset('storage/' . $acc->gambar) }}"
                                     alt="{{ $acc->nama }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <img src="https://placehold.co/400x300/F5F6FA/222222?text={{ urlencode($acc->nama) }}" alt="{{ $acc->nama }}" class="w-100 h-100 object-fit-cover">
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="text-center mb-3">
                                <h5 class="card-title fw-bold mb-1 text-brand-main">{{ $acc->nama }}</h5>
                                <p class="mb-1 text-muted" style="font-size: 1rem; font-weight: 500;">{{ $acc->jenis }}</p>
                                @php
                                    $stok = $acc->stok ?? 0;
                                @endphp
                                @if($stok > 0)
                                    <div class="mb-2 text-muted" style="font-size: 1rem; font-weight: 500;">
                                        {{ __('dashboard.available', ['count' => $stok]) }}
                                    </div>
                                @else
                                    <div class="mb-2 text-muted" style="font-size: 1rem; font-weight: 500;">
                                        {{ __('dashboard.out_of_stock') }}
                                    </div>
                                @endif
                                <div class="fw-bold text-brand-main">Rp {{ number_format($acc->harga_per_hari, 0, ',', '.') }}<span class="small fw-normal text-muted">{{ __('dashboard.per_day') }}</span></div>
                            </div>
                            <div class="mt-auto">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary add-to-cart-btn"
                                        data-type="accessory"
                                        data-id="{{ $acc->id }}"
                                        data-name="{{ $acc->nama }}"
                                        data-price="{{ $acc->harga_per_hari }}"
                                        data-price_type="per_hari">
                                        <i class="bi bi-cart"></i>
                                    </button>
                                    <a href="{{ route('pelanggan.rentals.create') }}?type=accessory&id={{ $acc->id }}" class="btn btn-sm btn-primary flex-grow-1">
                                        {{ __('dashboard.rent') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info border-0" style="background-color: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.2); color: #67e8f9;">
                                        <i class="bi bi-info-circle me-2"></i>{{ __('dashboard.no_accessories_available') }}
                                    </div>
                </div>
            @endforelse
        </div>
    </section>
</div>

<script>
    // Handle add to cart AJAX requests for dashboard items
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = parseFloat(this.getAttribute('data-price'));
            const price_type = this.getAttribute('data-price_type');

            // Validate data
            if(!type || !id || !name || isNaN(price)) {
                alert('{{ __('dashboard.js_incomplete_data') }}');
                return;
            }

            // Disable button to prevent multiple clicks
            this.disabled = true;
            const originalHTML = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

            fetch('/pelanggan/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    type: type,
                    id: id,
                    quantity: 1, // Default quantity
                    price_type: price_type
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Show success message using global function if available
                    if(window.showFlashMessage) {
                        window.showFlashMessage(data.message, 'success');
                    } else {
                        alert(data.message);
                    }
                } else {
                    // Show error message
                    if(window.showFlashMessage) {
                        window.showFlashMessage(data.message || '{{ __('dashboard.js_add_failed') }}', 'error');
                    } else {
                        alert(data.message || '{{ __('dashboard.js_add_failed') }}');
                    }
                }

                // Restore button
                this.disabled = false;
                this.innerHTML = originalHTML;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ __('dashboard.js_error') }}');
                // Restore button
                this.disabled = false;
                this.innerHTML = originalHTML;
            });
        });
    });
</script>
@endsection