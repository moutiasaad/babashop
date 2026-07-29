<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - إعدادات المتجر</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/user/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="/user/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="/user/css/image-uploader.min.css">
    <link rel="stylesheet" href="/user/css/select2.min.css">
    <link rel="stylesheet" href="/user/css/style.css">
    <link rel="stylesheet" href="/user/css/toastr.css">

</head>

<body>
    <!-- ................ start header area ................ -->
    @include('user.layouts.sidebar')
    <!-- sub header -->
    @include('user.layouts.header')

    <!-- ................ end header area ................ -->

    <!-- ................ start main area ................ -->
    <main class="main">
        <!-- start statics section -->
        <section class="mb_16">
            <div class="container-fluid px-0">
    
                <!-- Account Settings -->
                <h3 class="fs_22 mb_14">
                    إعدادات الحساب
                </h3>
                <div class="row g_24 mb-5">
                    {{-- <div class="col-sm-6 col-md-4 col-lg-3">
                        <a class="setting_item" href="/dashboard/settings/general">
                            <div class="setting_icon">
                                <i class="fi fi-rr-diamond"></i>
                            </div>
                            <div class="setting_title">إدارة الإشتراك</div>
                            <div class="setting_desc">إدارة اشتراكك</div>
                        </a>
                    </div> --}}
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <a class="setting_item" href="/dashboard/profile">
                            <div class="setting_icon">
                                <i class="fi fi-rr-user"></i>
                            </div>
                            <div class="setting_title">إعدادات الحساب</div>
                            <div class="setting_desc">تعديل بيانات الحساب</div>
                        </a>
                    </div>
                </div>
    
                <!-- Basic Settings -->
                <h3 class="fs_22 mb_14">
                    الإعدادات الأساسية
                </h3>
                <div class="row g_24">
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <a class="setting_item" href="/dashboard/settings/general">
                            <div class="setting_icon">
                                <i class="fi fi-rr-shop"></i>
                            </div>
                            <div class="setting_title">الإعدادات الاساسية</div>
                            <div class="setting_desc">الرابط , الشعار , الإسم</div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <a class="setting_item" href="/dashboard/settings/payments">
                            <div class="setting_icon">
                                <i class="fi fi-rr-credit-card"></i>
                            </div>
                            <div class="setting_title">طرق الدفع</div>
                            <div class="setting_desc">تفعيل بوابة الدفع والحسابات البنكية</div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <a class="setting_item" href="/dashboard/settings/delivary">
                            <div class="setting_icon">
                                <i class="fi fi-rr-truck-side"></i>
                            </div>
                            <div class="setting_title">خيارات الشحن و التوصيل</div>
                            <div class="setting_desc">تفعيل خيارات الشحن والتوصيل</div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <a class="setting_item" href="/dashboard/settings/domain">
                            <div class="setting_icon">
                                <i class="fi fi-rr-globe"></i>
                            </div>
                            <div class="setting_title">إعدادات الدومين</div>
                            <div class="setting_desc">التحكم في الدومين</div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="setting_item" data-bs-toggle="modal" data-bs-target="#comfirm_maintenance_mode_modal">
                            <div class="setting_icon">
                                <i class="fi fi-rr-tools"></i>
                            </div>
                            <div class="setting_title">وضع الصيانة</div>
                            <div class="setting_desc">
                                {{ auth()->check() && auth()->user()->disabled == 1 ? 'المتجر مغلق بشكل مؤقت' : 'إغلاق المتجر بشكل مؤقت' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <a class="setting_item" href="/dashboard/themes">
                            <div class="setting_icon">
                                <i class="fi fi-rr-palette"></i>
                                                        </div>
                            <div class="setting_title">تخصيص المتجر</div>
                            <div class="setting_desc">التحكم في التصميم الخارجي للمتجر</div>
                        </a>
                    </div>

                </div>
            </div>
        </section>
        <!-- end statics section -->
    </main>
        <!-- ...... start comfirm delete product modal ...... -->
    <div class="modal fade modal_sm" id="comfirm_maintenance_mode_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="w-100"  method="post" action="{{route('user.settings.toggleMaintenanceMode') }}">
                <div class="modal-content border-0 rounded_6">
                    <div class="modal-header bg_light2 py_12 px_16">
                        <div class="pe-1">
                            وضع الصيانة
                        </div>
                        <div>
                            <button type="reset" class="btn close_btn" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fi fi-rr-cross-small"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="user_id" name="user_id" value="">
                        <h6 class="fs_14 text_dark2 fw-medium">هل انت متأكد من تحويل الموقع إلى وضع الصيانة؟</h6>
                        <p class="fs_14 fs_gray2">يرجى العلم بأنه عند الموافقة سيتم إيقاف الموقع عن العمل.</p>
                        <div class="pt_24">
                            <div class="d-flex justify-content-end">
                                <div class="me-2">
                                    <button class="btn btn_sm btn-outline-secondary" type="button" data-bs-dismiss="modal" aria-label="Close">لا، تراجع</button>
                                </div>
                                <div>
                                    <button class="btn btn_sm btn-danger bg_danger" type="submit">نعم، تأكيد</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    

    <!-- ................ end main area ................ -->

    <!-- scripts -->
    <script src="/user/js/jquery-3.7.0.min.js"></script>
    <script src="/user/js/bootstrap.bundle.min.js"></script>
    <script src="/user/js/smooth-scrollbar.js"></script>
    <script src="/user/js/apexcharts.min.js"></script>
    <script src="/user/js/ckeditor.js"></script>
    <script src="/user/js/image-uploader.min.js"></script>
    <script src="/user/js/select2.min.js"></script>
    <script src="/user/js/script.js"></script>

    @include("user.layouts.footer")
</body>

</html>