<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>ShaiebExpo - Bannières</title>

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
                                        <h2 class="fs_18 fw-normal mb-0">Bannières</h2>
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
                                                <div>
                                                    <a class="btn btn_sm border rounded-pill" href="{{ route('admin.banner.add') }}">
                                                        <i class="fi fi-rr-plus lh-1"></i> Ajouter
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Table and Pagination -->
                            <div id="bannerTableContainer" class="overflow-x-auto">
                                <table class="table table-hover def_table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Nom</th>
                                            <th>Lien</th>
                                            <th>Ordre</th>
                                            <th>Visibilité</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="bannerTableBody">
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
        let bannerIdToAction = null;

        function showDeleteModal(bannerId) {
            bannerIdToAction = bannerId;
            $('#alert__modal').modal('show');
            $('.alert__title').text('Êtes-vous sûr de vouloir supprimer?');
            $('.alert__desc').text('Vous ne pourrez pas annuler cette action.');
        }

        function showVisibilityModal(bannerId) {
            bannerIdToAction = bannerId;
            $('#visibility__modal').modal('show');
            $('.visibility__title').text('Êtes-vous sûr de vouloir changer la visibilité?');
            $('.visibility__desc').text('La visibilité de la bannière sera modifiée.');
        }

        $('.alert__submitBtn').on('click', function() {
            delete_banner(bannerIdToAction);
        });

        $('.visibility__submitBtn').on('click', function() {
            visibility_update(bannerIdToAction);
        });

        function visibility_update(bannerId) {
            window.location.href = '/dashboard/banner/' + bannerId + '/visibility';
        }

        function delete_banner(bannerId) {
            window.location.href = '/dashboard/banner/' + bannerId + '/delete';
        }
    </script>

    <script>
        $(document).ready(function() {

            function fetch_data(page = 1, query = '', perPage = 10) {
                $.ajax({
                    url: "{{ route('admin.banner.index') }}",
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: {
                        page: page,
                        search: query,
                        per_page: perPage
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.data) {
                            renderTable(response.data);
                            renderPagination(response.pagination);
                            $('#totalCount').text(response.pagination.total);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }

            function renderTable(banners) {
                let tableBody = $('#bannerTableBody');
                tableBody.empty();

                if (banners.length === 0) {
                    tableBody.append(`
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="/admin/img/no-items.svg" alt="Aucun élément" class="mb-3" style="max-width: 200px;">
                                <p class="fs_18 text-gray">Aucune bannière à afficher</p>
                            </td>
                        </tr>
                    `);
                } else {
                    banners.forEach(banner => {
                        let imageUrl = banner.image || '/admin/img/placeholder.png';
                        let link = banner.link || '-';

                        tableBody.append(`
                            <tr>
                                <td><img src="${imageUrl}" alt="${banner.name}" style="width: 100px; height: 50px; object-fit: cover; border-radius: 8px;"></td>
                                <td>${banner.name}</td>
                                <td>${link}</td>
                                <td>${banner.order_item || 0}</td>
                                <td>${banner.visibility == 1 ? '<span class="badge bg-success">Visible</span>' : '<span class="badge bg-secondary">Masqué</span>'}</td>
                                <td>
                                    <a href="/dashboard/banner/${banner.id}/edit" class="btn btn_sm btn_primary">Modifier</a>
                                    <button class="btn btn_sm btn-danger" onclick="showDeleteModal(${banner.id})">Supprimer</button>
                                    <button class="btn btn_sm ${banner.visibility == 1 ? 'btn-warning' : 'btn-success'}" onclick="showVisibilityModal(${banner.id})">
                                        ${banner.visibility == 1 ? 'Masquer' : 'Afficher'}
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

            fetch_data();

            $('#search').on('keyup', function() {
                let query = $(this).val();
                fetch_data(1, query, $('#perPage').val());
            });

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                let page = $(this).data('page');
                let query = $('#search').val();
                fetch_data(page, query, $('#perPage').val());
            });

            $('#perPage').on('change', function() {
                let perPage = $(this).val();
                fetch_data(1, $('#search').val(), perPage);
            });
        });
    </script>

</body>

</html>
