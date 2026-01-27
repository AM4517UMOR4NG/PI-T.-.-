@extends('pelanggan.layout')

@section('pelanggan_content')
<div class="container-fluid p-0 d-flex flex-column align-items-center justify-content-center overflow-hidden position-relative" style="min-height: 85vh; background: radial-gradient(circle at center, #1a1c2c 0%, #000 100%);">
    
    <!-- Background Elements -->
    <div class="gacha-bg-grid"></div>
    <div class="gacha-rays"></div>

    <!-- Title -->
    <div class="text-center mb-5 position-relative z-2">
        <h1 class="fw-black text-uppercase text-warning text-shadow display-4 mb-0">Daily Gacha</h1>
        <p class="font-monospace letter-spacing-2" style="color: rgba(255,255,255,0.75) !important;">TEST YOUR LUCK // WIN DISCOUNTS</p>
    </div>

    <!-- The Loot Box -->
    <div class="scene">
        <div class="cube {{ $hasSpunToday ? 'disabled' : '' }}" id="lootBox">
            <div class="cube_face cube_face--front">?</div>
            <div class="cube_face cube_face--back"></div>
            <div class="cube_face cube_face--right"></div>
            <div class="cube_face cube_face--left"></div>
            <div class="cube_face cube_face--top"></div>
            <div class="cube_face cube_face--bottom"></div>
        </div>
        <div class="shadow"></div>
    </div>

    <!-- Action Button -->
    <div class="mt-5 text-center position-relative z-2">
        @if($hasSpunToday)
            <button class="btn btn-secondary btn-lg font-monospace px-5 py-3 rounded-pill" disabled style="opacity: 0.5; filter: grayscale(1);">
                <i class="bi bi-clock-history me-2"></i> COME BACK TOMORROW
            </button>
            <div class="mt-2 text-danger small font-monospace blink-text">DAILY LIMIT REACHED</div>
        @else
            <button id="spinBtn" class="btn btn-warning btn-lg font-monospace px-5 py-3 rounded-pill fw-bold shadow-lg spin-btn">
                <i class="bi bi-dice-5-fill me-2"></i> OPEN BOX
            </button>
            <div class="mt-2 small font-monospace" style="color: rgba(255,255,255,0.5) !important;">1x FREE SPIN / DAY</div>
        @endif
    </div>

</div>

<!-- Styles -->
<style>
    /* VARIABLES */
    :root {
        --box-size: 150px;
        --color-primary: #FFD700;
        --color-secondary: #FF4500;
    }

    .fw-black { font-weight: 900; }
    .letter-spacing-2 { letter-spacing: 2px; }
    .text-shadow { text-shadow: 0 0 10px rgba(255, 215, 0, 0.5); }
    .blink-text { animation: blink 2s infinite; }
    @keyframes blink { 0%, 100% { opacity: 0.5; } 50% { opacity: 1; } }

    /* BACKGROUND */
    .gacha-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        background-size: 50px 50px;
        z-index: 1;
        transform: perspective(100vh) rotateX(60deg) scale(2);
        animation: gridMove 20s linear infinite;
        opacity: 0.3;
    }

    @keyframes gridMove { 0% { background-position: 0 0; } 100% { background-position: 0 1000px; } }

    /* 3D CUBE */
    .scene {
        width: var(--box-size);
        height: var(--box-size);
        perspective: 600px;
        position: relative;
        z-index: 2;
    }

    .cube {
        width: 100%;
        height: 100%;
        position: relative;
        transform-style: preserve-3d;
        transform: translateZ(-75px) rotateX(-15deg) rotateY(-25deg);
        transition: transform 1s;
        cursor: pointer;
    }
    
    .cube.disabled {
        filter: grayscale(1);
        cursor: not-allowed;
    }

    .cube:not(.disabled):hover {
        animation: shake 0.5s infinite;
    }

    .cube.spinning {
        animation: spinBox 2s cubic-bezier(0.1, 0.7, 1.0, 0.1) infinite;
    }

    .cube.open {
        animation: openBox 1s forwards;
    }

    .cube_face {
        position: absolute;
        width: var(--box-size);
        height: var(--box-size);
        border: 4px solid var(--color-primary);
        line-height: var(--box-size);
        font-size: 60px;
        font-weight: bold;
        color: var(--color-primary);
        text-align: center;
        background: rgba(255, 215, 0, 0.1);
        box-shadow: 0 0 20px rgba(255, 215, 0, 0.2) inset;
    }

    .cube_face--front  { transform: rotateY(  0deg) translateZ(75px); background: radial-gradient(circle, rgba(255,215,0,0.4) 0%, rgba(0,0,0,0.8) 100%); }
    .cube_face--right  { transform: rotateY( 90deg) translateZ(75px); background: rgba(0,0,0,0.8); }
    .cube_face--back   { transform: rotateY(180deg) translateZ(75px); background: rgba(0,0,0,0.8); }
    .cube_face--left   { transform: rotateY(-90deg) translateZ(75px); background: rgba(0,0,0,0.8); }
    .cube_face--top    { transform: rotateX( 90deg) translateZ(75px); background: var(--color-primary); box-shadow: 0 0 50px var(--color-primary); }
    .cube_face--bottom { transform: rotateX(-90deg) translateZ(75px); background: rgba(0,0,0,0.8); }

    .shadow {
        position: absolute;
        width: 100%;
        height: 20px;
        background: radial-gradient(rgba(0,0,0,0.8), transparent);
        bottom: -50px;
        left: 0;
        transform: rotateX(90deg) scale(1.2);
        filter: blur(10px);
        z-index: 1;
    }

    @keyframes shake {
        0% { transform: translateZ(-75px) rotateX(-15deg) rotateY(-25deg) translate(2px, 2px); }
        25% { transform: translateZ(-75px) rotateX(-15deg) rotateY(-25deg) translate(-2px, -2px); }
        50% { transform: translateZ(-75px) rotateX(-15deg) rotateY(-25deg) translate(2px, -2px); }
        75% { transform: translateZ(-75px) rotateX(-15deg) rotateY(-25deg) translate(-2px, 2px); }
        100% { transform: translateZ(-75px) rotateX(-15deg) rotateY(-25deg) translate(2px, 2px); }
    }

    @keyframes spinBox {
        0% { transform: translateZ(-75px) rotateY(0deg) rotateX(0deg); }
        100% { transform: translateZ(-75px) rotateY(360deg) rotateX(360deg); }
    }
    
    .spin-btn {
        transition: all 0.3s;
    }
    .spin-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 0 30px var(--color-primary);
    }

