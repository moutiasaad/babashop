<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/favicon.png" type="image/x-icon">
        <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
        <title>{{ env('APP_NAME') }} - الكوبونات</title>
    
        <!-- stylesheets -->
    <link rel="stylesheet" href="/admin/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="/admin/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="/admin/css/style.css">
    </head>
    
    
<body>
    @include('admin.layouts.sidebar')
    @include('admin.layouts.header')

    <main class="main">
        <section class="mb_16">

            <div class="container-fluid px-0">
                <div class="row g_24">
                    <div class="col-12">
                        <div class="bg-white rounded_16 pb_12">
                            <div class="border-bottom py_12 px_16">
                                <div class="row align-items-center gy_16">
                                    <div class="col-md-5">
                                        <h2 class="fs_18 fw-normal mb-0">الكوبونات</h2>
                                        <div class="fs_14 text_gray6" id="totalItems">الإجمالي: 0</div>
                                    </div>
                                    <div class="col-md-7">
                                        <form action="#">
                                            <div class="text-end d-flex justify-content-end">
                                                <div class="text-end d-flex justify-content-end">
                                                    <div class="flex-grow-1">
                                                        <div class="input-group search_inpGrp border rounded-pill fs_sm overflow-hidden pr_xsm2 ms-auto">
                                                            <button class="btn btn_sm border-0 shadow-none" type="button"><i class="fi fi-rr-search"></i></button>
                                                            <input id="search" class="form-control inp_sm border-0 shadow-none" type="search" placeholder="البحث">
                                                        </div>
                                                    </div>
                                                </div>
                                            <div class="dropdown filter_dropdown d-inline-block mr_8">
                                                    <a class="btn btn_sm border rounded-pill" type="button" href="/dashboard/coupon/add">
                                                        <i class="fi fi-rr-add lh-1"></i> أضف كوبون جديد
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="overflow-x-auto" id="couponTableWrapper">
                                <div class="loading_box text-center py-5">
                                    <div class="spinner-border c_primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        <div id="paginationWrapper" class="mt-3"></div>

                            <!-- No Items Template -->
                            <div id="noItemsTemplate" class="d-none text-center py-5 text-center">
                                <div class="col-12 text-center">
                                    <img src="/admin/img/no-items.svg" alt="No Items" class="mb-3">
                                    <p class="fs_18 text-gray">لا توجد كوبونات لعرضها</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
<div class="modal fade modal_sm" id="confirm_product_delete_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded_16">
            <form class="w-100" action="{{ route('admin.coupon.destroy')}}" method="POST">
            <div class="modal-body p-3">
                <div class="alert__icon">
                    <img src="/assets/img/alert.png" alt="">
                 </div>
                <h6 class="alert__title">تاكيد تغيير حالة الكوبون </h6>
                <p class="alert__desc"></p>
                <input class="d-none temp_product_id" type="text">
                <input class=" temp_product_id"  name="temp_product_id" type="hidden">

                <div>
                    <div class="d-flex justify-content-center">
                        <div>
                            <button class="btn btn_sm btn-danger bg_danger" type="submit"  data-bs-dismiss="modal" aria-label="Close">نعم، تأكيد</button>
                        </div>
                        <div class="ms-2">
                            <button class="btn btn_sm btn-light alert__resetBtn" type="reset" data-bs-dismiss="modal" aria-label="Close">لا، تراجع</button>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>


    <!-- Scripts -->
    <script src="/admin/js/jquery-3.7.0.min.js"></script>
    <script src="/admin/js/bootstrap.bundle.min.js"></script>
    @include('admin.layouts.footer')

    
