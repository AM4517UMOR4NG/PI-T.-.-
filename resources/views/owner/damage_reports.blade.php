@extends('pemilik.layout')

@section('owner_content')
<style>
    /* ===== Perfect Positioning System ===== */
    .damage-report-page {
        padding: 2rem;
        max-width: 1600px;
        margin: 0 auto;
    }
    
    /* Header Section */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-color, #e5e7eb);
    }
    
    .page-header-content h1 {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        color: var(--text-primary, #1e293b);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .page-header-content p {
        margin: 0;
        color: var(--text-secondary, #64748b);
        font-size: 0.95rem;
    }
    
    .header-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    /* Stats Grid - Perfect 4 Column */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .stats-grid-half {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    /* Stat Card */
    .stat-card {
        background: var(--card-bg, #ffffff);
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color, #e5e7eb);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        border-radius: 4px 0 0 4px;
    }
    
    .stat-card.danger::before { background: linear-gradient(180deg, #ef4444, #dc2626); }
    .stat-card.primary::before { background: linear-gradient(180deg, #3b82f6, #2563eb); }
    .stat-card.info::before { background: linear-gradient(180deg, #06b6d4, #0891b2); }
    .stat-card.warning::before { background: linear-gradient(180deg, #f59e0b, #d97706); }
    .stat-card.success::before { background: linear-gradient(180deg, #10b981, #059669); }
    .stat-card.secondary::before { background: linear-gradient(180deg, #6b7280, #4b5563); }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1), 0 8px 24px rgba(0, 0, 0, 0.08);
    }
    
    .stat-content {
        flex: 1;
        min-width: 0;
    }
    
    .stat-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-secondary, #64748b);
        margin-bottom: 0.5rem;
    }
    
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary, #1e293b);
        line-height: 1.2;
    }
    
    .stat-value.success { color: #10b981; }
    .stat-value.danger { color: #ef4444; }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    .stat-icon.danger { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .stat-icon.primary { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .stat-icon.info { background: rgba(6, 182, 212, 0.1); color: #06b6d4; }
    .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-icon.secondary { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
    
    /* Data Table Card */
    .data-card {
        background: var(--card-bg, #ffffff);
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color, #e5e7eb);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .data-card-header {
        padding: 1.5rem 1.75rem;
        border-bottom: 1px solid var(--border-color, #e5e7eb);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .data-card-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .data-card-title h2 {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary, #1e293b);
    }
    
    .data-card-title p {
        margin: 0.25rem 0 0 0;
        font-size: 0.875rem;
        color: var(--text-secondary, #64748b);
    }
    
    .title-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .title-icon.danger { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .title-icon.success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    
    /* Perfect Table Styling */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table thead {
        background: var(--table-header-bg, #f8fafc);
    }
    
    .data-table th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-secondary, #64748b);
        border-bottom: 1px solid var(--border-color, #e5e7eb);
        white-space: nowrap;
    }
    
    .data-table th.text-end { text-align: right; }
    .data-table th.text-center { text-align: center; }
    
    .data-table td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color, #e5e7eb);
        color: var(--text-primary, #1e293b);
    }
    
    .data-table td.text-end { text-align: right; }
    .data-table td.text-center { text-align: center; }
    
    .data-table tbody tr {
        transition: background 0.2s ease;
    }
    
    .data-table tbody tr:hover {
        background: var(--table-hover-bg, #f8fafc);
    }
    
    .data-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Item Cell */
    .item-cell {
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }
    
    .item-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    .item-name {
        font-weight: 600;
        color: var(--text-primary, #1e293b);
    }
    
    /* Customer Cell */
    .customer-cell .name {
        font-weight: 600;
        color: var(--text-primary, #1e293b);
    }
    
    .customer-cell .email {
        font-size: 0.8125rem;
        color: var(--text-secondary, #64748b);
        margin-top: 0.125rem;
    }
    
    /* Badge Styles */
    .type-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        background: var(--badge-bg, #f1f5f9);
        color: var(--text-secondary, #64748b);
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.8125rem;
        font-weight: 600;
    }
    
    .status-badge.success {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }
    
    .status-badge.warning {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }
    
    .status-badge.secondary {
        background: rgba(107, 114, 128, 0.1);
        color: #4b5563;
    }
    
    /* Fine Badge */
    .fine-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }
    
    .fine-amount {
        font-weight: 700;
        color: #ef4444;
        font-size: 1rem;
    }
    
    .fine-amount.large {
        font-size: 1.25rem;
    }
    
    /* Rental ID */
    .rental-id {
        font-family: 'SF Mono', 'Fira Code', monospace;
        font-size: 0.875rem;
        color: var(--text-secondary, #64748b);
        background: var(--code-bg, #f1f5f9);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }
    
    /* Description Cell */
    .description-cell {
        max-width: 220px;
        font-size: 0.875rem;
        color: #ef4444;
        line-height: 1.5;
    }
    
    /* Date Cell */
    .date-cell .date {
        font-weight: 500;
        color: var(--text-primary, #1e293b);
    }
    
    .date-cell .time {
        font-size: 0.8125rem;
        color: var(--text-secondary, #64748b);
        margin-top: 0.125rem;
    }
    
    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }
    
    .empty-state-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: rgba(16, 185, 129, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #10b981;
    }
    
    .empty-state-text {
        font-size: 1rem;
        color: var(--text-secondary, #64748b);
    }
    
    /* Dark Mode */
    [data-theme="dark"] {
        --card-bg: #1e293b;
        --border-color: #334155;
        --text-primary: #f1f5f9;
        --text-secondary: #94a3b8;
        --table-header-bg: #0f172a;
        --table-hover-bg: #334155;
        --badge-bg: #334155;
        --code-bg: #334155;
    }
    
    [data-theme="dark"] .stat-card,
    [data-theme="dark"] .data-card {
        background: var(--card-bg);
        border-color: var(--border-color);
    }
    
    [data-theme="dark"] .stat-value,
    [data-theme="dark"] .item-name,
    [data-theme="dark"] .customer-cell .name,
    [data-theme="dark"] .data-card-title h2,
    [data-theme="dark"] .page-header-content h1 {
        color: var(--text-primary);
    }
    
    /* Responsive */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .damage-report-page {
            padding: 1rem;
        }
        
        .stats-grid,
        .stats-grid-half {
            grid-template-columns: 1fr;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .data-table th,
        .data-table td {
            padding: 1rem;
        }
    }
</style>

<div class="damage-report-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1>
               Laporan Kerusakan
            </h1>
            <p>Monitor barang rusak dan denda kerusakan dari semua penyewaan</p>
        </div>
    </div>

    <!-- Statistics Grid - 4 Columns -->
    <div class="stats-grid">
        <div class="stat-card danger">
            <div class="stat-content">
                <div class="stat-label">Total Barang Rusak</div>
                <div class="stat-value">{{ $stats['total_damaged'] }}</div>
            </div>
            <div class="stat-icon danger">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
        
        <div class="stat-card primary">
            <div class="stat-content">
                <div class="stat-label">Unit PS Rusak</div>
                <div class="stat-value">{{ $stats['unitps_damaged'] }}</div>
            </div>
            <div class="stat-icon primary">
                <i class="bi bi-controller"></i>
            </div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-content">
                <div class="stat-label">Game Rusak</div>
                <div class="stat-value">{{ $stats['games_damaged'] }}</div>
            </div>
            <div class="stat-icon info">
                <i class="bi bi-disc"></i>
            </div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-content">
                <div class="stat-label">Aksesoris Rusak</div>
                <div class="stat-value">{{ $stats['accessories_damaged'] }}</div>
            </div>
            <div class="stat-icon warning">
                <i class="bi bi-headset"></i>
            </div>
        </div>
    </div>

    <!-- Fine Statistics - 2 Columns -->
    <div class="stats-grid-half">
        <div class="stat-card success">
            <div class="stat-content">
                <div class="stat-label">Total Denda Terkumpul</div>
                <div class="stat-value success">Rp {{ number_format($stats['total_fine'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-icon success">
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>
        
        <div class="stat-card secondary">
            <div class="stat-content">
                <div class="stat-label">Denda Pending</div>
                <div class="stat-value">Rp {{ number_format($stats['pending_fine'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-icon secondary">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>
    </div>

    <!-- Damaged Items Table -->
    <div class="data-card">
        <div class="data-card-header">
            <div class="data-card-title">
                <div class="title-icon danger">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div>
                    <h2>Daftar Barang Rusak</h2>
                    <p>Semua item yang dilaporkan rusak dari penyewaan</p>
                </div>
            </div>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Tipe</th>
                    <th>Pelanggan</th>
                    <th>Rental ID</th>
                    <th>Keterangan</th>
                    <th class="text-end">Denda</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($damagedItems as $item)
                    @php
                        $itemName = $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Unknown';
                        $itemType = class_basename($item->rentable_type);
                    @endphp
                    <tr>
                        <td>
                            <div class="item-cell">
                                <div class="item-icon">
                                    @if($itemType == 'UnitPS')
                                        <i class="bi bi-controller"></i>
                                    @elseif($itemType == 'Game')
                                        <i class="bi bi-disc"></i>
                                    @else
                                        <i class="bi bi-headset"></i>
                                    @endif
                                </div>
                                <span class="item-name">{{ $itemName }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="type-badge">{{ $itemType }}</span>
                        </td>
                        <td>
                            <div class="customer-cell">
                                <div class="name">{{ $item->rental->customer->name ?? 'Unknown' }}</div>
                                <div class="email">{{ $item->rental->customer->email ?? '-' }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="rental-id">#{{ $item->rental_id }}</span>
                        </td>
                        <td>
                            <div class="description-cell">
                                {{ $item->fine_description ?? 'Tidak ada keterangan' }}
                            </div>
                        </td>
                        <td class="text-end">
                            @if($item->fine > 0)
                                <span class="fine-amount">Rp {{ number_format($item->fine, 0, ',', '.') }}</span>
                            @else
                                <span class="fine-badge">Belum ditentukan</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->rental->status === 'selesai')
                                <span class="status-badge success">
                                    <i class="bi bi-check-circle"></i> Selesai
                                </span>
                            @elseif($item->rental->status === 'menunggu_konfirmasi')
                                <span class="status-badge warning">
                                    <i class="bi bi-clock"></i> Menunggu
                                </span>
                            @else
                                <span class="status-badge secondary">
                                    {{ ucfirst(str_replace('_', ' ', $item->rental->status)) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="empty-state-text">Tidak ada barang rusak saat ini</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Rentals with Fines -->
    @if($rentalsWithFines->count() > 0)
    <div class="data-card">
        <div class="data-card-header">
            <div class="data-card-title">
                <div class="title-icon success">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div>
                    <h2>Riwayat Denda Kerusakan</h2>
                    <p>Semua rental yang memiliki denda kerusakan</p>
                </div>
            </div>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID Rental</th>
                    <th>Pelanggan</th>
                    <th>Item Rusak</th>
                    <th>Tanggal</th>
                    <th class="text-end">Total Denda</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rentalsWithFines as $rental)
                    @php
                        $damagedItemsList = $rental->items->where('condition', 'rusak')->map(function($item) {
                            return $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Unknown';
                        })->implode(', ');
                        if(empty($damagedItemsList)) {
                            $damagedItemsList = $rental->items->first()->rentable->nama ?? $rental->items->first()->rentable->judul ?? 'Unknown';
                        }
                    @endphp
                    <tr>
                        <td>
                            <span class="rental-id">#{{ $rental->id }}</span>
                        </td>
                        <td>
                            <div class="customer-cell">
                                <div class="name">{{ $rental->customer->name ?? 'Unknown' }}</div>
                                <div class="email">{{ $rental->customer->phone ?? '-' }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="description-cell" style="color: var(--text-primary);">
                                {{ $damagedItemsList }}
                            </div>
                        </td>
                        <td>
                            <div class="date-cell">
                                <div class="date">{{ $rental->updated_at->format('d M Y') }}</div>
                                <div class="time">{{ $rental->updated_at->format('H:i') }}</div>
                            </div>
                        </td>
                        <td class="text-end">
                            <span class="fine-amount large">Rp {{ number_format($rental->fine, 0, ',', '.') }}</span>
                        </td>
                        <td class="text-center">
                            @if($rental->status === 'selesai')
                                <span class="status-badge success">
                                    <i class="bi bi-check-circle"></i> Selesai
                                </span>
                            @elseif($rental->status === 'menunggu_konfirmasi')
                                <span class="status-badge warning">
                                    <i class="bi bi-clock"></i> Pending
                                </span>
                            @else
                                <span class="status-badge secondary">
                                    {{ ucfirst($rental->status) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
