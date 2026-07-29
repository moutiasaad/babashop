<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - لوحة التحكم</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/user/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="/user/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="/user/css/image-uploader.min.css">
    <link rel="stylesheet" href="/user/css/select2.min.css">
    <link rel="stylesheet" href="/user/css/style.css">
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
            <h2 class="fs_36 text-black fw-bold fw-normal mb_24">اللغات</h2>
            <div class="container-fluid px-0">
                <form action="#">
                    <div class="row g_24">
                        <!--  -->
                        <div class="col-12">
                            <div class="bg-white rounded_16 h-100">
                                <div class="border-bottom py_16 px_24">
                                    <h3 class="fs_18 fw-normal mb-0">اللغات</h3>
                                </div>
                                <div class="py_16 px_24">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex jutify-content-between py_8 border-bottom">
                                                <div class="pe-2 fs_16 flex-grow-1">
                                                    العربية
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" checked>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-12">
                                            <div class="d-flex jutify-content-between py_8 border-bottom">
                                                <div class="pe-2 fs_16 flex-grow-1">
                                                    الانجليزية
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" checked>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex jutify-content-between py_8 border-bottom">
                                                <div class="pe-2 fs_16 flex-grow-1">
                                                    الإسبانية
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" checked>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex jutify-content-between py_8 border-bottom">
                                                <div class="pe-2 fs_16 flex-grow-1">
                                                    الفرنسية
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" checked>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex jutify-content-between py_8">
                                                <div class="pe-2 fs_16 flex-grow-1">
                                                    الإيطالية
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" checked>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <!-- end statics section -->
    </main>
    <!-- ................ end main area ................ -->
