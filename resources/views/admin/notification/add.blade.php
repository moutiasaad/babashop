@php

$Model_PLURAL = 'التنبيهات';
$Model_SINGULAR = 'تنبيه';
$Model_API_ROUTE = 'notification';

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


</head>

<body>
    @include('admin.layouts.sidebar')
    @include('admin.layouts.header')

    <!-- Main Content -->
    <main class="main">
        <section class="mb_16">
            <div class="container-fluid px-0">
                <form action="{{ route('admin.' . $Model_API_ROUTE . '.store') }}" method="POST">
                    @csrf
                    <div class="main__header">
                        <div class="row">
                            <div class="col-6">
                                <h2 class="fs_24 text-black fw-bold fw-normal mb-0">إضافة {{$Model_SINGULAR}} جديد</h2>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <a href="{{ url('dashboard/' . request()->segment(2)) }}" class="btn btn_sm btn_primary_outline px-sm-4" type="reset">الغاء</a>
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
                                <h3 class="fs_20 mb_16">تفاصيل  {{$Model_SINGULAR}}</h3>
                                <div class="row g_16">
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">العنوان</label>
                                            <input type="text" name="title" class="form-control inp_sm"
                                                placeholder="">

                                            <!-- Error message in case of validation failure -->
                                        </div>
                                    </div>
                                     <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">الوصف</label>
                                            <textarea type="text" name="description" class="form-control inp_sm"
                                                placeholder=""></textarea>

                                            <!-- Error message in case of validation failure -->
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">المستلمين</label>
                                            <select name="type_receiver" class="form-select inp_sm" id="receiver_type_select">
                                                <option value="all">جميع المستخدمين</option>
                                                <option value="specific">مستخدمين محددين</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12" id="users_select_section" style="display: none;">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">اختر المستخدمين</label>
                                            <select name="users[]" multiple class="form-control inp_sm" id="users_select">
                                                @foreach(\App\Models\User::select('id', 'fullname', 'phone')->get() as $user)
                                                    <option value="{{ $user->id }}">{{ $user->fullname }} | {{ $user->phone }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">موعد الإرسال</label>
                                            <select name="date_type" class="form-select inp_sm" id="date_type_select">
                                                <option value="now">الآن</option>
                                                <option value="date">حدد تاريخ</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12" id="date_picker_section" style="display: none;">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">تاريخ ووقت الإرسال</label>
                                            <input type="datetime-local" name="date" class="form-control inp_sm" placeholder="">
                                        </div>
                                    </div>
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
