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
                <form action="{{ route('admin.balances.update', $merchant->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="main__header">
                        <div class="row">
                            <div class="col-6">
                                <h2 class="fs_24 text-black fw-bold fw-normal mb-0">تعديل التاجر</h2>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <a class="btn btn_sm btn_primary_outline px-sm-4" href="/dashboard/balances/">الغاء</a>
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
                                <h3 class="fs_20 mb_16">تفاصيل المتجر</h3>
                                <div class="row g_16">
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">عنوان المتجر</label>
                                            <input type="text" name="brand_name" class="form-control inp_sm" value="{{ $merchant->brand_name }}" disabled>
                                        </div>
                                    </div>
<div class="col-12">
    <div>
        <label class="form-label fs_14 c_dark2">الرصيد</label>
        <input type="text" class="form-control inp_sm" value="{{ round($merchant->balance->amount ?? 0, 2) }}" disabled>
    </div>
</div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">نوع المعاملة</label>
                                            <select class="form-select select_sm" name="type_merchant_id" required>
                                                <option value="withdraw" selected >سحب رصيد</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12" id="platform_fee_discount_div">
                                        <div class="col-12" id="platform_fee_discount_div">
                                            <label class="form-label fs_14 c_dark2">المبلغ </label>
                                            <div class="input-group">
                                                <input 
                                                    type="number" 
                                                    name="price" 
                                                    class="form-control inp_sm" 
                                                    placeholder="المبلغ" 
                                                    style="direction:rtl;" 
                                                    min="0" 
                                                    step="0.01" 
                                                    required
                                                >
                                                <span class="input-group-text fs_14">SAR</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">تفاصيل</label>
                                            <input type="text" name="description" class="form-control inp_sm" value="" >
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
        var $jq = jQuery.noConflict();
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
