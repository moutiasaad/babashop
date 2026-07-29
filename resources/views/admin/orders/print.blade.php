@php
use Carbon\Carbon;

$start = Carbon::parse($order->start_date_delivery)->format('gA'); // e.g., 5AM
$end = Carbon::parse($order->end_date_delivery)->format('gA'); // e.g., 4PM
$delivery_date = Carbon::parse($order->start_date_delivery)->translatedFormat('Y-m-d'); 
    $start = str_replace(['AM', 'PM'], ['ص', 'م'], $start);
    $end = str_replace(['AM', 'PM'], ['ص', 'م'], $end);


@endphp

<!DOCTYPE html>
<html lang="ar" dir="rtl">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Lovard | لوفارد</title>
      <meta name="description" content="بوابة لإدارة طلباتك">
      <link rel="icon" href="/favicon.ico" type="image/x-icon" sizes="16x16">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
      <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300..900&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@100..900&display=swap" rel="stylesheet">
   </head>
   <style>
       #map {
    width: 100%;
    height: 400px;
}

   </style>
   <body class="bg-light">
      <div class="container p-4">
        <header>
            <nav class="navbar navbar-light bg-white shadow-sm d-flex justify-content-center">
               <a href="#"><img src="https://framerusercontent.com/images/BIxPoOT10VnpkNEjJKLp49O94qM.png?scale-down-to=512" alt="Flower it logo" width="150"></a>
            </nav>
         </header>
        @if(session('successMsg'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('successMsg') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

         <main class="row mt-4">
            @if(false)
            <section class="col-lg-6">
               <div class="card shadow-sm">
                  <div class="card-header text-center">
                     <h3>تفاصيل الإهداء #{{ $order->id }}</h3>
                  </div>
                  <div class="card-body">
                     <div class="d-flex align-items-center mb-3">
                        <img class="rounded" src="{{ $order->product->image[0] ?? '#' }}" alt="{{ $order->product->name }}" width="120">
                        <div class="ms-3">
                           <p class="fw-bold">{{ $order->product->name }}</p>
                           <p class="text-muted">الكمية : 1</p>
                           <p class="fw-bold">السعر : {{ $order->unit_price }} ر.س</p>
                        </div>
                     </div>
                     <h5 class="text-start">كرت الإهداء</h5>
                     <div id="printableArea" class="text-center border rounded p-3 my-3">
                        <p class="fw-bold">{{ $order->client->fullname  }}</p>
                        <p>{{ $order->card_description['message'] ?? '-' }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-dark w-100" onclick="printDiv('printableArea')">طباعة الكرت</button>
                    </div>
                    <p class="text-danger text-start mt-3">ملاحظات : {{ $order->payment_note?? 'لا يوجد' }}</p>
                  </div>
               </div>
            </section>
            @endif
            <section class="col-lg-12 text-right">
                @if(false)
               <div class="card shadow-sm mb-3">
                  <div class="card-header">
                     <h3>بيانات التوصيل/الاستلام</h3>
                  </div>
                  <div class="card-body">
                     <ul class="list-unstyled ">
                        <li>تاريخ التوصيل: <span class="fw-bold">{{ $delivery_date }}</span></li>
                        <li>وقت التوصيل: <span class="fw-bold">{{ $start }} - {{ $end }}</span></li>
                        <li>المدينة: <span class="fw-bold">الرياض</span></li>
                        <li>إسم المستلم: <span class="fw-bold">{{ $order->fullname }}</span></li>
                        <li>رقم المستلم: <span class="fw-bold">{{ $order->phone }}</span></li>
                        <li>حالة الطلب: <span class="fw-bold">{{ $order->status }}</span></li>
                     </ul>
                  </div>
               </div>
               @endif
               <div class="card shadow-sm">
                  <div class="card-header">
                     <h3>تحديد موقع الإستلام</h3>
                  </div>
                  <div class="card-body">
                    <label class="form-label fs_14 c_dark2"> يرجى تحديد موقع الإستلام من الخريطة لدقة العنوان</label>
                    
                     <div class="col-12 ">
                        <div>
                        <div id="map"></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div>
                            <label class="form-label fs_14 c_dark2">حدد العنوان ( العنوان الوطني )</label>
                        <input id="search-box" type="text"  class="form-control  mb-4" placeholder="Search for a place" style="">
                        </div>

                    </div>
                    
                    <form method="POST"  action="{{ route('orders.order-details.save',$id) }}">
                        <input type="hidden" id="latitude" name="latitude" value="{{ $order->latitude ?? '21.4858' }}" />
                        <input type="hidden" id="longitude" name="longitude" value="{{ $order->longitude ?? '39.1925' }}" />
                        <div class="col-12 col-sm-12">
                            <div>
                                <label class="form-label fs_14 c_dark2">أوقات التوصيل</label>
                                <select class="form-control mb-2" name="delivery_time">
                                    @if($order->merchant && $order->merchant->deliveryTimes->isNotEmpty())
                                        @foreach($order->merchant->deliveryTimes as $deliveryTime)
                                            <option value="{{ $deliveryTime->id }}">
                                                {{ Carbon::parse($deliveryTime->start)->format('gA') }} - 
                                                {{ Carbon::parse($deliveryTime->end)->format('gA') }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">لا توجد أوقات توصيل متاحة</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12">
                            <div>
                                <label class="form-label fs_14 c_dark2">تاريخ التوصيل</label>
                            <input type="date" name="specific_date" class="form-control mb-2" 
                                   value="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary w-100" type="submit" >حفظ</button>
                        </div>
    
                    </form>
                    </div>
               </div>
            </section>
         </main>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
      <script src="/admin/js/jquery-3.7.0.min.js"></script>

      <script>
        function printDiv(divId) {
            var content = document.getElementById(divId).innerHTML;
            var originalBody = document.body.innerHTML;
            document.body.innerHTML = content;
            window.print();
            document.body.innerHTML = originalBody;
            location.reload();
        }
    </script>
    
<script>
    function initMap() {
        if (!document.getElementById("map")) {
            console.error("Map container not found.");
            return;
        }

        const defaultLocation = { 
            lat: {{ $order->latitude ?? '21.4858' }}, 
            lng: {{ $order->longitude ?? '39.1925' }} 
        };

        const map = new google.maps.Map(document.getElementById("map"), {
            center: defaultLocation,
            zoom: 12
        });

        const marker = new google.maps.Marker({
            position: defaultLocation,
            map: map,
            draggable: true
        });

        // Initialize the search box
        const input = document.getElementById("search-box");
        const searchBox = new google.maps.places.SearchBox(input);

        // Bias the search box results towards the current map bounds
        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
        });

        // Handle place selection from search box
        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();

            if (places.length === 0) return;

            const place = places[0];

            if (!place.geometry || !place.geometry.location) {
                console.error("Selected place contains no geometry.");
                return;
            }

            // Move the map to the selected location
            const location = place.geometry.location;
            map.setCenter(location);
            map.setZoom(15);
            marker.setPosition(location);

            // Update coordinates
            updateCoordinates(location.lat(), location.lng());
        });

        // Handle marker drag event
        marker.addListener("dragend", () => {
            const position = marker.getPosition();
            if (position) {
                updateCoordinates(position.lat(), position.lng());
            }
        });

        function updateCoordinates(lat, lng) {
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;
        }
    }

    // Ensure Google Maps is fully loaded before calling initMap()
    function loadGoogleMaps() {
        if (typeof google !== "undefined" && typeof google.maps !== "undefined") {
            initMap();
        } else {
            console.error("Google Maps API failed to load.");
        }
    }
</script>

<!-- ✅ Load Google Maps API Properly -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrDYCXAVQZeXxbZx84iRVe5SMmBpm5sy8&libraries=places&callback=loadGoogleMaps"></script>
   </body>
</html>