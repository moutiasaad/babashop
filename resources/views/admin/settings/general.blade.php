@php

$Model_PLURAL = 'إعدادات النظام';
$Model_SINGULAR = 'إعدادات النظام';

@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - {{$Model_PLURAL}}</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/admin/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="/admin/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="/admin/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    input[type="number"] {
        direction: rtl;
        text-align: right;
    }
    input[type="email"] {
        direction: rtl;
        text-align: right;
    }
</style>

</head>

<body>
    @include('admin.layouts.sidebar')
    @include('admin.layouts.header')

    <!-- Main Content -->
    <main class="main">
        <section class="mb_16">
            <div class="container-fluid px-0">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <div class="main__header">
                        <div class="row">
                            <div class="col-6">
                                <h2 class="fs_24 text-black fw-bold fw-normal mb-0">{{$Model_SINGULAR}}</h2>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <a href="{{ url('dashboard/') }}" class="btn btn_sm btn_primary_outline px-sm-4" type="reset">الغاء</a>
                                    </div>
                                    <div>
                                        <button class="btn btn_sm btn_primary" type="submit">حفظ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

<div class="row g_24">
    <div class="col-lg-12">
        <div class="bg-white rounded_16 p_24">
            <h3 class="fs_20 mb_16">إعدادات الشركة</h3>
            <div class="row g_16">

                <div class="col-12">
                    <label class="form-label fs_14 c_dark2">اسم الشركة</label>
                    <input type="text" name="name" class="form-control inp_sm"
                        value="{{ config('company.name') }}">
                </div>

                <div class="col-12">
                    <label class="form-label fs_14 c_dark2">الرقم الضريبي</label>
                    <input type="text" name="tva" class="form-control inp_sm"
                        value="{{ config('company.tva') }}">
                </div>

                <div class="col-12">
                    <label class="form-label fs_14 c_dark2">العنوان</label>
                    <input type="text" name="address" class="form-control inp_sm"
                        value="{{ config('company.address') }}">
                </div>

                <div class="col-12">
                    <label class="form-label fs_14 c_dark2">رقم الهاتف</label>
                    <input type="text" name="phone_number" class="form-control inp_sm"
                        value="{{ config('company.phone_number') }}">
                </div>

                <div class="col-12">
                    <label class="form-label fs_14 c_dark2">تكلفة التوصيل</label>
                    <input type="number" name="delivery_cost" step="0.01" class="form-control inp_sm"
                        value="{{ config('company.delivery_cost') }}">
                </div>

                <div class="col-12">
                    <label class="form-label fs_14 c_dark2">حساب السوشيال ميديا</label>
                    <input type="text" name="social_media" class="form-control inp_sm"
                        value="{{ config('company.social_media') }}">
                </div>

                <div class="col-12">
                    <label class="form-label fs_14 c_dark2">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control inp_sm"
                        value="{{ config('company.email') }}">
                </div>

            </div>
        </div>
    </div>
</div>
                </form>
            </div>
        </section>
    </main>

    <!-- scripts -->
    <script src="/admin/js/jquery-3.7.0.min.js"></script>
    <script src="/admin/js/bootstrap.bundle.min.js"></script>
    <script src="/admin/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @include('admin.layouts.footer')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const receiverSelect = document.getElementById("receiver_type_select");
        const usersSection = document.getElementById("users_select_section");
        const usersSelect = $('#users_select');

        usersSelect.select2({ width: '100%' });

        function toggleUserSelect() {
            if (receiverSelect.value === "specific") {
                usersSection.style.display = "block";
            } else {
                usersSection.style.display = "none";
            }
        }

        receiverSelect.addEventListener("change", toggleUserSelect);
        toggleUserSelect(); // initialize
    });
    document.addEventListener("DOMContentLoaded", function () {
        const dateTypeSelect = document.getElementById("date_type_select");
        const datePickerSection = document.getElementById("date_picker_section");

        function toggleDatePicker() {
            if (dateTypeSelect.value === "date") {
                datePickerSection.style.display = "block";
            } else {
                datePickerSection.style.display = "none";
            }
        }

        dateTypeSelect.addEventListener("change", toggleDatePicker);
        toggleDatePicker(); // initialize on load
    });
</script>

</body>

</html>
