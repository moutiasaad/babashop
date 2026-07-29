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
    <title>{{ env('APP_NAME') }} - خريطة المتاجر</title>

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
    img[src*="lovardportal.online"] {
        border-radius: 50%;
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
                            <h2 class="fs_24 text-black fw-bold fw-normal mb-0">خريطة المتاجر</h2>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-end">
                                <div class="me-2">
                                    <a href="{{ url('dashboard/' . request()->segment(2)) }}" class="btn btn_sm btn_primary_outline px-sm-4" type="reset">الرجوع</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g_24">
                    <div class="col-lg-12">
                        <div class="bg-white rounded_16 p_24 mb_24">
                                <div class="col-12 col-sm-12">
                                    <div>
                                        {{-- <label class="form-label fs_14 c_dark2">حدد العنوان *
                                            <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="العنوان الوطني المختصر" style="cursor: pointer;"></i>
                                        </label> --}}
                                        {{-- <input id="search-box" type="text" class="form-control inp_sm mb-4" placeholder="Search for a place"> --}}
                                        <div id="map"></div>
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
    const merchants = @json($merchants);

    function initMap() {
        const defaultLocation = { lat: 21.4858, lng: 39.1925 };
        const map = new google.maps.Map(document.getElementById("map"), {
            center: defaultLocation,
            zoom: 8,
        });

        const infoWindow = new google.maps.InfoWindow();

        merchants.forEach(merchant => {
            const lat = parseFloat(merchant.latitude);
            const lng = parseFloat(merchant.longitude);

            if (isNaN(lat) || isNaN(lng)) return;

            const marker = new google.maps.Marker({
                position: { lat, lng },
                map: map,
                icon: {
                    url: merchant.image,
                    scaledSize: new google.maps.Size(40, 40),
                    anchor: new google.maps.Point(20, 20)
                },
                title: merchant.brand_name,
            });

            const card = `
                <div style="max-width: 250px;">
                    <div><strong>${merchant.brand_name}</strong></div>
                    <div style="margin-top: 5px;">${merchant.description || ''}</div>
                    <div style="margin-top: 5px;">⭐ ${merchant.star || 0}</div>

                </div>
            `;

            marker.addListener("click", () => {
                infoWindow.setContent(card);
                infoWindow.open(map, marker);
            });
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

            map.setCenter(place.geometry.location);
            map.setZoom(15);
        });
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