<!-- ...... start ready product modal ...... -->
<div class="modal fade modal_md" id="ready_product_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="w-100" action="#">
            <div class="modal-content border-0 rounded_6">
                <div class="modal-header bg_light2 py_12 px_16">
                    <div class="pe-1">
                        <i class="fi fi-rr-dice me-1"></i> بيانات منتج جاهز  
                    </div>
                    <div>
                        <button type="reset" class="btn close_btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fi fi-rr-cross-small"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="btn-group w-100 mb_16" id="readyPrd-tabs" role="tablist">
                        <button type="button" class="btn btn_sm w-50 tab_btn active" id="readyPrd-tab1" data-bs-toggle="tab" data-bs-target="#readyPrd-tab1-pane" type="button" role="tab"><i class="fi fi-rr-memo me-2"></i>بيانات المنتج</button>
                        <button type="button" class="btn btn_sm w-50 tab_btn" id="readyPrd-tab2" data-bs-toggle="tab" data-bs-target="#readyPrd-tab2-pane" type="button" role="tab"><i class="fi fi-rr-add-image me-2"></i>صور وفيديوهات المنتج</button>
                    </div>
                    <div class="tab-content" id="readyPrd-tabsContent">
                        <!-- step/tab 1 -->
                        <div class="tab-pane fade show active" id="readyPrd-tab1-pane" role="tabpanel" aria-labelledby="readyPrd-tabs" tabindex="0">
                            <div class="row g_10">
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">إسم المنتج</label>
                                        <input type="text" class="form-control inp_sm" placeholder="ادخل إسم منتج">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">وزن المنتج</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control inp_sm" placeholder="ادخل وزن المنتج ">
                                            <div>
                                                <select class="form-select select_sm select_2">
                                                    <option value="1">كجم</option>
                                                    <option value="2">جنيه</option>
                                                    <option value="3">غرام</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">السعر</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control inp_sm" placeholder="ادخل سعر المنتج ">
                                            <div>
                                                <select class="form-select select_sm select_2">
                                                    <option value="1">ر.س</option>
                                                    <option value="2">دولار</option>
                                                    <option value="3">تاكا</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">السعر المخفض</label>
                                        <input type="text" class="form-control inp_sm" placeholder="ادخل السعر المخفض ( إختياري )">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">نهاية التخفيض</label>
                                        <input type="text" class="form-control inp_sm" placeholder="نهاية التخفيض ( إختياري )">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">رقم التخزين</label>
                                        <input type="text" class="form-control inp_sm" placeholder="ادخل رقم التخزين ">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">MPN</label>
                                        <input type="text" class="form-control inp_sm" placeholder="MPN">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">الكمية</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control inp_sm" placeholder="ادخل الكمية">
                                            <span class="input-group-text fs_14">
                                                <i class="fi fi-rr-infinity"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">التصنيف</label>
                                        <div class="input-group">
                                            <div class="flex-grow-1">
                                                <select class="form-select select_sm select_2">
                                                    <option selected disabled>إختر تصنيف المنتج</option>
                                                    <option value="1">إختر تصنيف المنتج</option>
                                                    <option value="2">إختر تصنيف المنتج</option>
                                                    <option value="3">إختر تصنيف المنتج</option>
                                                </select>
                                            </div>
                                            <span class="input-group-text fs_14 add_new_ctg__modalOpener cursor_pointer" >أضف تصنيف</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">الوصف</label>
                                        <div class="cke_rich_editor cke_rtl">
                                            <h2>عنوان تجريبي</h2>
                                            <p>هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها التطبيق.إذا كنت تحتاج إلى عدد أكبر من الفقرات يتيح لك مولد النص العربى زيادة عدد الفقرات كما تريد، النص لن يبدو مقسما ولا يحوي أخطاء لغوية، مولد النص العربى مفيد لمصممي المواقع على وجه الخصوص، حيث يحتاج العميل فى كثير من الأحيان أن يطلع على صورة حقيقية لتصميم الموقع.ومن هنا وجب على المصمم أن يضع نصوصا مؤقتة على التصميم ليظهر للعميل الشكل كاملاً،دور مولد النص العربى أن يوفر على المصمم عناء البحث عن نص بديل لا علاقة له بالموضوع الذى يتحدث عنه التصميم فيظهر بشكل لا يليق.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="py_12 px_16 d-block border rounded_6 mt-2 fs_14">
                                        <input class="form-check-input me-2" type="checkbox">
                                        امكانية كتابة ملاحظة
                                    </label>
                                </div>
                            </div>
                            <div class="pt_24">
                                <div class="d-flex justify-content-between">
                                    <div class="me-2">
                                        <button class="btn btn_sm btn_primary" type="button">حفظ بيانات المنتج</button>
                                    </div>
                                    <div>
                                        <button class="btn btn_sm btn_primary_outline px-sm-4" type="reset" data-bs-dismiss="modal" aria-label="Close">الغاء</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- step/tab 2 -->
                        <div class="tab-pane fade" id="readyPrd-tab2-pane" role="tabpanel" aria-labelledby="readyPrd-tabs" tabindex="0">
                            <div class="row g_10">
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2 mb_4">صور المنتج</label>
                                        <div class="fs_10 text_gray5 mb_8">المقاس لا يقل عن (100px ارتفاع * 250px عرض) من نوع ( jpg, jpeg, png , gif ) ولا يتجاوز 5 ميجابيت لكل صوره بحد اقصي 10 صور</div>
                                        <div id="ready_product_images" data-hide-input-name="product_images"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="pt_6 mb_16">
                                        <div class="input-group">
                                            <input type="text" class="form-control inp_sm" placeholder="أضف رابط فيديو من اليوتيوب">
                                            <span class="input-group-text fs_14">
                                                اضافة
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row g_10">
                                        <div class="col-6 col-sm-4">
                                            <div class="border rounded-1 overflow-hidden">
                                                <img class="img-fluid w-100" src="/user/img/product.png" alt="">
                                                <div class="p-2 bg_light2 border-top mt-2 d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <label>
                                                            <input class="form-check-input me-1" type="radio" name="ready_prdItem">
                                                            <span class="fs_10 text_gray5">الصورة الاساسية</span>
                                                        </label>
                                                    </div>
                                                    <div class="ps-1">
                                                        <button class="btn btn_sm p-1 lh-1 btn-outline-danger" type="button">
                                                            <i class="fi fi-rr-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4">
                                            <div class="border rounded-1 overflow-hidden">
                                                <img class="img-fluid w-100" src="/user/img/product.png" alt="">
                                                <div class="p-2 bg_light2 border-top mt-2 d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <label>
                                                            <input class="form-check-input me-1" type="radio" name="ready_prdItem">
                                                            <span class="fs_10 text_gray5">الصورة الاساسية</span>
                                                        </label>
                                                    </div>
                                                    <div class="ps-1">
                                                        <button class="btn btn_sm p-1 lh-1 btn-outline-danger" type="button">
                                                            <i class="fi fi-rr-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4">
                                            <div class="border rounded-1 overflow-hidden">
                                                <img class="img-fluid w-100" src="/user/img/product.png" alt="">
                                                <div class="p-2 bg_light2 border-top mt-2 d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <label>
                                                            <input class="form-check-input me-1" type="radio" name="ready_prdItem">
                                                            <span class="fs_10 text_gray5">الصورة الاساسية</span>
                                                        </label>
                                                    </div>
                                                    <div class="ps-1">
                                                        <button class="btn btn_sm p-1 lh-1 btn-outline-danger" type="button">
                                                            <i class="fi fi-rr-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt_24">
                                <div class="d-flex justify-content-between">
                                    <div class="me-2">
                                        <button class="btn btn_sm btn_primary" type="button">حفظ بيانات المنتج</button>
                                    </div>
                                    <div>
                                        <button class="btn btn_sm btn-outline-secondary px-sm-4" type="reset" data-bs-dismiss="modal" aria-label="Close">إغلاق</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ...... end ready product modal ...... -->

