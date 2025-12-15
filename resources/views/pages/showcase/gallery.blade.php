@extends('layouts.public')

@section('title', __('landing.gallery_title_accent') . ' ' . __('landing.gallery_title_suffix') . ' - PlayStation Rental')

@section('content')
<div class="showcase-gallery">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <h1 class="hero-title">
                <span class="title-accent">{{ __('landing.gallery_title_accent') }}</span> {{ __('landing.gallery_title_suffix') }}
            </h1>
            <p class="hero-subtitle">{{ __('landing.gallery_subtitle') }}</p>
            
            <!-- Category Pills -->
            <div class="category-pills">
                <button class="pill active" data-category="all">
                    <i class="bi bi-grid-3x3-gap-fill"></i> {{ __('landing.gallery_filter_all') }}
                </button>
                <button class="pill" data-category="unitps">
                    <i class="bi bi-controller"></i> {{ __('landing.gallery_filter_ps') }}
                </button>
                <button class="pill" data-category="games">
                    <i class="bi bi-disc-fill"></i> {{ __('landing.gallery_filter_games') }}
                </button>
                <button class="pill" data-category="accessories">
                    <i class="bi bi-headset"></i> {{ __('landing.gallery_filter_accessories') }}
                </button>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="scroll-indicator">
            <div class="mouse">
                <div class="wheel"></div>
            </div>
            <span>{{ __('landing.gallery_scroll_hint') }}</span>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-section" id="gallery">
        <div class="container">
            <!-- Stats Bar -->
            <div class="stats-bar">
                <div class="stat-item">
                    <span class="stat-number" data-count="{{ $units->count() }}">0</span>
                    <span class="stat-label">{{ __('landing.gallery_stat_units') }}</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-number" data-count="{{ $games->count() }}">0</span>
                    <span class="stat-label">{{ __('landing.gallery_stat_games') }}</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-number" data-count="{{ $accessories->count() }}">0</span>
                    <span class="stat-label">{{ __('landing.gallery_stat_accessories') }}</span>
                </div>
            </div>

            <!-- Masonry Gallery Grid -->
            <div class="gallery-grid" id="galleryGrid">
                <!-- PlayStation Units -->
                @foreach($units as $index => $unit)
                <div class="gallery-item" data-category="unitps" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                    <div class="item-card">
                        <div class="item-image">
                            @if($unit->foto)
                                <img src="{{ str_starts_with($unit->foto, 'http') ? $unit->foto : asset('storage/' . $unit->foto) }}" 
                                     alt="{{ $unit->name }}" loading="lazy">
                            @else
                                <img src="https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=400&h=300&fit=crop" 
                                     alt="{{ $unit->name }}" loading="lazy">
                            @endif
                            <div class="item-overlay">
                                <span class="item-category">
                                    <i class="bi bi-controller"></i> {{ $unit->model }}
                                </span>
                                <button class="view-btn" onclick="openLightbox(this)">
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </button>
                            </div>
                        </div>
                        <div class="item-info">
                            <h3>{{ $unit->name }}</h3>
                            <p class="item-brand">{{ $unit->brand }}</p>
                            <div class="item-price">
                                <span class="price">Rp {{ number_format($unit->price_per_hour, 0, ',', '.') }}</span>
                                <span class="unit">{{ __('landing.gallery_per_hour') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Games -->
                @foreach($games as $index => $game)
                <div class="gallery-item" data-category="games" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                    <div class="item-card">
                        <div class="item-image">
                            @if($game->gambar)
                                <img src="{{ str_starts_with($game->gambar, 'http') ? $game->gambar : asset('storage/' . $game->gambar) }}" 
                                     alt="{{ $game->judul }}" loading="lazy">
                            @else
                                <img src="https://images.unsplash.com/photo-1592155931584-901ac15763e3?w=400&h=300&fit=crop" 
                                     alt="{{ $game->judul }}" loading="lazy">
                            @endif
                            <div class="item-overlay">
                                <span class="item-category">
                                    <i class="bi bi-disc-fill"></i> {{ $game->platform }}
                                </span>
                                <button class="view-btn" onclick="openLightbox(this)">
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </button>
                            </div>
                        </div>
                        <div class="item-info">
                            <h3>{{ $game->judul }}</h3>
                            <p class="item-brand">{{ $game->genre }}</p>
                            <div class="item-price">
                                <span class="price">Rp {{ number_format($game->harga_per_hari, 0, ',', '.') }}</span>
                                <span class="unit">{{ __('landing.gallery_per_day') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Accessories -->
                @foreach($accessories as $index => $acc)
                <div class="gallery-item" data-category="accessories" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                    <div class="item-card">
                        <div class="item-image">
                            @if($acc->gambar)
                                <img src="{{ str_starts_with($acc->gambar, 'http') ? $acc->gambar : asset('storage/' . $acc->gambar) }}" 
                                     alt="{{ $acc->nama }}" loading="lazy">
                            @else
                                <img src="https://images.unsplash.com/photo-1625805866449-3589fe3f71a3?w=400&h=300&fit=crop" 
                                     alt="{{ $acc->nama }}" loading="lazy">
                            @endif
                            <div class="item-overlay">
                                <span class="item-category">
                                    <i class="bi bi-headset"></i> {{ $acc->jenis }}
                                </span>
                                <button class="view-btn" onclick="openLightbox(this)">
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </button>
                            </div>
                        </div>
                        <div class="item-info">
                            <h3>{{ $acc->nama }}</h3>
                            <p class="item-brand">{{ $acc->jenis }}</p>
                            <div class="item-price">
                                <span class="price">Rp {{ number_format($acc->harga_per_hari, 0, ',', '.') }}</span>
                                <span class="unit">{{ __('landing.gallery_per_day') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Empty State -->
            <div class="empty-state" id="emptyState" style="display: none;">
                <i class="bi bi-inbox"></i>
                <p>{{ __('landing.gallery_empty') }}</p>
            </div>
        </div>
    </section>


    <!-- Lightbox -->
    <div class="lightbox" id="lightbox">
        <button class="lightbox-close" onclick="closeLightbox()">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="lightbox-content">
            <img src="" alt="" id="lightboxImage">
            <div class="lightbox-info" id="lightboxInfo"></div>
        </div>
        <button class="lightbox-nav prev" onclick="navigateLightbox(-1)">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button class="lightbox-nav next" onclick="navigateLightbox(1)">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</div>

<style>
/* ===== Variables ===== */
:root {
    --primary: #0652DD;
    --primary-dark: #0440a8;
    --accent: #00d4ff;
    --dark: #0a0a0a;
    --darker: #050505;
    --light: #ffffff;
    --gray: #6b7280;
    --success: #10b981;
    
    /* Glass Effect Variables */
    --glass-bg: rgba(255, 255, 255, 0.05);
    --glass-border: rgba(255, 255, 255, 0.1);
    --glass-btn: rgba(255, 255, 255, 0.2);
    --pill-border: rgba(255, 255, 255, 0.1);
}

[data-theme="light"] {
    --dark: #ffffff;
    --darker: #f3f4f6;
    --light: #111827;
    --gray: #4b5563;
    
    /* Light Mode Glass Effects */
    --glass-bg: rgba(0, 0, 0, 0.05);
    --glass-border: rgba(0, 0, 0, 0.1);
    --glass-btn: rgba(0, 0, 0, 0.1);
    --pill-border: rgba(0, 0, 0, 0.1);
}

/* ===== Base Styles ===== */
.showcase-gallery {
    background: var(--dark);
    min-height: 100vh;
    overflow-x: hidden;
    transition: background 0.3s ease;
}

/* ===== Hero Section ===== */
.hero-section {
    position: relative;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    overflow: hidden;
}

.hero-bg {
    position: absolute;
    inset: 0;
    background: 
        radial-gradient(ellipse at 20% 20%, rgba(6, 82, 221, 0.3) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 80%, rgba(0, 212, 255, 0.2) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 50%, rgba(6, 82, 221, 0.1) 0%, transparent 70%);
    animation: pulse-bg 8s ease-in-out infinite;
}

@keyframes pulse-bg {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.05); }
}

.hero-content {
    position: relative;
    z-index: 10;
    text-align: center;
    max-width: 800px;
}

.hero-title {
    font-size: clamp(2.5rem, 8vw, 5rem);
    font-weight: 800;
    color: var(--light);
    margin-bottom: 1rem;
    line-height: 1.1;
}

.title-accent {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: 1.25rem;
    color: var(--gray);
    margin-bottom: 2.5rem;
}

/* Category Pills */
.category-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    justify-content: center;
}

.pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: 2px solid var(--pill-border);
    border-radius: 50px;
    background: var(--glass-bg);
    color: var(--gray);
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.pill:hover {
    border-color: var(--primary);
    color: var(--light);
    transform: translateY(-2px);
}

.pill.active {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-color: var(--primary);
    color: #ffffff;
    box-shadow: 0 4px 20px rgba(6, 82, 221, 0.4);
}

/* Scroll Indicator */
.scroll-indicator {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray);
    font-size: 0.85rem;
    animation: bounce 2s infinite;
}

.mouse {
    width: 26px;
    height: 40px;
    border: 2px solid var(--gray);
    border-radius: 20px;
    position: relative;
}

.wheel {
    width: 4px;
    height: 8px;
    background: var(--accent);
    border-radius: 2px;
    position: absolute;
    top: 8px;
    left: 50%;
    transform: translateX(-50%);
    animation: scroll-wheel 1.5s infinite;
}

@keyframes scroll-wheel {
    0% { opacity: 1; transform: translateX(-50%) translateY(0); }
    100% { opacity: 0; transform: translateX(-50%) translateY(10px); }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateX(-50%) translateY(0); }
    40% { transform: translateX(-50%) translateY(-10px); }
    60% { transform: translateX(-50%) translateY(-5px); }
}

/* ===== Gallery Section ===== */
.gallery-section {
    padding: 4rem 0;
    background: linear-gradient(180deg, var(--dark) 0%, var(--darker) 100%);
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

/* Stats Bar */
.stats-bar {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 2rem;
    padding: 1.5rem 2rem;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    margin-bottom: 3rem;
    backdrop-filter: blur(10px);
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: var(--accent);
}

.stat-label {
    font-size: 0.85rem;
    color: var(--gray);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.stat-divider {
    width: 1px;
    height: 40px;
    background: var(--glass-border);
}

/* Gallery Grid */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    align-items: stretch;
}

.gallery-item {
    opacity: 1;
    transform: scale(1);
    transition: opacity 0.4s ease, transform 0.4s ease;
    display: block;
}

.gallery-item.hidden {
    display: none !important;
}

/* Item Card */
.item-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.item-card:hover {
    transform: translateY(-8px);
    border-color: var(--primary);
    box-shadow: 0 20px 40px rgba(6, 82, 221, 0.2);
}

.item-image {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
    flex-shrink: 0;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.item-card:hover .item-image img {
    transform: scale(1.1);
}

.item-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.8) 100%);
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    padding: 1rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.item-card:hover .item-overlay {
    opacity: 1;
}

.item-category {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.8rem;
    background: var(--primary);
    color: #ffffff;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.view-btn {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: var(--glass-btn);
    color: var(--light);
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.view-btn:hover {
    background: var(--accent);
    transform: scale(1.1);
    color: #ffffff;
}

.item-info {
    padding: 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.item-info h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--light);
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.item-brand {
    font-size: 0.85rem;
    color: var(--gray);
    margin-bottom: 0.75rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.item-price {
    display: flex;
    align-items: baseline;
    gap: 0.25rem;
    margin-top: auto;
}

.item-price .price {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--success);
}

.item-price .unit {
    font-size: 0.8rem;
    color: var(--gray);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--gray);
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}


/* ===== Lightbox ===== */
.lightbox {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.95);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.lightbox.active {
    opacity: 1;
    visibility: visible;
}

.lightbox-close {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    width: 50px;
    height: 50px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    color: var(--light);
    font-size: 1.25rem;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 10;
}

.lightbox-close:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(90deg);
}

