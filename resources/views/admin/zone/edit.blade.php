@php

$Model_PLURAL = 'المناطقة';
$Model_SINGULAR = 'منطقة';
$Model_API_ROUTE = 'zone';
$Model_Name = 'zone';

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

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrDYCXAVQZeXxbZx84iRVe5SMmBpm5sy8&libraries=places,drawing"></script>
    <style>
        #map {
            height: 500px;
            width: 100%;
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
                <form action="{{ route('admin.zone.update', $zone->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="main__header">
                        <div class="row">
                            <div class="col-6">
                                <h2 class="fs_24 text-black fw-bold fw-normal mb-0">تعديل المنطقة</h2>
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
                                <h3 class="fs_20 mb_16">تفاصيل المنطقة</h3>
                                <div class="row g_16">
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">اسم المنطقة *</label>
                                            <input type="text" name="name" class="form-control inp_sm" value="{{ $zone->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">الحالة</label>
                                            <input type="text" name="status" class="form-control inp_sm" value="{{ $zone->status }}">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="py_12 px_16 d-block border rounded_6 mt-2 fs_14">
                                            <input class="form-check-input me-2" name="active" type="checkbox" {{ $zone->active ? 'checked' : '' }}>
                                            المنطقة نشطة
                                        </label>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">خط العرض الشمالي</label>
                                            <input type="text" id="northLat" name="northLat" class="form-control inp_sm" value="{{ $zone->northLat }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">خط العرض الجنوبي</label>
                                            <input type="text" id="southLat" name="southLat" class="form-control inp_sm" value="{{ $zone->southLat }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">خط الطول الشرقي</label>
                                            <input type="text" id="eastLng" name="eastLng" class="form-control inp_sm" value="{{ $zone->eastLng }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">خط الطول الغربي</label>
                                            <input type="text" id="westLng" name="westLng" class="form-control inp_sm" value="{{ $zone->westLng }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="bg-white rounded_16 p_24">
                                <h3 class="fs_20 mb_16">تحديد المنطقة *
                                    <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="ارسم منطقة مستطيلة على الخريطة لتحديد حدود المنطقة" style="cursor: pointer;"></i>
                                </h3>
                                <div class="mb-3">
                                    <button type="button" class="btn btn_sm btn_primary" id="drawZone">
                                        <i class="fas fa-draw-polygon"></i> ارسم منطقة
                                    </button>
                                    <button type="button" class="btn btn_sm btn_secondary" id="clearZone">
                                        <i class="fas fa-trash"></i> محو المنطقة
                                    </button>
                                </div>
                                <div id="map"></div>
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

    <script>
        let map;
        let drawingManager;
        let selectedZone;

        function initMap() {
            const defaultLocation = { lat: 24.7136, lng: 46.6753 }; // Riyadh, Saudi Arabia

            map = new google.maps.Map(document.getElementById("map"), {
                center: defaultLocation,
                zoom: 10,
            });

            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: null,
                drawingControl: false,
                rectangleOptions: {
                    fillColor: '#ff0000',
                    fillOpacity: 0.35,
                    strokeWeight: 2,
                    strokeColor: '#ff0000',
                    clickable: false,
                    editable: true,
                    zIndex: 1
                }
            });

            drawingManager.setMap(map);

            // Load existing zone if available
            const northLat = parseFloat(document.getElementById('northLat').value);
            const southLat = parseFloat(document.getElementById('southLat').value);
            const eastLng = parseFloat(document.getElementById('eastLng').value);
            const westLng = parseFloat(document.getElementById('westLng').value);

            if (northLat && southLat && eastLng && westLng) {
                const bounds = new google.maps.LatLngBounds(
                    new google.maps.LatLng(southLat, westLng),
                    new google.maps.LatLng(northLat, eastLng)
                );

                selectedZone = new google.maps.Rectangle({
                    bounds: bounds,
                    fillColor: '#ff0000',
                    fillOpacity: 0.35,
                    strokeWeight: 2,
                    strokeColor: '#ff0000',
                    editable: true,
                    map: map
                });

                // Center map on existing zone
                map.fitBounds(bounds);

                // Add listener for when rectangle is edited
                selectedZone.addListener('bounds_changed', function() {
                    updateCoordinatesFromRectangle(selectedZone);
                });
            }

            drawingManager.addListener('rectanglecomplete', function(rectangle) {
                if (selectedZone) {
                    selectedZone.setMap(null);
                }
                selectedZone = rectangle;
                updateCoordinatesFromRectangle(rectangle);

                // Add listener for when rectangle is edited
                rectangle.addListener('bounds_changed', function() {
                    updateCoordinatesFromRectangle(rectangle);
                });
            });
        }

        function updateCoordinatesFromRectangle(rectangle) {
            const bounds = rectangle.getBounds();
            const ne = bounds.getNorthEast();
            const sw = bounds.getSouthWest();

            document.getElementById('northLat').value = ne.lat();
            document.getElementById('southLat').value = sw.lat();
            document.getElementById('eastLng').value = ne.lng();
            document.getElementById('westLng').value = sw.lng();
        }

        $(document).ready(function() {
            $('#drawZone').on('click', function() {
                drawingManager.setDrawingMode(google.maps.drawing.OverlayType.RECTANGLE);
            });

            $('#clearZone').on('click', function() {
                if (selectedZone) {
                    selectedZone.setMap(null);
                    selectedZone = null;
                }
                drawingManager.setDrawingMode(null);
                document.getElementById('northLat').value = '';
                document.getElementById('southLat').value = '';
                document.getElementById('eastLng').value = '';
                document.getElementById('westLng').value = '';
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            if (typeof google !== "undefined") {
                initMap();
            }
        });
    </script>


</body>

</html>
