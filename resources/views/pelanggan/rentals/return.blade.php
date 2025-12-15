@extends('pelanggan.layout')

@section('pelanggan_content')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.95);
        --glass-border: rgba(226, 232, 240, 0.8);
        --glass-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    [data-theme="dark"] {
        --glass-bg: rgba(30, 41, 59, 0.95);
        --glass-border: rgba(255, 255, 255, 0.1);
        --glass-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        --primary: #3b82f6;
        --primary-rgb: 59, 130, 246;
        --bs-primary: #3b82f6;
        --bs-primary-rgb: 59, 130, 246;
    }

    [data-theme="dark"] .text-primary {
        color: #3b82f6 !important;
    }

    [data-theme="dark"] .border-primary {
        border-color: #3b82f6 !important;
    }

    [data-theme="dark"] .bg-primary {
        background-color: #3b82f6 !important;
    }

    .glass-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        box-shadow: var(--glass-shadow);
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .condition-option {
        border: 2px solid var(--glass-border);
        border-radius: 12px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        background: transparent;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .condition-option:hover {
        border-color: var(--primary);
        background: rgba(var(--primary-rgb), 0.02);
    }

    .condition-option.selected {
        border-color: var(--primary);
        background: rgba(var(--primary-rgb), 0.05);
        position: relative;
    }

    .condition-option.selected::after {
        content: '\F26E'; /* bootstrap icon check-circle-fill */
        font-family: 'bootstrap-icons';
        position: absolute;
        top: 10px;
        right: 10px;
        color: var(--primary);
        font-size: 1.2rem;
    }

    .condition-radio {
        display: none;
    }

    .fine-section {
        display: none;
        animation: slideDown 0.3s ease-out;
    }

    .fine-section.show {
        display: block;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .photo-upload-box {
        position: relative;
        border: 2px dashed var(--glass-border);
        border-radius: 12px;
        aspect-ratio: 1;
        transition: all 0.2s ease;
        background: rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .photo-upload-box:hover {
        border-color: var(--primary);
        background: rgba(var(--primary-rgb), 0.05);
    }

    .photo-upload-box.has-photo {
        border-style: solid;
        border-color: var(--success);
    }

    .photo-input {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }

    .photo-label {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        pointer-events: none;
        transition: opacity 0.2s;
    }

    .photo-preview {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        z-index: 1;
    }

    .photo-upload-box.has-photo .photo-label {
        opacity: 0;
    }
    
    .photo-upload-box.has-photo:hover .photo-label {
        opacity: 1;
        background: rgba(0,0,0,0.6);
        color: white;
        z-index: 2;
    }

    .btn-remove-photo {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #ef4444;
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 20;
        transition: all 0.2s;
        padding: 0;
    }

    .btn-remove-photo:hover {
        background: #dc2626;
        transform: scale(1.1);
    }

    /* Clean typography colors */
    .text-primary-soft { color: var(--primary); }
    .bg-primary-soft { background-color: rgba(var(--primary-rgb), 0.1); }

    /* Dark Mode Specific Overrides */
    [data-theme="dark"] .damage-section-title {
        color: #f3f4f6 !important;
    }
    [data-theme="dark"] .damage-label {
        color: #d1d5db !important;
    }
    [data-theme="dark"] .damage-notes {
        background-color: #1f2937 !important;
        color: #f3f4f6 !important;
        border-color: #374151;
    }
    [data-theme="dark"] .damage-notes::placeholder {
        color: #9ca3af;
    }
    [data-theme="dark"] .photo-label span {
        color: #d1d5db !important;
    }
    [data-theme="dark"] .photo-upload-box {
        background: rgba(255,255,255,0.05);
        border-color: rgba(255,255,255,0.2);
    }
    [data-theme="dark"] .photo-upload-box:hover {
        border-color: var(--primary);
        background: rgba(var(--primary-rgb), 0.1);
    }

    [data-theme="dark"] .damage-report-card {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    .damage-report-card {
        background-color: #f8f9fa !important; /* Light gray for light mode */
        border: 1px solid #dee2e6;
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold display-6 mb-1">Pengembalian Barang</h2>
            <p class="text-muted mb-0">Konfirmasi kondisi barang sebelum dikembalikan.</p>
        </div>
        <a href="{{ route('pelanggan.rentals.show', $rental) }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Info Card -->
    <div class="glass-card p-4 mb-5 border-start border-4 border-primary">
        <div class="d-flex gap-3">
            <div class="flex-shrink-0">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-info-lg fs-4"></i>
                </div>
            </div>
            <div>
                <h5 class="fw-bold mb-1">Panduan Pengembalian</h5>
                <p class="text-muted mb-0">
                    Mohon periksa kondisi barang dengan teliti. Pilih kondisi <strong>BAIK</strong> jika barang berfungsi normal. 
                    Pilih <strong>RUSAK</strong> jika terdapat kerusakan fisik atau fungsional, dan lampirkan foto bukti kerusakan.
                </p>
            </div>
        </div>
    </div>

    <form action="{{ route('pelanggan.rentals.process-return', $rental) }}" method="POST" id="returnForm" enctype="multipart/form-data">
        @csrf
        
        <div class="row g-4">
            <div class="col-lg-8">
                @foreach($rental->items as $index => $item)
                    @php
                        $itemName = 'Item Tidak Ditemukan';
                        $itemImage = null;
                        if($item->rentable) {
                            $itemName = $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Unknown';
                            $imgField = str_contains($item->rentable_type, 'UnitPS') ? 'foto' : 'gambar';
                            $itemImage = $item->rentable->$imgField ?? null;
                        }
                        $itemType = class_basename($item->rentable_type);
                    @endphp

                    <div class="glass-card mb-4 overflow-hidden">
                        <div class="p-4 border-bottom border-light bg-light bg-opacity-50">
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-shrink-0">
                                    @if($itemImage)
                                        <img src="{{ str_starts_with($itemImage, 'http') ? $itemImage : asset('storage/' . $itemImage) }}" 
                                             class="rounded-3 object-fit-cover border" 
                                             style="width: 64px; height: 64px;" 
                                             alt="{{ $itemName }}">
                                    @else
                                        <div class="rounded-3 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center text-secondary" style="width: 64px; height: 64px;">
                                            <i class="bi bi-box fs-3"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">{{ $itemName }}</h5>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border">{{ $itemType }}</span>
                                        <span class="text-muted small">Qty: {{ $item->quantity }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <h6 class="fw-bold mb-3 text-muted text-uppercase small ls-1">Pilih Kondisi Barang</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="condition-option selected" id="opt-baik-{{ $item->id }}">
                                        <input type="radio" name="items[{{ $item->id }}][condition]" value="baik" class="condition-radio" data-item-id="{{ $item->id }}" checked>
                                        <i class="bi bi-check-circle display-6 mb-2" style="color: #059669 !important;"></i>
                                        <span class="fw-bold d-block mb-1">Kondisi Baik</span>
                                        <span class="small text-muted">Barang berfungsi normal tanpa kerusakan</span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="condition-option" id="opt-rusak-{{ $item->id }}">
                                        <input type="radio" name="items[{{ $item->id }}][condition]" value="rusak" class="condition-radio" data-item-id="{{ $item->id }}">
                                        <i class="bi bi-exclamation-triangle display-6 mb-2" style="color: #f59e0b !important;"></i>
                                        <span class="fw-bold d-block mb-1">Ada Kerusakan</span>
                                        <span class="small text-muted">Terdapat kerusakan fisik atau fungsi</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Damage Report Section -->
                            <div class="fine-section" id="fine-section-{{ $item->id }}">
                                <div class="rounded-3 p-4 damage-report-card">
                                    <h6 class="fw-bold mb-3 damage-section-title" style="color: #374151;"><i class="bi bi-camera me-2"></i>Laporan Kerusakan</h6>
                                    
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold damage-label" style="color: #4b5563;">Deskripsi Kerusakan</label>
                                        <textarea name="items[{{ $item->id }}][damage_notes]" class="form-control damage-notes" rows="3" placeholder="Jelaskan detail kerusakan yang terjadi..."></textarea>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold mb-3 damage-label" style="color: #4b5563;">Bukti Foto (Wajib 6 Sisi)</label>
                                        <div class="row g-3">
                                            @foreach(['top' => 'Atas', 'bottom' => 'Bawah', 'front' => 'Depan', 'back' => 'Belakang', 'left' => 'Kiri', 'right' => 'Kanan'] as $key => $label)
                                                <div class="col-4 col-md-2">
                                                    <div class="photo-upload-box" data-item="{{ $item->id }}">
                                                        <input type="file" name="items[{{ $item->id }}][photos][{{ $key }}]" class="photo-input" accept="image/*">
                                                        <button type="button" class="btn-remove-photo" style="display: none;">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                        <div class="photo-label">
                                                            <i class="bi bi-camera mb-1"></i>
                                                            <span style="font-size: 0.7rem; color: #4b5563;">{{ $label }}</span>
                                                        </div>
                                                        <div class="photo-preview"></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <input type="hidden" name="items[{{ $item->id }}][fine_description]" value="Menunggu pengecekan admin">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-lg-4">
                <div class="glass-card p-4 sticky-top" style="top: 2rem;">
                    <h5 class="fw-bold mb-4">Ringkasan</h5>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Kode Rental</span>
                        <span class="fw-bold font-monospace">{{ $rental->kode ?? '#'.$rental->id }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Item</span>
                        <span class="fw-bold">{{ $rental->items->count() }} Item</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-muted">Tanggal Sewa</span>
                        <span class="fw-bold">{{ $rental->created_at->format('d M Y') }}</span>
                    </div>

                    <hr class="border-light my-4">

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                        Konfirmasi Pengembalian
                    </button>
                    <p class="text-center small text-muted mt-3 mb-0">
                        Pastikan semua data sudah benar sebelum konfirmasi.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle Condition Selection
    const radios = document.querySelectorAll('.condition-radio');
    
    function updateConditionUI(itemId, condition) {
        const optBaik = document.getElementById('opt-baik-' + itemId);
        const optRusak = document.getElementById('opt-rusak-' + itemId);
        const fineSection = document.getElementById('fine-section-' + itemId);
        
        // Reset classes
        optBaik.classList.remove('selected');
        optRusak.classList.remove('selected');
        
        if (condition === 'baik') {
            optBaik.classList.add('selected');
            fineSection.classList.remove('show');
        } else {
            optRusak.classList.add('selected');
            fineSection.classList.add('show');
        }
    }

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            updateConditionUI(this.dataset.itemId, this.value);
        });
        
        // Init state
        if(radio.checked) {
            updateConditionUI(radio.dataset.itemId, radio.value);
        }
    });

    // Handle Photo Preview
    const photoInputs = document.querySelectorAll('.photo-input');
    photoInputs.forEach(input => {
        input.addEventListener('change', function() {
            const box = this.closest('.photo-upload-box');
            const preview = box.querySelector('.photo-preview');
            
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.style.backgroundImage = `url('${e.target.result}')`;
                    box.classList.add('has-photo');
                    const removeBtn = box.querySelector('.btn-remove-photo');
                    if(removeBtn) removeBtn.style.display = 'flex';
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                preview.style.backgroundImage = '';
                box.classList.remove('has-photo');
                const removeBtn = box.querySelector('.btn-remove-photo');
                if(removeBtn) removeBtn.style.display = 'none';
            }
        });
    });

    // Handle Photo Removal
    document.querySelectorAll('.btn-remove-photo').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent triggering file input
            
            const box = this.closest('.photo-upload-box');
            const input = box.querySelector('.photo-input');
            const preview = box.querySelector('.photo-preview');
            
            input.value = ''; // Clear input
            preview.style.backgroundImage = '';
            box.classList.remove('has-photo');
            this.style.display = 'none';
        });
    });

    // Form Validation
    document.getElementById('returnForm').addEventListener('submit', function(e) {
        const rusakItems = document.querySelectorAll('.condition-radio[value="rusak"]:checked');
        let isValid = true;
        let errorMsg = '';

        rusakItems.forEach(radio => {
            const itemId = radio.dataset.itemId;
            const fineSection = document.getElementById('fine-section-' + itemId);
            const note = fineSection.querySelector('.damage-notes').value.trim();
            const photos = fineSection.querySelectorAll('.photo-input');
            let photoCount = 0;
            
            photos.forEach(p => { if(p.files.length > 0) photoCount++; });

            if (!note) {
                isValid = false;
                errorMsg = 'Mohon isi deskripsi kerusakan.';
            }
            if (photoCount < 6) {
                isValid = false;
                errorMsg = 'Mohon lengkapi 6 foto bukti kerusakan.';
            }
        });

        if (!isValid) {
            e.preventDefault();
            // Use SweetAlert if available, else standard alert
            if(typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Data Belum Lengkap',
                    text: errorMsg,
                    confirmButtonColor: '#3b82f6'
                });
            } else {
                alert(errorMsg);
            }
        } else {
            if(!confirm('Apakah Anda yakin data sudah benar?')) {
                e.preventDefault();
            }
        }
    });
});
</script>
@endsection

