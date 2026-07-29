<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - العملات</title>

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
            <h2 class="fs_36 text-black fw-bold fw-normal mb_24">العملات</h2>
            <div class="container-fluid px-0">
                <form>
                    <div class="row g_24">
                        <div class="col-12">
                            <div class="bg-white rounded_16 h-100">
                                <div class="border-bottom py_16 px_24">
                                    <h3 class="fs_18 fw-normal mb-0">العملات</h3>
                                </div>
                                <div class="py_16 px_24">
                                    <div class="row">
                                        @foreach($currencies as $currency)
                                            @php
                                                $isEnabled = $storeOptions->where('id_type', $currency->id)->where('enabled', 1)->first() ? 'checked' : '';
                                            @endphp
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between py_8 border-bottom">
                                                    <div class="pe-2 fs_16 flex-grow-1">{{ $currency->name }} ({{ $currency->symbole }})</div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-currency" 
                                                            type="checkbox" 
                                                            data-currency-id="{{ $currency->id }}"
                                                            {{ $isEnabled }} 
                                                            role="switch" @if($currency->symbole == auth()->user()->main_currency) checked disabled @endif>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.toggle-currency').forEach(function(switchElement) {
                    switchElement.addEventListener('change', function() {
                        const currencyId = this.getAttribute('data-currency-id');
                        const enabled = this.checked ? 1 : 0;
        
                        fetch("{{ route('user.settings.currency.toggle') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                currency_id: currencyId,
                                enabled: enabled
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                show_alert_toast("alert_primary", "تم بنجاح", "تم تحديث العملة بنجاح");
                            } else {
                                show_alert_toast("alert_danger", "خطأ", "حصل خطأ وقت تحديث العملة");
                            }
                        });
                    });
                });
            });
        </script>
                <!-- end statics section -->
    </main>
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