<script>
$(document).ready(function() {
    // Initial fetch of coupons
    fetchCoupons();

    // Function to handle product deletion
    function delete_product(product_id) {
        $(".temp_product_id").val(product_id);
        $("#confirm_product_delete_modal").modal("show");
    }
    window.delete_product = delete_product;

    // Fetch coupons when the search input changes
    $('#search').on('input', function() {
        fetchCoupons();
    });

    // Handle pagination link clicks
    $(document).on('click', '.pagination .page-link', function(event) {
        event.preventDefault();
        let page = $(this).data('page');
        if (page) {
            fetchCoupons(page);
        }
    });

    // Fetch coupons from the server
    function fetchCoupons(page = 1) {
        const search = $('#search').val();

        // Show loading spinner
        $('#couponTableWrapper').html(`
            <div class="loading_box text-center py-5">
                <div class="spinner-border c_primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);

        $.ajax({
            url: '{{ route("admin.coupons.getData") }}',
            type: 'GET',
            data: {
                search: search,
                page: page
            },
            success: function(response) {
                let rows = '';
                if (response.data.length > 0) {
                    // Generate table rows
                    response.data.forEach(function(item, index) {
                        const discountType = item.discount_type === "percent" ? '%' : 'SAR';
                        const status = item.active == 0
                            ? '<div class="btn rounded-pill border text_danger bg_dangerLt fs_16 py-1 px-4">غير مفعل</div>'
                            : '<div class="btn rounded-pill border text_success bg_successLt fs_16 py-1 px-4">مفعل</div>';

                        rows += `
                            <tr>
                                <td>${(page - 1) * response.per_page + index + 1}</td>
                                <td>${item.code}</td>
                                <td>${item.discount} ${discountType}</td>
                                <td>${item.description ?? "لا يوجد وصف"}</td>
                                <td style="text-align:right;">${status}</td>
                                <td style="text-align:right;">${item.formatted_date}</td>
                                <td>
                                    <button class="btn" onclick="delete_product(${item.id})"><i class="fi fi-rr-ban"></i></button>
                                </td>
                            </tr>
                        `;
                    });

                    // Render the table
                    $('#couponTableWrapper').html(`
                        <table class="table table-hover def_table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الكوبون</th>
                                    <th>قيمة الخصم</th>
                                    <th>الوصف</th>
                                    <th>حالة الكوبون</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    `);

                    // Render pagination links
                    $('#paginationWrapper').html(renderPagination(response));
                } else {
                    // Show "No Items" template if no data is returned
                    $('#couponTableWrapper').html($('#noItemsTemplate').html());
                    $('#paginationWrapper').html('');
                }

                // Update total items count
                $('#totalItems').text('الإجمالي: ' + response.total);
            },
            error: function() {
                alert("حدث خطأ أثناء جلب البيانات.");
            }
        });
    }

    // Render pagination links
    function renderPagination(pagination) {
        let html = '<div class="p_16"><nav aria-label="Page navigation example"><ul class="pagination pagination_sm mb-0">';

        // Previous page button
        if (pagination.prev_page_url) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page - 1}"><i class="fi fi-rr-angle-small-right"></i></a></li>`;
        }

        // Display page numbers with ellipsis
        let pages = [];
        let range = 2; // Number of page links to show before and after current page
        for (let i = 1; i <= pagination.last_page; i++) {
            if (
                i <= range ||
                i >= pagination.current_page - range && i <= pagination.current_page + range ||
                i > pagination.last_page - range
            ) {
                pages.push(i);
            }
        }

        // Add "..." where there are gaps
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
        return html;
    }
});

// Function to edit a coupon
function editCoupon(id) {
    $.ajax({
        url: '/dashboard/coupons/' + id + '/edit',
        type: 'GET',
        success: function(response) {
            $('#editCouponModal #coupon_id').val(response.id);
            $('#editCouponModal #coupon_code').val(response.code);
            $('#editCouponModal #coupon_discount').val(response.discount);
            $('#editCouponModal #coupon_expires_at').val(response.expires_at);
            $('#editCouponModal').modal('show');
        },
        error: function() {
            alert("حدث خطأ أثناء جلب بيانات الكوبون.");
        }
    });
}

// Function to delete a coupon
function deleteCoupon(id) {
    if (confirm('هل أنت متأكد من حذف هذا الكوبون؟')) {
        $.ajax({
            url: '/dashboard/coupons/' + id,
            type: 'DELETE',
            success: function() {
                fetchCoupons(); // Refresh the table after deletion
            },
            error: function() {
                alert("حدث خطأ أثناء حذف الكوبون.");
            }
        });
    }
}
</script>
<!-- Pagination Wrapper -->
</body>

</html>
