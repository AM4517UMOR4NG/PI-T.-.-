@extends('layouts.public')

@section('content')
    <!-- Hero Section -->
    <header class="hero">
        <div class="hero-bg">
            <div class="hero-blob blob-1"></div>
            <div class="hero-blob blob-2"></div>
        </div>
        <div class="hero-content">
            <h1>{{ __('landing.hero_title') }}</h1>
            <p>{{ __('landing.hero_subtitle') }}</p>
            <div class="hero-buttons">
                <a href="{{ route('register.show') }}" class="btn-hero btn-primary">
                    {{ __('landing.start_renting') }} <i class="fas fa-arrow-right"></i>
                </a>
                <a href="{{ route('showcase.gallery') }}?category=unitps" class="showcase-link">
                    {{ __('landing.learn_more') }}
                    
                </a>
            </div>
        </div>
    </header>

    <!-- Showcase Section -->
    <section id="showcase" class="showcase">
        <div class="showcase-header">
            <h2>{{ __('landing.showcase_title') }}</h2>
            <p>{{ __('landing.showcase_desc') }}</p>
        </div>
        <div class="showcase-grid">
            <!-- Consoles -->
            <div class="showcase-card">
                <div class="showcase-icon">
                    <i class="fa-brands fa-playstation"></i>
                </div>
                <h3>{{ __('landing.category_ps_title') }}</h3>
                <p>{{ __('landing.category_ps_desc') }}</p>
                <a href="{{ route('showcase.gallery') }}?category=unitps" class="showcase-link">
                    {{ __('landing.learn_more') }} <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Accessories -->
            <div class="showcase-card">
                <div class="showcase-icon">
                    <i class="fas fa-gamepad"></i>
                </div>
                <h3>{{ __('landing.category_acc_title') }}</h3>
                <p>{{ __('landing.category_acc_desc') }}</p>
                <a href="{{ route('showcase.gallery') }}?category=accessories" class="showcase-link">
                    {{ __('landing.learn_more') }} <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Games -->
            <div class="showcase-card">
                <div class="showcase-icon">
                    <i class="fas fa-compact-disc"></i>
                </div>
                <h3>{{ __('landing.category_game_title') }}</h3>
                <p>{{ __('landing.category_game_desc') }}</p>
                <a href="{{ route('showcase.gallery') }}?category=games" class="showcase-link">
                    {{ __('landing.learn_more') }} <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
@endsection