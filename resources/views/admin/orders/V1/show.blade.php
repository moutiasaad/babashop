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
        <section class="mb_16 order_details_section">
            <div class="container-fluid px-0">
    
                <!-- Order Details -->
                <div class="bg-white rounded_16 p-4 mb-3">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="fs_14">
                                <div class="mb-2 fw-semibold">#رقم الطلب </div>
                                <div class="mb-2">#{{ $order->id }} </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="fs_14 text-sm-center">
                                <div class="mb-2 fw-semibold"><i class="fi fi-rr-calendar"></i> التاريخ </div>
                                <div class="mb-2">{{ \Carbon\Carbon::parse($order->created_at)->locale('ar')->isoFormat('dddd D MMMM YYYY') }}</div>
                                <div class="">PM {{ $order->created_at->format('h:i') }} :الوقت</div>
                            </div>
                        </div>
                        


                        <div class="col-sm-4">
                            <div class="fs_14 text-sm-end">
                                <div class="mb-2 fw-semibold"><i class="fi fi-rr-flag"></i> حالة الطلب </div>
                                <div class="mb-2">
                                    <span class="alert alert-warning p-1">{{ $order->status }}</span>
                                </div>
                                 @if($order->getRawOriginal('status') == 2 &&  $order->driver->id == 1 &&  $order->is_shipped != 1)
                                <a href="#" class="btn btn-success btn-sm fs_10" data-bs-toggle="modal" data-bs-target="#barq_confirmation">
                                    <i class="fi fi-rr-truck"></i> تاكيد الشحن عبر BARQ
                                </a>
                                @endif
                                
                                @if($order->getRawOriginal('status') == 0)
                                <a href="#" class="btn btn-success btn-sm fs_10" data-bs-toggle="modal" data-bs-target="#status_update_modal">
                                    <i class="fi fi-rr-truck"></i> الموافقة على الطلب
                                </a>
                                <a href="{{ route('admin.order.cancel', $order->id) }}" class="btn btn-danger btn-sm fs_10">
                                    <i class="fi fi-rr-trash"></i> الغاء الطلب
                                </a>
                                @endif
                                @if($order->getRawOriginal('status') > 1)
                                <a href="#" class="btn btn-success btn-sm fs_10" data-bs-toggle="modal" data-bs-target="#order_update_modal">
                                    <i class="fi fi-rr-truck"></i> تحديث حالة الطلب
                                </a>
                                @endif

                            </div>
                            
                        </div>
                        <div class="col-sm-4">
                            <div class=" text-sm-right">
                                <div class="mb-2 fw-semibold"><i class="fi fi-rr-gift"></i> نوع الطلب </div>
                                <div class="">{{ $order->order_type == 1 ? 'لنفسي' : 'إهداء لشخص' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class=" text-sm-center">
                                <div class="mb-2 fw-semibold"><i class="fi fi-rr-shop"></i> المتجر </div>
                                <div class="">{{ $order->merchant->brand_name }} </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                        </div>

                    </div>
                </div>
    
                <!-- Customer and Payment Information -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6 col-lg-6">
                        <div class="bg-white rounded_16 p-3 h-100 position-relative">
                            <div class="text-center mb-2">
                                <div class="fs_14 fw-bold">تفاصيل المستلم</div>
                            </div>
                            <div class="fs_14 mb-1" >
                                <i class="fi fi-rr-phone-call"></i> إسم المستلم :<a href="#">{{ $order->fullname }}</a>
                            </div>
                            <div class="fs_14 mb-1" >
                                <i class="fi fi-rr-phone-call"></i> رقم :<a href="tel:{{ $order->phone }}">{{ $order->phone ?? $order->client->phone_number }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <div class="bg-white rounded_16 p-3 h-100 position-relative">
                            <div class="text-center mb-2">
                                <div class="fs_14 fw-bold">تفاصيل العميل</div>
                            </div>
                            <div class="fs_14 mb-1" >
                                <i class="fi fi-rr-phone-call"></i> إسم العميل :<a href="#">{{ $order->client->fullname  }}</a>
                            </div>
                            <div class="fs_14 mb-1" >
                                <i class="fi fi-rr-phone-call"></i> رقم :<a href="tel:{{ $order->client->phone  }}">{{  $order->client->phone  }}</a>
                            </div>
                            <div class="fs_14 mb-1">
                                <i class="fi fi-rr-envelope"></i> البريد :<a href="mailto:{{ $order->client->email }}">{{ $order->client->email ?? 'غير متوفر'}}</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6">
                        <div class="bg-white rounded_16 p-3 h-100">
                            <div class="bg-light rounded_12 p-3 d-flex justify-content-between mb-2">
                                <div class="pe-1">
                                    <div class="fs_14 mb-2">طريقة الشحن</div>
                                    <div class="fs_14 mb-2">شركة الشحن: {{ $order->driver->name ?? 'غير متوفر' }}</div>
                                    @php
                                        use Carbon\Carbon;

                                        $start = Carbon::parse($order->start_date_delivery)->format('gA'); // e.g., 5AM
                                        $end = Carbon::parse($order->end_date_delivery)->format('gA'); // e.g., 4PM
                                        $delivery_date = Carbon::parse($order->start_date_delivery)->translatedFormat('Y-m-d'); 
                                            $start = str_replace(['AM', 'PM'], ['ص', 'م'], $start);
                                            $end = str_replace(['AM', 'PM'], ['ص', 'م'], $end);

                                        
                                    @endphp
                                    
                                    <div class="fs_14 mb-2">فترة التوصيل: {{ $start }} - {{ $end }}</div>
                                    <div class="fs_14 mb-2">تاريخ التوصيل: {{ $delivery_date }}</div>
                                     @if($order->getRawOriginal('status') == 2 &&  $order->driver->id == 1 &&  $order->is_shipped != 1)
                                    <a href="#" class="btn btn-success btn-sm fs_10" data-bs-toggle="modal" data-bs-target="#shippment_update_modal">
                                        <i class="fi fi-rr-truck"></i> تحديد موقع العميل
                                    </a>
                                    @endif
    
                                </div>
                                <div>
                                    <span class="fs_26">
                                        <i class="fi fi-rr-truck-side"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="row gx-3 fs_14">
                                <div class="col-4">المدينة: {{ $order->client->city }}</div>
                                <div class="col-4">العنوان: {{ $order->client->address ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <div class="bg-white rounded_16 p-3 h-100">
                            <div class="bg-light rounded_12 p-3 d-flex justify-content-between mb-2">
                                <div class="pe-1">
                                    <div class="fs_14 mb-2">طريقة الدفع</div>
                                    <div class="fs_14 mb-2">{{ $order->paymentMethod->name ?? 'Myfatoorah' }}</div>
                                </div>
                                <div>
                                    <span class="fs_26">
                                        <i class="fi fi-rr-hand-holding-usd"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Products Table -->
                <div class="bg-white rounded_16 p-3 mb-3">
                    <div class="py_12 px_16">
                        <div class="row align-items-center gy_16">
                            <div class="col-md-6">
                                <h2 class="fs_18 fw-normal mb-0">المنتجات</h2>
                                <div class="fs_14 text_gray6">الإجمالي: 1 </div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto mb-2">
                        <table class="table table-hover def_table lastTr_borderless mb-0">
                            <thead>
                                <tr>
                                    <th>المنتج</th>
                                    <th>الكمية</th>
                                    <th>السعر</th>
                                    <th>رمز المنتج</th>
                                    <th>المجموع</th>
                                    <th>بيانات إضافية</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <a class="d-flex align-items-center" href="">
                                            <div class="pe-2">
                                                <img class="tbl_prdImg" src="{{ $order->product->image[0] ?? '#' }}" alt="product" width="64">
                                            </div>
                                            <div class="fs_12">{{ $order->product->name }}</div>
                                        </a>
                                    </td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ $order->unit_price }} SAR</td>
                                    <td>{{ $order->product->sku ?? '-' }}</td>
                                    <td>{{ $order->total_price }} SAR</td>
                                    <td>
                                        <!-- Card Description -->
                                            
                                        <!-- Design Attributes -->
                                        @if($order->getDesignAttributes())
                                            <div class="mt-2">
                                                <strong>الخصائص:</strong>
                                                <ul class="ps-3">
                                                    @foreach($order->getDesignAttributes() as $attribute)
                                                    <li>
                                                        @php
                                                            $typeLabels = [
                                                                'color_tape' => 'لون الشريط',
                                                                'tape' => 'نوع الشريط',
                                                                'cover' => 'نوع الغلاف',
                                                                'color_cover' => 'لون الغلاف',
                                                            ];
                                                        @endphp
                                                        {{ $typeLabels[$attribute->type] ?? $attribute->title }}
                                                    
                                                        @if($attribute->type === 'color_tape' || $attribute->type === 'color_cover')
                                                            <div class="d-inline-block mt-1 ms-2" style="width: 20px; height: 20px; background-color: {{ $attribute->title }}; border: 1px solid #000;"></div>
                                                            <span class="ms-2">{{ $attribute->title }}</span>
                                                        @endif
                                                    
                                                        @if($attribute->img && !($attribute->type === 'color_tape' || $attribute->type === 'color_cover'))
                                                            <img src="{{ env('FILES_CDN') }}/{{ $attribute->img }}" alt="{{ $attribute->title }}" width="32" class="ms-2">
                                                        @endif
                                                    </li>
                                                @endforeach
                                                </ul>
                                            </div>
                                        @endif
                
                                        <!-- Design Options -->
                                        @if($order->getDesignOptions())
                                            <div class="mt-2">
                                                <strong>الخيارات:</strong>
                                                <ul class="ps-3">
                                                    @foreach($order->getDesignOptions() as $option)
                                                        <li>
                                                            {{ $option->title }} - {{ $option->price }} SAR
                                                            @if($option->img)
                                                                <img src="{{env('FILES_CDN')}}/{{ $option->img }}" alt="{{ $option->title }}" width="32" class="ms-2">
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="bg-white rounded_16 p-3 mt-3">
                    <div class="py_12 px_16">
                        <div class="row align-items-center gy_16">
                            <div class="col-md-6">
                                <h2 class="fs_18 fw-normal mb-0">ملاحظات العميل </h2>
                            </div>
                        </div>
                    </div>

                    <div>
                        <table class="table table-hover def_table lastTr_borderless fs_14 mb-0">
                            <tbody>
                                <tr style="text-align:right;">
                                    <td class="">{{ $order->payment_note?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($order->getCardDescriptionAttribute())
                <div class="bg-white rounded_16 p-3 mt-3">
                    <div class="py_12 px_16">
                        <div class="row align-items-center gy_16">
                            <div class="col-md-6">
                                <h2 class="fs_18 fw-normal mb-0">تفاصيل بطاقة الإهداء</h2>
                            </div>
                        </div>
                    </div>

                    <div>
                        <table class="table table-hover def_table lastTr_borderless fs_14 mb-0">
                            <tbody>
                                <tr>
                                    <td><strong>من:</strong></td>
                                    <td class="text-end">{{ $order->card_description['from'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>إلى:</strong></td>
                                    <td class="text-end">{{ $order->card_description['to'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>الرابط:</strong></td>
                                    <td class="text-end">
                                        <a href="{{ $order->card_description['link'] ?? '#' }}" target="_blank">فتح الرابط</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>الرسالة:</strong></td>
                                    <td class="text-end">{{ $order->card_description['message'] ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

                <div class="bg-white rounded_16 mt-3 p-3">
                    <div class="overflow-x-auto">
                        <table class="table table-hover def_table lastTr_borderless fs_14 mb-0">
                            <tbody>
                                <tr>
                                    <td>إجمالي سعر المنتجات</td>
                                    <td class="text-end">{{ $order->total_price }} SAR</td>
                                </tr>
                                <tr>
                                    <td>معلوم التوصيل</td>
                                    <td class="text-end">{{ $order->delivery_cost ?? '0' }} SAR</td>
                                </tr>
@if($coupon)
    @php
        $total = $order->total_price;
        $discount = 0;
        $couponValue = '';

        if ($coupon->discount_type == 'percent') {
            $discount = ($coupon->discount / 100) * $total;
            $discount = min($discount, $coupon->max_discount ?? $discount);
            $couponValue = "{$coupon->discount}%";
        } elseif ($coupon->discount_type == 'fixed') {
            $discount = $coupon->discount;
            $couponValue = number_format($coupon->discount, 2) . ' SAR';
        }

        $discount = min($discount, $total); // Ensure discount does not exceed total
    @endphp
    <tr>
        <td>كوبون الخصم ({{ $couponValue }})</td>
        <td class="text-end">- {{ number_format($discount, 2) }} SAR</td>
    </tr>
@endif
                                <tr>
                                    <td>إجمالي الطلب</td>
                                    <td class="text-end">{{ $order->total_net_a_pay }} SAR</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                                           
                <!-- Order Summary -->
            </div>
        </section>
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
                                            <option value="6">بإنتضار العنوان</option>
                                            <option value="2">تحت التجهيز</option>
                                            <option value="3">تم الإستلام</option>
                                            <option value="5">مسترجعة</option>
                                            <option value="4">ملغاة</option>
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

    <div class="modal fade modal_sm" id="shippment_update_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="w-100" action="{{route('admin.order.chooseShippment',$order->id)}}"  method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id}}">
                <div class="modal-content border-0 rounded_6">
                    <div class="modal-header bg_light2 py_12 px_16">
                        <div class="pe-1">
                            تحديد موقع التوصيل 
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
                                    <div class="col-12 col-sm-6">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">خط العرض</label>
                                            <input type="text" id="latitude" name="latitude" class="form-control inp_sm " value="{{$order->latitude ?? ''}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">خط الطول</label>
                                            <input type="text" id="longitude" name="longitude" class="form-control inp_sm" value="{{$order->longitude ?? ''}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">حدد العنوان</label>
                                        <input id="search-box" type="text"  class="form-control  mb-4" placeholder="Search for a place" style="">
                                        <div id="map"></div>
                                        </div>

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

    <div class="modal fade modal_sm" id="barq_confirmation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="w-100" action="{{route('admin.order.confirmBarq',$order->id)}}"  method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id}}">
                <div class="modal-content border-0 rounded_6">
                    <div class="modal-header bg_light2 py_12 px_16">
                        <div class="pe-1">
                            تاكيد التوصيل في نظام برق 
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
                                            <h2 class="form-label fs_14 c_dark2"> سيتم تأكيد إضافة طلب الشحن وتسجيل بيانات الطلب في نظام برق</h2>
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
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrDYCXAVQZeXxbZx84iRVe5SMmBpm5sy8&libraries=places&v=weekly&debug=true" async defer></script>

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
    $(document).ready(function() {
    // When the select value changes
    $('#driver_id').on('change', function() {
        var selectedValue = $(this).val();
        
        // If the selected value is 1 or 2, hide the "سعر التوصيل" input
        if (selectedValue == '1' || selectedValue == '2') {
            $('#delivery_price_container').hide();
        } else {
            // Otherwise, show it
            $('#delivery_price_container').show();
        }
    });

    // Initial check if the page loads with a pre-selected value
    var initialValue = $('#driver_id').val();
    if (initialValue == '1' || initialValue == '2') {
        $('#delivery_price_container').hide();
    } else {
        $('#delivery_price_container').show();
    }
});

    function initMap() {
    const defaultLocation = { lat: {{$order->latitude ?? '21.4858'}}, lng: {{$order->longitude ?? '39.1925'}} };
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

    // Bias the SearchBox results towards the map's viewport.
    map.addListener("bounds_changed", () => {
        searchBox.setBounds(map.getBounds());
    });

    // Listen for the event fired when the user selects a prediction from the SearchBox.
    searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();

        if (places.length === 0) {
            return;
        }

        // Clear out the old markers if needed
        const place = places[0];

        if (!place.geometry || !place.geometry.location) {
            console.error("Returned place contains no geometry");
            return;
        }

        const location = place.geometry.location;
        map.setCenter(location);
        map.setZoom(15);

        marker.setPosition(location);
        updateCoordinates(location.lat(), location.lng());
    });

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

    updateCoordinates(defaultLocation.lat, defaultLocation.lng);
}

document.addEventListener("DOMContentLoaded", () => {
    if (typeof google !== "undefined") {
        initMap();
    }
});

    

        </script>
        
</body>

</html>