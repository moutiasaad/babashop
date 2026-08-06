<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/admin/img/icon-lovard.png">
    <title>{{ $product->name }} - ShaiebExpo</title>

    @php
        $hasDiscount = $product->discount_price && $product->discount_price < $product->price;
        $discountPercent = $hasDiscount ? round((($product->price - $product->discount_price) / $product->price) * 100) : 0;
        $productPrice = $hasDiscount ? $product->discount_price : $product->price;

        $primaryImage = null;
        $allImages = [];

        if (!empty($product->gallery)) {
            foreach ($product->gallery as $img) {
                $allImages[] = $img['image_path'];
                if ($img['is_primary']) {
                    $primaryImage = $img['image_path'];
                }
            }
        }

        if (!$primaryImage && !empty($product->image)) {
            $primaryImage = is_array($product->image) ? ($product->image[0] ?? '/admin/img/placeholder.png') : $product->image;
        }

        if (!$primaryImage) {
            $primaryImage = '/admin/img/placeholder.png';
        }

        // Make absolute URL for OG image
        $ogImage = $primaryImage;
        if (!str_starts_with($ogImage, 'http')) {
            $ogImage = url($ogImage);
        }

        $ogDescription = $product->description ? Str::limit(strip_tags($product->description), 160) : 'Decouvrez ' . $product->name . ' sur ShaiebExpo - ' . number_format($productPrice, 2) . ' TND';
    @endphp

    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="product">
    <meta property="og:title" content="{{ $product->name }} - ShaiebExpo">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="ShaiebExpo">
    <meta property="product:price:amount" content="{{ $productPrice }}">
    <meta property="product:price:currency" content="TND">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $product->name }} - ShaiebExpo">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <!-- General Meta Tags -->
    <meta name="description" content="{{ $ogDescription }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #fff;
            color: #1a1a1a;
            line-height: 1.5;
            min-height: 100vh;
        }

        /* App Banner */
        .app-banner {
            background: linear-gradient(135deg, #0778B5 0%, #055a8a 100%);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .app-banner-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .app-banner img {
            width: 36px;
            height: 36px;
            border-radius: 8px;
        }

        .app-banner-text {
            color: white;
        }

        .app-banner-text h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .app-banner-text p {
            font-size: 11px;
            opacity: 0.9;
        }

        .app-banner-btn {
            background: white;
            color: #0778B5;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
        }

        /* Product Image */
        .product-image-section {
            position: relative;
            background: #f5f5f5;
        }

        .main-image {
            width: 100%;
            height: 320px;
            object-fit: cover;
        }

        .discount-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #e74c3c;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        /* Thumbnail Gallery */
        .thumbnail-gallery {
            display: flex;
            gap: 8px;
            padding: 12px 16px;
            overflow-x: auto;
            background: #fff;
        }

        .thumbnail-gallery::-webkit-scrollbar {
            display: none;
        }

        .thumbnail {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid transparent;
            flex-shrink: 0;
            cursor: pointer;
        }

        .thumbnail.active {
            border-color: #0778B5;
        }

        /* Product Info */
        .product-info {
            padding: 16px;
        }

        .product-category {
            display: inline-block;
            background: #e3f2fd;
            color: #0778B5;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .product-name {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .product-price-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .product-price {
            font-size: 24px;
            font-weight: 700;
            color: #27ae60;
        }

        .original-price {
            font-size: 16px;
            color: #999;
            text-decoration: line-through;
        }

        .product-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 16px;
            line-height: 1.6;
        }

        .stock-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }

        .stock-status.in-stock {
            background: #e8f5e9;
            color: #27ae60;
        }

        .stock-status.out-of-stock {
            background: #ffebee;
            color: #e74c3c;
        }

        /* Divider */
        .divider {
            height: 8px;
            background: #f5f5f5;
        }

        /* Quick Order Section */
        .order-section {
            padding: 20px 16px;
        }

        .order-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .order-title i {
            color: #f39c12;
        }

        .order-subtitle {
            font-size: 13px;
            color: #888;
            margin-bottom: 20px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #333;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #0778B5;
        }

        .form-input::placeholder {
            color: #aaa;
        }

        /* Order Summary */
        .order-summary {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 16px;
            font-weight: 700;
            color: #0778B5;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #0778B5 0%, #055a8a 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:disabled {
            opacity: 0.7;
        }

        /* Success Alert */
        .success-alert {
            background: #d4edda;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            display: none;
        }

        .success-alert.show {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            animation: slideDown 0.3s ease;
        }

        .success-alert i {
            color: #27ae60;
            font-size: 24px;
            margin-top: 2px;
        }

        .success-alert h5 {
            font-size: 15px;
            font-weight: 600;
            color: #1e7e34;
            margin-bottom: 4px;
        }

        .success-alert p {
            font-size: 13px;
            color: #155724;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* App Download Section */
        .app-download {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 24px 16px;
            text-align: center;
            margin-top: 20px;
        }

        .app-download h3 {
            color: white;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .app-download p {
            color: rgba(255,255,255,0.7);
            font-size: 13px;
            margin-bottom: 16px;
        }

        .app-buttons {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .app-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: white;
            color: #1a1a1a;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 13px;
        }

        .app-btn i {
            font-size: 22px;
        }

        .app-btn span {
            text-align: left;
        }

        .app-btn small {
            display: block;
            font-size: 10px;
            color: #666;
        }

        .app-btn strong {
            display: block;
            font-weight: 600;
        }

        /* Loading Spinner */
        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <!-- App Banner -->
    <div class="app-banner">
        <div class="app-banner-content">
            <img src="/admin/img/logo_babashop.png" alt="ShaiebExpo">
            <div class="app-banner-text">
                <h4>ShaiebExpo</h4>
                <p>Telecharger l'application</p>
            </div>
        </div>
        <a href="{{ env('APP_STORE_LINK', '#') }}" class="app-banner-btn">
            <i class="fas fa-download"></i> Ouvrir
        </a>
    </div>

    <!-- Product Image -->
    <div class="product-image-section">
        @if($hasDiscount)
            <span class="discount-badge">-{{ $discountPercent }}%</span>
        @endif
        <img src="{{ $primaryImage }}" alt="{{ $product->name }}" class="main-image" id="mainImage">
    </div>

    <!-- Thumbnail Gallery -->
    @if(count($allImages) > 1)
        <div class="thumbnail-gallery">
            @foreach($allImages as $index => $img)
                <img src="{{ $img }}" alt="Image {{ $index + 1 }}"
                     class="thumbnail {{ $index === 0 ? 'active' : '' }}"
                     onclick="changeImage(this, '{{ $img }}')">
            @endforeach
        </div>
    @endif

    <!-- Product Info -->
    <div class="product-info">
        @if($product->category)
            <span class="product-category">{{ $product->category->name }}</span>
        @endif

        <h1 class="product-name">{{ $product->name }}</h1>

        <div class="product-price-row">
            @if($hasDiscount)
                <span class="product-price">{{ number_format($product->discount_price, 2) }} TND</span>
                <span class="original-price">{{ number_format($product->price, 2) }} TND</span>
            @else
                <span class="product-price">{{ number_format($product->price, 2) }} TND</span>
            @endif
        </div>

        @if($product->description)
            <p class="product-description">{!! nl2br(e($product->description)) !!}</p>
        @endif

        @if($product->qty !== null)
            @if($product->qty > 0)
                <span class="stock-status in-stock">
                    <i class="fas fa-check-circle"></i> En stock
                </span>
            @else
                <span class="stock-status out-of-stock">
                    <i class="fas fa-times-circle"></i> Rupture de stock
                </span>
            @endif
        @endif
    </div>

    <!-- Quick Order Section -->
    @if($quickOrderEnabled)
        <div class="divider"></div>

        <div class="order-section">
            <h2 class="order-title">
                <i class="fas fa-bolt"></i> Commande Rapide
            </h2>
            <p class="order-subtitle">Commandez directement depuis cette page</p>

            <!-- Success Alert -->
            <div class="success-alert" id="successAlert">
                <i class="fas fa-check-circle"></i>
                <div>
                    <h5>Commande envoyee!</h5>
                    <p>Numero de commande: <strong id="orderNumber"></strong></p>
                </div>
            </div>

            <!-- Order Form -->
            <form id="quickOrderForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">

                <div class="form-group">
                    <label class="form-label">Nom complet</label>
                    <input type="text" class="form-input" name="fullname" placeholder="Votre nom complet" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Telephone</label>
                    <input type="tel" class="form-input" name="phone" placeholder="Votre numero de telephone" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Adresse de livraison</label>
                    <input type="text" class="form-input" name="address" placeholder="Votre adresse complete" required>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="summary-row">
                        <span>Total</span>
                        <span>{{ number_format($productPrice, 2) }} TND</span>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-paper-plane"></i>
                    Envoyer la commande
                </button>
            </form>
        </div>
    @endif

    <!-- App Download Section -->
    <div class="app-download">
        <h3>Telecharger notre application</h3>
        <p>Pour une meilleure experience d'achat</p>
        <div class="app-buttons">
            <a href="{{ env('PLAY_STORE_LINK', '#') }}" class="app-btn">
                <i class="fab fa-google-play"></i>
                <span>
                    <small>Disponible sur</small>
                    <strong>Google Play</strong>
                </span>
            </a>
            <a href="{{ env('APP_STORE_LINK', '#') }}" class="app-btn">
                <i class="fab fa-apple"></i>
                <span>
                    <small>Telecharger sur</small>
                    <strong>App Store</strong>
                </span>
            </a>
        </div>
    </div>

    <script>
        // Image Gallery
        function changeImage(thumb, src) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        }

        // Form Submit
        document.getElementById('quickOrderForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.getElementById('submitBtn');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<div class="spinner"></div> Envoi...';

            try {
                const response = await fetch('{{ route("quick-order.store") }}', {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('orderNumber').textContent = '#' + data.order_id;
                    document.getElementById('successAlert').classList.add('show');
                    this.reset();
                    document.getElementById('successAlert').scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    alert(data.message || 'Erreur. Veuillez reessayer.');
                }
            } catch (error) {
                alert('Erreur. Veuillez reessayer.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        });
    </script>
</body>

</html>
