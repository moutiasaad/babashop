<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Babashop - Produits</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/admin/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="/admin/css/style.css">
    <style>
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }
        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
            color: white;
        }
        .btn-info i {
            font-size: 14px;
        }
        .toast {
            border-radius: 8px;
        }
    </style>
</head>

<body>
    @include('admin.layouts.sidebar-fr')
    @include('admin.layouts.header-fr')

    <div class="modal fade modal_sm" id="alert__modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded_16">
                <div class="modal-body p-3">
                    <div class="alert__icon">
                        <i class="fi fi-rr-check-circle"></i>
                        <i class="fi fi-rr-triangle-warning"></i>
                        <i class="fi fi-rr-info"></i>
                    </div>
                    <h6 class="alert__title"></h6>
                    <p class="alert__desc"></p>

                    <div>
                        <div class="d-flex justify-content-center">
                            <div>
                                <button class="btn btn_sm btn_primary alert__submitBtn" type="submit" aria-label="Close">Oui, confirmer</button>
                            </div>
                            <div class="ms-2">
                                <button class="btn btn_sm btn-light alert__resetBtn" type="reset" data-bs-dismiss="modal" aria-label="Close">Non, annuler</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal_sm" id="visibility__modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded_16">
                <div class="modal-body p-3">
                    <div class="alert__icon">
                        <i class="fi fi-rr-check-circle"></i>
                        <i class="fi fi-rr-triangle-warning"></i>
                        <i class="fi fi-rr-info"></i>
                    </div>
                    <h6 class="visibility__title"></h6>
                    <p class="visibility__desc"></p>

                    <div>
                        <div class="d-flex justify-content-center">
                            <div>
                                <button class="btn btn_sm btn_primary visibility__submitBtn" type="submit" aria-label="Close">Oui, confirmer</button>
                            </div>
                            <div class="ms-2">
                                <button class="btn btn_sm btn-light alert__resetBtn" type="reset" data-bs-dismiss="modal" aria-label="Close">Non, annuler</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                <div class="row g_24">
                    <div class="col-12">
                        <div class="bg-white rounded_16 pb_12">
                            <!-- Header -->
                            <div class="border-bottom py_12 px_16">
                                <div class="row align-items-center gy_16">
                                    <div class="col-md-5">
                                        <h2 class="fs_18 fw-normal mb-0">Produits</h2>
                                        <div class="fs_14 c_gray6">Total: <span id="totalCount">0</span></div>
                                    </div>
                                    <div class="col-md-7">
                                        <form action="#">
                                            <div class="text-end d-flex justify-content-end gap-2">
                                                <div class="flex-grow-1">
                                                    <div class="input-group search_inpGrp border rounded-pill fs_sm overflow-hidden pr_xsm2 ms-auto">
                                                        <button class="btn btn_sm border-0 shadow-none" type="button"><i class="fi fi-rr-search"></i></button>
                                                        <input class="form-control inp_sm border-0 shadow-none" type="search" id="search" placeholder="Rechercher">
                                                    </div>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn_sm border rounded-pill" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fi fi-rr-filter"></i> Boutique
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="#" data-merchant="">Toutes</a></li>
                                                        @foreach($merchants as $merchant)
                                                        <li><a class="dropdown-item" href="#" data-merchant="{{ $merchant->id }}">{{ $merchant->brand_name }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn_sm border rounded-pill" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fi fi-rr-filter"></i> Catégorie
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="#" data-category="">Toutes</a></li>
                                                        @foreach($categories as $category)
                                                        <li><a class="dropdown-item" href="#" data-category="{{ $category->id }}">{{ $category->name }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div>
                                                    <a class="btn btn_sm border rounded-pill" href="{{ route('admin.products.add') }}">
                                                        <i class="fi fi-rr-plus lh-1"></i> Ajouter
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Table and Pagination -->
                            <div id="productTableContainer" class="overflow-x-auto">
                                <table class="table table-hover def_table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Nom</th>
                                            <th>Type</th>
                                            <th>SKU</th>
                                            <th>Boutique</th>
                                            <th>Quantité</th>
                                            <th>Prix</th>
                                            <th>Visibilité</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="productTableBody">
                                        <tr>
                                            <td colspan="9" style="margin-top:100px;">
                                                <div class="loading_box text-center">
                                                    <div class="spinner-border c_primary" role="status">
                                                        <span class="visually-hidden">Chargement...</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Pagination -->
                                <div class="p_16">
                                    <div class="row g_16">
                                        <div class="col-md-9 order-2 order-md-1">
                                            <ul class="pagination pagination_sm mb-0" id="paginationLinks">
                                                <!-- Dynamic pagination links will be loaded here -->
                                            </ul>
                                        </div>
                                        <div class="col-md-3 order-1 order-md-2">
                                            <div class="w_max ms-auto">
                                                <select class="form-select select_xsm inp_numSelect select_2" id="perPage">
                                                    <option value="5">5</option>
                                                    <option value="10" selected>10</option>
                                                    <option value="15">15</option>
                                                    <option value="20">20</option>
                                                    <option value="30">30</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Scripts -->
    <script src="/admin/js/jquery-3.7.0.min.js"></script>
    <script src="/admin/js/bootstrap.bundle.min.js"></script>
    @include('admin.layouts.footer')

    <script>
        let productIdToAction = null;
        let merchantFilter = '';
        let categoryFilter = '';

        function showDeleteModal(productId) {
            productIdToAction = productId;
            $('#alert__modal').modal('show');
            $('.alert__title').text('Êtes-vous sûr de vouloir supprimer?');
            $('.alert__desc').text('Vous ne pourrez pas annuler cette action.');
        }

        function showVisibilityModal(productId) {
            productIdToAction = productId;
            $('#visibility__modal').modal('show');
            $('.visibility__title').text('Êtes-vous sûr de vouloir changer la visibilité?');
            $('.visibility__desc').text('La visibilité du produit sera modifiée.');
        }

        // Delete the product when confirmed
        $('.alert__submitBtn').on('click', function() {
            delete_product(productIdToAction);
        });

        $('.visibility__submitBtn').on('click', function() {
            visibility_update(productIdToAction);
        });

        function visibility_update(productId) {
            window.location.href = '/dashboard/products/' + productId + '/visibility';
        }

        function delete_product(productId) {
            window.location.href = '/dashboard/products/' + productId + '/delete';
        }

        function copyAndOpenLink(productId) {
            const productUrl = window.location.origin + '/product/' + productId;

            // Copy to clipboard
            navigator.clipboard.writeText(productUrl).then(function() {
                // Show a brief toast/notification
                showToast('Lien copie!');
            }).catch(function(err) {
                // Fallback for older browsers
                const tempInput = document.createElement('input');
                tempInput.value = productUrl;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                showToast('Lien copie!');
            });

            // Open in new tab
            window.open(productUrl, '_blank');
        }

        function showToast(message) {
            // Create toast element
            let toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 end-0 p-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <div class="toast show align-items-center text-white bg-success border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fi fi-rr-check me-2"></i>${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.closest('.position-fixed').remove()"></button>
                    </div>
                </div>
            `;
            document.body.appendChild(toast);

            // Auto-remove after 2 seconds
            setTimeout(() => {
                toast.remove();
            }, 2000);
        }

        // Filter dropdowns
        $('.dropdown-item[data-merchant]').on('click', function(e) {
            e.preventDefault();
            merchantFilter = $(this).data('merchant');
            fetch_data(1, $('#search').val(), $('#perPage').val());
        });

        $('.dropdown-item[data-category]').on('click', function(e) {
            e.preventDefault();
            categoryFilter = $(this).data('category');
            fetch_data(1, $('#search').val(), $('#perPage').val());
        });
    </script>

    <script>
        $(document).ready(function() {

            function fetch_data(page = 1, query = '', perPage = 10) {
                $.ajax({
                    url: "{{ route('admin.product.index') }}",
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: {
                        page: page,
                        search: query,
                        per_page: perPage,
                        merchant_id: merchantFilter,
                        category_id: categoryFilter
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Response:', response);
                        console.log('Products count:', response.data ? response.data.length : 0);
                        console.log('Total:', response.pagination ? response.pagination.total : 0);
                        if (response.data) {
                            renderTable(response.data);
                            renderPagination(response.pagination);
                            $('#totalCount').text(response.pagination.total);
                        } else {
                            console.error('No data in response');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        console.error('Status:', status);
                        console.error('Response:', xhr.responseText);
                        alert('Error loading products: ' + error);
                    }
                });
            }

            function renderTable(products) {
                let tableBody = $('#productTableBody');
                tableBody.empty();

                if (products.length === 0) {
                    tableBody.append(`
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <img src="/admin/img/no-items.svg" alt="Aucun élément" class="mb-3" style="max-width: 200px;">
                                <p class="fs_18 text-gray">Aucun produit à afficher</p>
                            </td>
                        </tr>
                    `);
                } else {
                    products.forEach(product => {
                        // Get image URL
                        let imageUrl = '/admin/img/placeholder.png';
                        if (product.image && Array.isArray(product.image) && product.image.length > 0) {
                            imageUrl = product.image[0];
                        }

                        // Get merchant name
                        let merchantName = product.merchant ? product.merchant.brand_name : '-';

                        tableBody.append(`
                            <tr>
                                <td><img src="${imageUrl}" alt="${product.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;"></td>
                                <td>${product.name}</td>
                                <td>${product.type || '-'}</td>
                                <td>${product.sku || '-'}</td>
                                <td>${merchantName}</td>
                                <td>${product.qty || 0}</td>
                                <td>${product.price ? product.price + ' DT' : '-'}</td>
                                <td>${product.visibility == 1 ? '<span class="badge bg-success">Visible</span>' : '<span class="badge bg-secondary">Masqué</span>'}</td>
                                <td>
                                    <button class="btn btn_sm btn-info" onclick="copyAndOpenLink(${product.id})" title="Copier le lien et ouvrir">
                                        <i class="fi fi-rr-link"></i>
                                    </button>
                                    <a href="/dashboard/products/${product.id}/edit" class="btn btn_sm btn_primary">Modifier</a>
                                    <button class="btn btn_sm btn-danger" onclick="showDeleteModal(${product.id})">Supprimer</button>
                                    <button class="btn btn_sm ${product.visibility == 1 ? 'btn-warning' : 'btn-success'}" onclick="showVisibilityModal(${product.id})">
                                        ${product.visibility == 1 ? 'Masquer' : 'Afficher'}
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                }
            }

            function renderPagination(pagination) {
                let paginationLinks = $('#paginationLinks');
                paginationLinks.empty();

                if (pagination.prev_page_url) {
                    paginationLinks.append(
                        `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page - 1}">Précédent</a></li>`
                    );
                }

                for (let i = 1; i <= pagination.last_page; i++) {
                    paginationLinks.append(
                        `<li class="page-item ${pagination.current_page == i ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`
                    );
                }

                if (pagination.next_page_url) {
                    paginationLinks.append(
                        `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page + 1}">Suivant</a></li>`
                    );
                }
            }

            // Initial data fetch
            fetch_data();

            // Handle search input
            $('#search').on('keyup', function() {
                let query = $(this).val();
                fetch_data(1, query, $('#perPage').val());
            });

            // Handle pagination click
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                let page = $(this).data('page');
                let query = $('#search').val();
                fetch_data(page, query, $('#perPage').val());
            });

            // Handle per page change
            $('#perPage').on('change', function() {
                let perPage = $(this).val();
                fetch_data(1, $('#search').val(), perPage);
            });
        });
    </script>

</body>

</html>
