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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <title>{{ env('APP_NAME') }} - الطلبات</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/admin/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="/admin/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="/admin/css/image-uploader.min.css">
    <link rel="stylesheet" href="/admin/css/select2.min.css">
    <link rel="stylesheet" href="/admin/css/style.css">
    
    <meta property="og:title" content="تطبيق لوفارد">
    <meta property="og:description" content="وجهتك الاولى لخيارات الإهداء 🩷 #لحظة_فريدة">
    <meta property="og:image" content="https://lovardportal.online/uploads/lovard-cover.jpg">
    <meta property="og:type" content="application">
    <meta property="og:url" content="{{ url()->current() }}">

    <style>
        #map {
            height: 400px;
        }
        #search-box {
    position: relative;
    z-index: 9999;
}

    </style>

</head>

<body>
    <!-- ................ start header area ................ -->
    @include('admin.layouts.sidebar')
    <!-- sub header -->
    @include('admin.layouts.header')

    <!-- ................ end header area ................ -->

    <main class="main" id="google_translate_element">
        <!-- start Products section -->
        @if (auth()->guard('admin')->check() && auth()->guard('admin')->user()->role_id == 1)                            
        <div class="d-flex gap-2 mb-4" >
            <a href="/dashboard/orders/{{$id}}?version=15" class="btn btn-outline-secondary w-100" >عرض تفاصيل الطلب</a>
        </div>
        @endif
                <div class="d-flex gap-2 mb-4" >
            <a target="_blank"  href="https://www.google.com/maps?q={{ $order->merchant->latitude ?? '21.4858' }},{{ $order->merchant->longitude ?? '39.1925' }}"
 class="btn btn-outline-secondary w-100" >عرض موقع المتجر على خرائط جوجل</a>
        </div>

        <div class="row">
            <section class="col-lg-6">
               <div class="card shadow-sm">
                  <div class="card-header text-center">
                     <h3 style="color:black;">تفاصيل الإهداء #{{ $order->id }}</h3>
                  </div>
                  <div class="card-body">
                    @foreach ($order->order_lines as $line)
                        <div class="d-flex align-items-center mb-3">
                            <img class="rounded" src="{{ $line->product->image[0] ?? '#' }}" alt="{{ $line->product->name }}" width="120">
                            <div class="ms-3">
                                <p class="fw-bold">{{ $line->product->name }}</p>
                                <p class="text-muted">الكمية : {{ $line->qty }}</p>
                                <p class="fw-bold">السعر : {{ $line->price }} ر.س</p>
                                <div class="mt-2">
                                    <i class="fi {{ $order->order_type == 1 ? 'fi-rr-user' : 'fi-rr-gift' }}"></i>
                                    <span class="ms-1">{{ $order->order_type == 0 ? 'طلب لنفسي' : 'إهداء لشخص' }}</span>
                                </div>
                            </div>
                        </div>
                
                        @php $card = json_decode($line->card_description, true); @endphp
                        @if (!empty($card['from']) || !empty($card['to']) || !empty($card['message']))
                            <h5 class="text-start">كرت الإهداء</h5>
                            <div id="printableArea_{{ $line->id }}" class="text-center border rounded p-3 my-3">
                                <div class="row">
                                    <div class="col">
                                        <p class="fw-bold">من: {{ $card['from'] ?? '-' }}</p>
                                    </div>
                                    <div class="col">
                                        <p class="fw-bold">إلى: {{ $card['to'] ?? '-' }}</p>
                                    </div>
                                </div>
                                <p>{{ $card['message'] ?? '-' }}</p>
                            </div>
                            <div class="d-flex gap-2 mb-4">
                                <button class="btn btn-dark w-100" onclick="printDiv('printableArea_{{ $line->id }}')">طباعة الكرت</button>
                            </div>
                        @endif
                    @endforeach
                
                    <p class="text-danger text-start mt-3">ملاحظات : {{ $order->payment_note ?? 'لا يوجد' }}</p>
                </div>
                               </div>
            </section>
            <section class="col-lg-6 text-right">
               <div class="card shadow-sm mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 style="color:black;">بيانات التوصيل/الاستلام</h4>
                    @if (auth()->guard('admin')->check() && auth()->guard('admin')->user()->role_id == 1)                            
                    <a class="btn btn-outline-secondary w-20" target="_blank" href="/order-details/{{$id}}">رابط العميل</a>
                    @endif
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
                     @if($order->getRawOriginal('status') != 3 &&  $order->getRawOriginal('status') != 4 )
                     <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary w-100"  data-bs-toggle="modal" data-bs-target="#order_update_modal" >تحديث حالة الطلب</button>
                    </div>
                    @endif
                    @if($order->getRawOriginal('status') != 4 )
                     <div class="d-flex gap-2 mt-2">
                        <a class="btn btn-outline-secondary w-100" target="_blank" href='/dashboard/orders/invoice/{{$order->id}}' >عرض الفاتورة</a>
                    </div>
                    @endif

                  </div>
               </div>
               <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 style="color:black;">تحديد موقع الإستلام</h3>
                    <a class="btn btn-outline-secondary w-20" target="_blank" 
                       href="https://www.google.com/maps?q={{ $order->latitude ?? '21.4858' }},{{ $order->longitude ?? '39.1925' }}">رابط جوجل ماب</a>
                </div>


                  <div class="card-body">
                    <label class="form-label fs_14 c_dark2" > يرجى تحديد موقع الإستلام من الخريطة لدقة العنوان</label>
                    
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
                        <div class="col-12 col-sm-12">
                            <div>
                                <label class="form-label fs_14 c_dark2">Latitude</label>
                                <input type="text" id="latitude" name="latitude" value="{{ $order->latitude ?? '21.4858' }}" class="form-control mb-2" >
                        </div>
                        <div class="col-12 col-sm-12">
                            <div>
                                <label class="form-label fs_14 c_dark2">Longitude</label>
                                <input type="text" id="longitude" name="longitude" value="{{ $order->longitude ?? '39.1925' }}" class="form-control mb-2"  >
                        </div>


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
                            <input type="date" name="specific_date" dir="rtl" class="form-control mb-2" 
                                   value="{{ $delivery_date }}">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary w-100" type="submit" >حفظ</button>
                        </div>
    
                    </form>
                    </div>
               </div>
            </section>
        </div>
        <!-- end Products section -->
    </main>
    
    
    <!-- ................ end main area ................ -->

    <div class="modal fade modal_sm" id="status_update_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="w-100" action="{{route('admin.order.assigndriver')}}"  method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id}}">
                <div class="modal-content border-0 rounded_6">
                    <div class="modal-header bg_light2 py_12 px_16">
                        <div class="pe-1">
                            قبول الطلب 
                        </div>
                        <div>
                            <button type="reset" class="btn close_btn" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fi fi-rr-cross-small"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                    <div class="col-12">
                        <label class="form-label fs_14 text_dark2">إختر طريقة الشحن</label>
                        <div class="input-group">
                            <div class="flex-grow-1">
                                <select class="form-control inp_sm form-select select_sm select_2" name="driver_id" id="driver_id" @if($order->driver_id == 2) disabled @endif>
                                    @php
                                        $drivers = \App\Models\Driver::all();
                                    @endphp
                                    @foreach ($drivers as $driver)
                                        @if($driver->id != 2 || ($order->driver_id == 2 && $driver->id == 2))
                                            <option value="{{ $driver->id }}" @selected($order->driver_id == $driver->id)>{{ $driver->name }}</option>
                                        @endif
                                    @endforeach
                                </select>  
                                @if($order->driver_id == 2)
                                <input type="hidden" name="driver_id" value="{{ $order->driver_id }}">
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- New input field for سعر التوصيل -->
                    <div class="col-12" id="delivery_price_container" style="display: none;">
                        <label class="form-label fs_14 text_dark2">سعر التوصيل</label>
                        <input type="text" class="form-control inp_sm" name="delivery_price">
                    </div>

                        <div class="pt_24">
                            <div class="d-flex justify-content-end">
                                <div class="me-2">
                                    <button class="btn btn_sm btn-outline-secondary" type="reset" data-bs-dismiss="modal" aria-label="Close">لا، تراجع</button>
                                </div>
                                <div>
                                    <button class="btn btn_sm btn_primary" type="submit"  data-bs-dismiss="modal" aria-label="Close">حفظ </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade modal_sm" id="order_update_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="w-100" action="{{route('admin.order.status.update' , $order->id)}}"  method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id}}">
                <div class="modal-content border-0 rounded_6">
                    <div class="modal-header bg_light2 py_12 px_16">
                        <div class="pe-1">