.lightbox-content {
    max-width: 90vw;
    max-height: 85vh;
    text-align: center;
}

.lightbox-content img {
    max-width: 100%;
    max-height: 75vh;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
}

.lightbox-info {
    margin-top: 1rem;
    color: var(--light);
    font-size: 1.1rem;
}

.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    color: var(--light);
    font-size: 1.25rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.lightbox-nav:hover {
    background: var(--primary);
}

.lightbox-nav.prev { left: 1.5rem; }
.lightbox-nav.next { right: 1.5rem; }

/* ===== Responsive ===== */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .category-pills {
        gap: 0.5rem;
    }
    
    .pill {
        padding: 0.6rem 1rem;
        font-size: 0.85rem;
    }
    
    .stats-bar {
        flex-direction: column;
        gap: 1rem;
    }
    
    .stat-divider {
        width: 60px;
        height: 1px;
    }
    
    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .gallery-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category Filter
    const pills = document.querySelectorAll('.pill');
    const items = document.querySelectorAll('.gallery-item');
    const emptyState = document.getElementById('emptyState');
    const grid = document.getElementById('galleryGrid');
    
    function filterByCategory(category) {
        // Update active pill
        pills.forEach(p => p.classList.remove('active'));
        const activePill = document.querySelector(`.pill[data-category="${category}"]`);
        if (activePill) activePill.classList.add('active');
        
        // Filter items
        let visibleCount = 0;
        items.forEach(item => {
            if (category === 'all' || item.dataset.category === category) {
                item.classList.remove('hidden');
                visibleCount++;
            } else {
                item.classList.add('hidden');
            }
        });
        
        // Show/hide empty state
        emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        
        // Scroll to gallery section
        if (category !== 'all') {
            setTimeout(() => {
                document.getElementById('gallery').scrollIntoView({ behavior: 'smooth' });
            }, 100);
        }
    }
    
    pills.forEach(pill => {
        pill.addEventListener('click', function() {
            filterByCategory(this.dataset.category);
        });
    });
    
    // Check URL parameter for initial category
    const urlParams = new URLSearchParams(window.location.search);
    const initialCategory = urlParams.get('category');
    if (initialCategory && ['unitps', 'games', 'accessories'].includes(initialCategory)) {
        filterByCategory(initialCategory);
    }
    
    // Animate stats numbers
    const statNumbers = document.querySelectorAll('.stat-number');
    const observerOptions = { threshold: 0.5 };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                const count = parseInt(target.dataset.count);
                animateNumber(target, count);
                observer.unobserve(target);
            }
        });
    }, observerOptions);
    
    statNumbers.forEach(num => observer.observe(num));
    
    function animateNumber(element, target) {
        let current = 0;
        const increment = target / 30;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current);
            }
        }, 30);
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});