<!-- add new category modal -->
<div class="modal fade modal_md" id="add_new_ctg_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="w-100" action="#">
            <div class="modal-content border-0 rounded_6">
                <div class="modal-header bg_light2 py_12 px_16">
                    <div class="pe-1">
                        <i class="fi fi-rr-dice me-1"></i> اضافة تصنيف جديد  
                    </div>
                    <div>
                        <button type="reset" class="btn close_btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fi fi-rr-cross-small"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row g_10">
                        <div class="col-12">
                            <div>
                                <label class="form-label fs_14 text_dark2">اسم التصنيف</label>
                                <input type="text" class="form-control inp_sm" placeholder="ادخل اسم التصنيف">
                            </div>
                        </div>
                        <div class="col-12">
                            <div>
                                <label class="form-label fs_14 text_dark2">رفع صورة (إختياري)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control inp_sm" placeholder="">
                                    <span class="input-group-text fs_14">
                                        اضافة
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="py_12 px_16 d-block border rounded_6 mt-2 fs_14">
                                <input class="form-check-input me-2" type="checkbox">
                                اضافة الى تصنيف رئيسي
                            </label>
                        </div>
                        <div class="col-12">
                            <div>
                                <label class="form-label fs_14 text_dark2">التصنيف الرئيسي</label>
                                <select class="form-select select_sm select_2">
                                    <option selected disabled>تحديد التصنيف الرئيسية</option>
                                    <option value="2">جنيه</option>
                                    <option value="3">غرام</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="pt_24">
                        <div class="d-flex justify-content-between">
                            <div class="me-2">
                                <button class="btn btn_sm btn_primary" type="button">إضافة التصنيف</button>
                            </div>
                            <div>
                                <button class="btn btn_sm btn_primary_outline px-sm-4" type="reset" data-bs-dismiss="modal" aria-label="Close">الغاء</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ...... end add new category modal ...... -->
