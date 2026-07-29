<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - الكوبونات</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/admin/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="/admin/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="/admin/css/style.css">
</head>

<body>
    <!-- ................ start header area ................ -->
    @include('admin.layouts.sidebar')
    <!-- sub header -->
    @include('admin.layouts.header')

    <!-- ................ end header area ................ -->

    <main class="main">
        <section class="mb_16">
            <div class="container-fluid px-0">
                <form action="{{ route('admin.coupons.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="main__header">
                        <div class="row">
                            <div class="col-6">
                                <h2 class="fs_24 text-black fw-bold mb-0">إضافة كوبون جديد</h2>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <a href="{{ url('dashboard/coupons') }}" class="btn btn_sm btn_primary_outline px-sm-4" type="reset">الغاء</a>
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
                                <h3 class="fs_20 mb_16">تفاصيل الكوبون</h3>
                                <div class="row g_16">
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">كود الكوبون</label>
                                        <input type="text" name="code" class="form-control inp_sm" placeholder="أدخل كود الكوبون" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">الوصف</label>
                                        <textarea name="description" class="form-control inp_sm"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">نوع الخصم</label>
                                        <select name="discount_type" id="discount_type" class="form-select inp_sm" required>
                                            <option value="percent">نسبة مئوية</option>
                                            <option value="fixed">مبلغ ثابت</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-12" id="fixed_discount_div" style="display: none;">
                                        <label class="form-label fs_14 c_dark2">قيمة الخصم (مبلغ ثابت)</label>
                                        <div class="input-group">
                                            <input type="number" name="fixed_discount" class="form-control inp_sm" placeholder="قيمة الخصم" style="direction:rtl;">
                                            <span class="input-group-text fs_14">SAR</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12" id="percent_discount_div">
                                        <label class="form-label fs_14 c_dark2">قيمة الخصم (نسبة مئوية)</label>
                                        <div class="input-group">
                                            <input type="number" name="percent_discount" class="form-control inp_sm" placeholder="قيمة الخصم" style="direction:rtl;" min="0" max="100" oninput="this.value = Math.floor(this.value); if(this.value > 100) this.value = 100;">
                                            <span class="input-group-text fs_14">%</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">عدد مرات الإستخدام</label>
                                        <input type="text" name="use_limit" class="form-control inp_sm" placeholder="أدخل عدد مرات الإستخدام" value= "1" required>
                                    </div>

                                     
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">تاريخ انتهاء الصلاحية</label>
                                        <input type="date" name="expires_at" class="form-control inp_sm" required>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                    </div>
                </form>
            </div>
        </section>
    </main>        
    <script>
        document.getElementById('discount_type').addEventListener('change', function() {
            var fixedDiv = document.getElementById('fixed_discount_div');
            var percentDiv = document.getElementById('percent_discount_div');
            
            if (this.value === 'fixed') {
                fixedDiv.style.display = 'block';
                percentDiv.style.display = 'none';
            } else if (this.value === 'percent') {
                fixedDiv.style.display = 'none';
                percentDiv.style.display = 'block';
            }
        });
    </script>
    
    
    
        <!-- ...... end add new category modal ...... -->

    <!-- ...... start comfirm delete product modal ...... -->
    <!-- scripts -->
    <script src="/admin/js/jquery-3.7.0.min.js"></script>
    <script src="/admin/js/bootstrap.bundle.min.js"></script>
    <script src="/admin/js/script.js"></script>
    @include('admin.layouts.footer')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "اختر الفئات المسموحة",
                allowClear: true
            });
        });
    </script>


        
</body>

</html>