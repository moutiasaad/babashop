@php

$Model_PLURAL = 'المتاجر';
$Model_SINGULAR = 'المتجر';
$Model_API_ROUTE = 'merchants';

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrDYCXAVQZeXxbZx84iRVe5SMmBpm5sy8&libraries=places"></script>
    <style>
        #map {
            height: 400px;
        }
    </style>
    <style>
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
            <form action="{{ route('admin.' . $Model_API_ROUTE . '.store') }}" method="POST" enctype='multipart/form-data'>
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
                    <div class="col-lg-6">
                        <div class="bg-white rounded_16 p_24">
                            <h3 class="fs_20 mb_16">تفاصيل {{$Model_SINGULAR}}</h3>
                            <div class="row g_16">
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 c_dark2"> اسم  {{$Model_SINGULAR}} *</label>
                                        <input type="text" name="brand_name" class="form-control inp_sm" placeholder="" value="{{ old('brand_name') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 c_dark2">نوع {{$Model_SINGULAR}} *</label>
                                        <select class="form-select select_sm" name="type_merchant_id">
                                            <option selected disabled>اختر نوع {{$Model_SINGULAR}}</option>
                                            @foreach ($merchant_type as $type)
                                                <option value="{{ $type->id }}" {{ old('type_merchant_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 c_dark2">  مفتوح عند *</label>
                                        <input type="time" name="open_at" class="form-control inp_sm" placeholder="" value="{{ old('open_at') }}">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 c_dark2">  يغلق في *</label>
                                        <input type="time" name="close_at" class="form-control inp_sm" placeholder="" value="{{ old('close_at') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 c_dark2">الوصف *</label>
                                        <textarea class="form-control" name="description" rows="4">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 c_dark2">تصنيفات</label>
                                        <select class="form-select select_sm" id="other_categories" name="other_categories[]" multiple>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ in_array($category->id, old('other_categories', [])) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                            <input type="file" id="image" name="image" style="display: none;" />
                                            <label class="form-label fs_14 c_dark2 mb_4">الصورة *</label>
                                            <div id="merchant_dropzone" class="dropzone_area">
                                                <i class="icon_upload"></i>
                                                <p class="mb-0 fs_12">حمل أو الصق الصورة</p>
                                            </div>
                                        <div id="product_preview_container" class="preview_area"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fs_14 c_dark2">البريد الإلكتروني للمسؤول *</label>
                                    <input type="email" name="admin_email" class="form-control inp_sm" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fs_14 c_dark2">كلمة المرور للمسؤول *</label>
                                    <input type="password" name="admin_password" class="form-control inp_sm" required>
                                </div>

                            </div>
                        </div>
                        <div class="bg-white rounded_16 p_24 mt-4">
                            <h3 class="fs_20 mb_16">أوقات التوصيل المتاحة *
                                <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="اعداد المنتجات و تجهيزيها فوري او مجدول" style="cursor: pointer;"></i>
                            </h3>
                            <div id="delivery_times_container">
                                <div class="row g_16 delivery-time-row">
                                    <div class="col-6">
                                        <label class="form-label fs_14 c_dark2">من</label>
                                        <input type="time" name="delivery_times[0][start]" 
                                            class="form-control inp_sm"
                                            value="{{ old('delivery_times.0.start') }}" required>
                                    </div>
                                    <div class="col-6 position-relative">
                                        <label class="form-label fs_14 c_dark2">إلى</label>
                                        <input type="time" name="delivery_times[0][end]" 
                                            class="form-control inp_sm"
                                            value="{{ old('delivery_times.0.end') }}" required>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn_sm btn_primary_outline px-sm-4 mt-3" id="add_delivery_time">
                                <i class="fas fa-plus"></i> إضافة وقت توصيل
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="bg-white rounded_16 p_24 mb_24">
                            <h3 class="fs_20 mb_16">تفاصيل المكان</h3>
                            <div class="row g_16">
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 c_dark2">المدينة *</label>
                                        <select class="form-select select_sm" name="city_id" required>
                                            <option selected disabled>اختر المدينة</option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 c_dark2">المناطق (الزونات)</label>
                                        <select class="form-select select_sm" id="zones_select" name="zones[]" multiple>
                                            @foreach ($zones as $zone)
                                                <option value="{{ $zone->id }}"
                                                    {{ in_array($zone->id, old('zones', [])) ? 'selected' : '' }}>
                                                    {{ $zone->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 c_dark2">العنوان *</label>
                                        <input type="text" name="street_name" class="form-control inp_sm" value="{{ old('street_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 c_dark2">خط العرض *</label>
                                        <input type="text" id="latitude" name="latitude" class="form-control inp_sm" value="{{ old('latitude') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div>
                                        <label class="form-label fs_14 c_dark2">خط الطول *</label>
                                        <input type="text" id="longitude" name="longitude" class="form-control inp_sm" value="{{ old('longitude') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12">
                                    <div>
                                        <label class="form-label fs_14 c_dark2">حدد العنوان *
                                            <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="العنوان الوطني المختصر" style="cursor: pointer;"></i>
                                        </label>
                                        <input id="search-box" type="text" class="form-control inp_sm mb-4" placeholder="Search for a place">
                                        <div id="map"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded_16 p_24">
                            <h3 class="fs_20 mb_16">تفاصيل التاجر</h3>
                            <div class="row g_16">
                                <div class="col-12">
                                    <label class="py_12 px_16 d-block border rounded_6 mt-2 fs_14">
                                        <input class="form-check-input me-2" name="enable_customization" type="checkbox" {{ old('enable_customization') ? 'checked' : '' }}>
                                        امكانية تخصيص المنتجات
                                    </label>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 c_dark2">عنوان إيميل التنبيه *
                                            <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="الايميل اللي تكون عليه  اشعارات الطلبات اليومية.
" style="cursor: pointer;"></i>
                                        </label>
                                        <input type="text" name="notif_email" class="form-control inp_sm" value="{{ old('notif_email') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="form-label fs_14 c_dark2">رقم الواتساب
                                            <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="رقم الواتساب الذي ستصل عليه إشعارات الطلبات الجديدة." style="cursor: pointer;"></i>
                                        </label>
                                        <input type="text" name="whatsapp_number" class="form-control inp_sm" value="{{ old('whatsapp_number') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <input type="file" id="contract_file" name="contract_file" style="display: none;" />
                                        <label class="form-label fs_14 c_dark2 mb_4">ملفات عقد التاجر</label>
                                        <div id="contract_dropzone" class="dropzone_area">
                                            <i class="icon_upload"></i>
                                            <p class="mb-0 fs_12">حمل أو الصق ملف</p>
                                        </div>
                                        <div id="product_preview_container_contract_file" class="preview_area_contract_file"></div>
                                    </div>
                                </div>
                                <div class="col-12" id="platform_fee_discount_div">
                                    <div class="col-12" id="platform_fee_discount_div">
                                        <label class="form-label fs_14 c_dark2">نسبة المنصة (نسبة مئوية) *
                                        </label>
                                        <div class="input-group">
                                            <input 
                                                type="number" 
                                                name="platform_fee" 
                                                class="form-control inp_sm" 
                                                placeholder="نسبة المنصة" 
                                                style="direction:rtl;" 
                                                min="0" 
                                                max="100" 
                                                step="0.01" 
                                                value="{{ old('platform_fee') }}"
                                            >
                                            <span class="input-group-text fs_14">%</span>
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
    <script src="/admin/js/select2.min.js"></script>
    <script src="/admin/js/jquery-sortable.js"></script>
    <script src="/admin/js/script.js"></script>
    @include('admin.layouts.footer')

    <script src="/admin/js/jquery-3.7.0.min.js"></script>
    <script>
    $(function () {
        let maxTimes = 3;
        let deliveryIndex = 1; // Starts at 1 because one row already exists

        $('#add_delivery_time').on('click', function () {
            if ($('.delivery-time-row').length >= maxTimes) return;

            const html = `
                <div class="row g_16 delivery-time-row">
                    <div class="col-6">
                        <label class="form-label fs_14 c_dark2">من</label>
                        <input type="time" name="delivery_times[${deliveryIndex}][start]" class="form-control inp_sm" required>
                    </div>
                    <div class="col-6 position-relative">
                        <label class="form-label fs_14 c_dark2">إلى</label>
                        <input type="time" name="delivery_times[${deliveryIndex}][end]" class="form-control inp_sm" required>
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-time" style="z-index: 1;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#delivery_times_container').append(html);
            deliveryIndex++;
        });

        $(document).on('click', '.remove-time', function () {
            $(this).closest('.delivery-time-row').remove();
        });
    });
</script>

    <script>
        $(document).ready(function() {
            $('#other_categories').select2({
                placeholder: "اختر التصنيفات الإضافية",
                allowClear: true,
                width: '100%'
            });

            $('#zones_select').select2({
                placeholder: "اختر المناطق",
                allowClear: true,
                width: '100%'
            });
        });

        var $jq = jQuery.noConflict();
        $jq(function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    });

        $jq(document).ready(function () {

            // Reference elements
            // Reference elements based on the provided IDs
    
// Reference elements based on the provided static IDs
const inputElement = $("#contract_file"); // Hidden file input
const previewArea = $("#product_preview_container_contract_file"); // Preview container

// Click event to open file input
$("#contract_dropzone").on("click", function () {
    inputElement.click();
});

// Change event to handle file input selection
inputElement.on("change", function (event) {
    const selectedFile = event.target.files[0]; // Get the selected file

    if (selectedFile) {
        uploadFileToServer(selectedFile); // Upload image to server
    }
});

// Function to upload file to the server
function uploadFileToServer(selectedFile) {
    let formData = new FormData();
    formData.append('file', selectedFile);

    $.ajax({
        url: '/dashboard/attachment/merchant',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total;
                    percentComplete = parseInt(percentComplete * 100);
                    previewArea.find('.progress-bar').css('width', percentComplete + '%');
                    if (percentComplete === 100) {
                        setTimeout(() => previewArea.find('.progress-bar').css('background-color', 'green'), 500);
                    }
                }
            }, false);
            return xhr;
        },
        success: function (response) {
            console.log('File uploaded successfully:', response);
            displayFilePreview(selectedFile, response);
        },
        error: function (error) {
            console.log('Error uploading file:', error);
        }
    });
}

// Function to generate file preview
function displayFilePreview(selectedFile, response) {
    const uniqueId = Date.now(); // Unique ID for each preview
    const previewHTML = `
        <div class="file_preview" id="preview_${uniqueId}">
            <img src="{{ env('FILES_CDN') }}${response.url}" data-thumbnail />
            <div class="file_details">
                <div class="file_name pe-1">${selectedFile.name}</div>
                <div class="file_size">${(selectedFile.size / 1024).toFixed(2)} KB</div>
                <input class="form-check-input me-1" type="hidden" value="${response.id}" name="attachments[]">
            </div>
            <div class="btn-group btn-group-sm">
                <button class="btn text-danger remove_file" data-preview-id="preview_${uniqueId}">
                    <i class="fi fi-rr-trash"></i>
                </button>
            </div>
        </div>
    `;
    previewArea.append(previewHTML);

    // Remove file event
    $(".remove_file").on("click", function () {
        const previewId = $(this).data("preview-id");
        $(`#${previewId}`).remove();
        inputElement.val(""); // Clear the file input
    });
}
            setupFileUploader("merchant_dropzone", "image", "product_preview_container");

            function setupFileUploader(dropzoneId, fileInputId, previewContainerId) {
            const fileInput = $(`#${fileInputId}`); // Hidden file input
            const previewContainer = $(`#${previewContainerId}`); // Preview container

            // Click event to open file input
            $(`#${dropzoneId}`).on("click", function () {
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
                            <button class="btn text-danger remove_file">
                                <i class="fi fi-rr-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                previewContainer.append(previewHTML);

                // Remove file event
                previewContainer.on("click", ".remove_file", function () {
                    fileInput.val(""); // Clear the file input
                    previewContainer.empty(); // Remove the preview
                });
            }
        }
    });
    
        function initMap() {
            const defaultLocation = { lat: 24.7136, lng: 46.6753 };
            const map = new google.maps.Map(document.getElementById("map"), {
                center: defaultLocation,
                zoom: 8,
            });
    
            const marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                draggable: true,
            });
    
            const searchBox = new google.maps.places.SearchBox(document.getElementById("search-box"));
    
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
    
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();
                if (places.length === 0) return;
    
                const place = places[0];
                if (!place.geometry) return;
    
                const location = place.geometry.location;
                map.setCenter(location);
                map.setZoom(15);
    
                marker.setPosition(location);
                updateCoordinates(location.lat(), location.lng());
            });
    
            marker.addListener("dragend", () => {
                const position = marker.getPosition();
                if (position) updateCoordinates(position.lat(), position.lng());
            });
    
            function updateCoordinates(lat, lng) {
                document.getElementById("latitude").value = lat;
                document.getElementById("longitude").value = lng;
            }
    
            updateCoordinates(defaultLocation.lat, defaultLocation.lng);
        }
    
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof google !== "undefined") {
                initMap();
            }
        });
    </script>
    @include('admin.layouts.footer')


</body>

</html>
