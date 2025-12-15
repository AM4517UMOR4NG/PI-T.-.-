@extends('layouts.public')

@section('title', __('landing.privacy_title') . ' - Rental PlayStation')

@section('content')
<div class="page-container">
    <div class="hero-bg">
        <div class="hero-blob blob-1"></div>
        <div class="hero-blob blob-2"></div>
    </div>

    <div class="page-header">
        <h1>{{ __('landing.privacy_title') }}</h1>
        <p>{{ __('landing.privacy_subtitle') }}</p>
    </div>
    <div class="page-content">
        <div class="content-card">
            <h2>{{ __('landing.privacy_1_title') }}</h2>
            <p>{{ __('landing.privacy_1_desc') }}</p>
            
            <h2>{{ __('landing.privacy_2_title') }}</h2>
            <p>{{ __('landing.privacy_2_desc') }}</p>
            <ul>
                <li>{{ __('landing.privacy_2_1') }}</li>
                <li>{{ __('landing.privacy_2_2') }}</li>
                <li>{{ __('landing.privacy_2_3') }}</li>
            </ul>
            
            <h2>{{ __('landing.privacy_3_title') }}</h2>
            <p>{{ __('landing.privacy_3_desc') }}</p>
            
            <h2>{{ __('landing.privacy_4_title') }}</h2>
            <p>{{ __('landing.privacy_4_desc') }}</p>
        </div>
    </div>
</div>
@endsection