<!-- ...... start digital product modal ...... -->
<div class="modal fade modal_lg" id="digital_product_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="w-100" action="#">
            <div class="modal-content border-0 rounded_6">
                <div class="modal-header bg_light2 py_12 px_16">
                    <div class="pe-1">
                        <i class="fi fi-rr-gift-card me-1"></i> بيانات منتج رقمي
                    </div>
                    <div>
                        <button type="reset" class="btn close_btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fi fi-rr-cross-small"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="btn-group w-100 mb_16" id="digitalPrd-tabs" role="tablist">
                        <button type="button" class="btn btn_sm w_33 tab_btn active" id="digitalPrd-tab1" data-bs-toggle="tab" data-bs-target="#digitalPrd-tab1-pane" type="button" role="tab"><i class="fi fi-rr-memo me-2"></i>بيانات المنتج</button>
                        <button type="button" class="btn btn_sm w_33 tab_btn" id="digitalPrd-tab2" data-bs-toggle="tab" data-bs-target="#digitalPrd-tab2-pane" type="button" role="tab"><i class="fi fi-rr-add-image me-2"></i>صور وفيديوهات المنتج</button>
                        <button type="button" class="btn btn_sm w_33 tab_btn" id="digitalPrd-tab3" data-bs-toggle="tab" data-bs-target="#digitalPrd-tab3-pane" type="button" role="tab"><i class="fi fi-rr-shopping-cart me-2"></i> الملفات </button>
                    </div>
                    <div class="tab-content" id="digitalPrd-tabsContent">
                        <!-- step/tab 1 -->
                        <div class="tab-pane fade show active" id="digitalPrd-tab1-pane" role="tabpanel" aria-labelledby="digitalPrd-tabs" tabindex="0">
                            <div class="row g_10">
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">إسم المنتج</label>
                                        <input type="text" class="form-control inp_sm" placeholder="ادخل إسم منتج">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">وزن المنتج</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control inp_sm" placeholder="ادخل وزن المنتج ">
                                            <div>
                                                <select class="form-select select_sm select_2">
                                                    <option value="1">كجم</option>
                                                    <option value="2">جنيه</option>
                                                    <option value="3">غرام</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">السعر</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control inp_sm" placeholder="ادخل سعر المنتج ">
                                            <div>
                                                <select class="form-select select_sm select_2">
                                                    <option value="1">ر.س</option>
                                                    <option value="2">دولار</option>
                                                    <option value="3">تاكا</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">السعر المخفض</label>
                                        <input type="text" class="form-control inp_sm" placeholder="ادخل السعر المخفض ( إختياري )">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">نهاية التخفيض</label>
                                        <input type="text" class="form-control inp_sm" placeholder="نهاية التخفيض ( إختياري )">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">رقم التخزين</label>
                                        <input type="text" class="form-control inp_sm" placeholder="ادخل رقم التخزين ">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">MPN</label>
                                        <input type="text" class="form-control inp_sm" placeholder="MPN">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">الكمية</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control inp_sm" placeholder="ادخل الكمية">
                                            <span class="input-group-text fs_14">
                                                <i class="fi fi-rr-infinity"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">التصنيف</label>
                                        <div class="input-group">
                                            <div class="flex-grow-1">
                                                <select class="form-select select_sm select_2">
                                                    <option selected disabled>إختر تصنيف المنتج</option>
                                                    <option value="1">إختر تصنيف المنتج</option>
                                                    <option value="2">إختر تصنيف المنتج</option>
                                                    <option value="3">إختر تصنيف المنتج</option>
                                                </select>
                                            </div>
                                            <span class="input-group-text fs_14 add_new_ctg__modalOpener cursor_pointer" >أضف تصنيف</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">الوصف</label>
                                        <div class="cke_rich_editor cke_rtl">
                                            <h2>عنوان تجريبي</h2>
                                            <p>هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها التطبيق.إذا كنت تحتاج إلى عدد أكبر من الفقرات يتيح لك مولد النص العربى زيادة عدد الفقرات كما تريد، النص لن يبدو مقسما ولا يحوي أخطاء لغوية، مولد النص العربى مفيد لمصممي المواقع على وجه الخصوص، حيث يحتاج العميل فى كثير من الأحيان أن يطلع على صورة حقيقية لتصميم الموقع.ومن هنا وجب على المصمم أن يضع نصوصا مؤقتة على التصميم ليظهر للعميل الشكل كاملاً،دور مولد النص العربى أن يوفر على المصمم عناء البحث عن نص بديل لا علاقة له بالموضوع الذى يتحدث عنه التصميم فيظهر بشكل لا يليق.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="py_12 px_16 d-block border rounded_6 mt-2 fs_14">
                                        <input class="form-check-input me-2" type="checkbox">
                                        امكانية كتابة ملاحظة
                                    </label>
                                </div>
                            </div>
                            <div class="pt_24">
                                <div class="d-flex justify-content-between">
                                    <div class="me-2">
                                        <button class="btn btn_sm btn_primary" type="button">حفظ بيانات المنتج</button>
                                    </div>
                                    <div>
                                        <button class="btn btn_sm btn_primary_outline px-sm-4" type="reset" data-bs-dismiss="modal" aria-label="Close">الغاء</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- step/tab 2 -->
                        <div class="tab-pane fade" id="digitalPrd-tab2-pane" role="tabpanel" aria-labelledby="digitalPrd-tabs" tabindex="0">
                            <div class="row g_10">
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2 mb_4">صور المنتج</label>
                                        <div class="fs_10 text_gray5 mb_8">المقاس لا يقل عن (100px ارتفاع * 250px عرض) من نوع ( jpg, jpeg, png , gif ) ولا يتجاوز 5 ميجابيت لكل صوره بحد اقصي 10 صور</div>
                                        <div id="digital_product_images" data-hide-input-name="product_images"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="pt_6 mb_16">
                                        <div class="input-group">
                                            <input type="text" class="form-control inp_sm" placeholder="أضف رابط فيديو من اليوتيوب">
                                            <span class="input-group-text fs_14">
                                                اضافة
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row g_10">
                                        <div class="col-6 col-sm-4">
                                            <div class="border rounded-1 overflow-hidden">
                                                <img class="img-fluid w-100" src="/user/img/product.png" alt="">
                                                <div class="p-2 bg_light2 border-top mt-2 d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <label>
                                                            <input class="form-check-input me-1" type="radio" name="digital_prdItem">
                                                            <span class="fs_10 text_gray5">الصورة الاساسية</span>
                                                        </label>
                                                    </div>
                                                    <div class="ps-1">
                                                        <button class="btn btn_sm p-1 lh-1 btn-outline-danger" type="button">
                                                            <i class="fi fi-rr-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4">
                                            <div class="border rounded-1 overflow-hidden">
                                                <img class="img-fluid w-100" src="/user/img/product.png" alt="">
                                                <div class="p-2 bg_light2 border-top mt-2 d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <label>
                                                            <input class="form-check-input me-1" type="radio" name="digital_prdItem">
                                                            <span class="fs_10 text_gray5">الصورة الاساسية</span>
                                                        </label>
                                                    </div>
                                                    <div class="ps-1">
                                                        <button class="btn btn_sm p-1 lh-1 btn-outline-danger" type="button">
                                                            <i class="fi fi-rr-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4">
                                            <div class="border rounded-1 overflow-hidden">
                                                <img class="img-fluid w-100" src="/user/img/product.png" alt="">
                                                <div class="p-2 bg_light2 border-top mt-2 d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <label>
                                                            <input class="form-check-input me-1" type="radio" name="digital_prdItem">
                                                            <span class="fs_10 text_gray5">الصورة الاساسية</span>
                                                        </label>
                                                    </div>
                                                    <div class="ps-1">
                                                        <button class="btn btn_sm p-1 lh-1 btn-outline-danger" type="button">
                                                            <i class="fi fi-rr-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt_24">
                                <div class="d-flex justify-content-between">
                                    <div class="me-2">
                                        <button class="btn btn_sm btn_primary" type="button">حفظ بيانات المنتج</button>
                                    </div>
                                    <div>
                                        <button class="btn btn_sm btn-outline-secondary px-sm-4" type="reset" data-bs-dismiss="modal" aria-label="Close">إغلاق</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- step/tab 3 -->
                        <div class="tab-pane fade" id="digitalPrd-tab3-pane" role="tabpanel" aria-labelledby="digitalPrd-tabs" tabindex="0">
                            <div class="row g_10">
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">الوصف ( إختياري )</label>
                                        <div class="cke_rich_editor cke_rtl">
                                            <h2>عنوان تجريبي</h2>
                                            <p>هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها التطبيق.إذا كنت تحتاج إلى عدد أكبر من الفقرات يتيح لك مولد النص العربى زيادة عدد الفقرات كما تريد، النص لن يبدو مقسما ولا يحوي أخطاء لغوية، مولد النص العربى مفيد لمصممي المواقع على وجه الخصوص، حيث يحتاج العميل فى كثير من الأحيان أن يطلع على صورة حقيقية لتصميم الموقع.ومن هنا وجب على المصمم أن يضع نصوصا مؤقتة على التصميم ليظهر للعميل الشكل كاملاً،دور مولد النص العربى أن يوفر على المصمم عناء البحث عن نص بديل لا علاقة له بالموضوع الذى يتحدث عنه التصميم فيظهر بشكل لا يليق.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2 mb_4">صور المنتج</label>
                                        <div class="fs_10 text_gray5 mb_8">المقاس لا يقل عن (100px ارتفاع * 250px عرض) من نوع ( jpg, jpeg, png , gif ) ولا يتجاوز 5 ميجابيت لكل صوره بحد اقصي 10 صور</div>
                                        <div id="digital_product_content_images" data-hide-input-name="digital_content_images"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="border rounded_5 p_12 text-center fs_20">
                                        <i class="fi fi-rr-plus-small"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="pt_24">
                                <div class="d-flex justify-content-between">
                                    <div class="me-2">
                                        <button class="btn btn_sm btn_primary" type="button">حفظ بيانات المنتج</button>
                                    </div>
                                    <div>
                                        <button class="btn btn_sm btn_primary_outline px-sm-4" type="reset" data-bs-dismiss="modal" aria-label="Close">الغاء</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ...... end digital product modal ...... -->
