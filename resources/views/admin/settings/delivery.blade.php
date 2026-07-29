<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - خيارات التوصيل</title>

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
            <h2 class="fs_36 text-black fw-bold fw-normal mb_24">خيارات التوصيل</h2>
            <div class="container-fluid px-0">
                <form>
                    <div class="row g_24">
                        <div class="col-12">
                            <div class="bg-white rounded_16 h-100">
                                <div class="border-bottom py_16 px_24">
                                    <h3 class="fs_18 fw-normal mb-0">وسائل التوصيل</h3>
                                </div>
                                <div class="py_16 px_24">
                                    <div class="row">
                                        @foreach($deliveryMethods as $method)
                                            @php
                                                $isEnabled = $storeOptions->where('id_type', $method->id)->where('enabled', 1)->first() ? 'checked' : '';
                                            @endphp
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between py_8 border-bottom">
                                                    <div class="pe-2 fs_16 flex-grow-1">{{ $method->name }}</div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-delivery-method" 
                                                            type="checkbox" 
                                                            data-delivery-id="{{ $method->id }}"
                                                            {{ $isEnabled }} 
                                                            role="switch">
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
        
                <!-- end statics section -->
    </main>
    <!-- ................ end main area ................ -->


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
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-delivery-method').forEach(function(switchElement) {
                switchElement.addEventListener('change', function() {
                    const deliveryId = this.getAttribute('data-delivery-id');
                    const enabled = this.checked ? 1 : 0;
    
                    fetch("{{ route('user.settings.delivery.toggle') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            delivery_id: deliveryId,
                            enabled: enabled
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            show_alert_toast("alert_primary", "تم بنجاح", "تم تحديث وسيلة التوصيل بنجاح");
                        } else {
                            show_alert_toast("alert_danger", "خطأ", "حصل خطأ وقت تحديث وسيلة التوصيل");
                        }
                    });
                });
            });
        });
    </script>

</body>

</html>