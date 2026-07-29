<header class="header">
    <div class="header_menu">
        <div class="header_top border-bottom px_16" style="background:#eff3fd;">
            <div class="py_8 text-center">
                <a class="d-inline-block" href="#">
                    <a class="d-inline-block" href="#">
                        <img class="header_topLogo" src="/admin/img/logo.png" alt="logo">
                    </a>
                    <span style="color:#88bef5;">Shaieb Store</span>
                </a>
            </div>
            <button class="btn header_tglBtn fs_16 d-xl-none" type="button">
                <i class="fi fi-rr-cross"></i>
            </button>
        </div>
        <div class="header_main" data-scrollbar>
            <ul class="header_nav">
                @if(auth()->guard('admin')->user()->hasPermission('view_dashboard'))

                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard">
                        <i class="fi fi-rr-home"></i> الرئيسية
                    </a>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->hasPermission('view_products'))
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/product/0">
                        <i class="fi fi-rr-dice-d6"></i> المنتجات العادية
                    </a>
                </li>
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/product/0?visibility=2">
                        <i class="fi fi-rr-dice-d6"></i> المنتجات المخفية
                    </a>
                </li>
               @if(auth()->guard('admin')->user()->role_id == 1)
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/product_approve">
                        <i class="fi fi-rr-map"></i> المنتجات بإنتظار المراجعة
                       @if(auth()->guard('admin')->user()->getPendingProducts() != 0 )
                        <span class="badge bg-danger p-1 position-absolute top-50 end-0 me-3 translate-middle-y">{{ auth()->guard('admin')->user()->getPendingProducts() ?? "0" }}</span>
                        @endif


                    </a>
                </li>
                @endif
                <!--<li class="header_navItem">-->
                <!--    <a class="header_navLink" href="/dashboard/design-attributes">-->
                <!--        <i class="fi fi-rr-map"></i> صفات منتجات صمم هديتك-->
                <!--    </a>-->
                <!--</li>-->

                @endif
                @if(auth()->guard('admin')->user()->role_id == 1)
                

                @if(auth()->guard('admin')->user()->hasPermission('view_categories'))
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/category">
                        <i class="fi fi-rr-building"></i> الأقسام
                    </a>
                </li>
                @endif
                @endif

                @if(auth()->guard('admin')->user()->hasPermission('view_orders'))
                <li class="header_navItem">
                    <a class="header_navLink {{ request()->is('dashboard/orders*') ? 'active' : '' }}" href="/dashboard/orders">
                        <i class="fi fi-rr-to-do"></i> الطلبات 
                       @if(auth()->guard('admin')->user()->getPendingOrdersCount() != 0 )
                        <span class="badge bg-danger p-1 position-absolute top-50 end-0 me-3 translate-middle-y">{{ auth()->guard('admin')->user()->getPendingOrdersCount() ?? "0" }}</span>
                        @endif
                    </a>
                </li>
                @endif
                
                @if(auth()->guard('admin')->user()->role_id == 1)

                @if(auth()->guard('admin')->user()->hasPermission('view_notification'))
                <li class="header_navItem">
                    <a class="header_navLink {{ request()->is('dashboard/notification*') ? 'active' : '' }}" href="/dashboard/notification">
                        <i class="fi fi-rr-bell"></i> تنبيهات الاعضاء
                    </a>
                </li>
                <li class="header_navItem">
                    <a class="header_navLink {{ request()->is('dashboard/notification_template*') ? 'active' : '' }}" href="/dashboard/notification_template">
                        <i class="fi fi-rr-document"></i> قوالب التنبيهات
                    </a>
                </li>
                
                @endif

                @if(auth()->guard('admin')->user()->hasPermission('view_coupons'))
                <li class="header_navItem">
                    <a class="header_navLink {{ request()->is('dashboard/coupons*') ? 'active' : '' }}" href="/dashboard/coupons">
                        <i class="fi fi-rr-ticket"></i> الكوبونات
                    </a>
                </li>
                @endif

                @if(auth()->guard('admin')->user()->hasPermission('view_promotion'))
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/promotion/0">
                        <i class="fi fi-rr-building"></i> العروض
                    </a>
                </li>
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/promotion/1">
                        <i class="fi fi-rr-building"></i> البانر الرئيسي
                    </a>
                </li>
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/promotion/2">
                        <i class="fi fi-rr-megaphone"></i> إعلانات التطبيق
                    </a>
                </li>
                
                @endif
                @if(auth()->guard('admin')->user()->hasPermission('view_dedication'))
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/dedication">
                        <i class="fi fi-rr-map"></i> عبارات الإهداء
                    </a>
                </li>
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/dedicationCat">
                        <i class="fi fi-rr-building"></i> مجموعات عبارات الإهداء
                    </a>
                </li> 
                @endif
                @if(auth()->guard('admin')->user()->hasPermission('view_clients'))
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/users">
                        <i class="fi fi-rr-users"></i> العملاء
                    </a>
                </li>
                @endif

                @if(auth()->guard('admin')->user()->hasPermission('view_merchants'))
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/merchants">
                        <i class="fi fi-rr-shopping-cart"></i> المتاجر
                    </a>
                </li>
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/merchants?hidden=1">
                        <i class="fi fi-rr-shopping-cart"></i> المتاجر المحجوبة
                    </a>
                </li>
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/merchants/map">
                        <i class="fi fi-rr-shopping-cart"></i> خريطة المتاجر
                    </a>
                </li>
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/merchant_categorie">
                        <i class="fi fi-rr-building"></i> أقسام المتاجر
                    </a>
                </li>

                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/merchant-users">
                        <i class="fi fi-rr-shopping-cart"></i> مستخدمي المتاجر
                    </a>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->hasPermission('view_blances'))
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/balances">
                        <i class="fi fi-rr-book"></i>
                        أرصدة المتاجر
                    </a>
                </li>
                @endif

                @if(auth()->guard('admin')->user()->hasPermission('view_admins'))
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/admin-users">
                        <i class="fi fi-rr-users"></i> مستخدمي الإدارة
                    </a>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->hasPermission('view_logs'))
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/logadmin">
                        <i class="fi fi-rr-book"></i>
                        السجلات
                    </a>
                </li>
                @endif
                 @if(auth()->guard('admin')->user()->hasPermission('view_system_config'))
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/settings/config">
                        <i class="fi fi-rr-settings"></i> إعدادات النظام
                    </a>
                </li>
                @endif

                @endif

                @if(auth()->guard('admin')->user()->hasPermission('view_drivers'))
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/driver">
                        <i class="fi fi-rr-truck-side"></i> مناديب التوصيل
                    </a>
                </li>
                @endif


                @if(auth()->guard('admin')->user()->role_id == 2)
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/settings/payments">
                        <i class="fi fi-rr-chart-area"></i> الرصيد
                    </a>
                </li>
                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/merchant-users">
                        <i class="fi fi-rr-shopping-cart"></i> مستخدمي المتاجر
                    </a>
                </li>

                <li class="header_navItem">
                    <a class="header_navLink" href="/dashboard/profile">
                        <i class="fi fi-rr-settings"></i> الإعدادات
                    </a>
                </li>
                @endif


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
