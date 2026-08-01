<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Babashop - Commandes</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/admin/css/uicons-regular-rounded.css">
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
                <div class="row g_24">
                    <div class="col-12">
                        <div class="bg-white rounded_16 pb_12">
                            <!-- Header -->
                            <div class="border-bottom py_12 px_16">
                                <div class="row align-items-center gy_16">
                                    <div class="col-md-5">
                                        <h2 class="fs_18 fw-normal mb-0">Commandes</h2>
                                        <div class="fs_14 c_gray6">Total: <span id="totalCount">0</span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filters -->
                            <div class="border-bottom py_12 px_16">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <input type="text" id="search" class="form-control" placeholder="Rechercher...">
                                    </div>

                                    @if(auth()->guard('admin')->user()->role_id == 1)
                                    <div class="col-md-2">
                                        <select id="merchantFilter" class="form-select">
                                            <option value="">Toutes les boutiques</option>
                                            @foreach($merchants as $merchant)
                                                <option value="{{ $merchant->id }}">{{ $merchant->brand_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif

                                    <div class="col-md-2">
                                        <select id="statusFilter" class="form-select">
                                            <option value="">Tous les statuts</option>
                                            <option value="0">En attente</option>
                                            <option value="1">Confirmée</option>
                                            <option value="2">En préparation</option>
                                            <option value="3">En livraison</option>
                                            <option value="4">Livrée</option>
                                            <option value="5">Annulée</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <select id="paymentFilter" class="form-select">
                                            <option value="">Tous paiements</option>
                                            <option value="1">Payée</option>
                                            <option value="0">Non payée</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Orders Table -->
                            <div class="px_16 pt_12">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>N° Commande</th>
                                                <th>Client</th>
                                                <th>Boutique</th>
                                                <th>Montant</th>
                                                <th>Statut</th>
                                                <th>Paiement</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ordersTableBody">
                                            <tr>
                                                <td colspan="8" class="text-center py-5">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Chargement...</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="p_16">
                                    <div class="row g_16">
                                        <div class="col-md-9 order-2 order-md-1">
                                            <ul class="pagination pagination_sm mb-0" id="pagination">
                                                <!-- Dynamic pagination links will be loaded here -->
                                            </ul>
                                        </div>
                                        <div class="col-md-3 order-1 order-md-2">
                                            <div class="w_max ms-auto">
                                                <select class="form-select select_xsm inp_numSelect select_2" id="perPage">
                                                    <option value="10" selected>10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
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

    <!-- Update Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le statut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="orderIdToUpdate">
                    <div class="mb-3">
                        <label class="form-label">Nouveau statut</label>
                        <select id="newStatus" class="form-select">
                            <option value="0">En attente</option>
                            <option value="1">Confirmée</option>
                            <option value="2">En préparation</option>
                            <option value="3">En livraison</option>
                            <option value="4">Livrée</option>
                            <option value="5">Annulée</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Note (optionnel)</label>
                        <textarea id="adminNote" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="confirmStatusUpdate">Confirmer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- scripts -->
    <script src="/admin/js/jquery-3.7.0.min.js"></script>
    <script src="/admin/js/bootstrap.bundle.min.js"></script>
    <script src="/admin/js/main.js"></script>

    <script>
        let currentPage = 1;
        let merchantFilter = '';
        let statusFilter = '';
        let paymentFilter = '';
        let searchQuery = '';
        let perPage = 10;

        function fetch_data(page = 1, query = '', perPageValue = 10) {
            $.ajax({
                url: "{{ route('admin.orders.index') }}",
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    page: page,
                    search: query,
                    per_page: perPageValue,
                    merchant_id: merchantFilter,
                    status: statusFilter,
                    is_paid: paymentFilter
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
                    $('#ordersTableBody').html(`
                        <tr>
                            <td colspan="8" class="text-center py-5 text-danger">
                                Erreur lors du chargement
                            </td>
                        </tr>
                    `);
                }
            });
        }

        function renderTable(orders) {
            let tableBody = $('#ordersTableBody');
            tableBody.empty();

            if (orders.length === 0) {
                tableBody.append(`
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <img src="/admin/img/no-items.svg" alt="Aucun élément" class="mb-3" style="max-width: 200px;">
                            <p class="fs_18 c_gray6">Aucune commande à afficher</p>
                        </td>
                    </tr>
                `);
            } else {
                orders.forEach(order => {
                    let statusBadge = getStatusBadge(order.status);
                    let paymentBadge = order.is_paid ? '<span class="badge bg-success">Payée</span>' : '<span class="badge bg-warning text-dark">Non payée</span>';
                    let merchantName = order.merchant ? order.merchant.brand_name : '-';
                    let date = new Date(order.created_at).toLocaleDateString('fr-FR');

                    tableBody.append(`
                        <tr>
                            <td><strong>#${order.id}</strong></td>
                            <td>
                                ${order.fullname || '-'}<br>
                                <small class="text-muted">${order.phone || ''}</small>
                            </td>
                            <td>${merchantName}</td>
                            <td><strong>${order.total_net_a_pay + ' DT'}</strong></td>
                            <td>${statusBadge}</td>
                            <td>${paymentBadge}</td>
                            <td>${date}</td>
                            <td>
                                <a href="/dashboard/orders/${order.id}" class="btn btn-sm btn-primary">
                                    Voir
                                </a>
                                <button class="btn btn-sm btn-warning" onclick="showStatusModal(${order.id}, ${order.status})">
                                    Statut
                                </button>
                            </td>
                        </tr>
                    `);
                });
            }
        }

        function getStatusBadge(status) {
            const badges = {
                0: '<span class="badge bg-secondary">En attente</span>',
                1: '<span class="badge bg-info">Confirmée</span>',
                2: '<span class="badge bg-primary">En préparation</span>',
                3: '<span class="badge" style="background:#ff9800;">En livraison</span>',
                4: '<span class="badge bg-success">Livrée</span>',
                5: '<span class="badge bg-danger">Annulée</span>'
            };
            return badges[status] || '<span class="badge bg-secondary">Inconnu</span>';
        }

        function renderPagination(pagination) {
            let paginationLinks = $('#pagination');
            paginationLinks.empty();

            if (pagination.last_page <= 1) return;

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

        window.showStatusModal = function(orderId, currentStatus) {
            $('#orderIdToUpdate').val(orderId);
            $('#newStatus').val(currentStatus);
            $('#adminNote').val('');
            $('#statusModal').modal('show');
        };

        $('#confirmStatusUpdate').on('click', function() {
            const orderId = $('#orderIdToUpdate').val();
            const newStatus = $('#newStatus').val();
            const adminNote = $('#adminNote').val();

            $.ajax({
                url: `/dashboard/orders/${orderId}/status`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    status: newStatus,
                    admin_note: adminNote
                },
                success: function(response) {
                    $('#statusModal').modal('hide');
                    fetch_data(currentPage, searchQuery, perPage);
                    alert(response.message || 'Statut mis à jour');
                },
                error: function(xhr) {
                    alert('Erreur lors de la mise à jour');
                }
            });
        });

        $('#search').on('keyup', function() {
            searchQuery = $(this).val();
            currentPage = 1;
            fetch_data(currentPage, searchQuery, perPage);
        });

        $('#merchantFilter, #statusFilter, #paymentFilter').on('change', function() {
            merchantFilter = $('#merchantFilter').val() || '';
            statusFilter = $('#statusFilter').val() || '';
            paymentFilter = $('#paymentFilter').val() || '';
            currentPage = 1;
            fetch_data(currentPage, searchQuery, perPage);
        });

        $('#perPage').on('change', function() {
            perPage = $(this).val();
            currentPage = 1;
            fetch_data(currentPage, searchQuery, perPage);
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            currentPage = $(this).data('page');
            fetch_data(currentPage, searchQuery, perPage);
        });

        // Initial load
        fetch_data(currentPage, searchQuery, perPage);
    </script>

</body>

</html>
