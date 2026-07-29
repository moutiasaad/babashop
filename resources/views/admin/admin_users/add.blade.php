@php

$Model_PLURAL = 'مستخدمين الإدارة';
$Model_SINGULAR = 'مستخدم الإدارة';
$Model_API_ROUTE = 'admin_users';
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
                                            <label class="form-label fs_14 c_dark2">الاسم الكامل</label>
                                            <input type="text" name="name" class="form-control inp_sm" placeholder="" value="{{ old('name', $merchantUser->fullname ?? '') }}">
                                        <label class="form-label fs_14 c_dark2">المسمى الوظيفي</label>
                                            <input type="text" name="title" class="form-control inp_sm" placeholder="" value="{{ old('name', $merchantUser->title ?? '') }}">

                                            <!-- Email Field -->
                                            <label class="form-label fs_14 c_dark2">البريد الإلكتروني</label>
                                            <input type="email" name="email" class="form-control inp_sm" placeholder="" value="{{ old('email', $merchantUser->email ?? '') }}">
                    
                                            <!-- Phone Field -->
                                            <label class="form-label fs_14 c_dark2">رقم الهاتف</label>
                                            <input type="text" name="phone" class="form-control inp_sm" placeholder="" value="{{ old('phone', $merchantUser->phone ?? '') }}">
                    
                                            <!-- Password Field -->
                                            <label class="form-label fs_14 c_dark2">كلمة المرور</label>
                                            <input type="password" name="password" class="form-control inp_sm" placeholder="">
                                            <div class="col-12">
                                                <label class="form-label fs_14 text_dark2">الصلاحيات</label>
                                                @foreach ($items as $item)
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="{{$item->name}}" name="permissions[]" value="{{$item->id}}">
                                                    <label class="custom-control-label" for="product_show">{{$item->text}}</label>
                                                </div>
                                                @endforeach
                                                <!-- Add more permissions as needed -->
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
    @include('admin.layouts.footer')


</body>

</html>
