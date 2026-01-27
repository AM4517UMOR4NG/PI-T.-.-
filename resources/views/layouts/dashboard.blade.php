<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — PlayStation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Light Theme Palette */
            --bg-light: #F5F6FA;      /* Abu Sangat Terang - Global Container */
            --sidebar-bg: #0652DD;    /* Warna biru - Blue for sidebar */
            --sidebar-hover-bg: #0652DD; /* Warna biru - Same blue for sidebar hover */
            --card-bg: #FFFFFF;       /* Putih Murni - White for card background */
            --card-border: #E5E7EB;   /* Abu Border Tipis - Light gray for borders */

            --primary: #0652DD;       /* Warna biru - Blue as main */
            --primary-hover: #0652DD; /* Warna biru - Same blue for hover */
            --secondary: #06b6d4;     /* Cyan 500 */

            --text-main: #000000;     /* HITAM MURNI - Main text color */
            --text-muted: #000000;    /* HITAM MURNI - Muted text */
            --text-dim: #000000;      /* HITAM MURNI - For less important details */

            --success: #22c55e;       /* Green 500 */
            --warning: #eab308;       /* Yellow 500 */
            --danger: #ef4444;        /* Red 500 */

            --sidebar-width: 230px; /* Kurangi lebar sidebar */
            --sidebar-collapsed-width: 70px;
            --header-height: 70px;
        }

        [data-theme="dark"] {
            --bg-light: #0f172a;
            --sidebar-bg: #1e293b;
            --sidebar-hover-bg: #334155;
            --card-bg: #1e293b;
            --card-border: #475569;
            
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --text-dim: #64748b;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
            margin: 0;
            line-height: 1.6;
        }

        /* Custom Brand Text Utility */
        .text-brand-main {
            color: var(--primary) !important;
        }

        /* Button Overrides for Brand Color */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        
        /* Subtle Grid Background */
        .bg-grid {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 30px 30px;
            z-index: -2;
            pointer-events: none;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1040;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: none;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        [data-theme="dark"] .sidebar {
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            height: 56px;
            display: flex;
            align-items: center;
            padding: 0 1rem;
            white-space: nowrap;
            background: inherit;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 0.5rem;
        }

        .logo-icon {
            min-width: 28px;
            height: 28px;
            background: var(--primary);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            margin-right: 8px;
            box-shadow: none;
        }

        .logo-text {
            font-weight: 800;
            font-size: 1.1rem; /* Kurangi ukuran font lebih jauh */
            color: white;
            letter-spacing: 0.4px;
            opacity: 1;
            transition: opacity 0.2s;
        }

        .sidebar.collapsed .logo-text {
            opacity: 0;
            pointer-events: none;
            display: none;
        }

        .sidebar-menu {
            flex: 1;
            padding: 0 0.75rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.6rem 0.85rem;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            white-space: nowrap;
            font-weight: 500;
            font-size: 0.875rem;
            margin: 0;
        }

        .sidebar-menu .nav-link {
            margin-bottom: 0 !important;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(3px);
        }

        .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }

        .nav-link i {
            font-size: 1.15rem;
            min-width: 22px;
            display: flex;
            justify-content: center;
            margin-right: 10px;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem 0;
        }

        .sidebar.collapsed .nav-link:hover {
            transform: none;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        /* Center logo when sidebar is collapsed */
        .sidebar.collapsed .sidebar-header {
            justify-content: center;
            padding: 0;
            border-bottom: none;
        }

        .sidebar.collapsed .logo-icon {
            margin-right: 0;
        }

        /* Sidebar Heading */
        .sidebar-heading {
            color: #E5E7EB;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.65rem;
            letter-spacing: 0.5px;
            padding: 0.75rem 0.85rem 0.4rem;
            margin: 0;
            white-space: nowrap;
        }

        .sidebar.collapsed .sidebar-heading {
            opacity: 0;
            display: none;
        }
        
        /* Logout section */
        .sidebar-menu .mt-auto {
            margin-top: auto !important;
            padding-top: 0.75rem !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        /* Utility Overrides for Dark Theme */
        .text-muted {
            color: var(--text-muted) !important;
        }
        
        .text-white {
            color: var(--text-main) !important;
        }
        
        .text-secondary {
            color: var(--secondary) !important;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding-top: calc(var(--header-height) + 2rem); /* Nilai default sebelumnya */
        }

        body.sidebar-collapsed .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Top Navbar */
        .top-navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            height: var(--header-height);
            background: var(--card-bg); /* Use variable for background */
            z-index: 1030;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: none;
            border-bottom: 1px solid var(--card-border);
        }

        .sidebar.collapsed + .main-content .top-navbar,
        body.sidebar-collapsed .top-navbar {
            left: var(--sidebar-collapsed-width);
        }

        .toggle-btn {
            background: transparent;
            border: none;
            color: var(--text-main);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .toggle-btn:hover {
            background: #0652DD;
            color: white;
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            border: none;
            border-radius: 16px;
            box-shadow: 0 0 0 1px var(--card-border), 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            margin-bottom: 1.5rem;
        }
        
        /* Dark mode card dengan border yang terlihat */
        [data-theme="dark"] .card {
            box-shadow: 0 0 0 1px #475569, 0 4px 6px -1px rgba(0, 0, 0, 0.15), 0 2px 4px -1px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: #FFFFFF; /* PUTIH MURNI */
            border-bottom: 1px solid var(--card-border);
            padding: 1.25rem 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Tables - Light Theme */
        .table {
            --bs-table-bg: transparent;
            --bs-table-color: #000000; /* HITAM MURNI */
            --bs-table-border-color: var(--card-border);
            margin-bottom: 0;
        }

        .table th {
            color: #000000; /* HITAM MURNI */
            font-weight: 600;
            background: #FFFFFF; /* PUTIH MURNI */
            border-bottom: 2px solid var(--card-border);
            padding: 1rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table td {
            vertical-align: middle;
            padding: 1rem;
            border-bottom: 1px solid var(--card-border);
            color: #000000; /* HITAM MURNI */
        }

        .table tbody tr:hover {
            background-color: rgba(30, 64, 255, 0.05);
        }

        /* Badges - Light Theme Contrast */
        .badge {
            font-weight: 600;
            padding: 0.5em 0.8em;
            letter-spacing: 0.025em;
            border-radius: 6px;
        }

        /* Colors that provide good contrast on light backgrounds */
        .bg-success-subtle {
            background-color: rgba(34, 197, 94, 0.15) !important;
            color: #000000 !important; /* HITAM MURNI untuk kontras maksimal */
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        .bg-warning-subtle {
            background-color: rgba(234, 179, 8, 0.15) !important;
            color: #000000 !important; /* HITAM MURNI untuk kontras maksimal */
            border: 1px solid rgba(234, 179, 8, 0.3);
        }
        .bg-danger-subtle {
            background-color: rgba(239, 68, 68, 0.15) !important;
            color: #000000 !important; /* HITAM MURNI untuk kontras maksimal */
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .bg-primary-subtle {
            background-color: rgba(30, 64, 255, 0.15) !important;
            color: #000000 !important; /* HITAM MURNI untuk kontras maksimal */
            border: 1px solid rgba(30, 64, 255, 0.3);
        }
        .bg-secondary-subtle {
            background-color: rgba(148, 163, 184, 0.15) !important;
            color: #000000 !important; /* HITAM MURNI untuk kontras maksimal */
            border: 1px solid rgba(148, 163, 184, 0.3);
        }
        .bg-info-subtle {
            background-color: rgba(6, 182, 212, 0.15) !important;
            color: #000000 !important; /* HITAM MURNI untuk kontras maksimal */
            border: 1px solid rgba(6, 182, 212, 0.3);
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        /* Pagination Styles */
        .pagination {
            margin: 0;
            gap: 0.25rem;
        }
        
        .pagination .page-link {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--card-border);
            color: var(--text-muted);
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        
        .pagination .page-link:hover:not(.disabled) {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-main);
            border-color: var(--primary);
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        
        .pagination .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: transparent;
        }

        .pagination .page-link svg {
            width: 14px;
            height: 14px;
            vertical-align: middle;
        }

        /* Mobile Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .top-navbar {
                left: 0 !important;
            }
            
            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.7); /* Darker overlay */
                backdrop-filter: blur(4px);
                z-index: 1035;
                display: none;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
        }
        /* Form Controls Light Theme */
        .form-control, .form-select {
            background-color: #FFFFFF;
            border-color: #A3A3A3;
            color: #000000; /* HITAM MURNI */
        }
        .form-control:focus, .form-select:focus {
            background-color: #FFFFFF;
            border-color: var(--primary);
            color: #000000; /* HITAM MURNI */
            box-shadow: 0 0 0 0.25rem rgba(30, 64, 255, 0.25);
        }
        .form-control::placeholder {
            color: #000000; /* HITAM MURNI */
            opacity: 0.7;
        }
        .input-group-text {
            background-color: #F5F6FA;
            border-color: #A3A3A3;
            color: #000000; /* HITAM MURNI */
        }

        /* Ensure text is readable in all containers */
        .card, .modal-content, .dropdown-menu {
            color: var(--text-main);
        }

        /* Fix for specific text-dark usages that might be on dark background */
        /* We target text-dark that is NOT inside a badge, alert, or light background */
        body:not(.bg-light):not(.bg-white) .text-dark:not(.badge):not(.alert):not(.btn):not(.bg-white):not(.bg-light):not(.bg-warning) {
            color: var(--text-muted) !important;
        }

        /* Specific logout link color */
        .text-danger {
            color: white !important;
        }
        
        /* Prevent dark navy/blue colors on badges that would blend with background */
        .badge.bg-dark, .badge[style*="background-color: #1e293b"], 
        .badge[style*="background-color: #0f172a"],
        .badge[style*="background: #1e293b"],
        .badge[style*="background: #0f172a"] {
            background-color: rgba(99, 102, 241, 0.3) !important;
            color: #a5b4fc !important;
            border: 1px solid rgba(99, 102, 241, 0.5);
        }
        
        /* Ensure no text uses colors similar to background */
        .text-navy, .text-slate-900, .text-slate-800,
        [style*="color: #0f172a"], [style*="color: #1e293b"],
        [style*="color: #003087"] {
            color: var(--text-main) !important;
        }
        
        /* Override Bootstrap default badge colors for better contrast */
        .badge.bg-dark {
            background-color: rgba(71, 85, 105, 0.4) !important;
            color: #e2e8f0 !important;
        }
        
        .badge.bg-primary {
            background-color: var(--primary) !important;
            color: #ffffff !important;
        }
        
        .badge.bg-info {
            background-color: var(--secondary) !important;
            color: #ffffff !important;
        }

        /* ===== Stylish SweetAlert2 Custom Styles ===== */
        .swal-logout-popup {
            border-radius: 20px !important;
            padding: 2rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            border: none !important;
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%) !important;
        }

        .swal-logout-popup .swal2-title {
            color: #1e293b !important;
            font-family: 'Outfit', sans-serif !important;
            padding: 0 !important;
            margin-bottom: 0 !important;
        }

        .swal-logout-popup .swal2-html-container {
            color: #64748b !important;
            font-family: 'Outfit', sans-serif !important;
            margin: 1rem 0 !important;
        }

        .swal-actions {
            gap: 12px !important;
            margin-top: 1.5rem !important;
        }

        .swal-confirm-btn {
            background: linear-gradient(135deg, #0652DD 0%, #0043b8 100%) !important;
            color: white !important;
            border: none !important;
            padding: 12px 28px !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            font-family: 'Outfit', sans-serif !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(6, 82, 221, 0.35) !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .swal-confirm-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(6, 82, 221, 0.45) !important;
        }

        .swal-confirm-btn:active {
            transform: translateY(0) !important;
        }

        .swal-cancel-btn {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%) !important;
            color: #475569 !important;
            border: 1px solid #cbd5e1 !important;
            padding: 12px 28px !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            font-family: 'Outfit', sans-serif !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .swal-cancel-btn:hover {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
        }

        .swal-cancel-btn:active {
            transform: translateY(0) !important;
        }

        /* Success/Error/Warning popup styles */
        .swal-success-popup {
            border-radius: 20px !important;
            padding: 2rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            background: linear-gradient(145deg, #ffffff 0%, #f0fdf4 100%) !important;
        }

        .swal-error-popup {
            border-radius: 20px !important;
            padding: 2rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            background: linear-gradient(145deg, #ffffff 0%, #fef2f2 100%) !important;
        }

        .swal-warning-popup {
            border-radius: 20px !important;
            padding: 2rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            background: linear-gradient(145deg, #ffffff 0%, #fffbeb 100%) !important;
        }

        /* Toast notification styles */
        .swal2-popup.swal2-toast {
            border-radius: 12px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15) !important;
            padding: 12px 20px !important;
        }

        .swal2-popup.swal2-toast .swal2-title {
            font-family: 'Outfit', sans-serif !important;
            font-weight: 500 !important;
        }

        /* ============================================
           DARK/LIGHT MODE ADAPTIVE COLORS - GLOBAL FIX
           ============================================ */
        
        /* CSS Variables for adaptive colors */
        :root {
            --adaptive-text-primary: #1e293b;
            --adaptive-text-secondary: #64748b;
            --adaptive-text-muted: #94a3b8;
            --adaptive-bg-card: #ffffff;
            --adaptive-bg-light: #f8fafc;
            --adaptive-border: #e2e8f0;
            --adaptive-success: #059669;
            --adaptive-danger: #dc2626;
            --adaptive-warning: #d97706;
            --adaptive-info: #0284c7;
        }

        [data-theme="dark"] {
            --adaptive-text-primary: #f1f5f9;
            --adaptive-text-secondary: #cbd5e1;
            --adaptive-text-muted: #94a3b8;
            --adaptive-bg-card: #1e293b;
            --adaptive-bg-light: #334155;
            --adaptive-border: #475569;
            --adaptive-success: #34d399;
            --adaptive-danger: #f87171;
            --adaptive-warning: #fbbf24;
            --adaptive-info: #38bdf8;
        }

        /* Global text color fixes for dark mode */
        [data-theme="dark"] .text-dark,
        [data-theme="dark"] [style*="color: #1e293b"],
        [data-theme="dark"] [style*="color: #222"],
        [data-theme="dark"] [style*="color: #333"],
        [data-theme="dark"] [style*="color: #000"],
        [data-theme="dark"] [style*="color:#1e293b"],
        [data-theme="dark"] [style*="color:#222"],
        [data-theme="dark"] [style*="color:#333"] {
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] [style*="color: #64748b"],
        [data-theme="dark"] [style*="color:#64748b"],
        [data-theme="dark"] [style*="color: #475569"],
        [data-theme="dark"] [style*="color:#475569"] {
            color: var(--adaptive-text-secondary) !important;
        }

        [data-theme="dark"] [style*="color: #94a3b8"],
        [data-theme="dark"] [style*="color:#94a3b8"] {
            color: var(--adaptive-text-muted) !important;
        }

        /* Fix background colors in dark mode */
        [data-theme="dark"] [style*="background-color: #f8fafc"],
        [data-theme="dark"] [style*="background-color:#f8fafc"],
        [data-theme="dark"] [style*="background: #f8fafc"],
        [data-theme="dark"] [style*="background:#f8fafc"],
        [data-theme="dark"] [style*="background-color: #f1f5f9"],
        [data-theme="dark"] [style*="background-color:#f1f5f9"] {
            background-color: var(--adaptive-bg-light) !important;
        }

        [data-theme="dark"] [style*="background-color: #ffffff"],
        [data-theme="dark"] [style*="background-color:#ffffff"],
        [data-theme="dark"] [style*="background: #ffffff"],
        [data-theme="dark"] [style*="background:#ffffff"],
        [data-theme="dark"] [style*="background-color: white"],
        [data-theme="dark"] [style*="background: white"] {
            background-color: var(--adaptive-bg-card) !important;
        }

        /* Fix border colors in dark mode */
        [data-theme="dark"] [style*="border: 1px solid #e2e8f0"],
        [data-theme="dark"] [style*="border:1px solid #e2e8f0"],
        [data-theme="dark"] [style*="border-color: #e2e8f0"],
        [data-theme="dark"] [style*="border: 1px solid #f1f5f9"],
        [data-theme="dark"] [style*="border-color: #f1f5f9"] {
            border-color: var(--adaptive-border) !important;
        }

        /* Card and container fixes for dark mode */
        [data-theme="dark"] .card,
        [data-theme="dark"] .rental-card,
        [data-theme="dark"] .modal-content {
            background-color: var(--adaptive-bg-card) !important;
            border-color: var(--adaptive-border) !important;
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .card-header,
        [data-theme="dark"] .card-body,
        [data-theme="dark"] .card-footer {
            background-color: var(--adaptive-bg-card) !important;
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .card-header.bg-white {
            background-color: var(--adaptive-bg-card) !important;
        }

        /* Table fixes for dark mode */
        [data-theme="dark"] .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--adaptive-text-primary);
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .table th,
        [data-theme="dark"] .table td {
            color: var(--adaptive-text-primary) !important;
            border-color: var(--adaptive-border) !important;
        }

        [data-theme="dark"] .table thead th,
        [data-theme="dark"] .table-light th,
        [data-theme="dark"] .bg-light {
            background-color: var(--adaptive-bg-light) !important;
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .table tbody tr:hover {
            background-color: rgba(99, 102, 241, 0.1) !important;
        }

        [data-theme="dark"] .table-danger {
            background-color: rgba(239, 68, 68, 0.2) !important;
        }

        /* Alert fixes for dark mode */
        [data-theme="dark"] .alert {
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .alert-success,
        [data-theme="dark"] .alert-success-custom {
            background-color: rgba(16, 185, 129, 0.2) !important;
            border-color: rgba(16, 185, 129, 0.4) !important;
            color: #34d399 !important;
        }

        [data-theme="dark"] .alert-danger,
        [data-theme="dark"] .alert-danger-custom {
            background-color: rgba(239, 68, 68, 0.2) !important;
            border-color: rgba(239, 68, 68, 0.4) !important;
            color: #f87171 !important;
        }

        [data-theme="dark"] .alert-warning,
        [data-theme="dark"] .alert-warning-custom {
            background-color: rgba(245, 158, 11, 0.2) !important;
            border-color: rgba(245, 158, 11, 0.4) !important;
            color: #fbbf24 !important;
        }

        [data-theme="dark"] .alert-info,
        [data-theme="dark"] .alert-info-custom {
            background-color: rgba(6, 182, 212, 0.2) !important;
            border-color: rgba(6, 182, 212, 0.4) !important;
            color: #22d3ee !important;
        }

        /* Badge fixes for dark mode */
        [data-theme="dark"] .badge {
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .badge.bg-light,
        [data-theme="dark"] .badge-neutral {
            background-color: var(--adaptive-bg-light) !important;
            color: var(--adaptive-text-primary) !important;
            border-color: var(--adaptive-border) !important;
        }

        [data-theme="dark"] .badge.bg-secondary {
            background-color: #475569 !important;
            color: #f1f5f9 !important;
        }

        [data-theme="dark"] .badge-pending,
        [data-theme="dark"] .badge.bg-warning {
            background-color: rgba(245, 158, 11, 0.3) !important;
            color: #fbbf24 !important;
            border-color: rgba(245, 158, 11, 0.5) !important;
        }

        [data-theme="dark"] .badge-active,
        [data-theme="dark"] .badge-paid,
        [data-theme="dark"] .badge.bg-success {
            background-color: rgba(16, 185, 129, 0.3) !important;
            color: #34d399 !important;
            border-color: rgba(16, 185, 129, 0.5) !important;
        }

        [data-theme="dark"] .badge-cancelled,
        [data-theme="dark"] .badge-unpaid,
        [data-theme="dark"] .badge.bg-danger {
            background-color: rgba(239, 68, 68, 0.3) !important;
            color: #f87171 !important;
            border-color: rgba(239, 68, 68, 0.5) !important;
        }

        [data-theme="dark"] .badge-waiting,
        [data-theme="dark"] .badge.bg-info {
            background-color: rgba(6, 182, 212, 0.3) !important;
            color: #22d3ee !important;
            border-color: rgba(6, 182, 212, 0.5) !important;
        }

        [data-theme="dark"] .badge-done,
        [data-theme="dark"] .badge.bg-primary {
            background-color: rgba(99, 102, 241, 0.3) !important;
            color: #a5b4fc !important;
            border-color: rgba(99, 102, 241, 0.5) !important;
        }

        /* Form control fixes for dark mode */
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select,
        [data-theme="dark"] textarea {
            background-color: var(--adaptive-bg-light) !important;
            border-color: var(--adaptive-border) !important;
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .form-control::placeholder,
        [data-theme="dark"] .form-select::placeholder,
        [data-theme="dark"] textarea::placeholder {
            color: var(--adaptive-text-muted) !important;
        }

        [data-theme="dark"] .form-label,
        [data-theme="dark"] label {
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .input-group-text {
            background-color: var(--adaptive-bg-light) !important;
            border-color: var(--adaptive-border) !important;
            color: var(--adaptive-text-primary) !important;
        }

        /* Text utility fixes for dark mode */
        [data-theme="dark"] .text-muted {
            color: var(--adaptive-text-muted) !important;
        }

        [data-theme="dark"] .text-secondary {
            color: var(--adaptive-text-secondary) !important;
        }

        [data-theme="dark"] .text-dark {
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .fw-bold,
        [data-theme="dark"] .fw-semibold,
        [data-theme="dark"] strong,
        [data-theme="dark"] b {
            color: inherit;
        }

        /* Heading fixes for dark mode */
        [data-theme="dark"] h1, [data-theme="dark"] h2, [data-theme="dark"] h3,
        [data-theme="dark"] h4, [data-theme="dark"] h5, [data-theme="dark"] h6 {
            color: var(--adaptive-text-primary) !important;
        }

        /* Info row fixes (used in rental show) */
        [data-theme="dark"] .info-row {
            border-color: var(--adaptive-border) !important;
        }

        [data-theme="dark"] .info-label {
            color: var(--adaptive-text-muted) !important;
        }

        [data-theme="dark"] .info-value {
            color: var(--adaptive-text-primary) !important;
        }

        /* Dropdown fixes for dark mode */
        [data-theme="dark"] .dropdown-menu {
            background-color: var(--adaptive-bg-card) !important;
            border-color: var(--adaptive-border) !important;
        }

        [data-theme="dark"] .dropdown-item {
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .dropdown-item:hover {
            background-color: var(--adaptive-bg-light) !important;
        }

        /* Specific color overrides for inline styles */
        [data-theme="dark"] [style*="color: #059669"],
        [data-theme="dark"] [style*="color:#059669"] {
            color: #34d399 !important;
        }

        [data-theme="dark"] [style*="color: #991b1b"],
        [data-theme="dark"] [style*="color:#991b1b"],
        [data-theme="dark"] [style*="color: #dc2626"],
        [data-theme="dark"] [style*="color:#dc2626"] {
            color: #f87171 !important;
        }

        [data-theme="dark"] [style*="color: #92400e"],
        [data-theme="dark"] [style*="color:#92400e"],
        [data-theme="dark"] [style*="color: #d97706"],
        [data-theme="dark"] [style*="color:#d97706"] {
            color: #fbbf24 !important;
        }

        [data-theme="dark"] [style*="color: #0e7490"],
        [data-theme="dark"] [style*="color:#0e7490"],
        [data-theme="dark"] [style*="color: #0284c7"],
        [data-theme="dark"] [style*="color:#0284c7"] {
            color: #22d3ee !important;
        }

        [data-theme="dark"] [style*="color: #065f46"],
        [data-theme="dark"] [style*="color:#065f46"] {
            color: #34d399 !important;
        }

        [data-theme="dark"] [style*="color: #3730a3"],
        [data-theme="dark"] [style*="color:#3730a3"] {
            color: #a5b4fc !important;
        }

        /* Progress bar fixes */
        [data-theme="dark"] .progress {
            background-color: var(--adaptive-bg-light) !important;
        }

        /* Border utility fixes */
        [data-theme="dark"] .border,
        [data-theme="dark"] .border-bottom,
        [data-theme="dark"] .border-top {
            border-color: var(--adaptive-border) !important;
        }

        /* Condition card fixes (return form) */
        [data-theme="dark"] .condition-card {
            border-color: var(--adaptive-border) !important;
            background-color: var(--adaptive-bg-card) !important;
        }

        [data-theme="dark"] .condition-card:hover {
            border-color: #6366f1 !important;
        }

        [data-theme="dark"] .condition-card.selected-baik {
            border-color: #34d399 !important;
            background-color: rgba(16, 185, 129, 0.2) !important;
        }

        [data-theme="dark"] .condition-card.selected-rusak {
            border-color: #f87171 !important;
            background-color: rgba(239, 68, 68, 0.2) !important;
        }

        [data-theme="dark"] .fine-section {
            background-color: rgba(245, 158, 11, 0.2) !important;
            border-color: rgba(245, 158, 11, 0.4) !important;
        }

        /* Small text fixes */
        [data-theme="dark"] small,
        [data-theme="dark"] .small {
            color: var(--adaptive-text-muted) !important;
        }

        [data-theme="dark"] small.text-muted,
        [data-theme="dark"] .small.text-muted {
            color: var(--adaptive-text-muted) !important;
        }

        /* Icon color fixes */
        [data-theme="dark"] .bi {
            color: inherit;
        }

        /* Specific page element fixes */
        [data-theme="dark"] .avatar-sm,
        [data-theme="dark"] .icon-shape {
            color: inherit;
        }

        /* Fix for bg-white elements */
        [data-theme="dark"] .bg-white {
            background-color: var(--adaptive-bg-card) !important;
        }

        /* Fix for bg-light elements */
        [data-theme="dark"] .bg-light {
            background-color: var(--adaptive-bg-light) !important;
            color: var(--adaptive-text-primary) !important;
        }

        /* Fix for rounded containers with inline bg */
        [data-theme="dark"] .rounded[style*="background-color: #f8fafc"],
        [data-theme="dark"] .p-3[style*="background-color: #f8fafc"] {
            background-color: var(--adaptive-bg-light) !important;
            border-color: var(--adaptive-border) !important;
        }

        /* Fix hero section text */
        [data-theme="dark"] [style*="color: white"] {
            color: white !important;
        }

        /* Ensure price colors stay visible */
        [data-theme="dark"] [style*="color: #009432"] {
            color: #34d399 !important;
        }

        /* Fix for table-custom */
        [data-theme="dark"] .table-custom th {
            background-color: var(--adaptive-bg-light) !important;
            color: var(--adaptive-text-secondary) !important;
        }

        [data-theme="dark"] .table-custom td {
            color: var(--adaptive-text-primary) !important;
            border-color: var(--adaptive-border) !important;
        }

        /* Fix for primary-subtle in dark mode */
        [data-theme="dark"] .bg-primary-subtle {
            background-color: rgba(99, 102, 241, 0.2) !important;
            color: #a5b4fc !important;
        }

        /* Fix for opacity backgrounds */
        [data-theme="dark"] .bg-opacity-10 {
            --bs-bg-opacity: 0.2 !important;
        }

        /* Fix for font-monospace elements */
        [data-theme="dark"] .font-monospace {
            color: var(--adaptive-text-primary) !important;
        }

        /* Fix for list-group */
        [data-theme="dark"] .list-group-item {
            background-color: var(--adaptive-bg-card) !important;
            border-color: var(--adaptive-border) !important;
            color: var(--adaptive-text-primary) !important;
        }

        /* Fix for nav-tabs */
        [data-theme="dark"] .nav-tabs .nav-link {
            color: var(--adaptive-text-secondary) !important;
        }

        [data-theme="dark"] .nav-tabs .nav-link.active {
            background-color: var(--adaptive-bg-card) !important;
            border-color: var(--adaptive-border) !important;
            color: var(--adaptive-text-primary) !important;
        }

        /* Fix for accordion */
        [data-theme="dark"] .accordion-item {
            background-color: var(--adaptive-bg-card) !important;
            border-color: var(--adaptive-border) !important;
        }

        [data-theme="dark"] .accordion-button {
            background-color: var(--adaptive-bg-card) !important;
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .accordion-body {
            background-color: var(--adaptive-bg-card) !important;
            color: var(--adaptive-text-primary) !important;
        }

        /* Additional dark mode fixes for common patterns */
        [data-theme="dark"] .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3) !important;
        }

        [data-theme="dark"] .border-light {
            border-color: var(--adaptive-border) !important;
        }

        [data-theme="dark"] .border-secondary {
            border-color: var(--adaptive-border) !important;
        }

        /* Fix for text colors used in admin dashboard */
        [data-theme="dark"] .text-primary {
            color: #818cf8 !important;
        }

        [data-theme="dark"] .text-info {
            color: #22d3ee !important;
        }

        [data-theme="dark"] .text-warning {
            color: #fbbf24 !important;
        }

        [data-theme="dark"] .text-success {
            color: #34d399 !important;
        }

        [data-theme="dark"] .text-danger:not(.nav-link):not(button) {
            color: #f87171 !important;
        }

        /* Fix for border-start colored borders */
        [data-theme="dark"] .border-primary {
            border-color: #818cf8 !important;
        }

        [data-theme="dark"] .border-info {
            border-color: #22d3ee !important;
        }

        [data-theme="dark"] .border-warning {
            border-color: #fbbf24 !important;
        }

        [data-theme="dark"] .border-success {
            border-color: #34d399 !important;
        }

        [data-theme="dark"] .border-danger {
            border-color: #f87171 !important;
        }

        /* Fix for icon-shape backgrounds */
        [data-theme="dark"] .icon-shape {
            background-color: rgba(99, 102, 241, 0.2) !important;
        }

        [data-theme="dark"] .icon-shape.bg-primary {
            background-color: rgba(99, 102, 241, 0.2) !important;
        }

        [data-theme="dark"] .icon-shape.bg-info {
            background-color: rgba(6, 182, 212, 0.2) !important;
        }

        [data-theme="dark"] .icon-shape.bg-warning {
            background-color: rgba(245, 158, 11, 0.2) !important;
        }

        [data-theme="dark"] .icon-shape.bg-success {
            background-color: rgba(16, 185, 129, 0.2) !important;
        }

        [data-theme="dark"] .icon-shape.bg-danger {
            background-color: rgba(239, 68, 68, 0.2) !important;
        }

        [data-theme="dark"] .icon-shape.bg-secondary {
            background-color: rgba(100, 116, 139, 0.2) !important;
        }

        /* Fix for bg-opacity classes */
        [data-theme="dark"] .bg-primary.bg-opacity-10 {
            background-color: rgba(99, 102, 241, 0.2) !important;
        }

        [data-theme="dark"] .bg-info.bg-opacity-10 {
            background-color: rgba(6, 182, 212, 0.2) !important;
        }

        [data-theme="dark"] .bg-warning.bg-opacity-10 {
            background-color: rgba(245, 158, 11, 0.2) !important;
        }

        [data-theme="dark"] .bg-success.bg-opacity-10 {
            background-color: rgba(16, 185, 129, 0.2) !important;
        }

        [data-theme="dark"] .bg-danger.bg-opacity-10 {
            background-color: rgba(239, 68, 68, 0.2) !important;
        }

        [data-theme="dark"] .bg-secondary.bg-opacity-10 {
            background-color: rgba(100, 116, 139, 0.2) !important;
        }

        /* Fix for rounded-pill badges */
        [data-theme="dark"] .rounded-pill.bg-warning {
            background-color: rgba(245, 158, 11, 0.3) !important;
            color: #fbbf24 !important;
        }

        [data-theme="dark"] .rounded-pill.bg-primary {
            background-color: rgba(99, 102, 241, 0.3) !important;
            color: #a5b4fc !important;
        }

        [data-theme="dark"] .rounded-pill.bg-success {
            background-color: rgba(16, 185, 129, 0.3) !important;
            color: #34d399 !important;
        }

        [data-theme="dark"] .rounded-pill.bg-danger {
            background-color: rgba(239, 68, 68, 0.3) !important;
            color: #f87171 !important;
        }

        /* Fix for avatar backgrounds */
        [data-theme="dark"] .avatar-sm.bg-light {
            background-color: var(--adaptive-bg-light) !important;
            color: var(--adaptive-text-primary) !important;
        }

        /* Fix for specific dashboard elements */
        [data-theme="dark"] .card-title {
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .card-subtitle {
            color: var(--adaptive-text-secondary) !important;
        }

        /* Fix for breadcrumb */
        [data-theme="dark"] .breadcrumb-item {
            color: var(--adaptive-text-secondary) !important;
        }

        [data-theme="dark"] .breadcrumb-item.active {
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .breadcrumb-item a {
            color: var(--adaptive-text-secondary) !important;
        }

        /* Fix for pagination in dark mode */
        [data-theme="dark"] .pagination .page-link {
            background-color: var(--adaptive-bg-card) !important;
            border-color: var(--adaptive-border) !important;
            color: var(--adaptive-text-secondary) !important;
        }

        [data-theme="dark"] .pagination .page-item.active .page-link {
            background-color: #6366f1 !important;
            border-color: #6366f1 !important;
            color: white !important;
        }

        [data-theme="dark"] .pagination .page-link:hover {
            background-color: var(--adaptive-bg-light) !important;
            color: var(--adaptive-text-primary) !important;
        }

        /* Fix for modal */
        [data-theme="dark"] .modal-header {
            background-color: var(--adaptive-bg-card) !important;
            border-color: var(--adaptive-border) !important;
        }

        [data-theme="dark"] .modal-body {
            background-color: var(--adaptive-bg-card) !important;
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .modal-footer {
            background-color: var(--adaptive-bg-card) !important;
            border-color: var(--adaptive-border) !important;
        }

        [data-theme="dark"] .modal-title {
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* Fix for offcanvas */
        [data-theme="dark"] .offcanvas {
            background-color: var(--adaptive-bg-card) !important;
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .offcanvas-header {
            border-color: var(--adaptive-border) !important;
        }

        /* Fix for popover */
        [data-theme="dark"] .popover {
            background-color: var(--adaptive-bg-card) !important;
            border-color: var(--adaptive-border) !important;
        }

        [data-theme="dark"] .popover-header {
            background-color: var(--adaptive-bg-light) !important;
            border-color: var(--adaptive-border) !important;
            color: var(--adaptive-text-primary) !important;
        }

        [data-theme="dark"] .popover-body {
            color: var(--adaptive-text-primary) !important;
        }

        /* Fix for tooltip */
        [data-theme="dark"] .tooltip-inner {
            background-color: var(--adaptive-bg-light) !important;
            color: var(--adaptive-text-primary) !important;
        }

        /* Ensure all paragraph text is readable */
        [data-theme="dark"] p {
            color: var(--adaptive-text-secondary);
        }

        [data-theme="dark"] p.text-muted {
            color: var(--adaptive-text-muted) !important;
        }

        /* Fix for lead text */
        [data-theme="dark"] .lead {
            color: var(--adaptive-text-secondary) !important;
        }

        /* Fix for blockquote */
        [data-theme="dark"] blockquote {
            color: var(--adaptive-text-primary) !important;
            border-color: var(--adaptive-border) !important;
        }

        /* Fix for code elements */
        [data-theme="dark"] code {
            color: #f472b6 !important;
            background-color: rgba(244, 114, 182, 0.1) !important;
        }

        [data-theme="dark"] pre {
            background-color: var(--adaptive-bg-light) !important;
            color: var(--adaptive-text-primary) !important;
            border-color: var(--adaptive-border) !important;
        }

        /* Fix for hr elements */
        [data-theme="dark"] hr {
            border-color: var(--adaptive-border) !important;
            opacity: 0.5;
        }

        /* Fix for figure caption */
        [data-theme="dark"] figcaption {
            color: var(--adaptive-text-muted) !important;
        }

        /* Fix for mark/highlight */
        [data-theme="dark"] mark {
            background-color: rgba(250, 204, 21, 0.3) !important;
            color: var(--adaptive-text-primary) !important;
        }

        /* Ensure links are visible */
        [data-theme="dark"] a:not(.btn):not(.nav-link):not(.dropdown-item):not(.badge) {
            color: #818cf8;
        }

        [data-theme="dark"] a:not(.btn):not(.nav-link):not(.dropdown-item):not(.badge):hover {
            color: #a5b4fc;
        }

        /* ============================================
           LIGHT MODE FIXES - Ensure readability
           ============================================ */
        
        /* Light mode text colors */
        :root:not([data-theme="dark"]) .text-white,
        :root:not([data-theme="dark"]) [style*="color: white"],
        :root:not([data-theme="dark"]) [style*="color:#fff"],
        :root:not([data-theme="dark"]) [style*="color: #fff"] {
            /* Keep white text white in light mode for specific elements */
        }

        /* Fix card headers with bg-white in light mode */
        :root:not([data-theme="dark"]) .card-header.bg-white {
            background-color: #ffffff !important;
            color: #1e293b !important;
        }

        /* Ensure dark text on light backgrounds */
        :root:not([data-theme="dark"]) .card,
        :root:not([data-theme="dark"]) .card-body,
        :root:not([data-theme="dark"]) .card-header,
        :root:not([data-theme="dark"]) .card-footer {
            color: #1e293b;
        }

        :root:not([data-theme="dark"]) .card h1,
        :root:not([data-theme="dark"]) .card h2,
        :root:not([data-theme="dark"]) .card h3,
        :root:not([data-theme="dark"]) .card h4,
        :root:not([data-theme="dark"]) .card h5,
        :root:not([data-theme="dark"]) .card h6 {
            color: #1e293b !important;
        }

        /* Fix table text in light mode */
        :root:not([data-theme="dark"]) .table th,
        :root:not([data-theme="dark"]) .table td {
            color: #1e293b;
        }

        :root:not([data-theme="dark"]) .table thead th {
            color: #64748b;
        }

        /* Fix form controls in light mode */
        :root:not([data-theme="dark"]) .form-control,
        :root:not([data-theme="dark"]) .form-select {
            color: #1e293b;
            background-color: #ffffff;
        }

        /* Fix badges in light mode */
        :root:not([data-theme="dark"]) .badge.bg-light {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
        }

        /* Fix list group in light mode */
        :root:not([data-theme="dark"]) .list-group-item {
            color: #1e293b;
            background-color: #ffffff;
        }

        /* Fix modal in light mode */
        :root:not([data-theme="dark"]) .modal-content {
            color: #1e293b;
        }

        :root:not([data-theme="dark"]) .modal-header,
        :root:not([data-theme="dark"]) .modal-body,
        :root:not([data-theme="dark"]) .modal-footer {
            color: #1e293b;
        }

        /* Fix dropdown in light mode */
        :root:not([data-theme="dark"]) .dropdown-menu {
            color: #1e293b;
        }

        :root:not([data-theme="dark"]) .dropdown-item {
            color: #1e293b;
        }

        /* Fix alerts in light mode */
        :root:not([data-theme="dark"]) .alert-success {
            color: #065f46;
            background-color: #d1fae5;
        }

        :root:not([data-theme="dark"]) .alert-danger {
            color: #991b1b;
            background-color: #fee2e2;
        }

        :root:not([data-theme="dark"]) .alert-warning {
            color: #92400e;
            background-color: #fef3c7;
        }

        :root:not([data-theme="dark"]) .alert-info {
            color: #0e7490;
            background-color: #cffafe;
        }

        /* Fix pagination in light mode */
        :root:not([data-theme="dark"]) .pagination .page-link {
            color: #1e293b;
        }

        /* ============================================
           SWAL (SweetAlert2) ADAPTIVE COLORS
           ============================================ */
        
        /* SweetAlert2 Custom Popup Styles */
        .swal-logout-popup {
            border-radius: 20px !important;
            padding: 2rem !important;
        }
        
        .swal-confirm-btn {
            background: linear-gradient(135deg, #0652DD 0%, #1e40af 100%) !important;
            color: white !important;
            border: none !important;
            padding: 12px 28px !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(6, 82, 221, 0.3) !important;
        }
        
        .swal-confirm-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(6, 82, 221, 0.4) !important;
        }
        
        .swal-cancel-btn {
            background: #f1f5f9 !important;
            color: #64748b !important;
            border: 1px solid #e2e8f0 !important;
            padding: 12px 28px !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            transition: all 0.3s ease !important;
        }
        
        .swal-cancel-btn:hover {
            background: #e2e8f0 !important;
            color: #475569 !important;
        }
        
        .swal-actions {
            gap: 12px !important;
        }
        
        /* Adaptive Confirm Action Popup Styles */
        .swal-dark-popup {
            border-radius: 20px !important;
            background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%) !important;
            border: 1px solid rgba(148, 163, 184, 0.15) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
        }
        
        .swal-light-popup {
            border-radius: 20px !important;
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%) !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
        }
        
        .swal-confirm-btn-adaptive {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            color: white !important;
            border: none !important;
            padding: 12px 28px !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.35) !important;
        }
        
        .swal-confirm-btn-adaptive:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.45) !important;
        }
        
        .swal-cancel-btn-adaptive {
            background: transparent !important;
            color: #64748b !important;
            border: 1px solid #e2e8f0 !important;
            padding: 12px 28px !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            transition: all 0.3s ease !important;
        }
        
        html[data-theme="dark"] .swal-cancel-btn-adaptive {
            color: #94a3b8 !important;
            border-color: #475569 !important;
        }
        
        .swal-cancel-btn-adaptive:hover {
            background: #f1f5f9 !important;
            color: #1e293b !important;
        }
        
        html[data-theme="dark"] .swal-cancel-btn-adaptive:hover {
            background: #334155 !important;
            color: #f1f5f9 !important;
        }
        
        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.3); opacity: 0; }
        }
        
        /* Dark mode SweetAlert - using html attribute selector */
        html[data-theme="dark"] .swal2-popup,
        html[data-theme="dark"] .swal-logout-popup,
        .swal2-popup.swal-dark-mode {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
            border: 1px solid #334155 !important;
        }

        html[data-theme="dark"] .swal2-title,
        .swal2-popup.swal-dark-mode .swal2-title {
            color: #f1f5f9 !important;
        }
        
        html[data-theme="dark"] .swal2-title span,
        .swal2-popup.swal-dark-mode .swal2-title span {
            color: #f1f5f9 !important;
        }

        html[data-theme="dark"] .swal2-html-container,
        html[data-theme="dark"] .swal2-content,
        .swal2-popup.swal-dark-mode .swal2-html-container {
            color: #cbd5e1 !important;
        }
        
        html[data-theme="dark"] .swal2-html-container p,
        html[data-theme="dark"] .swal-logout-popup p,
        .swal2-popup.swal-dark-mode p {
            color: #94a3b8 !important;
        }

        html[data-theme="dark"] .swal2-input,
        html[data-theme="dark"] .swal2-textarea,
        html[data-theme="dark"] .swal2-select {
            background-color: #334155 !important;
            border-color: #475569 !important;
            color: #f1f5f9 !important;
        }

        html[data-theme="dark"] .swal2-validation-message {
            background-color: #334155 !important;
            color: #f87171 !important;
        }
        
        html[data-theme="dark"] .swal-confirm-btn,
        .swal2-popup.swal-dark-mode .swal-confirm-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3) !important;
        }
        
        html[data-theme="dark"] .swal-confirm-btn:hover {
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4) !important;
        }
        
        html[data-theme="dark"] .swal-cancel-btn,
        .swal2-popup.swal-dark-mode .swal-cancel-btn {
            background: #334155 !important;
            color: #cbd5e1 !important;
            border: 1px solid #475569 !important;
        }
        
        html[data-theme="dark"] .swal-cancel-btn:hover {
            background: #475569 !important;
            color: #f1f5f9 !important;
        }
        
        /* Luxury Logout Popup Styles */
        .swal-logout-luxury {
            border-radius: 24px !important;
            padding: 0 !important;
            overflow: hidden !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        }
        
        .swal-logout-luxury-dark {
            background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%) !important;
            border: 1px solid rgba(148, 163, 184, 0.1) !important;
        }
        
        .swal-logout-luxury-light {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%) !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
        }
        
        .logout-btn-confirm {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%) !important;
            color: white !important;
            border: none !important;
            padding: 14px 32px !important;
            border-radius: 14px !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 10px 30px -5px rgba(239, 68, 68, 0.5) !important;
            position: relative !important;
            overflow: hidden !important;
        }
        
        .logout-btn-confirm::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: -100% !important;
            width: 100% !important;
            height: 100% !important;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent) !important;
            transition: left 0.5s !important;
        }
        
        .logout-btn-confirm:hover::before {
            left: 100% !important;
        }
        
        .logout-btn-confirm:hover {
            transform: translateY(-3px) scale(1.02) !important;
            box-shadow: 0 15px 40px -5px rgba(239, 68, 68, 0.6) !important;
        }
        
        .logout-btn-cancel-light {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%) !important;
            color: #475569 !important;
            border: 1px solid #cbd5e1 !important;
            padding: 14px 32px !important;
            border-radius: 14px !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        
        .logout-btn-cancel-light:hover {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1) !important;
        }
        
        .logout-btn-cancel-dark {
            background: linear-gradient(135deg, #334155 0%, #1e293b 100%) !important;
            color: #e2e8f0 !important;
            border: 1px solid #475569 !important;
            padding: 14px 32px !important;
            border-radius: 14px !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        
        .logout-btn-cancel-dark:hover {
            background: linear-gradient(135deg, #475569 0%, #334155 100%) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.3) !important;
        }
        
        .swal-logout-actions {
            gap: 16px !important;
            padding: 0 !important;
        }

        /* Light mode SweetAlert */
        :root:not([data-theme="dark"]) .swal2-popup,
        :root:not([data-theme="dark"]) .swal-logout-popup {
            background-color: #ffffff !important;
            color: #1e293b !important;
        }

        :root:not([data-theme="dark"]) .swal2-title {
            color: #1e293b !important;
        }
        
        :root:not([data-theme="dark"]) .swal2-title span {
            color: #1e293b !important;
        }

        :root:not([data-theme="dark"]) .swal2-html-container,
        :root:not([data-theme="dark"]) .swal2-content {
            color: #64748b !important;
        }
        
        :root:not([data-theme="dark"]) .swal2-html-container p,
        :root:not([data-theme="dark"]) .swal-logout-popup p {
            color: #64748b !important;
        }

        /* ============================================
           ADDITIONAL GLOBAL FIXES
           ============================================ */

        /* Fix for inline style backgrounds that need to adapt */
        [data-theme="dark"] [style*="background: white"],
        [data-theme="dark"] [style*="background-color: white"],
        [data-theme="dark"] [style*="background: #fff"],
        [data-theme="dark"] [style*="background-color: #fff"] {
            background-color: var(--adaptive-bg-card) !important;
        }

        /* Fix for specific damage report colors */
        [data-theme="dark"] [style*="background-color: #fef2f2"] {
            background-color: rgba(239, 68, 68, 0.15) !important;
        }

        [data-theme="dark"] [style*="border: 1px solid #fecaca"] {
            border-color: rgba(239, 68, 68, 0.4) !important;
        }

        /* Fix for kasir dashboard gradient cards - keep gradients visible */
        [data-theme="dark"] [style*="linear-gradient"] {
            /* Gradients should remain as-is, text is white */
        }

        /* Fix for photo upload boxes */
        [data-theme="dark"] .photo-upload-box {
            background-color: var(--adaptive-bg-light) !important;
            border-color: var(--adaptive-border) !important;
        }

        [data-theme="dark"] .photo-upload-box:hover {
            border-color: #6366f1 !important;
        }

        /* Fix for owner report page */
        [data-theme="dark"] .card[style*="background: white"] {
            background-color: var(--adaptive-bg-card) !important;
        }

        /* Ensure fw-bold text inherits proper color */
        .fw-bold.text-dark {
            color: var(--text-main) !important;
        }

        [data-theme="dark"] .fw-bold.text-dark {
            color: var(--adaptive-text-primary) !important;
        }

        /* Fix for text-truncate elements */
        [data-theme="dark"] .text-truncate {
            color: inherit;
        }

        /* Fix for empty state messages */
        [data-theme="dark"] .text-center.text-muted {
            color: var(--adaptive-text-muted) !important;
        }

        /* Fix for border-start colored cards */
        [data-theme="dark"] .border-start.border-4 {
            background-color: var(--adaptive-bg-card) !important;
        }

        /* Fix for bg-gradient cards - ensure text is readable */
        .bg-gradient {
            color: white !important;
        }

        .bg-gradient * {
            color: inherit;
        }

        .bg-gradient .opacity-75 {
            opacity: 0.75;
        }

        .bg-gradient .opacity-50 {
            opacity: 0.5;
        }

        /* Fix for specific inline color styles in light mode */
        :root:not([data-theme="dark"]) [style*="color: #1e293b"] {
            color: #1e293b !important;
        }

        :root:not([data-theme="dark"]) [style*="color: #64748b"] {
            color: #64748b !important;
        }

        /* Ensure main content area has proper text color */
        .main-content {
            color: var(--text-main);
        }

        [data-theme="dark"] .main-content {
            color: var(--adaptive-text-primary);
        }

        /* Fix for span elements with inline colors */
        [data-theme="dark"] span[style*="color: #1e293b"],
        [data-theme="dark"] span[style*="color:#1e293b"],
        [data-theme="dark"] div[style*="color: #1e293b"],
        [data-theme="dark"] div[style*="color:#1e293b"] {
            color: var(--adaptive-text-primary) !important;
        }

        /* Fix for bg-secondary-subtle */
        [data-theme="dark"] .bg-secondary-subtle {
            background-color: rgba(100, 116, 139, 0.2) !important;
            color: #cbd5e1 !important;
        }

        /* Fix for bg-*-subtle classes */
        [data-theme="dark"] .bg-warning-subtle {
            background-color: rgba(245, 158, 11, 0.2) !important;
            color: #fbbf24 !important;
        }

        [data-theme="dark"] .bg-success-subtle {
            background-color: rgba(16, 185, 129, 0.2) !important;
            color: #34d399 !important;
        }

        [data-theme="dark"] .bg-danger-subtle {
            background-color: rgba(239, 68, 68, 0.2) !important;
            color: #f87171 !important;
        }

        [data-theme="dark"] .bg-info-subtle {
            background-color: rgba(6, 182, 212, 0.2) !important;
            color: #22d3ee !important;
        }

        /* Fix for text-*-emphasis classes */
        [data-theme="dark"] .text-warning-emphasis {
            color: #fbbf24 !important;
        }

        [data-theme="dark"] .text-success-emphasis {
            color: #34d399 !important;
        }

        [data-theme="dark"] .text-danger-emphasis {
            color: #f87171 !important;
        }

        [data-theme="dark"] .text-info-emphasis {
            color: #22d3ee !important;
        }

        [data-theme="dark"] .text-primary-emphasis {
            color: #818cf8 !important;
        }

        /* Fix for toast notifications */
        [data-theme="dark"] .toast {
            background-color: var(--adaptive-bg-card) !important;
            color: var(--adaptive-text-primary) !important;
            border-color: var(--adaptive-border) !important;
        }

        [data-theme="dark"] .toast-header {
            background-color: var(--adaptive-bg-light) !important;
            color: var(--adaptive-text-primary) !important;
            border-color: var(--adaptive-border) !important;
        }

        [data-theme="dark"] .toast-body {
            color: var(--adaptive-text-primary) !important;
        }

        /* ============================================
           USER DROPDOWN ADAPTIVE COLORS
           ============================================ */
        .user-dropdown {
            background-color: var(--adaptive-bg-card, #ffffff) !important;
            border: 1px solid var(--adaptive-border, #e2e8f0) !important;
        }
        
        .user-dropdown .dropdown-header {
            color: var(--adaptive-text-muted, #64748b) !important;
        }
        
        .user-dropdown .dropdown-item {
            color: var(--adaptive-text-primary, #1e293b) !important;
        }
        
        .user-dropdown .dropdown-item:hover {
            background-color: var(--adaptive-bg-light, #f8fafc) !important;
        }
        
        .user-dropdown .dropdown-item.text-danger {
            color: #ef4444 !important;
        }
        
        .user-dropdown .dropdown-item.text-danger:hover {
            background-color: rgba(239, 68, 68, 0.1) !important;
        }
        
        .user-dropdown .dropdown-divider {
            border-color: var(--adaptive-border, #e2e8f0) !important;
        }
        
        [data-theme="dark"] .user-dropdown {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        
        [data-theme="dark"] .user-dropdown .dropdown-header {
            color: #94a3b8 !important;
        }
        
        [data-theme="dark"] .user-dropdown .dropdown-item {
            color: #f1f5f9 !important;
        }
        
        [data-theme="dark"] .user-dropdown .dropdown-item:hover {
            background-color: #334155 !important;
        }
        
        [data-theme="dark"] .user-dropdown .dropdown-item.text-danger {
            color: #f87171 !important;
        }
        
        [data-theme="dark"] .user-dropdown .dropdown-item.text-danger:hover {
            background-color: rgba(248, 113, 113, 0.1) !important;
        }
        
        [data-theme="dark"] .user-dropdown .dropdown-divider {
            border-color: #475569 !important;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="bg-grid"></div>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon">
                <i class="bi bi-playstation fs-4"></i>
            </div>
            <span class="logo-text" style="font-size: 1.1rem;">PlayStation</span>
        </div>
        
        <nav class="sidebar-menu">
            @yield('sidebar_menu')
            
            <div class="mt-auto">
                <form method="POST" action="{{ route('logout') }}" onsubmit="confirmLogout(event)">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent text-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>{{ __('dashboard.logout') }}</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- Top Navbar -->
    <header class="top-navbar" id="topNavbar">
        <div class="d-flex align-items-center gap-3">
            <button class="toggle-btn" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <h5 class="m-0 fw-semibold d-none d-md-block">@yield('header_title', 'Dashboard')</h5>
        </div>
        
        <div class="d-flex align-items-center gap-3">
            <!-- Language Switcher - Only for Pelanggan -->
            @if(Auth::check() && Auth::user()->role === 'pelanggan')
            <div class="dropdown">
                <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-translate fs-5" style="color: var(--text-main);"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="background-color: var(--card-bg); border-color: var(--card-border);">
                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'id') }}" style="color: var(--text-main);">🇮🇩 Indonesia</a></li>
                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}" style="color: var(--text-main);">🇬🇧 English</a></li>
                </ul>
            </div>
            
            <!-- Quick Dashboard Link -->
            <a href="{{ route('dashboard.pelanggan') }}" class="btn btn-link text-decoration-none position-relative" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('dashboard.home') }}">
                <i class="bi bi-grid-fill fs-5" style="color: var(--text-main);"></i>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-primary border border-light rounded-circle" style="width: 8px; height: 8px; margin-top: 10px; margin-left: -8px;">
                    <span class="visually-hidden">New alerts</span>
                </span>
            </a>
            @endif

            <!-- Dark Mode Toggle -->
            <button class="btn btn-link text-decoration-none" id="darkModeToggle">
                <i class="bi bi-moon-stars fs-5" style="color: var(--text-main);"></i>
            </button>

            <div class="dropdown">
                <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                    <div id="navbarAvatar" class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold overflow-hidden" style="width: 32px; height: 32px;">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-100 h-100 object-fit-cover">
                        @else
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        @endif
                    </div>
                    <span class="d-none d-sm-block" style="color: var(--text-main);">{{ Auth::user()->name ?? 'User' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg user-dropdown">
                    <li><h6 class="dropdown-header">{{ __('dashboard.signed_in_as') }} {{ Auth::user()->role ?? 'User' }}</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i> {{ __('dashboard.profile') }}</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <button type="button" class="dropdown-item text-danger" onclick="handleLogout(event)"><i class="bi bi-box-arrow-right me-2"></i> {{ __('dashboard.logout') }}</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const topNavbar = document.getElementById('topNavbar');
            const toggleBtn = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');
            const body = document.body;
            
            const darkModeToggle = document.getElementById('darkModeToggle');
            const icon = darkModeToggle.querySelector('i');

            // Dark Mode Logic
            const currentTheme = localStorage.getItem('theme');
            if (currentTheme) {
                document.documentElement.setAttribute('data-theme', currentTheme);
                if (currentTheme === 'dark') {
                    icon.classList.replace('bi-moon-stars', 'bi-sun');
                }
            }

            darkModeToggle.addEventListener('click', () => {
                let theme = 'light';
                if (document.documentElement.getAttribute('data-theme') !== 'dark') {
                    theme = 'dark';
                    icon.classList.replace('bi-moon-stars', 'bi-sun');
                } else {
                    icon.classList.replace('bi-sun', 'bi-moon-stars');
                }
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
            });

            // Check local storage for preference
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed && window.innerWidth >= 992) {
                sidebar.classList.add('collapsed');
                body.classList.add('sidebar-collapsed');
                // Adjust navbar position manually since CSS variable dependency in calc() might lag
                topNavbar.style.left = 'var(--sidebar-collapsed-width)';
            }

            function toggleSidebar() {
                if (window.innerWidth >= 992) {
                    // Desktop: Collapse/Expand
                    sidebar.classList.toggle('collapsed');
                    body.classList.toggle('sidebar-collapsed');
                    
                    const collapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebar-collapsed', collapsed);
                    
                    // Update navbar position
                    if (collapsed) {
                        topNavbar.style.left = 'var(--sidebar-collapsed-width)';
                    } else {
                        topNavbar.style.left = 'var(--sidebar-width)';
                    }
                } else {
                    // Mobile: Show/Hide
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                }
            }

            toggleBtn.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);

            // Handle resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    
                    // Restore collapsed state
                    if (localStorage.getItem('sidebar-collapsed') === 'true') {
                        sidebar.classList.add('collapsed');
                        topNavbar.style.left = 'var(--sidebar-collapsed-width)';
                    } else {
                        sidebar.classList.remove('collapsed');
                        topNavbar.style.left = 'var(--sidebar-width)';
                    }
                } else {
                    sidebar.classList.remove('collapsed');
                    topNavbar.style.left = '0';
                }
            });
        });

        // Global Flash Message Function using SweetAlert2 - Stylish Version
        function showFlashMessage(message, type = 'success') {
            const iconColors = {
                success: '#10b981',
                error: '#ef4444',
                warning: '#f59e0b',
                info: '#0652DD'
            };
            
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                background: '#ffffff',
                color: '#1e293b',
                iconColor: iconColors[type] || iconColors.info,
                customClass: {
                    popup: 'swal2-toast-custom'
                },
                showClass: {
                    popup: 'animate__animated animate__slideInRight animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__slideOutRight animate__faster'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: type,
                title: `<span style="font-weight: 500; font-size: 0.95rem;">${message}</span>`
            });
        }

        // Global Confirm Action Helper - Adaptive Dark/Light Mode
        function confirmAction(message, formId) {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            
            Swal.fire({
                html: `
                    <div style="padding: 20px 10px;">
                        <!-- Animated Warning Icon -->
                        <div style="width: 80px; height: 80px; margin: 0 auto 20px; background: ${isDark ? 'linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.3) 100%)' : 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)'}; border-radius: 50%; display: flex; align-items: center; justify-content: center; position: relative;">
                            <div style="position: absolute; inset: -4px; border: 2px solid ${isDark ? 'rgba(239, 68, 68, 0.3)' : 'rgba(239, 68, 68, 0.2)'}; border-radius: 50%; animation: pulse-ring 1.5s ease-out infinite;"></div>
                            <i class="bi bi-trash3" style="font-size: 2.2rem; color: ${isDark ? '#f87171' : '#dc2626'};"></i>
                        </div>
                        
                        <!-- Title -->
                        <h2 style="font-size: 1.4rem; font-weight: 700; margin: 0 0 12px; color: ${isDark ? '#f1f5f9' : '#1e293b'};">
                            Hapus Item?
                        </h2>
                        
                        <!-- Message -->
                        <p style="color: ${isDark ? '#94a3b8' : '#64748b'}; font-size: 0.95rem; margin: 0; line-height: 1.5;">${message}</p>
                    </div>
                `,
                showCancelButton: true,
                buttonsStyling: false,
                reverseButtons: true,
                focusCancel: true,
                background: isDark ? '#1e293b' : '#ffffff',
                customClass: {
                    popup: isDark ? 'swal-dark-popup rounded-4' : 'swal-light-popup rounded-4',
                    actions: 'swal-actions'
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInDown animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp animate__faster'
                },
                didOpen: () => {
                    // Style confirm button (red/danger)
                    const confirmBtn = Swal.getConfirmButton();
                    confirmBtn.innerHTML = '<i class="bi bi-trash me-2"></i>Ya, Hapus';
                    confirmBtn.style.cssText = 'background-color: #ef4444; color: white; border: none; padding: 12px 28px; border-radius: 50px; font-weight: 600; margin-left: 12px; cursor: pointer; transition: all 0.2s;';
                    confirmBtn.onmouseover = () => { confirmBtn.style.backgroundColor = '#dc2626'; confirmBtn.style.transform = 'translateY(-2px)'; };
                    confirmBtn.onmouseout = () => { confirmBtn.style.backgroundColor = '#ef4444'; confirmBtn.style.transform = 'translateY(0)'; };
                    
                    // Style cancel button (green/success)
                    const cancelBtn = Swal.getCancelButton();
                    cancelBtn.innerHTML = '<i class="bi bi-x-lg me-2"></i>Batal';
                    cancelBtn.style.cssText = 'background-color: #10b981; color: white; border: none; padding: 12px 28px; border-radius: 50px; font-weight: 600; cursor: pointer; transition: all 0.2s;';
                    cancelBtn.onmouseover = () => { cancelBtn.style.backgroundColor = '#059669'; cancelBtn.style.transform = 'translateY(-2px)'; };
                    cancelBtn.onmouseout = () => { cancelBtn.style.backgroundColor = '#10b981'; cancelBtn.style.transform = 'translateY(0)'; };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        html: '<div style="padding: 20px;"><div class="spinner-border text-primary mb-3" role="status"></div><p style="margin: 0; color: ' + (isDark ? '#f1f5f9' : '#1e293b') + ';">Menghapus...</p></div>',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        background: isDark ? '#1e293b' : '#ffffff',
                    });
                    document.getElementById(formId).submit();
                }
            });
            return false;
        }

        // Handle Logout from dropdown button
        function handleLogout(event) {
            event.preventDefault();
            event.stopPropagation();
            showLuxuryLogoutPopup(document.getElementById('logout-form'));
        }

        // Luxury Logout Confirmation (for sidebar)
        function confirmLogout(event) {
            event.preventDefault();
            showLuxuryLogoutPopup(event.target.closest('form'));
        }

        // Luxury Logout Confirmation (for dropdown)
        function confirmLogoutPartial(event) {
            event.preventDefault();
            showLuxuryLogoutPopup(event.target);
        }
        
        // Main Luxury Logout Popup
        function showLuxuryLogoutPopup(form) {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            
            Swal.fire({
                html: `
                    <div style="padding: 30px 20px;">
                        <!-- Animated Icon -->
                        <div class="logout-icon-container">
                            <div class="logout-icon-ring"></div>
                            <div class="logout-icon-ring logout-icon-ring-2"></div>
                            <div class="logout-icon-inner">
                                <i class="bi bi-door-open"></i>
                            </div>
                        </div>
                        
                        <!-- Title -->
                        <h2 style="font-size: 1.75rem; font-weight: 700; margin: 25px 0 12px; color: ${isDark ? '#f1f5f9' : '#1e293b'};">
                            Keluar dari Akun?
                        </h2>
                        
                        <!-- Subtitle -->
                        <p style="color: ${isDark ? '#94a3b8' : '#64748b'}; font-size: 1rem; margin: 0 0 30px; line-height: 1.6;">
                            Sesi Anda akan berakhir dan Anda perlu<br>login kembali untuk mengakses akun.
                        </p>
                        
                        <!-- Buttons -->
                        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                            <button type="button" class="logout-btn-action logout-btn-cancel" onclick="Swal.close()">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </button>
                            <button type="button" class="logout-btn-action logout-btn-logout" id="btn-do-logout">
                                <i class="bi bi-box-arrow-right me-2"></i>Ya, Keluar
                            </button>
                        </div>
                    </div>
                    
                    <style>
                        .logout-icon-container {
                            width: 100px;
                            height: 100px;
                            margin: 0 auto;
                            position: relative;
                        }
                        
                        .logout-icon-ring {
                            position: absolute;
                            width: 100%;
                            height: 100%;
                            border: 3px solid ${isDark ? 'rgba(239, 68, 68, 0.3)' : 'rgba(239, 68, 68, 0.2)'};
                            border-top-color: #ef4444;
                            border-radius: 50%;
                            animation: luxurySpin 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
                        }
                        
                        .logout-icon-ring-2 {
                            width: 80%;
                            height: 80%;
                            top: 10%;
                            left: 10%;
                            border-color: ${isDark ? 'rgba(251, 146, 60, 0.2)' : 'rgba(251, 146, 60, 0.15)'};
                            border-top-color: #fb923c;
                            animation: luxurySpin 2s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite reverse;
                        }
                        
                        .logout-icon-inner {
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            width: 60px;
                            height: 60px;
                            background: ${isDark ? 'linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%)' : 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)'};
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 10px 30px -10px rgba(239, 68, 68, 0.5);
                            animation: luxuryPulse 2s ease-in-out infinite;
                        }
                        
                        .logout-icon-inner i {
                            font-size: 1.75rem;
                            color: ${isDark ? '#fca5a5' : '#dc2626'};
                        }
                        
                        .logout-btn-action {
                            padding: 14px 28px;
                            border-radius: 12px;
                            font-weight: 600;
                            font-size: 0.95rem;
                            cursor: pointer;
                            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            min-width: 140px;
                        }
                        
                        .logout-btn-cancel {
                            background: ${isDark ? 'linear-gradient(135deg, #334155 0%, #1e293b 100%)' : 'linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%)'};
                            color: ${isDark ? '#e2e8f0' : '#475569'};
                            border: 1px solid ${isDark ? '#475569' : '#cbd5e1'};
                        }
                        
                        .logout-btn-cancel:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 8px 25px -5px ${isDark ? 'rgba(0,0,0,0.4)' : 'rgba(0,0,0,0.1)'};
                            background: ${isDark ? 'linear-gradient(135deg, #475569 0%, #334155 100%)' : 'linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%)'};
                        }
                        
                        .logout-btn-logout {
                            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                            color: white;
                            border: none;
                            box-shadow: 0 8px 25px -5px rgba(239, 68, 68, 0.5);
                            position: relative;
                            overflow: hidden;
                        }
                        
                        .logout-btn-logout::before {
                            content: '';
                            position: absolute;
                            top: 0;
                            left: -100%;
                            width: 100%;
                            height: 100%;
                            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                            transition: left 0.5s;
                        }
                        
                        .logout-btn-logout:hover::before {
                            left: 100%;
                        }
                        
                        .logout-btn-logout:hover {
                            transform: translateY(-3px) scale(1.02);
                            box-shadow: 0 12px 35px -5px rgba(239, 68, 68, 0.6);
                        }
                        
                        @keyframes luxurySpin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                        
                        @keyframes luxuryPulse {
                            0%, 100% { transform: translate(-50%, -50%) scale(1); }
                            50% { transform: translate(-50%, -50%) scale(1.05); }
                        }
                    </style>
                `,
                showConfirmButton: false,
                showCancelButton: false,
                background: isDark ? 'linear-gradient(145deg, #1e293b 0%, #0f172a 100%)' : 'linear-gradient(145deg, #ffffff 0%, #f8fafc 100%)',
                customClass: {
                    popup: `swal-logout-luxury ${isDark ? 'swal-logout-luxury-dark' : 'swal-logout-luxury-light'}`
                },
                showClass: {
                    popup: 'animate__animated animate__zoomIn animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut animate__faster'
                },
                didOpen: () => {
                    document.getElementById('btn-do-logout').addEventListener('click', () => {
                        showLuxuryLogoutLoading(isDark, form);
                    });
                }
            });
        }
        
        // Luxury Loading Animation for Logout
        function showLuxuryLogoutLoading(isDark, form) {
            Swal.fire({
                html: `
                    <div style="padding: 40px 20px;">
                        <!-- Animated Loader -->
                        <div class="luxury-loader">
                            <div class="loader-circle"></div>
                            <div class="loader-circle loader-circle-2"></div>
                            <div class="loader-circle loader-circle-3"></div>
                            <div class="loader-icon">
                                <i class="bi bi-person-check"></i>
                            </div>
                        </div>
                        
                        <!-- Text -->
                        <h3 style="font-size: 1.5rem; font-weight: 700; margin: 30px 0 10px; color: ${isDark ? '#f1f5f9' : '#1e293b'};">
                            Sedang Keluar...
                        </h3>
                        <p style="color: ${isDark ? '#94a3b8' : '#64748b'}; font-size: 0.95rem; margin: 0;">
                            Sampai jumpa kembali! 👋
                        </p>
                        
                        <!-- Progress Bar -->
                        <div class="logout-progress-container">
                            <div class="logout-progress-bar"></div>
                        </div>
                    </div>
                    
                    <style>
                        .luxury-loader {
                            width: 120px;
                            height: 120px;
                            margin: 0 auto;
                            position: relative;
                        }
                        
                        .loader-circle {
                            position: absolute;
                            width: 100%;
                            height: 100%;
                            border: 4px solid transparent;
                            border-top-color: #3b82f6;
                            border-radius: 50%;
                            animation: loaderSpin 1s linear infinite;
                        }
                        
                        .loader-circle-2 {
                            width: 85%;
                            height: 85%;
                            top: 7.5%;
                            left: 7.5%;
                            border-top-color: #8b5cf6;
                            animation-duration: 1.5s;
                            animation-direction: reverse;
                        }
                        
                        .loader-circle-3 {
                            width: 70%;
                            height: 70%;
                            top: 15%;
                            left: 15%;
                            border-top-color: #ec4899;
                            animation-duration: 2s;
                        }
                        
                        .loader-icon {
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            width: 50px;
                            height: 50px;
                            background: ${isDark ? 'linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%)' : 'linear-gradient(135deg, #3b82f6 0%, #6366f1 100%)'};
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 10px 30px -10px rgba(59, 130, 246, 0.6);
                            animation: iconPulse 1s ease-in-out infinite;
                        }
                        
                        .loader-icon i {
                            font-size: 1.5rem;
                            color: white;
                        }
                        
                        .logout-progress-container {
                            width: 200px;
                            height: 4px;
                            background: ${isDark ? '#334155' : '#e2e8f0'};
                            border-radius: 4px;
                            margin: 25px auto 0;
                            overflow: hidden;
                        }
                        
                        .logout-progress-bar {
                            height: 100%;
                            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
                            border-radius: 4px;
                            animation: progressAnim 1.2s ease-in-out forwards;
                        }
                        
                        @keyframes loaderSpin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                        
                        @keyframes iconPulse {
                            0%, 100% { transform: translate(-50%, -50%) scale(1); }
                            50% { transform: translate(-50%, -50%) scale(1.1); }
                        }
                        
                        @keyframes progressAnim {
                            0% { width: 0%; }
                            100% { width: 100%; }
                        }
                    </style>
                `,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                background: isDark ? 'linear-gradient(145deg, #1e293b 0%, #0f172a 100%)' : 'linear-gradient(145deg, #ffffff 0%, #f8fafc 100%)',
                customClass: {
                    popup: `swal-logout-luxury ${isDark ? 'swal-logout-luxury-dark' : 'swal-logout-luxury-light'}`
                },
                showClass: {
                    popup: 'animate__animated animate__fadeIn animate__faster'
                }
            });
            
            // Submit form after animation
            setTimeout(() => {
                if (form) form.submit();
            }, 1200);
        }
    </script>
    @yield('scripts')
</body>
</html>