تحديث حالة الطلب                        </div>
                        <div>
                            <button type="reset" class="btn close_btn" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fi fi-rr-cross-small"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                    <div class="col-12">
                        <label class="form-label fs_14 text_dark2">إختر الحالة</label>
                        <div class="input-group">
                            <div class="flex-grow-1">
                                <select class="form-control inp_sm form-select select_sm select_2" name="status_id" id="status_id">
                                    {{-- <option value="6">بانتظار تحديد عنوان المُهدى إليه</option> --}}
                                    <option value="2">جاري تجهيز طلبك</option>
                                    <option value="7">تم تجهيز طلبك</option>
                                    {{-- <option value="3">تم تسليم الهدية</option>
                                    <option value="4">ملغاة</option>
                                    <option value="5">مسترجعة</option> --}}
                                </select>  
                            </div>
                        </div>
                    </div>
                    
                    <!-- New input field for سعر التوصيل -->

                        <div class="pt_24">
                            <div class="d-flex justify-content-end">
                                <div class="me-2">
                                    <button class="btn btn_sm btn-outline-secondary" type="reset" data-bs-dismiss="modal" aria-label="Close">لا، تراجع</button>
                                </div>
                                <div>
                                    <button class="btn btn_sm btn_primary" type="submit"  data-bs-dismiss="modal" aria-label="Close">حفظ </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade modal_sm" id="barq_confirmation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="w-100" action="{{route('admin.order.confirmBarq',$order->id)}}"  method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id}}">
                <div class="modal-content border-0 rounded_6">
                    <div class="modal-header bg_light2 py_12 px_16">
                        <div class="pe-1">
                            تاكيد التوصيل في تطبيق المندوب 
                        </div>
                        <div>
                            <button type="reset" class="btn close_btn" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fi fi-rr-cross-small"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <div class="input-group">
                                <div class="flex-grow-1 row">
                                    <div class="col-12 ">
                                        <div>
                                            <h2 class="form-label fs_14 c_dark2"> سيتم تأكيد إضافة طلب الشحن وتسجيل بيانات الطلب في تطبيق المندوب</h2>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <div class="pt_24">
                            <div class="d-flex justify-content-end">
                                <div class="me-2">
                                    <button class="btn btn_sm btn-outline-secondary" type="reset" data-bs-dismiss="modal" aria-label="Close">لا، تراجع</button>
                                </div>
                                <div>
                                    <button class="btn btn_sm btn_primary" type="submit"  data-bs-dismiss="modal" aria-label="Close">حفظ </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- scripts -->
        <script src="/admin/js/jquery-3.7.0.min.js"></script>

    <script src="/admin/js/bootstrap.bundle.min.js"></script>

    <script src="/admin/js/smooth-scrollbar.js"></script>
    <script src="/admin/js/apexcharts.min.js"></script>
    <script src="/admin/js/ckeditor.js"></script>
    <script src="/admin/js/dropzone.min.js"></script>
    <script src="/admin/js/select2.min.js"></script>
    <script src="/admin/js/jquery-sortable.js"></script>
    <script src="/admin/js/script.js"></script>
    @include("admin.layouts.footer")
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
    $(document).ready(function() {
    $(".header_tglBtn,.header_backdrop").click(function(){
        $(".header").toggleClass("active_header");
    });
        });

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