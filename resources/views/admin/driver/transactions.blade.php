@php

$Model_PLURAL = 'معاملات المندوبين';
$Model_SINGULAR = 'معاملة';
$Model_API_ROUTE = 'driver';
$Model_Name = 'transactions';

@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - {{ $Model_PLURAL }}</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/admin/css/bootstrap.rtl.min.css">
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

        .badge-earning {
            background-color: #28a745;
            color: white;
        }

        .badge-withdraw {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>
    @include('admin.layouts.sidebar')
    @include('admin.layouts.header')

    <!-- Main Content -->
    <main class="main" id="google_translate_element">
        <section class="mb_16">
            <div class="container-fluid px-0">
                <div class="row g_24">
                    <div class="col-12">
                        <div class="bg-white rounded_16 pb_12">
                            <!-- Header -->
                            <div class="border-bottom py_12 px_16">
                                <div class="row align-items-center gy_16">
                                    <div class="col-md-5">
                                        <h2 class="fs_18 fw-normal mb-0">{{ $Model_PLURAL }}</h2>
                                        <div class="fs_14 c_gray6">الإجمالي: {{ $items->total() }} </div>
                                    </div>
                                    <div class="col-md-7">
                                        <form action="#">
                                            <div class="text-end d-flex justify-content-end gap-2">
                                                <div class="flex-grow-1">
                                                    <div
                                                        class="input-group search_inpGrp border rounded-pill fs_sm overflow-hidden pr_xsm2 ms-auto">
                                                        <button class="btn btn_sm border-0 shadow-none"
                                                            type="button"><i class="fi fi-rr-search"></i></button>
                                                        <input class="form-control inp_sm border-0 shadow-none"
                                                            type="search" id="search" placeholder="البحث">
                                                    </div>
                                                </div>
                                                <div class="dropdown filter_dropdown d-inline-block">
                                                    <select class="form-select select_sm" id="typeFilter">
                                                        <option value="">جميع المعاملات</option>
                                                        <option value="earning">أرباح</option>
                                                        <option value="withdrawal">سحب</option>
                                                    </select>
                                                </div>
                                                <div class="d-inline-block">
                                                    <a class="btn btn_sm border rounded-pill"
                                                        href="{{ route('admin.'.$Model_API_ROUTE.'.index') }}">
                                                        <i class="fi fi-rr-arrow-left lh-1"></i> رجوع
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Table and Pagination -->
                            <div id="transactionTableContainer" class="overflow-x-auto">
                                <table class="table table-hover def_table mb-0">
                                    <thead>
                                        <tr>
                                            <th>المندوب</th>
                                            <th>النوع</th>
                                            <th>المبلغ</th>
                                            <th>الوصف</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>

                                    <tbody id="transactionTableBody">
                                        <td colspan="5" style="margin-top:100px;">
                                            <div class="loading_box text-center">
                                                <div class="spinner-border c_primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                        </td>

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

    <!-- Scripts -->
    <script src="/admin/js/jquery-3.7.0.min.js"></script>
    <script src="/admin/js/bootstrap.bundle.min.js"></script>
    @include('admin.layouts.footer')
    <!-- AJAX logic for Search, Filter and Pagination -->
    <script>
        $(document).ready(function() {

            function fetch_data(page = 1, query = '', perPage = 10, type = '') {
                $.ajax({
                    url: "{{ route('admin.driver.transactions.data') }}",
                    method: 'GET',
                    data: {
                        page: page,
                        search: query,
                        per_page: perPage,
                        type: type
                    },
                    success: function(response) {
                        renderTable(response.data);
                        renderPagination(response.pagination);
                    }
                });
            }

            function renderTable(transactions) {
                let tableBody = $('#transactionTableBody');
                tableBody.empty();

                if (transactions.length === 0) {
                    // Append a row with "No Items" message
                    tableBody.append(`
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="/admin/img/no-items.svg" alt="No Items" class="mb-3">
                                <p class="fs_18 text-gray">لا توجد بيانات لعرضها</p>
                            </td>
                        </tr>
                    `);
                } else {
                    transactions.forEach(transaction => {
                        let typeLabel = transaction.type === 'earning' ? 'أرباح' : 'سحب';
                        let typeBadgeClass = transaction.type === 'earning' ? 'badge-earning' : 'badge-withdraw';
                        let driverName = transaction.driver ? transaction.driver.name : 'غير محدد';
                        let description = transaction.description || '-';

                        // Format date as Y/m/d H:i:s
                        let date = new Date(transaction.created_at);
                        let formattedDate = date.getFullYear() + '/' +
                                          String(date.getMonth() + 1).padStart(2, '0') + '/' +
                                          String(date.getDate()).padStart(2, '0') + ' ' +
                                          String(date.getHours()).padStart(2, '0') + ':' +
                                          String(date.getMinutes()).padStart(2, '0') + ':' +
                                          String(date.getSeconds()).padStart(2, '0');

                        tableBody.append(`
                            <tr>
                                <td>${driverName}</td>
                                <td><span class="badge ${typeBadgeClass}">${typeLabel}</span></td>
                                <td>${parseFloat(transaction.amount).toFixed(2)} ر.س</td>
                                <td>${description}</td>
                                <td>${formattedDate}</td>
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
                        `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page - 1}">السابق</a></li>`
                        );
                }

                for (let i = 1; i <= pagination.last_page; i++) {
                    paginationLinks.append(
                        `<li class="page-item ${pagination.current_page == i ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`
                        );
                }

                if (pagination.next_page_url) {
                    paginationLinks.append(
                        `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page + 1}">التالي</a></li>`
                        );
                }
            }

            // Initial data fetch
            fetch_data();

            // Handle search input
            $('#search').on('keyup', function() {
                let query = $(this).val();
                let type = $('#typeFilter').val();
                fetch_data(1, query, $('#perPage').val(), type);
            });

            // Handle type filter change
            $('#typeFilter').on('change', function() {
                let type = $(this).val();
                let query = $('#search').val();
                fetch_data(1, query, $('#perPage').val(), type);
            });

            // Handle pagination click
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                let page = $(this).data('page');
                let query = $('#search').val();
                let type = $('#typeFilter').val();
                fetch_data(page, query, $('#perPage').val(), type);
            });

            // Handle per page change
            $('#perPage').on('change', function() {
                let perPage = $(this).val();
                let type = $('#typeFilter').val();
                fetch_data(1, $('#search').val(), perPage, type);
            });
        });
    </script>

</body>

</html>