<!-- ...... start digital card modal ...... -->
<div class="modal fade modal_lg" id="digital_card_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="w-100" action="#">
            <div class="modal-content border-0 rounded_6">
                <div class="modal-header bg_light2 py_12 px_16">
                    <div class="pe-1">
                        <i class="fi fi-rr-square-star me-1"></i> بيانات بطاقة رقمية 
                    </div>
                    <div>
                        <button type="reset" class="btn close_btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fi fi-rr-cross-small"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="btn-group w-100 mb_16" id="digitalCard-tabs" role="tablist">
                        <button type="button" class="btn btn_sm w_33 tab_btn active" id="digitalCard-tab1" data-bs-toggle="tab" data-bs-target="#digitalCard-tab1-pane" type="button" role="tab"><i class="fi fi-rr-memo me-2"></i>بيانات المنتج</button>
                        <button type="button" class="btn btn_sm w_33 tab_btn" id="digitalCard-tab2" data-bs-toggle="tab" data-bs-target="#digitalCard-tab2-pane" type="button" role="tab"><i class="fi fi-rr-add-image me-2"></i>صور وفيديوهات المنتج</button>
                        <button type="button" class="btn btn_sm w_33 tab_btn" id="digitalCard-tab3" data-bs-toggle="tab" data-bs-target="#digitalCard-tab3-pane" type="button" role="tab"><i class="fi fi-rr-grid me-2"></i> الملفات </button>
                    </div>
                    <div class="tab-content" id="digitalCard-tabsContent">
                        <!-- step/tab 1 -->
                        <div class="tab-pane fade show active" id="digitalCard-tab1-pane" role="tabpanel" aria-labelledby="digitalCard-tabs" tabindex="0">
                            <div class="row g_10">
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">إسم المنتج</label>
                                        <input type="text" class="form-control inp_sm" placeholder="ادخل إسم منتج">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">وزن المنتج</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control inp_sm" placeholder="ادخل وزن المنتج ">
                                            <div>
                                                <select class="form-select select_sm select_2">
                                                    <option value="1">كجم</option>
                                                    <option value="2">جنيه</option>
                                                    <option value="3">غرام</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">السعر</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control inp_sm" placeholder="ادخل سعر المنتج ">
                                            <div>
                                                <select class="form-select select_sm select_2">
                                                    <option value="1">ر.س</option>
                                                    <option value="2">دولار</option>
                                                    <option value="3">تاكا</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">السعر المخفض</label>
                                        <input type="text" class="form-control inp_sm" placeholder="ادخل السعر المخفض ( إختياري )">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">نهاية التخفيض</label>
                                        <input type="text" class="form-control inp_sm" placeholder="نهاية التخفيض ( إختياري )">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">رقم التخزين</label>
                                        <input type="text" class="form-control inp_sm" placeholder="ادخل رقم التخزين ">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">MPN</label>
                                        <input type="text" class="form-control inp_sm" placeholder="MPN">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">الكمية</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control inp_sm" placeholder="ادخل الكمية">
                                            <span class="input-group-text fs_14">
                                                <i class="fi fi-rr-infinity"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">التصنيف</label>
                                        <div class="input-group">
                                            <div class="flex-grow-1">
                                                <select class="form-select select_sm select_2">
                                                    <option selected disabled>إختر تصنيف المنتج</option>
                                                    <option value="1">إختر تصنيف المنتج</option>
                                                    <option value="2">إختر تصنيف المنتج</option>
                                                    <option value="3">إختر تصنيف المنتج</option>
                                                </select>
                                            </div>
                                            <span class="input-group-text fs_14 add_new_ctg__modalOpener cursor_pointer" >أضف تصنيف</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">الوصف</label>
                                        <div class="cke_rich_editor cke_rtl">
                                            <h2>عنوان تجريبي</h2>
                                            <p>هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها التطبيق.إذا كنت تحتاج إلى عدد أكبر من الفقرات يتيح لك مولد النص العربى زيادة عدد الفقرات كما تريد، النص لن يبدو مقسما ولا يحوي أخطاء لغوية، مولد النص العربى مفيد لمصممي المواقع على وجه الخصوص، حيث يحتاج العميل فى كثير من الأحيان أن يطلع على صورة حقيقية لتصميم الموقع.ومن هنا وجب على المصمم أن يضع نصوصا مؤقتة على التصميم ليظهر للعميل الشكل كاملاً،دور مولد النص العربى أن يوفر على المصمم عناء البحث عن نص بديل لا علاقة له بالموضوع الذى يتحدث عنه التصميم فيظهر بشكل لا يليق.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="py_12 px_16 d-block border rounded_6 mt-2 fs_14">
                                        <input class="form-check-input me-2" type="checkbox">
                                        امكانية كتابة ملاحظة
                                    </label>
                                </div>
                            </div>
                            <div class="pt_24">
                                <div class="d-flex justify-content-between">
                                    <div class="me-2">
                                        <button class="btn btn_sm btn_primary" type="button">حفظ بيانات المنتج</button>
                                    </div>
                                    <div>
                                        <button class="btn btn_sm btn_primary_outline px-sm-4" type="reset" data-bs-dismiss="modal" aria-label="Close">الغاء</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- step/tab 2 -->
                        <div class="tab-pane fade" id="digitalCard-tab2-pane" role="tabpanel" aria-labelledby="digitalCard-tabs" tabindex="0">
                            <div class="row g_10">
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2 mb_4">صور المنتج</label>
                                        <div class="fs_10 text_gray5 mb_8">المقاس لا يقل عن (100px ارتفاع * 250px عرض) من نوع ( jpg, jpeg, png , gif ) ولا يتجاوز 5 ميجابيت لكل صوره بحد اقصي 10 صور</div>
                                        <div id="card_product_images" data-hide-input-name="product_images"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="pt_6 mb_16">
                                        <div class="input-group">
                                            <input type="text" class="form-control inp_sm" placeholder="أضف رابط فيديو من اليوتيوب">
                                            <span class="input-group-text fs_14">
                                                اضافة
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row g_10">
                                        <div class="col-6 col-sm-4">
                                            <div class="border rounded-1 overflow-hidden">
                                                <img class="img-fluid w-100" src="/user/img/product.png" alt="">
                                                <div class="p-2 bg_light2 border-top mt-2 d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <label>
                                                            <input class="form-check-input me-1" type="radio" name="digital_prdItem">
                                                            <span class="fs_10 text_gray5">الصورة الاساسية</span>
                                                        </label>
                                                    </div>
                                                    <div class="ps-1">
                                                        <button class="btn btn_sm p-1 lh-1 btn-outline-danger" type="button">
                                                            <i class="fi fi-rr-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4">
                                            <div class="border rounded-1 overflow-hidden">
                                                <img class="img-fluid w-100" src="/user/img/product.png" alt="">
                                                <div class="p-2 bg_light2 border-top mt-2 d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <label>
                                                            <input class="form-check-input me-1" type="radio" name="digital_prdItem">
                                                            <span class="fs_10 text_gray5">الصورة الاساسية</span>
                                                        </label>
                                                    </div>
                                                    <div class="ps-1">
                                                        <button class="btn btn_sm p-1 lh-1 btn-outline-danger" type="button">
                                                            <i class="fi fi-rr-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4">
                                            <div class="border rounded-1 overflow-hidden">
                                                <img class="img-fluid w-100" src="/user/img/product.png" alt="">
                                                <div class="p-2 bg_light2 border-top mt-2 d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <label>
                                                            <input class="form-check-input me-1" type="radio" name="digital_prdItem">
                                                            <span class="fs_10 text_gray5">الصورة الاساسية</span>
                                                        </label>
                                                    </div>
                                                    <div class="ps-1">
                                                        <button class="btn btn_sm p-1 lh-1 btn-outline-danger" type="button">
                                                            <i class="fi fi-rr-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt_24">
                                <div class="d-flex justify-content-between">
                                    <div class="me-2">
                                        <button class="btn btn_sm btn_primary" type="button">حفظ بيانات المنتج</button>
                                    </div>
                                    <div>
                                        <button class="btn btn_sm btn-outline-secondary px-sm-4" type="reset" data-bs-dismiss="modal" aria-label="Close">إغلاق</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- step/tab 3 -->
                        <div class="tab-pane fade" id="digitalCard-tab3-pane" role="tabpanel" aria-labelledby="digitalCard-tabs" tabindex="0">
                            <div class="row g_10">
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">نوع الكود</label>
                                        <select class="form-select select_sm select_2">
                                            <option value="1">كود مباشر</option>
                                            <option value="2">كود مباشر</option>
                                            <option value="3">كود مباشر</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 text_dark2">تفاصيل الكود</label>
                                        <textarea class="form-control inp_sm" style="min-height: 150px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="border rounded_5 p_12 text-center fs_20">
                                        <i class="fi fi-rr-plus-small"></i>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="py_12 px_16 d-block border rounded_6 mt-2 fs_14">
                                        <input class="form-check-input me-2" type="checkbox">
                                        إضافة مجموعة أكواد دفعة واحدة
                                    </label>
                                </div>
                            </div>
                            <div class="pt_24">
                                <div class="d-flex justify-content-between">
                                    <div class="me-2">
                                        <button class="btn btn_sm btn_primary" type="button">حفظ بيانات المنتج</button>
                                    </div>
                                    <div>
                                        <button class="btn btn_sm btn_primary_outline px-sm-4" type="reset" data-bs-dismiss="modal" aria-label="Close">الغاء</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ...... end digital card modal ...... -->

    <!-- ...... start comfirm delete product modal ...... -->
    <div class="modal fade modal_sm" id="confirm_product_delete_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="w-100" action="#">
                <div class="modal-content border-0 rounded_6">
                    <div class="modal-header bg_light2 py_12 px_16">
                        <div class="pe-1">
                            حذف منج ( منتج تجريبي 1 )
                        </div>
                        <div>
                            <button type="reset" class="btn close_btn" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fi fi-rr-cross-small"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <input class="d-none temp_product_id" type="text">
                        <h6 class="fs_14 text_dark2 fw-medium">هل انت متأكد من حذف هذا المنتج؟</h6>
                        <p class="fs_14 fs_gray2">يرجى العلم بأنه سيتم حذف المنتج بشكل نهائياً
                        </p>
                        <div class="pt_24">
                            <div class="d-flex justify-content-end">
                                <div class="me-2">
                                    <button class="btn btn_sm btn-outline-secondary" type="reset" data-bs-dismiss="modal" aria-label="Close">لا، تراجع</button>
                                </div>
                                <div>
                                    <button class="btn btn_sm btn-danger bg_danger" type="submit"  data-bs-dismiss="modal" aria-label="Close">نعم، تأكيد</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- ...... end comfirm delete product modal ...... -->

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