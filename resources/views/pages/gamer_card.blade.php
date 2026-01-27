@extends('pelanggan.layout')

@section('pelanggan_content')
<div class="container-fluid p-0 d-flex align-items-center justify-content-center overflow-hidden" style="min-height: 85vh;">
    
    <!-- 3D Card Container -->
    <div class="gamer-card-container">
        <div class="gamer-card" id="tiltCard">
            <!-- Inner container for Flip Effect -->
            <div class="gamer-card-inner">
                
                <!-- FRONT SIDE -->
                <div class="gamer-card-front">
                    <!-- Holographic & Texture Layers -->
                    <div class="texture-overlay"></div>
                    <div class="holographic-overlay"></div>
                    <div class="scanlines"></div>
                    <div class="card-shine"></div>
                    
                    <!-- Content -->
                    <div class="card-content position-relative z-2 h-100 d-flex flex-column p-4" style="color: white !important;">
                        <!-- Top Decor -->
                        <div class="d-flex justify-content-between align-items-center mb-3 opacity-50 font-monospace small">
                            <span>PS-RENTAL // SYSTEM</span>
                            <span>ID: {{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <!-- Avatar & Identity -->
                        <div class="text-center mb-4 position-relative">
                            <div class="avatar-container d-inline-block position-relative">
                                <div class="avatar-ring-outer"></div>
                                <div class="avatar-ring-inner"></div>
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle object-fit-cover border border-2 border-white position-relative z-2" width="100" height="100">
                                @else
                                    <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold fs-1 border border-2 border-white position-relative z-2" style="width: 100px; height: 100px;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <!-- Level Badge Float -->
                                <div class="position-absolute bottom-0 start-50 translate-middle-x z-3">
                                    <span class="badge rounded-pill bg-warning text-dark border border-2 border-dark fw-black px-3 shadow-lg">
                                        LVL {{ number_format($totalXP / 1000, 0) }}
                                    </span>
                                </div>
                            </div>
                            
                            <h3 class="mt-4 mb-0 fw-black text-uppercase text-shadow user-select-none">{{ $user->name }}</h3>
                            <div class="text-uppercase small letter-spacing-2 opacity-75 mb-2">{{ $currentLevel }} MEMBER</div>
                            
                            <!-- Class Badge -->
                            <div class="d-inline-flex align-items-center px-3 py-1 rounded-1 bg-white bg-opacity-10 border border-white border-opacity-25 backdrop-blur">
                                <i class="bi bi-stars me-2 text-warning"></i> 
                                <span class="fw-bold font-monospace text-uppercase">{{ $rpgClass }}</span>
                            </div>
                        </div>

                        <!-- Progress Section -->
                        <div class="mt-auto mb-4">
                            <div class="d-flex justify-content-between small fw-bold mb-1 font-monospace">
                                <span>EXP</span>
                                <span>{{ number_format($totalXP) }} / {{ number_format($nextLevelXP) }}</span>
                            </div>
                            <div class="progress bg-black bg-opacity-50 rounded-0" style="height: 6px; border: 1px solid rgba(255,255,255,0.2);">
                                @php
                                    $percent = ($currentLevel === 'Sultan') ? 100 : min(100, ($totalXP / $nextLevelXP) * 100);
                                @endphp
                                <div class="progress-bar bg-warning shadow-warning" role="progressbar" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>

                        <!-- Footer Hint -->
                        <div class="text-center opacity-50 small font-monospace blink-text">
                            [ CLICK TO FLIP ]
                        </div>
                    </div>
                </div>

                <!-- BACK SIDE -->
                <div class="gamer-card-back">
                    <div class="texture-overlay"></div>
                    <div class="scanlines"></div>
                    
                    <div class="card-content position-relative z-2 h-100 d-flex flex-column p-4" style="color: white !important;">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-white border-opacity-25 pb-2">
                            <h5 class="mb-0 fw-bold font-monospace">PLAYER STATS</h5>
                            <i class="bi bi-cpu text-info"></i>
                        </div>

                        <!-- Radar Chart Container -->
                        <div class="flex-grow-1 position-relative d-flex align-items-center justify-content-center">
                            <canvas id="statsChart"></canvas>
                        </div>

                        <!-- Stats Breakdown -->
                        <div class="row g-2 font-monospace small mt-3 opacity-75">
                            <div class="col-6">
                                <div class="d-flex justify-content-between border-bottom border-white border-opacity-10 pb-1">
                                    <span>STR</span> <span class="text-info">{{ $str }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between border-bottom border-white border-opacity-10 pb-1">
                                    <span>INT</span> <span class="text-success">{{ $int }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between border-bottom border-white border-opacity-10 pb-1">
                                    <span>DEX</span> <span class="text-warning">{{ $dex }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between border-bottom border-white border-opacity-10 pb-1">
                                    <span>LUCK</span> <span class="text-danger">{{ $luck }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top border-white border-opacity-10 text-center">
                            <p class="mb-0 small fst-italic text-white text-opacity-50">"{{ $description }}"</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* VARIABLES */
    :root {
        --card-width: 380px;
        --card-height: 560px;
        --color-primary: #FFD700;
        --color-bg-dark: #1a1c2c;
    }

    /* TYPOGRAPHY */
    .fw-black { font-weight: 900; }
    .letter-spacing-2 { letter-spacing: 2px; }
    .text-shadow { text-shadow: 0 4px 10px rgba(0,0,0,0.5); }
    .shadow-warning { box-shadow: 0 0 10px rgba(255, 215, 0, 0.7); }

    /* CARD CONTAINER & PERSPECTIVE */
    .gamer-card-container {
        perspective: 1500px;
        width: var(--card-width);
        height: var(--card-height);
        position: relative;
        cursor: pointer;
    }

    .gamer-card {
        width: 100%;
        height: 100%;
        position: relative;
        transform-style: preserve-3d;
        transition: transform 0.1s ease-out; /* For tilt */
    }

    /* INNER FLIP WRAPPER */
    .gamer-card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transform-style: preserve-3d;
    }

    /* FLIP STATE */
    .gamer-card-container.flipped .gamer-card-inner {
        transform: rotateY(180deg);
    }

    /* FRONT & BACK COMMON */
    .gamer-card-front, .gamer-card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
        border: 1px solid rgba(255,255,255,0.1);
    }

    /* FRONT STYLING */
    .gamer-card-front {
        background: linear-gradient(135deg, #1e2024 0%, #121212 100%);
    }

    @if($currentLevel == 'Sultan')
        .gamer-card-front { background: linear-gradient(135deg, #FFD700 0%, #8B4513 100%); color: #000 !important; }
    @elseif($currentLevel == 'Diamond')
        .gamer-card-front { background: linear-gradient(135deg, #70e1f5 0%, #ffd194 100%); }
    @elseif($currentLevel == 'Platinum')
        .gamer-card-front { background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%); color: #333 !important; }
    @endif

    /* BACK STYLING */
    .gamer-card-back {
        background: #0f0c29;  /* fallback for old browsers */
        background: -webkit-linear-gradient(to right, #24243e, #302b63, #0f0c29);  /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to right, #24243e, #302b63, #0f0c29); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        transform: rotateY(180deg);
        display: flex;
        flex-direction: column;
    }

    /* TEXTURE & OVERLAYS */
    .texture-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");
        z-index: 0;
        opacity: 0.5;
    }

    .holographic-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(125deg, rgba(255,255,255,0.0) 35%, rgba(255,255,255,0.2) 42%, rgba(255,255,255,0.0) 55%);
        z-index: 1;
        mix-blend-mode: overlay;
        opacity: 0.6;
    }

    .scanlines {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0) 50%, rgba(0,0,0,0.1) 50%);
        background-size: 100% 4px;
        z-index: 1;
        pointer-events: none;
    }

    .card-shine {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(255,255,255,0.15) 0%, transparent 40%);
        z-index: 2;
        pointer-events: none;
    }

    /* AVATAR RINGS */
    .avatar-ring-outer {
        position: absolute;
        inset: -8px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.2);
        border-top-color: var(--color-primary);
        animation: spin 8s linear infinite;
    }
    
    .avatar-ring-inner {
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.2);
        border-bottom-color: var(--color-primary);
        animation: spin-reverse 6s linear infinite;
    }

    .blink-text {
        animation: blink 2s infinite;
    }

    @keyframes spin { 100% { transform: rotate(360deg); } }
    @keyframes spin-reverse { 100% { transform: rotate(-360deg); } }
    @keyframes blink { 0%, 100% { opacity: 0.5; } 50% { opacity: 1; } }

</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // TILT EFFECT
        const cardContainer = document.querySelector('.gamer-card-container');
        const cardInner = document.querySelector('.gamer-card-inner');
        const card = document.getElementById('tiltCard');
        
        // Flip on Click
        cardContainer.addEventListener('click', () => {
            cardContainer.classList.toggle('flipped');
        });

        // Tilt Logic
        cardContainer.addEventListener('mousemove', (e) => {
            if(cardContainer.classList.contains('flipped')) return; // Disable tilt if flipped for ease of reading

            const rect = cardContainer.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = ((y - centerY) / centerY) * -10; 
            const rotateY = ((x - centerX) / centerX) * 10;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
            
            card.style.setProperty('--mouse-x', `${x}px`);
            card.style.setProperty('--mouse-y', `${y}px`);
        });

        cardContainer.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
        });

        // RADAR CHART
        const ctx = document.getElementById('statsChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(255, 215, 0, 0.5)'); // Gold
        gradient.addColorStop(1, 'rgba(255, 215, 0, 0.1)');

        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['STR', 'INT', 'DEX', 'LUCK'],
                datasets: [{
                    label: 'Player Stats',
                    data: [{{ $str }}, {{ $int }}, {{ $dex }}, {{ $luck }}],
                    backgroundColor: gradient,
                    borderColor: '#FFD700',
                    borderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#FFD700',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#FFD700'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: { color: 'rgba(255, 255, 255, 0.1)' },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        pointLabels: {
                            color: '#fff',
                            font: { size: 12, family: 'monospace' }
                        },
                        ticks: { display: false, backdropColor: 'transparent' },
                        suggestedMin: 0,
                        suggestedMax: 100
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endsection
