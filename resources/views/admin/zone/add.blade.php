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


</head>

<body>
    @include('admin.layouts.sidebar')
    @include('admin.layouts.header')

    <!-- Main Content -->
    <main class="main">
        <section class="mb_16">
            <div class="container-fluid px-0">
                <form action="{{ route('admin.' . $Model_API_ROUTE . '.store') }}" method="POST" enctype='multipart/form-data' >
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
                                <h3 class="fs_20 mb_16">تفاصيل {{$Model_SINGULAR}}</h3>
                                <div class="row g_16">
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2"> اسم  {{$Model_SINGULAR}}</label>
                                            <input type="text" name="name" class="form-control inp_sm" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2"> حالة  {{$Model_SINGULAR}}</label>
                                            <input type="text" name="status" class="form-control inp_sm" placeholder="" value="{{ old('status') }}">
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
