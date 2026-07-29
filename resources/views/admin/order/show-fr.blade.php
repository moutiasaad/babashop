<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Shaieb Store - Commande #{{ $order->id }}</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/admin/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="/admin/css/uicons-brands.css">
    <link rel="stylesheet" href="/admin/css/style.css">
</head>

<body>
    @include('admin.layouts.sidebar-fr')
    @include('admin.layouts.header-fr')

    <!-- Main Content -->
    <main class="main">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <section class="mb_16">
            <div class="container-fluid px-0">
                <!-- Header -->
                <div class="row g_24 mb_24">
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary mb-2">
                                    <i class="fi fi-rr-arrow-left"></i> Retour
                                </a>
                                <h2 class="fs_24 fw-bold mb-0">Commande #{{ $order->id }}</h2>
                                <p class="text-muted mb-0">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div>
                                @php
                                    $statusBadges = [0 => 'secondary', 1 => 'info', 2 => 'primary', 3 => 'warning', 4 => 'success', 5 => 'danger'];
                                    $badgeColor = $statusBadges[$order->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badgeColor }} fs_16 p-2">{{ $order->status_text }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g_24">
                    <!-- Left Column -->
                    <div class="col-lg-8">
                        <!-- Client Info -->
                        <div class="bg-white rounded_16 p_24 mb_24">
                            <h3 class="fs_20 mb_16">Informations client</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nom:</strong> {{ $order->fullname ?? '-' }}</p>
                                    <p><strong>Téléphone:</strong> {{ $order->phone ?? '-' }}</p>
                                    @if($order->user)
                                        <p><strong>Email:</strong> {{ $order->user->email ?? '-' }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Adresse:</strong></p>
                                    <p class="text-muted">{{ $order->address ?? '-' }}</p>
                                    @if($order->latitude && $order->longitude)
                                        <a href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fi fi-rr-marker"></i> Voir sur la carte
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @if($order->client_note)
                                <div class="mt-3">
                                    <p><strong>Note du client:</strong></p>
                                    <div class="alert alert-info">{{ $order->client_note }}</div>
                                </div>
                            @endif
                        </div>

                        <!-- Order Items -->
                        <div class="bg-white rounded_16 p_24 mb_24">
                            <div class="d-flex align-items-center justify-content-between mb_16">
                                <h3 class="fs_20 mb-0">Articles commandés</h3>
                                @if($order->orderLines)
                                    <span class="badge bg-secondary">{{ $order->orderLines->count() }} article{{ $order->orderLines->count() > 1 ? 's' : '' }}</span>
                                @endif
                            </div>

                            @php
                                $lines = $order->orderLines && $order->orderLines->count() > 0
                                    ? $order->orderLines
                                    : collect();

                                // fallback to legacy product field if no lines
                                $hasLines = $lines->count() > 0;
                            @endphp

                            @if($hasLines)
                                <div class="d-flex flex-column gap-3">
                                @foreach($lines as $index => $line)
                                    @php
                                        $product   = $line->product;
                                        $images    = $product?->image ?? [];
                                        $imgUrl    = (is_array($images) && count($images) > 0)
                                                        ? $images[0]
                                                        : (is_string($images) && $images ? $images : null);
                                        $options   = $line->selected_options ?? [];
                                        if (is_string($options)) $options = json_decode($options, true) ?? [];
                                    @endphp
                                    <div class="border rounded-3 overflow-hidden" style="border-color:#e9ecef!important;">
                                        <div class="d-flex">
                                            {{-- Product image --}}
                                            <div class="flex-shrink-0" style="width:110px;min-height:110px;background:#f8f9fa;">
                                                @if($imgUrl)
                                                    <img src="{{ $imgUrl }}"
                                                         alt="{{ $product?->name }}"
                                                         style="width:110px;height:110px;object-fit:cover;display:block;">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100" style="width:110px;height:110px;">
                                                        <i class="fi fi-rr-picture" style="font-size:32px;color:#ced4da;"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Product details --}}
                                            <div class="flex-grow-1 p-3">
                                                <div class="d-flex justify-content-between align-items-start gap-2">
                                                    <div class="flex-grow-1">
                                                        <h6 class="fw-bold mb-1" style="font-size:15px;">
                                                            {{ $product?->name ?? 'Produit supprimé' }}
                                                        </h6>
                                                        @if($product?->sku)
                                                            <p class="text-muted mb-1" style="font-size:12px;">
                                                                SKU: {{ $product->sku }}
                                                            </p>
                                                        @endif

                                                        {{-- Options --}}
                                                        @if(!empty($options))
                                                            <div class="d-flex flex-wrap gap-1 mb-2">
                                                                @foreach($options as $optName => $optValue)
                                                                    <span style="display:inline-flex;align-items:center;gap:4px;background:#f0f3ff;color:#3d5af1;border:1px solid #c5cfff;border-radius:20px;padding:2px 10px;font-size:11px;font-weight:500;">
                                                                        <span style="color:#888;font-weight:400;">{{ $optName }}</span>
                                                                        <span style="color:#3d5af1;">{{ $optValue }}</span>
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        {{-- Qty + unit price --}}
                                                        <div class="d-flex align-items-center gap-3 mt-1">
                                                            <span class="text-muted" style="font-size:13px;">
                                                                Qté: <strong class="text-dark">{{ $line->qty }}</strong>
                                                            </span>
                                                            <span class="text-muted" style="font-size:13px;">
                                                                Prix unitaire: <strong class="text-dark">{{ number_format($line->unit_price, 2) }} DT</strong>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    {{-- Line total --}}
                                                    <div class="text-end flex-shrink-0">
                                                        <span class="fw-bold" style="font-size:16px;color:#3d5af1;">
                                                            {{ number_format($line->price, 2) }} DT
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>

                            @elseif($order->product)
                                {{-- Legacy single-product fallback --}}
                                @php
                                    $images = $order->product->image ?? [];
                                    $imgUrl = (is_array($images) && count($images) > 0) ? $images[0] : null;
                                @endphp
                                <div class="border rounded-3 overflow-hidden">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0" style="width:110px;height:110px;background:#f8f9fa;">
                                            @if($imgUrl)
                                                <img src="{{ $imgUrl }}" alt="{{ $order->product->name }}" style="width:110px;height:110px;object-fit:cover;display:block;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center h-100">
                                                    <i class="fi fi-rr-picture" style="font-size:32px;color:#ced4da;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 p-3">
                                            <h6 class="fw-bold mb-1">{{ $order->product->name }}</h6>
                                            <p class="text-muted mb-0" style="font-size:13px;">
                                                Qté: {{ $order->quantity }} &nbsp;|&nbsp;
                                                Prix unitaire: {{ $order->unit_price }} DT
                                            </p>
                                        </div>
                                        <div class="p-3 text-end">
                                            <span class="fw-bold" style="font-size:16px;color:#3d5af1;">{{ $order->total_price }} DT</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">Aucun article trouvé.</p>
                            @endif

                            <!-- Price Summary -->
                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Sous-total</span>
                                    <span>{{ number_format($order->total_price, 2) }} DT</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Livraison</span>
                                    <span>{{ number_format($order->delivery_cost ?? 0, 2) }} DT</span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold fs_18 border-top pt-2 mt-2">
                                    <span>Total à payer</span>
                                    <span style="color:#3d5af1;">{{ number_format($order->total_net_a_pay, 2) }} DT</span>
                                </div>
                            </div>
                        </div>

                        @if($order->admin_note)
                            <div class="bg-white rounded_16 p_24 mb_24">
                                <h3 class="fs_20 mb_16">Notes administratives</h3>
                                <div class="alert alert-secondary">{{ $order->admin_note }}</div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-4">
                        <!-- Order Status -->
                        <div class="bg-white rounded_16 p_24 mb_24">
                            <h3 class="fs_20 mb_16">Statut</h3>
                            <form id="statusForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Statut actuel</label>
                                    <select name="status" class="form-select" id="orderStatus">
                                        <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>En attente</option>
                                        <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>Confirmée</option>
                                        <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>En préparation</option>
                                        <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>En livraison</option>
                                        <option value="4" {{ $order->status == 4 ? 'selected' : '' }}>Livrée</option>
                                        <option value="5" {{ $order->status == 5 ? 'selected' : '' }}>Annulée</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Note</label>
                                    <textarea name="admin_note" class="form-control" rows="3">{{ $order->admin_note }}</textarea>
                                </div>
                                <button type="submit" class="btn btn_primary w-100">Mettre à jour</button>
                            </form>
                        </div>

                        <!-- Payment Status -->
                        <div class="bg-white rounded_16 p_24 mb_24">
                            <h3 class="fs_20 mb_16">Paiement</h3>
                            <form id="paymentForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Statut</label>
                                    <select name="is_paid" class="form-select">
                                        <option value="0" {{ $order->is_paid == 0 ? 'selected' : '' }}>Non payée</option>
                                        <option value="1" {{ $order->is_paid == 1 ? 'selected' : '' }}>Payée</option>
                                    </select>
                                </div>
                                @if($order->payment_note)
                                    <div class="mb-3">
                                        <p class="mb-1"><strong>Note:</strong></p>
                                        <p class="text-muted">{{ $order->payment_note }}</p>
                                    </div>
                                @endif
                                <button type="submit" class="btn btn_primary w-100">Mettre à jour</button>
                            </form>
                        </div>

                        <!-- Merchant -->
                        @if($order->merchant)
                            <div class="bg-white rounded_16 p_24 mb_24">
                                <h3 class="fs_20 mb_16">Boutique</h3>
                                <div class="d-flex align-items-center gap-3">
                                    @if($order->merchant->image)
                                        <img src="/{{ $order->merchant->image }}" alt="{{ $order->merchant->brand_name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                    @endif
                                    <div>
                                        <h5 class="mb-0">{{ $order->merchant->brand_name }}</h5>
                                        @if($order->merchant->whatsapp_number)
                                            <a href="https://wa.me/{{ $order->merchant->whatsapp_number }}" target="_blank" class="text-success">
                                                <i class="fi fi-brands-whatsapp"></i> WhatsApp
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- scripts -->
    <script src="/admin/js/jquery-3.7.0.min.js"></script>
    <script src="/admin/js/bootstrap.bundle.min.js"></script>
    <script src="/admin/js/main.js"></script>

    <script>
        $('#statusForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '/dashboard/orders/{{ $order->id }}/status',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.message || 'Statut mis à jour');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Erreur lors de la mise à jour');
                }
            });
        });

        $('#paymentForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '/dashboard/orders/{{ $order->id }}/payment',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.message || 'Paiement mis à jour');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Erreur');
                }
            });
        });
    </script>

</body>

</html>