</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const spinBtn = document.getElementById('spinBtn');
        const lootBox = document.getElementById('lootBox');

        if (!spinBtn) return; // Already spun

        spinBtn.addEventListener('click', async function() {
            // UI State
            spinBtn.disabled = true;
            spinBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> OPENING...';
            lootBox.classList.add('spinning');

            try {
                // API Call
                const response = await fetch("{{ route('gacha.spin') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                });
                
                const result = await response.json();

                // Animation Delay for suspense
                setTimeout(() => {
                    lootBox.classList.remove('spinning');
                    
                    if (result.status === 'success') {
                        handleResult(result);
                    } else {
                        // Handle Error (Limit reached etc)
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: result.message,
                            background: '#1a1c2c',
                            color: '#fff'
                        }).then(() => location.reload());
                    }
                }, 2000);

            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Something went wrong', 'error');
                spinBtn.disabled = false;
                lootBox.classList.remove('spinning');
            }
        });

        function handleResult(data) {
            if(data.is_win) {
                // Confetti Explosion
                confetti({
                    particleCount: 150,
                    spread: 70,
                    origin: { y: 0.6 }
                });

                let title = 'CONGRATULATIONS!';
                let icon = 'success';
                let bg = '#1a1c2c';
                
                if(data.reward_type === 'legendary') title = 'LEGENDARY DROP!';
                if(data.reward_type === 'epic') title = 'EPIC FIND!';
                
                Swal.fire({
                    title: title,
                    html: `
                        <h4 class="text-warning mb-3">${data.description}</h4>
                        <div class="p-3 bg-white bg-opacity-10 rounded border border-white border-opacity-25 mb-3">
                            <small class="text-white opacity-75">VOUCHER CODE:</small>
                            <h2 class="font-monospace text-white fw-bold user-select-all cursor-pointer" onclick="copyCode('${data.coupon_code}')">${data.coupon_code}</h2>
                        </div>
                        <small class="text-muted">Use this code at checkout!</small>
                    `,
                    icon: icon,
                    background: bg,
                    color: '#fff',
                    confirmButtonText: 'AWESOME!',
                    confirmButtonColor: '#FFD700',
                    allowOutsideClick: false
                }).then(() => {
                    location.reload();
                });

            } else {
                Swal.fire({
                    title: 'ZONK!',
                    text: data.description,
                    icon: 'info',
                    background: '#1a1c2c',
                    color: '#fff',
                    confirmButtonText: 'Try Again Tomorrow'
                }).then(() => {
                    location.reload();
                });
            }
        }
    });

    function copyCode(code) {
        navigator.clipboard.writeText(code);
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true
        });
        Toast.fire({
            icon: 'success',
            title: 'Copied to clipboard'
        });
    }
</script>
@endsection
