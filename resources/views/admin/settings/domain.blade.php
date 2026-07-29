<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - إعدادات الدومين</title>

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
            <div class="container-fluid px-0">
                <form action="#">
                    <div class="row g_24">
                        <div class="col-12">
                            <div class="bg-white rounded_16 h-100">
                                <div class="border-bottom pt_16 px_24">
                                    <ul class="nav nav-tabs nav_tabs" id="domainOptionsTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="customDomain-tab" data-bs-toggle="tab" data-bs-target="#customDomain" type="button" role="tab" aria-controls="customDomain" aria-selected="true">
                                                 ربط مع دومين خاص
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="purchaseDomain-tab" data-bs-toggle="tab" data-bs-target="#purchaseDomain" type="button" role="tab" aria-controls="purchaseDomain" aria-selected="false">
                                                 شراء دومين
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="py_24 px_24">
                                    <div class="tab-content" id="domainOptionsTabContent">
                                        <!-- Custom Domain Tab -->
                                        <div class="tab-pane fade show active" id="customDomain" role="tabpanel" aria-labelledby="customDomain-tab">
                                            <form method="POST" action="{{ route('user.settings.domain.store') }}">
                                                @csrf
                                                @if($domain)
                                                    <div class="alert alert-success" role="alert">
                                                        تم إرسال طلب ربط الدومين بنجاح. يرجى انتظار مراجعة فريق عمل Payline.
                                                    </div>
                                                @endif
    
                                                <div class="row g_10">
                                                    <div class="col-12">
                                                        <label class="form-label fs_14 text_dark2">الدومين</label>
                                                        <input type="text" class="form-control inp_sm" name="domain" id="domain_input" placeholder="ادخل اسم الدومين" {{ $domain ? 'value=' . $domain . ' disabled' : '' }}>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fs_14 text_dark2">تعديل خوادم الأسماء (Nameservers)</label>
                                                        <div class="input-group mb-2">
                                                            <input type="text" class="form-control inp_sm" value="ns1.payline.com" disabled>
                                                        </div>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control inp_sm" value="ns2.payline.com" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 pt_24">
                                                        <div class="d-flex justify-content-between">
                                                            <button id="save_button" class="btn btn_sm btn_primary" type="submit">حفظ</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
    
                                        <!-- Purchase Domain Tab -->
                                        <div class="tab-pane fade" id="purchaseDomain" role="tabpanel" aria-labelledby="purchaseDomain-tab">
                                            <form method="POST" action="">
                                                <div class="row g_10">
                                                    <div class="col-12">
                                                        <label class="form-label fs_14 text_dark2">الدومين</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control inp_sm" id="check_domain" placeholder="أدخل إسم الدومين">
                                                            <select class="form-select select_sm select_2" id="domain_extension">
                                                                <option value=".com">.com</option>
                                                                <option value=".net">.net</option>
                                                                <option value=".io">.io</option>
                                                                <option value=".me">.me</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 pt_6 mb_16">
                                                        <button class="btn btn_sm btn-primary" id="check_domain_button" type="button">تحقق من توفر الدومين</button>
                                                    </div>
                                                    <div id="domain_check_message" class="fs_14"></div> <!-- Success/Error message area -->
                                                </div>
                                                <div class="col-12 pt_24">
                                                    <div class="d-flex justify-content-between">
                                                        <button class="btn btn_sm btn_primary" type="button" id="pay_button" style="display: none;">دفع</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div> <!-- End of tab content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <!-- end statics section -->
    </main>
    <!-- ...... end ready product modal ...... -->


    <!-- ...... start comfirm delete product modal ...... -->
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
    <script>
$(document).ready(function() {
    $('#check_domain_button').click(function() {
        var domain = $('#check_domain').val();
        var extension = $('#domain_extension').val();
        var fullDomain = domain + extension;

        // Send AJAX request to check domain availability
        $.ajax({
            url: "{{route('user.settings.checkDomainAvailability')}}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                domain: fullDomain
            },
            success: function(response) {
                if (response.available) {
                    $('#domain_check_message').html('الدومين متاح').css('color', 'green');
                    $('#pay_button').show(); // Show the "Pay" button
                    $('#save_button').text('دفع'); // Change the save button text to "Pay"
                } else {
                    $('#domain_check_message').html('الدومين غير متاح').css('color', 'red');
                    $('#pay_button').hide(); // Hide the "Pay" button
                    $('#save_button').text('حفظ'); // Reset the save button text to "Save"
                }
            },
            error: function() {
                $('#domain_check_message').html('حدث خطأ في التحقق من الدومين').css('color', 'red');
            }
        });
    });

    $('#pay_button').click(function() {
        var domain = $('#check_domain').val();
        var extension = $('#domain_extension').val();
        var fullDomain = domain + extension;

        // Re-check domain availability before redirecting to payment
        $.ajax({
            url: "{{route('user.settings.checkDomainAvailability')}}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                domain: fullDomain
            },
            success: function(response) {
                if (response.available) {
                    // If available, redirect to payment
                    window.location.href = "{{route('user.settings.paymentRoute')}}" + "?domain=" + fullDomain;
                } else {
                    $('#domain_check_message').html('عذراً، الدومين غير متاح').css('color', 'red');
                }
            },
            error: function() {
                $('#domain_check_message').html('حدث خطأ أثناء التحقق من الدومين').css('color', 'red');
            }
        });
    });
});
    </script>
</body>

</html>