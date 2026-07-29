<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - الطلبات</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/admin/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="/admin/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="/admin/css/image-uploader.min.css">
    <link rel="stylesheet" href="/admin/css/select2.min.css">
    <link rel="stylesheet" href="/admin/css/style.css">
</head>

<body>
    <!-- ................ start header area ................ -->
    @include('admin.layouts.sidebar')
    <!-- sub header -->
    @include('admin.layouts.header')

    <!-- ................ end header area ................ -->

    <main class="main" id="google_translate_element">
        <section class="mb_16">
        
            <div class="container-fluid px-0">
                <div class="row g_24">
                    <div class="col-12">
                        <div class="bg-white rounded_16 pb_12">
                            <!-- header -->
                            <div class="border-bottom py_12 px_16">
                                <div class="row align-items-center gy_16">
                                    <div class="col-md-5">
                                        <h2 class="fs_18 fw-normal mb-0">الطلبات</h2>
                                    </div>
                                    <div class="col-md-7">
                                        <form id="orderFilters">
                                            <div class="text-end d-flex justify-content-end">
                                                <div class="fle x-grow-1">
                                                    <div class="input-group search_inpGrp border rounded-pill fs_sm overflow-hidden pr_xsm2 ms-auto">
                                                        <button class="btn btn_sm border-0 shadow-none" type="button"><i class="fi fi-rr-search"></i></button>
                                                        <input id="search" class="form-control inp_sm border-0 shadow-none" type="search" placeholder="البحث">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- table -->
                            <div class="overflow-x-auto" >
                                <table class="table table-hover ">
                                    <thead>
                                        <tr>
                                            <th>الطلب</th>
                                            <th>المتجر </th>
                                            <th>السعر </th>
                                            <th>الحالة</th>
                                            <th>تاريخ الإنشاء</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderTableWrapper">
                                        <tr>
                                        <td colspan="7" style="margin-top:100px;">
                                            <div class="loading_box text-center">
                                                <div class="spinner-border c_primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>  
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="dropdown filter_dropdown d-inline-block mr_8">
                                    <select id="perPage" class="form-select form-select-sm">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>
        
        <!-- No Items Template -->
        <div id="noItemsTemplate" class="d-none text-center py-5 text-center">
            <div class="col-12 text-center">
            <img src="/admin/img/no-items.svg" alt="No Items" class="mb-3">
            <p class="fs_18 text-gray">لا توجد طلبات لعرضها</p>
            </div>
        </div>
                <!-- end Products section -->
    </main>


    <!-- ................ end main area ................ -->

    <!-- scripts -->
    <script src="/admin/js/jquery-3.7.0.min.js"></script>
    <script src="/admin/js/bootstrap.bundle.min.js"></script>
    <script src="/admin/js/smooth-scrollbar.js"></script>
    <script src="/admin/js/apexcharts.min.js"></script>
    <script src="/admin/js/ckeditor.js"></script>
    <script src="/admin/js/image-uploader.min.js"></script>
    <script src="/admin/js/select2.min.js"></script>
    <script src="/admin/js/script.js"></script>
    @include("admin.layouts.footer")

    <script>
$(document).ready(function() {

function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}


    // Function to fetch orders
    function fetchOrders(page = 1) {
        var search = $('#search').val();
        var perPage = $('#perPage').val();

        $.ajax({
            url: "{{ route('admin.orders.getOrders') }}",
            data: {
                search: search,
                per_page: perPage,
                page: page
            },
            success: function(response) {
                $('#orderTableWrapper').html(renderOrders(response.orders.data));

                // Update pagination links
                if (response.orders.total > 0) {
                    $('#orderTableWrapper').append(renderPagination(response.orders));
                } else {
                    $('#orderTableWrapper').html($('#noItemsTemplate').html());
                }
            }
        });
    }

    // Render order rows
    function renderOrders(orders) {

        var html = '<table class="table table-hover def_table mb-0"><tbody>';
        $.each(orders, function(index, order) {
            let orderText = "";
            let orderDot="";
            html += `<tr>
    <td>
        <a class="tbl_detailsLink" href="/dashboard/orders/${order.id}">
            <div class="mb_4">${order.client.phone}</div>
            <div class="fs_12 c_gray3"><span>#${order.id}</span></div>
        </a>
    </td>
    <td>
        ${order.merchant?.brand_name ?? "-"} 
    </td>
    <td>
        ${order.total_net_a_pay} SAR
    </td>
    <td>
        ${order.status}
    </td>
    <td>${new Date(order.created_at).toLocaleDateString()}</td>
    <td class="text-end">
        <a href="/dashboard/orders/${order.id}">
            <i class="fi fi-rr-arrow-up-right-from-square"></i>
        </a>
    </td>
</tr>
`;
        });
        html += '</tbody></table>';
        return html;
    }

    // Render pagination links
    function renderPagination(pagination) {
        var html = '<div class="p_16"><nav aria-label="Page navigation example"><ul class="pagination pagination_sm mb-0">';
    
        // Previous page button
        if (pagination.prev_page_url) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page - 1}"><i class="fi fi-rr-angle-small-right"></i></a></li>`;
        }
    
        // Display page numbers with ellipsis
        var pages = [];
        var range = 2; // Number of page links to show before and after current page
        for (var i = 1; i <= pagination.last_page; i++) {
            // Check if the page should be shown
            if (
                i <= range || 
                i >= pagination.current_page - range && i <= pagination.current_page + range || 
                i > pagination.last_page - range
            ) {
                pages.push(i);
            }
        }
    
        // Add "..." where there are gaps
        for (var i = 0; i < pages.length; i++) {
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

    // Initial load
    fetchOrders();

    // Search input change
$('#search').on('input', debounce(function () {
    fetchOrders();
}, 200));    

$('#perPage').on('change', debounce(function () {
    fetchOrders();
}, 500));    

    // Handle pagination click
    $(document).on('click', '.pagination .page-link', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        fetchOrders(page);
    });
});

    </script>
    @include("admin.layouts.footer")
</body>

</html>