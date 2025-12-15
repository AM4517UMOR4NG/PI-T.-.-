@extends('layouts.public')

@section('title', __('landing.about_title') . ' - Rental PlayStation')

@section('content')
<div class="page-container">
    <div class="hero-bg">
        <div class="hero-blob blob-1"></div>
        <div class="hero-blob blob-2"></div>
    </div>
    
    <div class="page-header">
        <h1>{{ __('landing.about_title') }}</h1>
        <p>{{ __('landing.about_subtitle') }}</p>
    </div>
    <div class="page-content">
        <div class="content-card">
            <h2>{{ __('landing.about_who_title') }}</h2>
            <p>{{ __('landing.about_who_desc') }}</p>
            
            <h2>{{ __('landing.about_mission_title') }}</h2>
            <p>{{ __('landing.about_mission_desc') }}</p>
            
            <h2>{{ __('landing.about_why_title') }}</h2>
            <ul>
                <li>{{ __('landing.about_why_1') }}</li>
                <li>{{ __('landing.about_why_2') }}</li>
                <li>{{ __('landing.about_why_3') }}</li>
            </ul>
        </div>
    </div>
</div>
@endsection
