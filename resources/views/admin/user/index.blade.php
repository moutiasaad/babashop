@php

$Model_PLURAL = 'المستخدمين';
$Model_SINGULAR = 'المستخدم';
$Model_API_ROUTE = 'users';

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
    </style>
</head>

<body>
    @include('admin.layouts.sidebar')
    @include('admin.layouts.header')

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
                                    aria-label="Close">نعم، تأكيد</button>
                            </div>
                            <div class="ms-2">
                                <button class="btn btn_sm btn-light alert__resetBtn" type="reset"
                                    data-bs-dismiss="modal" aria-label="Close">لا، تراجع</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


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
                                            <div class="text-end d-flex justify-content-end">
                                                <div class="flex-grow-1">
                                                    <div
                                                        class="input-group search_inpGrp border rounded-pill fs_sm overflow-hidden pr_xsm2 ms-auto">
                                                        <button class="btn btn_sm border-0 shadow-none"
                                                            type="button"><i class="fi fi-rr-search"></i></button>
                                                        <input class="form-control inp_sm border-0 shadow-none"
                                                            type="search" id="search" placeholder="البحث">
                                                    </div>
                                                </div>
                                                {{-- <div class="dropdown filter_dropdown d-inline-block mr_8">
                                                    <a class="btn btn_sm border rounded-pill"
                                                        href="{{ route('admin.'.$Model_API_ROUTE.'.add') }}">
                                                        <i class="fi fi-rr-filter lh-1"></i> إضافة جديد
                                                    </a>
                                                </div> --}}
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Table and Pagination -->
                            <div id="countryTableContainer" class="overflow-x-auto">
                                <table class="table table-hover def_table mb-0">
                                    <thead>
                                        <tr>
                                            <th>اسم</th>
                                            <th>رقم الهاتف</th>
                                            <th>البريد الإلكتروني</th>
                                            <th>تاريخ الميلاد</th>
                                            <th>الحالة</th>
                                            
                                            <th>تاريخ الإنشاء</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="countryTableBody">
                                        <td colspan="3" style="margin-top:100px;">
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
    <!-- AJAX logic for Search and Pagination -->
    <script>
        let productIdToDelete = null;

        function showDeleteModal(productId) {
            productIdToDelete = productId;
            $('#alert__modal').modal('show');
            $('.alert__title').text('هل أنت متأكد أنك تريد الحذف؟');
            $('.alert__desc').text('لن تتمكن من التراجع عن هذا الإجراء.');
        }

        // Delete the product when confirmed
        $('.alert__submitBtn').on('click', function() {
            delete_product(productIdToDelete);
        });
    </script>

    <script>
        $(document).ready(function() {

            function fetch_data(page = 1, query = '', perPage = 10) {
                $.ajax({
                    url: "{{ route('admin.' . $Model_API_ROUTE . '.index') }}", 
                    method: 'GET',
                    data: {
                        page: page,
                        search: query,
                        per_page: perPage
                    },
                    success: function(response) {
                        renderTable(response.users);
                        renderPagination(response.pagination);
                    }
                });
            }

            function renderTable(models) {
                let tableBody = $('#countryTableBody');
                tableBody.empty();

                if (models.length === 0) {
                    // Append a row with "No Items" message
                    tableBody.append(`
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <img src="/admin/img/no-items.svg" alt="No Items" class="mb-3">
                                <p class="fs_18 text-gray">لا توجد بلدان لعرضها</p>
                            </td>
                        </tr>
                    `);
                } else {
                    models.forEach(model => {
                        console.log(model);
                        tableBody.append(`
                            <tr>
                                <td>${model.fullname}</td>
                                <td>${model.phone}</td>
                                <td>${model.email}</td>
                                <td>${model.birth_date ? model.birth_date : 'غير محدد'}</td>
                                <td>${model.is_verified ? 'مفعل' : 'غير مفعل'}</td>
                                <td>${new Date(model.created_at).toLocaleDateString()}</td>
                                
                            </tr>
                        `);
                    });
                }
            }

    function renderPagination(pagination) {
        let paginationLinks = $('#paginationLinks');
        let html = '<div class="p_16"><nav aria-label="Page navigation example"><ul class="pagination pagination_sm mb-0">';
    
        // Previous page button
        if (pagination.prev_page_url) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page - 1}"><i class="fi fi-rr-angle-small-right"></i></a></li>`;
        }
    
        // Display page numbers with ellipsis
        let pages = [];
        let range = 2;
        for (let i = 1; i <= pagination.last_page; i++) {
            if (
                i <= range || 
                (i >= pagination.current_page - range && i <= pagination.current_page + range) || 
                i > pagination.last_page - range
            ) {
                pages.push(i);
            }
        }
    
        for (let i = 0; i < pages.length; i++) {
            if (i > 0 && pages[i] !== pages[i - 1] + 1) {
                html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            html += `<li class="page-item ${pagination.current_page == pages[i] ? 'active' : ''}"><a class="page-link" href="#" data-page="${pages[i]}">${pages[i]}</a></li>`;
        }
    
        // Next page button
        if (pagination.next_page_url) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page + 1}"><i class="fi fi-rr-angle-small-left"></i></a></li>`;
        }
    
        html += '</ul></nav></div>';
        paginationLinks.html(html);
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

    <script>
        function delete_product(productId) {
            // Redirect to the delete route
            window.location.href = '/dashboard/{{ $Model_API_ROUTE }}/' + productId + '/delete';
        }
    </script>

</body>

</html>
