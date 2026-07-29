<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Shaieb Store - Catégories</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/admin/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="/admin/css/style.css">

    <style>
        .loader {
            width: 18px;
            height: 18px;
            border: 3px solid #FFF;
            border-bottom-color: transparent;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    @include('admin.layouts.sidebar-fr')
    @include('admin.layouts.header-fr')

    <div class="modal fade modal_sm" id="alert__modal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1">
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
                                <button class="btn btn_sm btn_primary alert__submitBtn" type="submit"
                                    aria-label="Close">Oui, confirmer</button>
                            </div>
                            <div class="ms-2">
                                <button class="btn btn_sm btn-light alert__resetBtn" type="reset"
                                    data-bs-dismiss="modal" aria-label="Close">Non, annuler</button>
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
                                        <h2 class="fs_18 fw-normal mb-0">Catégories</h2>
                                        <div class="fs_14 c_gray6">Total: <span id="totalCount">0</span></div>
                                    </div>
                                    <div class="col-md-7">
                                        <form action="#">
                                            <div class="text-end d-flex justify-content-end">
                                                <div class="flex-grow-1">
                                                    <div
                                                        class="input-group search_inpGrp border rounded-pill fs_sm overflow-hidden pr_xsm2 ms-auto">
                                                        <button class="btn btn_sm border-0 shadow-none"
                                                            type="button"><i class="fi fi-rr-search"></i></button>
                                                        <input class="form-control inp_sm border-0 shadow-none"
                                                            type="search" id="search" placeholder="Rechercher">
                                                    </div>
                                                </div>
                                                <div class="dropdown filter_dropdown d-inline-block mr_8">
                                                    <a class="btn btn_sm border rounded-pill"
                                                        href="{{ route('admin.category.add') }}">
                                                        <i class="fi fi-rr-plus lh-1"></i> Ajouter
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Table and Pagination -->
                            <div id="categoryTableContainer" class="overflow-x-auto">
                                <table class="table table-hover def_table mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Image</th>
                                            <th>Nom</th>
                                            <th>Visibilité</th>
                                            <th>Date de création</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="categoryTableBody">
                                        <tr>
                                            <td colspan="6" style="margin-top:100px;">
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
                                                <select class="form-select select_xsm inp_numSelect select_2"
                                                    id="perPage">
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

    <div class="modal fade modal_sm" id="visibility__modal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1">
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
                                <button class="btn btn_sm btn_primary visibility__submitBtn" type="submit"
                                    aria-label="Close">Oui, confirmer</button>
                            </div>
                            <div class="ms-2">
                                <button class="btn btn_sm btn-light alert__resetBtn" type="reset"
                                    data-bs-dismiss="modal" aria-label="Close">Non, annuler</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/admin/js/jquery-3.7.0.min.js"></script>
    <script src="/admin/js/bootstrap.bundle.min.js"></script>
    @include('admin.layouts.footer')

    <!-- AJAX logic for Search and Pagination -->
    <script>
        let categoryIdToAction = null;

        function showDeleteModal(categoryId) {
            categoryIdToAction = categoryId;
            $('#alert__modal').modal('show');
            $('.alert__title').text('Êtes-vous sûr de vouloir supprimer?');
            $('.alert__desc').text('Vous ne pourrez pas annuler cette action.');
        }

        function showVisibilityModal(categoryId) {
            categoryIdToAction = categoryId;
            $('#visibility__modal').modal('show');
            $('.visibility__title').text('Êtes-vous sûr de vouloir changer la visibilité?');
            $('.visibility__desc').text('La visibilité de la catégorie sera modifiée.');
        }

        // Delete the category when confirmed
        $('.alert__submitBtn').on('click', function() {
            delete_category(categoryIdToAction);
        });

        $('.visibility__submitBtn').on('click', function() {
            visibility_update(categoryIdToAction);
        });

        function visibility_update(categoryId) {
            window.location.href = '/dashboard/category/' + categoryId + '/visiblity';
        }

        function delete_category(categoryId) {
            window.location.href = '/dashboard/category/' + categoryId + '/delete';
        }
    </script>

    <script>
        $(document).ready(function() {

            function fetch_data(page = 1, query = '', perPage = 10) {
                $.ajax({
                    url: "{{ route('admin.category.index') }}",
                    method: 'GET',
                    data: {
                        page: page,
                        search: query,
                        per_page: perPage
                    },
                    success: function(response) {
                        renderTable(response.data);
                        renderPagination(response.pagination);
                        $('#totalCount').text(response.pagination.total);
                    }
                });
            }

            function renderTable(categories) {
                let tableBody = $('#categoryTableBody');
                tableBody.empty();

                if (categories.length === 0) {
                    tableBody.append(`
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="/admin/img/no-items.svg" alt="Aucun élément" class="mb-3" style="max-width: 200px;">
                                <p class="fs_18 text-gray">Aucune catégorie à afficher</p>
                            </td>
                        </tr>
                    `);
                } else {
                    categories.forEach(category => {
                        // Remove CDN prefix for display
                        let imageUrl = category.image;
                        if (imageUrl && imageUrl.includes('{{env("FILES_CDN")}}')) {
                            imageUrl = imageUrl.replace('{{env("FILES_CDN")}}', '');
                        }

                        tableBody.append(`
                            <tr>
                               <td>${category.order_item}</td>
                                <td><img src="${imageUrl}" alt="${category.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;"></td>
                                <td>${category.name}</td>
                                <td>${category.visibility == 1 ? '<span class="badge bg-success">Visible</span>' : '<span class="badge bg-secondary">Masquée</span>'}</td>
                                <td>${new Date(category.created_at).toLocaleDateString('fr-FR')}</td>
                                <td>
                                    <a href="/dashboard/category/${category.id}/edit" class="btn btn_sm btn_primary">Modifier</a>
                                    <button class="btn btn_sm btn-danger" onclick="showDeleteModal(${category.id})">Supprimer</button>
                                    <button
                                        class="btn btn_sm ${category.visibility == 1 ? 'btn-warning' : 'btn-success'}"
                                        onclick="showVisibilityModal(${category.id})">
                                        ${category.visibility == 1 ? 'Masquer' : 'Afficher'}
                                    </button>
                                    <a href="/dashboard/product/0?category_id=${category.id}" class="btn btn_sm btn-info">Voir produits</a>
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
