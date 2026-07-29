<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - خيارات الدفع</title>

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

    <!-- ................ start main area ................ -->
    <main class="main">
        <!-- start statics section -->
        <section class="mb_16">
            <div class="container-fluid px-0">
                <form action="#">
                    <div class="row g_24">
                        <!-- payment method list  -->
                        <div class="col-12">
                            <div class="bg-white rounded_16 h-100">
                                <div class="border-bottom pt_16 px_24">
                                    <ul class="nav nav-tabs nav_tabs" id="optionsTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="options1-tab" data-bs-toggle="tab" data-bs-target="#options1-tab-pane" type="button"
                                                role="tab" aria-controls="options1-tab-pane" aria-selected="true">الرصيد</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="options3-tab" data-bs-toggle="tab" data-bs-target="#options3-tab-pane" type="button"
                                                role="tab" aria-controls="options3-tab-pane" aria-selected="false">حسابك البنكي</button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="py_24 px_24">
                                    <div class="tab-content" id="optionsTabContent">
                                        <!-- Balance Tab -->
                                        <div class="alert alert-warning" role="alert">
                                            الرصيد المعروض هو بعد خصم نسبة المنصة الموضحة في صفحة الإعدادات، بالإضافة إلى أي تكاليف توصيل إن وجدت.
                                        </div>

                                        <div class="tab-pane fade show active" id="options1-tab-pane" role="tabpanel" aria-labelledby="options1-tab" tabindex="0">
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <div class="card h-100 bg_light2">
                                                        <div class="card-body px-3 py-4">
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <div class="fs_16 mb_16">الرصيد الكلي</div>
                                                                    <div class="fs_24">{{$balance->amount ?? "0.00"}} SAR</div>
                                                                </div>
                                                                                                                        <div class="col-md-6">
                                                            <div class="text-end">
                                                               <a class="btn btn_primary btn_sm" href="/dashboard/settings/payments/transactions" type="button"> <i class="fi fi-rr-bank"></i> المعاملات المالية</a>
                                                            </div>
                                                         </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Transactions Tab -->
    
                                        <!-- Bank Account Tab -->
                                        <div class="tab-pane fade" id="options3-tab-pane" role="tabpanel" aria-labelledby="options3-tab" tabindex="0">
                                            <div class="py-5">
                                                <div class="text-center">
                                                    @if($bankDetail)
                                                    <div class="fs_16 fw-normal mb_16">تعديل بيانات الحساب البنكي</div>
                                                     <div>
                                                        <button class="btn btn_primary btn_sm" type="button" data-bs-toggle="modal" data-bs-target="#bank_details_modal"><i class="fi fi-rr-plus-small"></i> تعديل الحساب</button>
                                                    </div>

                                                    @else
                                                    <div class="fs_16 fw-normal mb_16">ليس لديك اي حساب بنكي مضاف، اضف حساب لتسريع المعاملات المستقبلية</div>
                                                    <div>
                                                        <button class="btn btn_primary btn_sm" type="button" data-bs-toggle="modal" data-bs-target="#bank_details_modal"><i class="fi fi-rr-plus-small"></i> اضافة حساب</button>
                                                    </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
    
            <!-- Payment Method Settings Form -->
        </section>
        <!-- end statics section -->
    </main>
        <!-- ................ end main area ................ -->
        <!-- ................ end main area ................ -->
    <div class="modal fade modal_sm" id="confirm_withdraw_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="w-100" action="{{ route('admin.settings.withdrawRequest') }} " method="POST">
                <div class="modal-content border-0 rounded_6">
                    <div class="modal-header bg_light2 py_12 px_16">
                        <div class="pe-1">
                            طلب سحب
                        </div>
                        <div>
                            <button type="reset" class="btn close_btn" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fi fi-rr-cross-small"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <input class="d-none temp_product_id" type="text">
                        <input class=" temp_product_id"  name="temp_product_id" type="hidden">

                        <h6 class="fs_14 text_dark2 fw-medium">هل أنت متأكد من سحب على حسابك البنكي؟</h6>
                        <p class="fs_14 fs_gray2"> عند التاكيد سيتم إرسال طلب سحب إلى فريق لوفارد لمراجعته.</p>
                         </p>
                        <div class="pt_24">
                            <div class="d-flex justify-content-end">
                                <div class="me-2">
                                    <button class="btn btn_sm btn-outline-secondary" type="reset" data-bs-dismiss="modal" aria-label="Close">لا، تراجع</button>
                                </div>
                                <div>
                                    <button class="btn btn_sm btn-primary bg_primary" type="submit"  data-bs-dismiss="modal" aria-label="Close">نعم، تأكيد</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

        <!-- ................ end main area ................ -->
        <div class="modal fade modal_md" id="bank_details_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form id="bank_details_form" class="w-100" action="{{ route('admin.settings.bank_details.save') }}" method="POST">
                    @csrf
                    <div class="modal-content border-0 rounded_6">
                        <div class="modal-header bg_light2 py_12 px_16">
                            <div class="pe-1">
                                <i class="fi fi-rr-bank me-1"></i> تفاصيل الحساب البنكي
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
                                    <label class="form-label fs_14 text_dark2">اسم البنك</label>
                                    <input type="text" class="form-control inp_sm" name="bank_name" placeholder="اسم البنك" required
                                        value="{{ $bankDetail->bank_name ?? '' }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fs_14 text_dark2">رقم الحساب</label>
                                    <input type="text" class="form-control inp_sm" name="account_number" placeholder="رقم الحساب" required
                                        value="{{ $bankDetail->account_number ?? '' }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fs_14 text_dark2">IBAN</label>
                                    <input type="text" class="form-control inp_sm" name="iban" placeholder="IBAN"
                                        value="{{ $bankDetail->iban ?? '' }}">
                                </div>
                            </div>
                            <div class="pt_24">
                                <div class="d-flex justify-content-between">
                                    <div class="me-2">
                                        <button class="btn btn_sm btn_primary" type="submit">حفظ التفاصيل</button>
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
            <!-- ................ end main area ................ -->
    
    <!-- scripts -->
    <script src="/admin/js/jquery-3.7.0.min.js"></script>
    <script src="/admin/js/bootstrap.bundle.min.js"></script>
    <script src="/admin/js/dropzone.min.js"></script>
    <script src="/admin/js/smooth-scrollbar.js"></script>
    <script src="/admin/js/apexcharts.min.js"></script>
    <script src="/admin/js/ckeditor.js"></script>
    <script src="/admin/js/select2.min.js"></script>
    <script src="/admin/js/jquery-sortable.js"></script>
    <script src="/admin/js/script.js"></script>
    @include('admin.layouts.footer')

    <script src="/admin/js/jquery-3.7.0.min.js"></script>

    <script>
            function edit_paymentMethod(query_id){
        new bootstrap.Offcanvas('#edit_payementMethodOffcanvas'+query_id).show();
    }
    window.edit_paymentMethod=edit_paymentMethod;

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-payment-method').forEach(function(switchElement) {
                switchElement.addEventListener('change', function() {
                    const paymentId = this.getAttribute('data-payment-id');
                    const enabled = this.checked ? 1 : 0;
    
                    // Send the AJAX request to toggle the payment method
                    fetch("{{ route('admin.settings.payments.toggle') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            payment_id: paymentId,
                            enabled: enabled,
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success( "تم تحديث وسيلة الدفع بنجاح" , "تم بنجاح");

                        } else {
                            toastr.error(  "حصل خطا وقت تحديث وسيلة الدفع" , "خطأ");
                        }
                    });
                });
            });
        });
    </script>
    @include('admin.layouts.footer')

</body>

</html>