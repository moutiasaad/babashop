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

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrDYCXAVQZeXxbZx84iRVe5SMmBpm5sy8&libraries=places"></script>
    <style>
        #map {
            height: 400px;
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
                <form action="{{ route('admin.merchants.profileUpdate')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="main__header">
                        <div class="row">
                            <div class="col-6">
                                <h2 class="fs_24 text-black fw-bold fw-normal mb-0">تفاصيل المتجر</h2>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <a href="{{ url('dashboard') }}" class="btn btn_sm btn_primary_outline px-sm-4" type="reset">الغاء</a>
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
                                <h3 class="fs_20 mb_16">تفاصيل التاجر</h3>
                                <div class="row g_16">
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">عنوان المتجر *</label>
                                            <input type="text" name="brand_name" class="form-control inp_sm" value="{{ $merchant->brand_name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">مفتوح عند *</label>
                                            <input type="time" name="open_at" class="form-control inp_sm" value="{{ $merchant->open_at }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">يغلق في *</label>
                                            <input type="time" name="close_at" class="form-control inp_sm" value="{{ $merchant->close_at }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">الوصف *</label>
                                            <textarea class="form-control" name="description" rows="4" required>{{ $merchant->description }}</textarea>
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
                                            <div id="product_preview_container" class="preview_area">
                                                <div id="product_preview_container" class="preview_area">
                                                    @if ($merchant->image)
                                                        <div class="file_preview">
                                                <img src="{{ $merchant->image }}" data-thumbnail />
                                                            <div class="file_details">
                                                                <div class="file_name pe-1">الصورة</div>
                                                                <div class="file_size">- KB</div>
                                                            </div>
                                                            <div class="btn-group btn-group-sm">
                                                                <a class="btn text-primary" href="${fileUrl}" download>
                                                                    <i class="fi fi-rr-down-to-line"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">سياسة الإسترجاع</label>
                                            <textarea class="form-control" name="return_policy" id="return_policy_editor" rows="4" >{{ $merchant->return_policy }}</textarea>
                                        </div>
                                    </div>
                                    

                                </div>
                            </div>
                        <div class="bg-white rounded_16 p_24 mt-4">
                            <h3 class="fs_20 mb_16">أوقات التوصيل المتاحة *
                                <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="اعداد المنتجات و تجهيزيها فوري او مجدول" style="cursor: pointer;"></i>
                            </h3>
                            <div id="delivery_times_container">
                                @foreach ($merchant->deliveryTimes as $index => $deliveryTime)
                                    <div class="row g_16 delivery-time-row">
                                        <div class="col-6">
                                            <label class="form-label fs_14 c_dark2">من</label>
                                            <input type="time" name="delivery_times[{{ $index }}][start]" 
                                                class="form-control inp_sm"
                                                value="{{ $deliveryTime->start }}" required>
                                        </div>
                                        <div class="col-6 position-relative">
                                            <label class="form-label fs_14 c_dark2">إلى</label>
                                            <input type="time" name="delivery_times[{{ $index }}][end]" 
                                                class="form-control inp_sm"
                                                value="{{ $deliveryTime->end }}" required>
                                            @if ($index > 0)
                                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-time" style="z-index: 1;"><i class="fas fa-times"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
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
                                                    <option value="{{ $city->id }}" {{ $merchant->city_id == $city->id ? 'selected' : '' }}>
                                                        {{ $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">العنوان *</label>
                                            <input type="text" name="street_name" class="form-control inp_sm" value="{{ $merchant->street_name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">خط العرض *</label>
                                            <input type="text" id="latitude" name="latitude" class="form-control inp_sm" value="{{ $merchant->latitude }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">خط الطول *</label>
                                            <input type="text" id="longitude" name="longitude" class="form-control inp_sm" value="{{ $merchant->longitude }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">حدد العنوان *
                                                <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="العنوان الوطني المختصر" style="cursor: pointer;"></i>
                                            </label>
                                        <input id="search-box" type="text"  class="form-control inp_sm mb-4" placeholder="Search for a place" style="">
                                        <div id="map"></div>
                                        </div>

                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">عنوان إيميل التنبيه *
                                                <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="الايميل اللي تكون عليه  اشعارات الطلبات اليومية.
    " style="cursor: pointer;"></i>
</label>
                                            <input type="text" name="notif_email" class="form-control inp_sm" value="{{ $merchant->notif_email}}" >
                                        </div>

                                    <!--<div class="col-12">-->
                                    <!--    <label class="py_12 px_16 d-block border rounded_6 mt-2 fs_14">-->
                                    <!--        <input class="form-check-input me-2" name="delivery_is_free" type="checkbox" {{ $merchant->delivery_is_free ? 'checked' : '' }}>-->
                                    <!--        توصيل مجاني-->
                                    <!--    </label>-->
                                    <!--</div>-->

                                    <div class="col-12" id="percent_discount_div">
                                        <label class="form-label fs_14 c_dark2">أدخل أقل مبلغ مطلوب للحصول على التوصيل المجاني</label>
                                        <div class="input-group">
                                            <input type="number" name="price_free_delivery" class="form-control inp_sm" value="{{ $merchant->price_free_delivery}}" style="direction:rtl;" min="0" >
                                            <span class="input-group-text fs_14">SAR</span>
                                        </div>
                                    </div>
                                    <div class="col-12" id="percent_discount_div">
                                        <label class="form-label fs_14 c_dark2">نسبة الخصم (نسبة مئوية)
                                            <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="المقصود الخصومات الموسمية و غيرها و حتى اليومية تكون ظاهرة لجميع الزبائن،
                                            ٥٪؜/١٠٪؜/١٥٪؜….." style="cursor: pointer;"></i>                                            
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="attribut_reduction" class="form-control inp_sm" value="{{ $merchant->attribut_reduction}}" placeholder="قيمة الخصم" style="direction:rtl;" min="0" max="100" oninput="this.value = Math.floor(this.value); if(this.value > 100) this.value = 100;">
                                            <span class="input-group-text fs_14">%</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">وقت التوصيل</label>
                                            <select class="form-select select_sm" name="attribut_delivery" required>
                                                <option selected disabled>اختر وقت التوصيل</option>
                                                <option value="اليوم" {{ $merchant->attribut_delivery == "اليوم" ? 'selected' : '' }}>اليوم</option>
                                                <option value="يومين" {{ $merchant->attribut_delivery == "يومين" ? 'selected' : '' }}>يومين</option>
                                                <option value="ثلاث ايام" {{ $merchant->attribut_delivery == "ثلاث ايام" ? 'selected' : '' }}>ثلاث ايام</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12" id="platform_fee_discount_div">
                                        <label class="form-label fs_14 c_dark2">نسبة المنصة (نسبة مئوية)</label>
                                        <div class="input-group">
                                            <input 
                                                type="number" 
                                                name="fees" 
                                                class="form-control inp_sm" 
                                                value="{{ $merchant->platform_fee }}" 
                                                placeholder="نسبة المنصة" 
                                                style="direction:rtl;" 
                                                min="0" 
                                                max="100" 
                                                step="0.01" disabled
                                            >
                                            <span class="input-group-text fs_14">%</span>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div>
                                            <label class="form-label fs_14 c_dark2 mb_4">ملفات عقد التاجر</label>
                                            <div id="product_preview_container_contract_file" class="preview_area_contract_file">
                                                <div id="product_preview_container_contract_file" class="preview_area_contract_file">
                                                @foreach($merchant->attachments as $attachment)
                                            <div class="file_preview" id="preview_{{ $attachment->id }}">
                                                <img src="{{ env('FILES_CDN') }}/uploads/{{ $attachment->folder }}/{{ $attachment->name }}" data-thumbnail />
                                                <div class="file_details">
                                                    <div class="file_name pe-1">{{ $attachment->name }}</div>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <label>
                                                        <input type="hidden" name="attachments[]" value="{{ $attachment->id }}">
                                                    </label>
                                                            <div class="btn-group btn-group-sm">
                                                                <a class="btn text-primary" href="{{ env('FILES_CDN') }}/uploads/{{ $attachment->folder }}/{{ $attachment->name }}" download>
                                                                    <i class="fi fi-rr-down-to-line"></i>
                                                                </a>
                                                            </div>
                                                </div>
                                            </div>
                                        @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">حالة الظهور *</label>
                                            <select class="form-select select_sm" name="visibility" required>
                                                    <option value="0" {{ $merchant->visibility == 0 ? 'selected' : '' }}>
                                                        مخفي
                                                    </option>

                                                    <option value="1" {{ $merchant->visibility == 1 ? 'selected' : '' }}>
                                                        ظاهر
                                                    </option>
                                            </select>
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
<style>
    .ck-editor__editable {
        max-height: 200px !important; /* Limits the height */
        overflow-y: auto !important;  /* Enables vertical scrolling */
    }
</style>
    <script>
    $(function () {
        let maxTimes = 3;
        let deliveryIndex = {{ $merchant->deliveryTimes->count() }};

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
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-time" style="z-index: 1;"><i class="fas fa-times"></i></button>
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

    <script src="/admin/js/jquery-3.7.0.min.js"></script>
        <script>
                    var $jq = jQuery.noConflict();
                $jq(function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    });

    ClassicEditor
        .create(document.querySelector('#return_policy_editor'), {
            language: 'ar',
        })
        .then(editor => {
            editor.ui.view.editable.element.style.maxHeight = "200px";
            editor.ui.view.editable.element.style.overflowY = "auto";
        })
        .catch(error => {
            console.error(error);
        });

        var $jq = jQuery.noConflict();
        $jq(document).ready(function () {
            // Reference elements
            setupFileUploader("contract_dropzone", "contract_file", "product_preview_container_contract_file");
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
            const defaultLocation = { lat: {{$merchant->latitude ?? "21.4858" }}, lng: {{$merchant->longitude ?? "39.1925" }} };
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
