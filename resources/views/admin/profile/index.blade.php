@php

$Model_PLURAL = 'المتاجر';
$Model_SINGULAR = 'المتجر';
$Model_API_ROUTE = 'merchants';

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
    <main class="main">
        <!-- start statics section -->
        <section class="mb_16">
            <div class="container-fluid px-0">
                <!-- Success message -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
        
                <form action="{{ route('admin.user.updateProfile') }}" method="POST" enctype="multipart/form-data">
                    @csrf <!-- Include CSRF token for security -->
                    <div class="row g_24">
                        <div class="col-12">
                            <div class="bg-white rounded_16 h-100 p_24">
                                <div class="row g_16">
        
                                    <!-- Store Name (Non-editable) -->
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 text_dark2">صورة المتجر</label>
                                            <input  class="form-control inp_sm" type="file" 
                                            name="image" 
                                            accept="image/jpeg, image/jpg, image/png"   onchange="document.querySelector('.store_img').src = URL.createObjectURL(this.files[0])">
                                            <img class="store_img mt-2 rounded" src="{{ $user->image ? asset($user->image) : '/admin/img/user.svg' }}" alt="Store Image" style="width: 100px; height: 100px; object-fit: cover;">

                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 text_dark2">الإسم</label>
                                            <input type="text" class="form-control inp_sm" name="name" value="{{ $user->name }}">
                                        </div>
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 text_dark2">رقم الهاتف</label>
                                            <input type="text" class="form-control inp_sm" name="phone" value="{{ $user->phone }}">
                                        </div>
                                    </div>
        

                                    <!-- Password Change Section -->
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 text_dark2">كلمة المرور الحالية</label>
                                            <input type="password" class="form-control inp_sm" name="current_password" placeholder="أدخل كلمة المرور الحالية لتأكيد التغييرات">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 text_dark2">كلمة المرور الجديدة</label>
                                            <input type="password" class="form-control inp_sm" name="new_password" placeholder="أدخل كلمة المرور الجديدة">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 text_dark2">تأكيد كلمة المرور الجديدة</label>
                                            <input type="password" class="form-control inp_sm" name="confirm_password" placeholder="تأكيد كلمة المرور الجديدة">
                                        </div>
                                    </div>
                                </div>
                                <!-- Save Button -->
                                <div class="pt_24">
                                    <button class="btn btn_sm btn_primary rounded-pill px-5" type="submit">حفظ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
                <!-- end statics section -->
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
                        renderTable(response.merchants);
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
                            <td colspan="8" class="text-center py-5">
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
                                <td>${model.brand_name}</td>
                                <td><img src="${model.image}" alt="${model.brand_name}" style="width: 50px; height: auto;"></td>
                                <td>${model.type_merchant_id}</td>
                                <td>${model.region_id}</td>
                                <td>${model.city_id}</td>
                                <td>${model.open_at} - ${model.close_at}</td>
                                <td>${model.enable_customization ? 'نعم' : 'لا'}</td>
                                <td>${new Date(model.created_at).toLocaleDateString()}</td>
                                <td>
                                <a href="/dashboard/{{ $Model_API_ROUTE }}/${model.id}/edit" class="btn btn_sm btn_primary">تعديل</a>
                                <button class="btn btn_sm btn_danger" onclick="showDeleteModal(${model.id})">حذف</button>
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
                        `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page - 1}">Previous</a></li>`
                        );
                }

                for (let i = 1; i <= pagination.last_page; i++) {
                    paginationLinks.append(
                        `<li class="page-item ${pagination.current_page == i ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`
                        );
                }

                if (pagination.next_page_url) {
                    paginationLinks.append(
                        `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page + 1}">Next</a></li>`
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

    <script>
        function delete_product(productId) {
            // Redirect to the delete route
            window.location.href = '/dashboard/{{ $Model_API_ROUTE }}/' + productId + '/delete';
        }
    </script>

</body>

</html>
