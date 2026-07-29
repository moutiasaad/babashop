<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - خيارات المتجر</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/user/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="/user/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="/user/css/image-uploader.min.css">
    <link rel="stylesheet" href="/user/css/select2.min.css">
    <link rel="stylesheet" href="/user/css/style.css">
    <link rel="stylesheet" href="/user/css/toastr.css">

    <style>
        input[type="checkbox"].form-check-input {
    width: 20px;
    height: 20px;
    appearance: auto; /* Ensures default checkbox display */
    cursor: pointer;
    margin: 0; /* Adjust any default margin */
}

    </style>
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
            <h2 class="fs_36 text-black fw-bold fw-normal mb_24">خيارات المتجر</h2>
            <div class="container-fluid px-0">
                <form action="#">
                    <div class="row g_24">
                        <!-- Store Options -->
                        <div class="col-12">
                            <div class="bg-white rounded_16 h-100">
                                <div class="border-bottom py_16 px_24">
                                    <h3 class="fs_18 fw-normal mb-0">الخيارات</h3>
                                </div>
                                <div class="py_16 px_24">
                                    <div class="row">
                                        @foreach($storeOptions as $option)
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between align-items-center py_8 border-bottom">
                                                    <div class="d-flex align-items-center flex-grow-1" style="gap: 8px;">
                                                        <input 
                                                            type="checkbox" 
                                                            class="form-check-input toggle-option" 
                                                            data-attribut="{{ $option->attribut }}" 
                                                            {{ auth()->user()->hasOption($option->attribut) ? 'checked' : '' }}
                                                            style="width: 20px; height: 20px; cursor: pointer;"
                                                        >
                                                        <span class="fs_16">{{ $option->title }}</span>
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
            document.querySelectorAll('.toggle-option').forEach(function(switchElement) {
                switchElement.addEventListener('change', function() {
                    const attribut = this.getAttribute('data-attribut');
                    const enabled = this.checked ? 1 : 0;
    
                    fetch("{{ route('user.settings.toggleOption') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            attribut: attribut,
                            enabled: enabled
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success( "تم تحديث الميزة بنجاح" , "تم بنجاح");
                        } else {
                            toastr.error( "حصل خطا وقت التحديث", "خطا");
                        }
                    });
                });
            });
        });
    </script>
    
</body>

</html>