// Lightbox functionality
let currentLightboxIndex = 0;
let lightboxItems = [];

function openLightbox(button) {
    const card = button.closest('.item-card');
    const img = card.querySelector('.item-image img');
    const title = card.querySelector('.item-info h3').textContent;
    
    // Get all visible items for navigation
    lightboxItems = Array.from(document.querySelectorAll('.gallery-item:not(.hidden) .item-card'));
    currentLightboxIndex = lightboxItems.indexOf(card);
    
    showLightboxImage(img.src, title);
    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function showLightboxImage(src, title) {
    document.getElementById('lightboxImage').src = src;
    document.getElementById('lightboxInfo').textContent = title;
}

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = '';
}

function navigateLightbox(direction) {
    currentLightboxIndex += direction;
    
    if (currentLightboxIndex < 0) currentLightboxIndex = lightboxItems.length - 1;
    if (currentLightboxIndex >= lightboxItems.length) currentLightboxIndex = 0;
    
    const card = lightboxItems[currentLightboxIndex];
    const img = card.querySelector('.item-image img');
    const title = card.querySelector('.item-info h3').textContent;
    
    showLightboxImage(img.src, title);
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const lightbox = document.getElementById('lightbox');
    if (!lightbox.classList.contains('active')) return;
    
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') navigateLightbox(-1);
    if (e.key === 'ArrowRight') navigateLightbox(1);
});

// Close lightbox on background click
document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) closeLightbox();
});
</script>
@endsection
