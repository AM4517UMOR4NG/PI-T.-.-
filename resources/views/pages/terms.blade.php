@extends('layouts.public')

@section('title', __('landing.terms_title') . ' - Rental PlayStation')

@section('content')
<div class="page-container">
    <div class="hero-bg">
        <div class="hero-blob blob-1"></div>
        <div class="hero-blob blob-2"></div>
    </div>

    <div class="page-header">
        <h1>{{ __('landing.terms_title') }}</h1>
        <p>{{ __('landing.terms_subtitle') }}</p>
    </div>
    <div class="page-content">
        <div class="content-card">
            <h2>{{ __('landing.terms_1_title') }}</h2>
            <p>{{ __('landing.terms_1_desc') }}</p>
            
            <h2>{{ __('landing.terms_2_title') }}</h2>
            <ul>
                <li>{{ __('landing.terms_2_1') }}</li>
                <li>{{ __('landing.terms_2_2') }}</li>
                <li>{{ __('landing.terms_2_3') }}</li>
            </ul>
            
            <h2>{{ __('landing.terms_3_title') }}</h2>
            <ul>
                <li>{{ __('landing.terms_3_1') }}</li>
                <li>{{ __('landing.terms_3_2') }}</li>
                <li>{{ __('landing.terms_3_3') }}</li>
            </ul>

            <h2>{{ __('landing.terms_4_title') }}</h2>
            <p>{{ __('landing.terms_4_desc') }}</p>
        </div>
    </div>
</div>
@endsection
