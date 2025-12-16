@extends('pelanggan.layout')

@section('pelanggan_content')
    <div class="container-fluid">
        <!-- Search & Filter Section -->
        <div class="card card-hover-lift mb-4 animate-fade-in">
            <div class="card-body">
                <form method="GET" action="{{ route('pelanggan.games.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small text-uppercase fw-bold text-muted">{{ __('catalog.platform') }}</label>
                        <select name="platform" class="form-select">
                            <option value="">{{ __('catalog.all_platforms') }}</option>
                            @foreach (['PS3', 'PS4', 'PS5'] as $opt)
                                <option value="{{ $opt }}" @selected(request('platform') === $opt)>{{ $opt }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-uppercase fw-bold text-muted">{{ __('catalog.genre') }}</label>
                        <input type="text" name="genre" value="{{ request('genre') }}" class="form-control"
                            placeholder="{{ __('catalog.genre_placeholder') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-uppercase fw-bold text-muted">{{ __('catalog.search_game') }}</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                                placeholder="{{ __('catalog.search_game_placeholder') }}">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">{{ __('catalog.filter') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Games List -->
        <div class="card">
            <div class="card-body">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
                    @forelse($games as $game)
                        <div class="col">
                            @php
                                $platformKey = $game->platform ?? 'PS4';
                            @endphp
                            <div class="card h-100 shadow-sm position-relative card-blue-left" style="border-radius: 16px;">
                                <!-- Platform Badge -->
                                <div class="position-absolute" style="top: 12px; left: 12px; z-index: 10;">
                                    <span class="d-flex align-items-center justify-content-center fw-bold" style="background: #0652DD; color: #fff; width: 44px; height: 44px; border-radius: 50%; font-size: 0.7rem; box-shadow: 0 3px 10px rgba(6,82,221,0.4);">
                                        {{ $platformKey }}
                                    </span>
                                </div>
                                <!-- Best Seller Badge -->
                                @if(in_array($game->id, $topSellingIds ?? []))
                                <div class="position-absolute" style="top: 12px; right: 12px; z-index: 10;">
                                    <span class="badge d-flex align-items-center gap-1 px-2 py-1" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #fff; font-size: 0.7rem; border-radius: 20px; box-shadow: 0 2px 8px rgba(245, 158, 11, 0.4);">
                                        <i class="bi bi-fire"></i> Paling Laku
                                    </span>
                                </div>
                                @endif
                                <div class="position-relative"
                                    style="height: 200px; overflow: hidden; border-radius: 0;">
                                    @if ($game->gambar)
                                        <img src="{{ str_starts_with($game->gambar, 'http') ? $game->gambar : asset('storage/' . $game->gambar) }}"
                                            alt="{{ $game->judul }}" class="w-100 h-100 object-fit-cover"
                                            style="transition: transform 0.3s ease;">
                                    @else
                                        <img src="https://placehold.co/300x400/F5F6FA/222222?text={{ urlencode($game->judul) }}"
                                            alt="{{ $game->judul }}" class="w-100 h-100 object-fit-cover"
                                            style="transition: transform 0.3s ease;">
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="text-center mb-3">
                                        <h5 class="card-title fw-bold mb-1 text-truncate text-brand-main">{{ $game->judul }}</h5>
                                        <p class="mb-1 text-muted fw-bold">
                                            {{ $game->platform }} • {{ $game->genre }}</p>
                                        @php
                                            $stok = $game->stok ?? 0;
                                        @endphp
                                        @if ($stok > 0)
                                            <div class="mb-2 text-success fw-bold small">
                                                <i class="bi bi-check-circle-fill me-1"></i>{{ __('catalog.available') }} {{ $stok }}
                                            </div>
                                        @else
                                            <div class="mb-2 text-danger fw-bold small">
                                                <i class="bi bi-x-circle-fill me-1"></i>{{ __('catalog.out_of_stock') }}
                                            </div>
                                        @endif
                                        <div class="fw-bold fs-5 text-brand-main">Rp
                                            {{ number_format($game->harga_per_hari, 0, ',', '.') }}<span
                                                class="small fw-normal text-muted ms-1">{{ __('catalog.per_day') }}</span></div>
                                    </div>
                                    <div class="mt-auto">
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary add-to-cart-btn"
                                                data-type="game"
                                                data-id="{{ $game->id }}"
                                                data-name="{{ $game->judul }}"
                                                data-price="{{ $game->harga_per_hari }}"
                                                data-price_type="per_hari">
                                                <i class="bi bi-cart"></i>
                                            </button>
                                            <a href="{{ route('pelanggan.rentals.create') }}?type=game&id={{ $game->id }}" class="btn btn-sm btn-primary flex-grow-1">
                                                {{ __('catalog.rent') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-disc display-1 text-muted"></i>
                                <p class="mt-3 mb-0 text-muted">{{ __('catalog.no_games') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer border-top-0 bg-transparent py-3">
                {{ $games->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <script>
        // Handle add to cart AJAX requests for dashboard items
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function() {
                const type = this.getAttribute('data-type');
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const price = parseFloat(this.getAttribute('data-price'));
                const price_type = this.getAttribute('data-price_type');

                // Validate data
                if(!type || !id || !name || isNaN(price)) {
                    alert('Data item tidak lengkap');
                    return;
                }

                // Disable button to prevent multiple clicks
                this.disabled = true;
                const originalHTML = this.innerHTML;
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

                fetch('{{ route("pelanggan.cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        type: type,
                        id: id,
                        quantity: 1, // Default quantity
                        price_type: price_type
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Show success message using global function if available
                        if(window.showFlashMessage) {
                            window.showFlashMessage(data.message, 'success');
                        } else {
                            alert(data.message);
                        }
                    } else {
                        // Show error message
                        if(window.showFlashMessage) {
                            window.showFlashMessage(data.message || 'Gagal menambahkan ke keranjang', 'error');
                        } else {
                            alert(data.message || 'Gagal menambahkan ke keranjang');
                        }
                    }

                    // Restore button
                    this.disabled = false;
                    this.innerHTML = originalHTML;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambahkan ke keranjang');
                    // Restore button
                    this.disabled = false;
                    this.innerHTML = originalHTML;
                });
            });
        });
    </script>
@endsection
