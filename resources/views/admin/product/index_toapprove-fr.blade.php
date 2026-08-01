<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Babashop - Produits en attente</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/admin/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="/admin/css/style.css">
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

    <div class="modal fade modal_sm" id="delete__modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded_16">
                <div class="modal-body p-3">
                    <div class="alert__icon">
                        <i class="fi fi-rr-check-circle"></i>
                        <i class="fi fi-rr-triangle-warning"></i>
                        <i class="fi fi-rr-info"></i>
                    </div>
                    <h6 class="delete__title"></h6>
                    <p class="delete__desc"></p>

                    <div>
                        <div class="d-flex justify-content-center">
                            <div>
                                <button class="btn btn_sm btn_primary delete__submitBtn" type="submit" aria-label="Close">Oui, confirmer</button>
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
                                        <h2 class="fs_18 fw-normal mb-0">Produits en attente de révision</h2>
                                        <div class="fs_14 c_gray6">Total: {{ $items->total() }}</div>
                                    </div>
                                    <div class="col-md-7">
                                        <form action="#">
                                            <div class="text-end d-flex justify-content-end">
                                                <div class="flex-grow-1">
                                                    <div class="input-group search_inpGrp border rounded-pill fs_sm overflow-hidden pr_xsm2 ms-auto">
                                                        <button class="btn btn_sm border-0 shadow-none" type="button"><i class="fi fi-rr-search"></i></button>
                                                        <input class="form-control inp_sm border-0 shadow-none" type="search" id="search" placeholder="Rechercher">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div id="productTableContainer" class="overflow-x-auto">
                                <table class="table table-hover def_table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Nom du produit</th>
                                            <th>Type</th>
                                            <th>Quantité</th>
                                            <th>SKU</th>
                                            <th>Boutique</th>
                                            <th>Prix</th>
                                            <th>Date de création</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if($items->count() == 0)
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <img src="/admin/img/no-items.svg" alt="Aucun élément" class="mb-3" style="max-width: 200px;">
                                                <p class="fs_18 text-gray">Aucun produit en attente</p>
                                            </td>
                                        </tr>
                                        @else
                                        @foreach($items as $product)
                                        <tr>
                                            <td>
                                                @if(is_array($product->image) && count($product->image) > 0)
                                                <img src="{{ $product->image[0] }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                                @else
                                                <img src="/admin/img/placeholder.png" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                                @endif
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->type ?? '-' }}</td>
                                            <td>{{ $product->qty ?? 0 }}</td>
                                            <td>{{ $product->sku ?? '-' }}</td>
                                            <td>{{ $product->merchant ? $product->merchant->brand_name : '-' }}</td>
                                            <td>{{ $product->price ? $product->price . ' DT' : '-' }}</td>
                                            <td>{{ $product->created_at ? $product->created_at->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                <button class="btn btn_sm btn-success" onclick="showApproveModal({{ $product->id }})">Approuver</button>
                                                <button class="btn btn_sm btn-danger" onclick="showDeleteModal({{ $product->id }})">Supprimer</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>

                                <!-- Pagination -->
                                <div class="p_16">
                                    <div class="row g_16">
                                        <div class="col-md-9 order-2 order-md-1">
                                            {{ $items->links('pagination::bootstrap-5') }}
                                        </div>
                                        <div class="col-md-3 order-1 order-md-2">
                                            <div class="w_max ms-auto">
                                                <select class="form-select select_xsm inp_numSelect select_2" id="perPage" onchange="window.location.href='?per_page=' + this.value">
                                                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                                                    <option value="10" {{ request('per_page') == 10 || !request('per_page') ? 'selected' : '' }}>10</option>
                                                    <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                                                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                                    <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
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

        function showApproveModal(productId) {
            productIdToAction = productId;
            $('#alert__modal').modal('show');
            $('.alert__title').text('Êtes-vous sûr de vouloir approuver ce produit?');
            $('.alert__desc').text('Le produit sera approuvé et affiché dans la boutique');
        }

        function showDeleteModal(productId) {
            productIdToAction = productId;
            $('#delete__modal').modal('show');
            $('.delete__title').text('Êtes-vous sûr de vouloir supprimer ce produit?');
            $('.delete__desc').text('Le produit sera supprimé définitivement');
        }

        $('.alert__submitBtn').on('click', function() {
            window.location.href = '/dashboard/product_approve/' + productIdToAction;
        });

        $('.delete__submitBtn').on('click', function() {
            window.location.href = '/dashboard/products/' + productIdToAction + '/delete';
        });
    </script>

</body>

</html>
