<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/admin/img/icon-lovard.png">
    <title>{{ env('APP_NAME') }} - الرئيسية</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/admin/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="/admin/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="/admin/css/dropzone.min.css">
    <link rel="stylesheet" href="/admin/css/select2.min.css">
    <link rel="stylesheet" href="/admin/css/style.css?v=1">
</head>

<body>
    <!-- ................ start header area ................ -->
    @include('admin.layouts.sidebar')
    <!-- sub header -->
    @include('admin.layouts.header')
    <!-- alerts -->
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
                                <button class="btn btn_sm btn_primary alert__submitBtn" type="submit"  data-bs-dismiss="modal" aria-label="Close">نعم، تأكيد</button>
                            </div>
                            <div class="ms-2">
                                <button class="btn btn_sm btn-light alert__resetBtn" type="reset" data-bs-dismiss="modal" aria-label="Close">لا، تراجع</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ................ end header area ................ -->

    <!-- ................ start main area ................ -->
    <main class="main">
        <!-- start statics section -->
        <section class="mb_16">
            <div class="container-fluid px-0">
                <div class="row g_24">
                    
                    <!-- Total sales -->
                    <div class="col-md-6 col-lg-6">
                        <div class="chart_card">
                            <div class="chart_header">
                                <div class="chart_iconBox c_warning2 bg_warning2Lt">
                                    <i class="fi fi-sr-shopping-cart"></i>
                                </div>
                                <div class="chart_info">
                                    <div class="chart_title">إجمالي المبيعات</div>
                                    <div class="chart_value">{{ $totalOrders ?? 0 }}</div>
                                </div>
                                <div class="chart_extraInfo c_success">
                                    +{{ $dailySales ?? 0 }} مبيعة اليوم
                                </div>
                            </div>
                            <div class="chart_wrapper">
                                <div id="total_sales_chart"></div>
                            </div>
                        </div>
                    </div>
        
                    <!-- Total profits -->
                    <div class="col-md-6 col-lg-6">
                        <div class="chart_card">
                            <div class="chart_header">
                                <div class="chart_iconBox c_success bg_success2Lt">
                                    <i class="fi fi-sr-investment"></i>
                                </div>
                                <div class="chart_info">
                                    <div class="chart_title">إجمالي المعاملات</div>
                                    <div class="chart_value">SAR {{ number_format($totalSales ?? 0, 2) }}</div>
                                </div>
                                <div class="chart_extraInfo c_success">
                                    +SAR{{ $dailyProfits ?? 0 }} اليوم
                                </div>
                            </div>
                            <div class="chart_wrapper">
                                <div id="total_profits_chart"></div>
                            </div>
                        </div>
                    </div>
    
                    <!-- Number of sales/month -->
                    <div class="col-md-6 col-lg-7">
                        <div class="bg-white rounded_16 h-100 d-flex flex-column">
                            <div class="border-bottom py_12 px_16">
                                <h2 class="fs_18 fw-normal mb-0">عدد المبيعات / شهر</h2>
                            </div>
                            <div class="p_12 pt-1 flex-grow-1">
                                <div id="sales_chart_1" class="sales_chart"></div>
                            </div>
                        </div>
                    </div>
    
                    <!-- Statistics -->
                    <div class="col-md-6 col-lg-5">
                        <div class="bg-white rounded_16 h-100 d-flex flex-column">
                            <div class="border-bottom py_12 px_16">
                                <h2 class="fs_18 fw-normal mb-0">إحصائيات</h2>
                            </div>
                            <div class="p_12 pt-1 flex-grow-1">
                                <div id="statistics_chart" class="statistics_chart"></div>
                            </div>
                        </div>
                    </div>
                    
                    </div>
            </div>

                </div>
                
            </div>
            
        </section>
        <!-- end statics section -->
    </main>
        <!-- ................ end main area ................ -->
    <!-- ................ start help desk area ................ -->


    <!-- ................ end help desk area ................ -->

    <!-- scripts -->
    <script src="/admin/js/jquery-3.7.0.min.js"></script>
    <script src="/admin/js/bootstrap.bundle.min.js"></script>
    <script src="/admin/js/smooth-scrollbar.js"></script>
    <script src="/admin/js/apexcharts.min.js"></script>
    <script src="/admin/js/ckeditor.js"></script>
    <script src="/admin/js/dropzone.min.js"></script>
    <script src="/admin/js/select2.min.js"></script>
    <script src="/admin/js/jquery-sortable.js"></script>
    <script src="/admin/js/script.js?v=4"></script>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const chartData = @json($stats);

        // ----- Sales Chart
        if ($("#sales_chart_1").length > 0) {
            var sales_chart = new ApexCharts(document.querySelector("#sales_chart_1"), {
                series: [
                    {
                        name: "المبيعات الشهرية",
                        data: chartData.monthlyOrders,
                    },
                ],
                xaxis: {
                    categories: chartData.months,
                    labels: {
                        style: {
                            fontSize: "var(--fs_12)",
                            foreColor: "#626D73"
                        }
                    }
                },
                chart: {
                    type: "bar",
                    height: "100%",
                },
                colors: ["#0778B5"],
                plotOptions: {
                    bar: {
                        columnWidth: "55%",
                        borderRadius: 4,
                    },
                },
            });
            sales_chart.render();
        }

        // ----- Total Sales Chart
        if ($("#total_sales_chart").length > 0) {
            var total_sales_chart = new ApexCharts(document.querySelector("#total_sales_chart"), {
                series: [
                    {
                        name: "المبيعات الشهرية",
                        data: chartData.monthlyOrders,
                    },
                ],
                xaxis: {
                    categories: chartData.months,
                },
                chart: {
                    type: "area",
                    height: "100%",
                },
                colors: ["#E89806"],
                stroke: {
                    curve: "smooth",
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100],
                    },
                },
            });
            total_sales_chart.render();
        }

        // ----- Total Products Chart
        if ($("#total_products_chart").length > 0) {
            var total_products_chart = new ApexCharts(document.querySelector("#total_products_chart"), {
                series: [
                    {
                        name: "الطلبات الشهرية",
                        data: chartData.monthlyOrders,
                    },
                ],
                xaxis: {
                    categories: chartData.months,
                },
                chart: {
                    type: "area",
                    height: "100%",
                },
                colors: ["#5B93FF"],
                stroke: {
                    curve: "smooth",
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100],
                    },
                },
            });
            total_products_chart.render();
        }

        // ----- Total Profits Chart
        if ($("#total_profits_chart").length > 0) {
            var total_profits_chart = new ApexCharts(document.querySelector("#total_profits_chart"), {
                series: [
                    {
                        name: "الأرباح الشهرية",
                        data: chartData.monthlyProfits,
                    },
                ],
                xaxis: {
                    categories: chartData.months,
                },
                chart: {
                    type: "area",
                    height: "100%",
                },
                colors: ["#2B9943"],
                stroke: {
                    curve: "smooth",
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100],
                    },
                },
            });
            total_profits_chart.render();
        }

        // ----- Statistics Chart
        if ($("#statistics_chart").length > 0) {
            var statistics_chart = new ApexCharts(document.querySelector("#statistics_chart"), {
                series: chartData.recentStatistics,
                labels: ["مكتملة", "قيد التنفيذ", "ملغاة"],
                chart: {
                    type: "donut",
                    height: "400px",
                },
                legend: {
                    position: "bottom",
                },
            });
            statistics_chart.render();
        }
    });
</script>


</html>