<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - PlayStation Rental</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-color: #0f172a;
            --text-color: #f8fafc;
            --accent-color: #3b82f6;
            --error-color: #ef4444;
            --warning-color: #f59e0b;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Outfit', sans-serif;
            margin: 0;
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-container {
            text-align: center;
            position: relative;
            z-index: 10;
            padding: 2rem;
        }

        /* Glitch Animation for Error Code */
        .error-code {
            font-size: 8rem;
            font-weight: 800;
            line-height: 1;
            position: relative;
            display: inline-block;
            color: var(--text-color);
        }

        .error-code::before,
        .error-code::after {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .error-code::before {
            left: 2px;
            text-shadow: -1px 0 #ff00c1;
            clip: rect(44px, 450px, 56px, 0);
            animation: glitch-anim 5s infinite linear alternate-reverse;
        }

        .error-code::after {
            left: -2px;
            text-shadow: -1px 0 #00fff9;
            clip: rect(44px, 450px, 56px, 0);
            animation: glitch-anim2 5s infinite linear alternate-reverse;
        }

        /* Floating Icon Animation */
        .floating-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: inline-block;
            animation: float 6s ease-in-out infinite;
        }

        .error-message {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.8;
            font-weight: 300;
        }

        /* Suggested Links Card */
        .suggestion-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 2rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
            transform: translateY(20px);
            opacity: 0;
            animation: slideUp 0.8s ease-out forwards 0.5s;
        }

        .suggestion-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .suggestion-link {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .suggestion-link:hover {
            background: rgba(59, 130, 246, 0.1);
            border-color: var(--accent-color);
            transform: translateX(5px);
            color: #fff;
        }

        .suggestion-link i {
            margin-right: 12px;
            font-size: 1.2rem;
            color: var(--accent-color);
        }

        /* Background Effects */
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            z-index: 1;
        }
        .shape-1 { background: #3b82f6; width: 300px; height: 300px; top: -100px; left: -100px; }
        .shape-2 { background: #8b5cf6; width: 400px; height: 400px; bottom: -150px; right: -150px; }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @keyframes slideUp {
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes glitch-anim {
            0% { clip: rect(30px, 9999px, 10px, 0); }
            20% { clip: rect(85px, 9999px, 90px, 0); }
            40% { clip: rect(10px, 9999px, 50px, 0); }
            60% { clip: rect(60px, 9999px, 40px, 0); }
            80% { clip: rect(95px, 9999px, 85px, 0); }
            100% { clip: rect(20px, 9999px, 70px, 0); }
        }
        @keyframes glitch-anim2 {
            0% { clip: rect(15px, 9999px, 40px, 0); }
            20% { clip: rect(65px, 9999px, 30px, 0); }
            40% { clip: rect(90px, 9999px, 10px, 0); }
            60% { clip: rect(25px, 9999px, 95px, 0); }
            80% { clip: rect(55px, 9999px, 50px, 0); }
            100% { clip: rect(10px, 9999px, 80px, 0); }
        }
    </style>
</head>
<body>

    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="error-container">
        <div class="floating-icon text-@yield('color', 'primary')">
            @yield('icon')
        </div>
        
        <div class="error-code" data-text="@yield('code')">
            @yield('code')
        </div>

        <h2 class="h4 mt-4 fw-bold text-uppercase letter-spacing-2">@yield('message')</h2>
        <p class="error-message">@yield('description')</p>

        <div class="suggestion-card">
            <p class="small text-uppercase text-muted fw-bold mb-3">Maybe you want to go here?</p>
            <ul class="suggestion-list">
                @auth
                    @if(auth()->user()->role === 'pelanggan')
                        <li>
                            <a href="{{ route('dashboard.pelanggan') }}" class="suggestion-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>My Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pelanggan.unitps.index') }}" class="suggestion-link">
                                <i class="bi bi-controller"></i>
                                <span>Rent PlayStation</span>
                            </a>
                        </li>
                    @elseif(auth()->user()->role === 'admin')
                         <li>
                            <a href="{{ route('dashboard.admin') }}" class="suggestion-link">
                                <i class="bi bi-speedometer2"></i>
                                <span>Admin Panel</span>
                            </a>
                        </li>
                    @endif
                @else
                    <li>
                        <a href="{{ route('login.show') }}" class="suggestion-link">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Login to System</span>
                        </a>
                    </li>
                @endauth
                
                <li>
                    <a href="{{ url('/') }}" class="suggestion-link">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Homepage</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ url()->previous() }}" class="suggestion-link">
                        <i class="bi bi-arrow-left"></i>
                        <span>Go Back</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

</body>
</html>
