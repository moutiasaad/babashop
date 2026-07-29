@php
$type = $promotion->type;
if($type == 0){
$Model_PLURAL = 'العروض';
$Model_SINGULAR = 'العرض';
}else if($type == 1){
    $Model_PLURAL = 'البانر الرئيسي';
    $Model_SINGULAR = 'البانر الرئيسي';

}else {
    $Model_PLURAL = 'إعلانات التطبيق';
    $Model_SINGULAR = 'إعلانات التطبيق';
}

$Model_API_ROUTE = 'promotion';
$Model_Name = 'promotions';

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
</style>


</head>

<body>
    @include('admin.layouts.sidebar')
    @include('admin.layouts.header')

    <!-- Main Content -->
    <main class="main">
        <section class="mb_16">
            <div class="container-fluid px-0">
                <form action="{{ route('admin.promotion.update', $promotion->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="main__header">
                        <div class="row">
                            <div class="col-6">
                                <h2 class="fs_24 text-black fw-bold fw-normal mb-0">تعديل {{$Model_SINGULAR}}</h2>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <a href="{{ url('dashboard/' . request()->segment(2)) }}/{{$promotion->id}}" class="btn btn_sm btn_primary_outline px-sm-4" type="reset">الغاء</a>
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
                                <h3 class="fs_20 mb_16">تفاصيل {{$Model_SINGULAR}}</h3>
                                <div class="row g_16">
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">اسم {{$Model_SINGULAR}}</label>
                                            <input type="text" name="name" class="form-control inp_sm" value="{{ $promotion->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <input type="file" id="image" name="image" style="display: none;" />
                                            <label class="form-label fs_14 c_dark2 mb_4">الصورة</label>
                                            <div id="merchant_dropzone" class="dropzone_area">
                                                <i class="icon_upload"></i>
                                                <p class="mb-0 fs_12">حمل أو الصق الصورة</p>
                                            </div>
                                            <div id="product_preview_container" class="preview_area">
                                                @if ($promotion->image)
                                                <div class="file_preview">
                                                    <img src="{{ asset($promotion->image) }}" data-thumbnail />
                                                    <div class="file_details">
                                                        <div class="file_name pe-1">الصورة</div>
                                                        <div class="file_size">10 KB</div>
                                                    </div>
                                                    <div class="btn-group btn-group-sm">
                                                        <a class="btn text-primary" href="${fileUrl}" download>
                                                            <i class="fi fi-rr-down-to-line"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                            </div>
                                            <div class="col-12">
                                                <div>
                                                    <label class="form-label fs_14 c_dark2">ترتيب العرض</label>
                                                    <input type="number" name="order_item" class="form-control inp_sm" value="{{ $promotion->order_item }}" required>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fs_14 c_dark2">عند الضغط</label>
                                                <select name="click_action" class="form-select inp_sm" id="click_action_select">
                                                    <option value="">لا شيء</option>
                                                    <option value="product" {{ $promotion->click_action === 'product' ? 'selected' : '' }}>منتج</option>
                                                    <option value="merchant" {{ $promotion->click_action === 'merchant' ? 'selected' : '' }}>متجر</option>
                                                    <option value="category" {{ $promotion->click_action === 'category' ? 'selected' : '' }}>قسم</option>
                                                </select>
                                            </div>

                                            <div class="col-12" id="click_value_wrapper" style="display: none;">
                                                <label class="form-label fs_14 c_dark2">اختر</label>
                                                <select name="click_value" class="form-control inp_sm select_2" id="click_value_select">
                                                    @if ($promotion->click_value)
                                                        <option value="{{ $promotion->click_value }}" selected>العنصر الحالي</option>
                                                    @endif
                                                </select>
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
    <script src="/admin/js/dropzone.min.js"></script>
    <script src="/admin/js/smooth-scrollbar.js"></script>
    <script src="/admin/js/apexcharts.min.js"></script>
    <script src="/admin/js/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="/admin/js/jquery-sortable.js"></script>
    <script src="/admin/js/script.js"></script>
    @include("admin.layouts.footer")

    <script>
    const clickActionSelect = $('#click_action_select');
const clickValueWrapper = $('#click_value_wrapper');
const clickValueSelect = $('#click_value_select');

function loadClickOptions(selectedType, selectedId = null) {
    if (!selectedType) {
        clickValueWrapper.hide();
        clickValueSelect.empty();
        return;
    }

    clickValueWrapper.show();
    clickValueSelect.empty().append('<option value="">جاري التحميل...</option>');

    $.get(`/dashboard/promotion-options/${selectedType}`, function (data) {
        clickValueSelect.empty();
        data.forEach(item => {
            const selected = selectedId && selectedId == item.id ? 'selected' : '';
            clickValueSelect.append(`<option value="${item.id}" ${selected}>${item.name}</option>`);
        });
    });
}

clickActionSelect.on('change', function () {
    const selected = $(this).val();
    loadClickOptions(selected);
});

@if($promotion->click_action)
    loadClickOptions('{{ $promotion->click_action }}', '{{ $promotion->click_value }}');
@endif

clickValueSelect.select2({ width: '100%' });

// Reference elements
const fileInput = $("#image"); // Hidden file input
const previewContainer = $("#product_preview_container"); // Preview container

// Click event to open file input
$("#merchant_dropzone").on("click", function () {
    fileInput.click();
});

// Change event to handle file input selection
fileInput.on("change", function (event) {
    const file = event.target.files[0]; // Get the selected file

    if (file) {
        previewContainer.empty(); // Clear any previous preview
        previewFile(file); // Preview the new file
    }
});

// Function to generate file preview
function previewFile(file) {
    const fileUrl = URL.createObjectURL(file);
    const previewHTML = `
        <div class="file_preview">
            <img src="${fileUrl}" data-thumbnail />
            <div class="file_details">
                <div class="file_name pe-1">${file.name}</div>
                <div class="file_size">${(file.size / 1024).toFixed(2)} KB</div>
            </div>
            <div class="btn-group btn-group-sm">
                <a class="btn text-primary" href="${fileUrl}" download>
                    <i class="fi fi-rr-down-to-line"></i>
                </a>
                <button class="btn text-danger" id="remove_file">
                    <i class="fi fi-rr-trash"></i>
                </button>
            </div>
        </div>
    `;
    previewContainer.append(previewHTML);

    // Remove file event
    $("#remove_file").on("click", function () {
        fileInput.val(""); // Clear the file input
        previewContainer.empty(); // Remove the preview
    });
}

    </script>
    @include('admin.layouts.footer')


</body>

</html>
