<header class="header">
    <div class="header_menu">
        <div class="header_top border-bottom px_16" style="background:#eff3fd;">
            <div class="py_8 text-center">
                <a class="d-inline-block" href="#">
                    <h3 style="color:#B4442A; margin: 0; font-weight: 600;">Babashop</h3>
                </a>
            </div>
            <button class="btn header_tglBtn fs_16 d-xl-none" type="button">
                <i class="fi fi-rr-cross"></i>
            </button>
        </div>
        <div class="header_main" data-scrollbar>
            <ul class="header_nav">
                <!-- Dashboard -->
                @if(auth()->guard('admin')->user()->hasPermission('view_dashboard'))
                <li class="header_navItem">
                    <a class="header_navLink {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                        <i class="fi fi-rr-home"></i> Tableau de bord
                    </a>
                </li>
                @endif

                <!-- Products Section -->
                @if(auth()->guard('admin')->user()->hasPermission('view_products'))
                <li class="header_navItem">
                    <a class="header_navLink {{ request()->is('dashboard/products') ? 'active' : '' }}" href="/dashboard/products">
                        <i class="fi fi-rr-dice-d6"></i> Produits
                    </a>
                </li>

                @if(auth()->guard('admin')->user()->role_id == 1)
                <li class="header_navItem">
                    <a class="header_navLink {{ request()->is('dashboard/product_approve') ? 'active' : '' }}" href="/dashboard/product_approve">
                        <i class="fi fi-rr-map"></i> Produits en attente
                        @if(auth()->guard('admin')->user()->getPendingProducts() != 0)
                        <span class="badge bg-danger p-1 position-absolute top-50 end-0 me-3 translate-middle-y">
                            {{ auth()->guard('admin')->user()->getPendingProducts() ?? "0" }}
                        </span>
                        @endif
                    </a>
                </li>
                @endif
                @endif

                <!-- Categories (Admin only) -->
                @if(auth()->guard('admin')->user()->role_id == 1 && auth()->guard('admin')->user()->hasPermission('view_categories'))
                <li class="header_navItem">
                    <a class="header_navLink {{ request()->is('dashboard/category*') ? 'active' : '' }}" href="/dashboard/category">
                        <i class="fi fi-rr-building"></i> Catégories
                    </a>
                </li>
                @endif

                <!-- Banners (Admin only) -->
                @if(auth()->guard('admin')->user()->role_id == 1)
                <li class="header_navItem">
                    <a class="header_navLink {{ request()->is('dashboard/banner*') ? 'active' : '' }}" href="/dashboard/banner">
                        <i class="fi fi-rr-picture"></i> Bannières
                    </a>
                </li>
                @endif

                <!-- Orders -->
                @if(auth()->guard('admin')->user()->hasPermission('view_orders'))
                <li class="header_navItem">
                    <a class="header_navLink {{ request()->is('dashboard/orders*') ? 'active' : '' }}" href="/dashboard/orders">
                        <i class="fi fi-rr-to-do"></i> Commandes
                        @if(auth()->guard('admin')->user()->getPendingOrdersCount() != 0)
                        <span class="badge bg-danger p-1 position-absolute top-50 end-0 me-3 translate-middle-y">
                            {{ auth()->guard('admin')->user()->getPendingOrdersCount() ?? "0" }}
                        </span>
                        @endif
                    </a>
                </li>
                @endif

                <!-- Merchants (Admin only) -->
                @if(auth()->guard('admin')->user()->role_id == 1 && auth()->guard('admin')->user()->hasPermission('view_merchants'))
                <li class="header_navItem">
                    <a class="header_navLink {{ request()->is('dashboard/merchants') && !request()->has('hidden') ? 'active' : '' }}" href="/dashboard/merchants">
                        <i class="fi fi-rr-shopping-cart"></i> Boutiques
                    </a>
                </li>
                @endif

                <!-- Logout -->
                <li class="header_navItem mt-auto">
                    <a class="header_navLink" href="/logout">
                        <i class="fi fi-rr-sign-out-alt"></i> Déconnexion
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="header_backdrop"></div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all the navigation links
        var navLinks = document.querySelectorAll('.header_navLink');

        // Get the current URL path
        var currentPath = window.location.pathname;

        // Loop through each nav link and check if its href matches the current URL
        navLinks.forEach(function(link) {
            if (link.getAttribute('href') === currentPath) {
                // Remove 'active' class from other links
                navLinks.forEach(l => l.classList.remove('active'));
                // Add 'active' class to the clicked link
                link.classList.add('active');
            }
        });
    });
</script>
