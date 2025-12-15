<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Rental PlayStation - Experience the Next Level')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/landing-new.css') }}">
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="{{ url('/') }}" class="nav-brand">
            <i class="fa-brands fa-playstation"></i>
            <span>RENTAL PS</span>
        </a>
        
        <div class="nav-actions">
            <!-- Theme Toggle -->
            <button class="toggle-btn" id="theme-toggle" title="Toggle Theme">
                <i class="fas fa-moon"></i>
            </button>

            <!-- Language Switcher -->
            <div class="lang-switch">
                <a href="{{ route('lang.switch', 'id') }}" class="toggle-btn {{ app()->getLocale() == 'id' ? 'active' : '' }}">ID</a>
                <span style="color: var(--glass-border)">|</span>
                <a href="{{ route('lang.switch', 'en') }}" class="toggle-btn {{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
            </div>

            <div class="nav-links">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="nav-btn btn-login">Dashboard</a>
                    @else
                        <a href="{{ route('login.show') }}" class="nav-btn btn-login">Login</a>
                        @if (Route::has('register.show'))
                            <a href="{{ route('register.show') }}" class="nav-btn btn-register">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    @yield('content')

    @if(!isset($hideFooter) || !$hideFooter)
    <!-- Footer -->
    <footer>
        <div class="footer-grid">
            <div class="footer-brand">
                <h3><i class="fa-brands fa-playstation"></i> RENTAL PS</h3>
                <p>{{ __('landing.footer_desc') }}</p>
            </div>
            <div class="footer-col">
                <h4>{{ __('landing.footer_links') }}</h4>
                <a href="{{ route('about') }}">{{ __('landing.footer_about') }}</a>
                <a href="{{ route('terms') }}">Terms & Conditions</a>
                <a href="{{ route('privacy') }}">Privacy Policy</a>
            </div>
            <div class="footer-col">
                <h4>{{ __('landing.footer_contact') }}</h4>
                <a href="#" id="contact-email"><i class="fas fa-envelope"></i> <span>Loading...</span></a>
                <a href="#" id="contact-phone"><i class="fas fa-phone"></i> <span>Loading...</span></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Rental PlayStation. All rights reserved.</p>
        </div>
    </footer>
    @endif

    <script>
        // Secure Contact Info
        document.addEventListener('DOMContentLoaded', function() {
            // Base64 encoded values
            const e = "YWVrbW9ob3BAZ21haWwuY29t"; // aekmohop@gmail.com
            const p = "MDgyMjM5MDc2NjM2"; // 082239076636
            
            const emailLink = document.getElementById('contact-email');
            const phoneLink = document.getElementById('contact-phone');
            
            if (emailLink) {
                const decodedEmail = atob(e);
                emailLink.href = "mailto:" + decodedEmail;
                emailLink.querySelector('span').textContent = decodedEmail;
            }
            
            if (phoneLink) {
                const decodedPhone = atob(p);
                phoneLink.href = "https://wa.me/62" + decodedPhone.substring(1); // Format for WhatsApp if needed, or just tel:
                // Let's stick to simple display first, or maybe tel: link
                phoneLink.href = "tel:+62" + decodedPhone.substring(1);
                phoneLink.querySelector('span').textContent = "+62 " + decodedPhone.substring(1);
            }
        });

        // Theme Toggle Logic
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;
        const icon = themeToggle.querySelector('i');

        // Check local storage
        const savedTheme = localStorage.getItem('theme') || 'dark';
        html.setAttribute('data-theme', savedTheme);
        updateIcon(savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcon(newTheme);
        });

        function updateIcon(theme) {
            if (theme === 'dark') {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            } else {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
        }
    </script>
</body>
</html>
