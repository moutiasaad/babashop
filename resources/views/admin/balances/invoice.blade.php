@php
use Carbon\Carbon;
Carbon::setLocale('ar');
@endphp

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>فاتورة</title>
  <link rel="stylesheet" href="/invoice/style.css?v=5" type="text/css" media="all" />
  <style>
    .currency-icon {
      width: 14px;
      height: 14px;
      display: inline-block;
      position: relative;
      top: -1px;
      margin-left: 4px;
    }

    .total-price {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 6px;
    }

    .total-price img {
      width: 16px;
      height: 16px;
    }
  </style>
</head>

<body>
  <div>
    <div class="py-4">
      <div class="px-14 py-6">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-full align-top">
                <div>
                  <img src="https://lovardportal.online/admin/img/logo.png" class="h-12" alt="لوفارد" />
                </div>
              </td>
              <td class="align-top">
                <div class="text-sm">
                  <table class="border-collapse border-spacing-0">
                    <tbody>
                      <tr>
                        <td class="border-l pl-4">
                          <div>
                            <p class="whitespace-nowrap text-slate-400 text-right">التاريخ</p>
                            <p class="whitespace-nowrap font-bold text-main text-right">
                                {{ Carbon::parse($order->created_at)->translatedFormat('d F Y') }}
                            </p>
                          </div>
                        </td>
                        <td class="pr-4">
                          <div>
                            <p class="whitespace-nowrap text-slate-400 text-right">رقم الفاتورة</p>
                            <p class="whitespace-nowrap font-bold text-main text-right">LOV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="bg-slate-100 px-14 py-6 text-sm">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-1/2 align-top">
                <div class="text-sm text-neutral-600">
                  <p class="font-bold">{{config('company.name')}}</p>
                  <p>الرقم الموحد للمنشاه: {{config('company.tva')}}</p>
                  <p>رقم الجوال: {{config('company.phone_number')}}</p>
                  <p>وسائل التواصل الإجتماعي: {{config('company.social_media')}}</p>
                  <p>الإيميل: {{config('company.email')}}</p>
                  <p>{{config('company.address')}} , المملكة العربية السعودية</p>
                </div>
              </td>
              <td class="w-1/2 align-top text-left" style="text-align: left;">
                <div class="text-sm text-neutral-600">
                  <p class="font-bold">العميل</p>
                  <p>الاسم: {{ $order->merchant->brand_name ?? '-' }}</p>
                  <p>رقم العميل: {{ $order->merchant->whatsapp_number ?? '-' }}</p>
                  <p>العنوان: {{ $order->merchant->street_name ?? '-' }}</p>
                  <p>المملكة العربية السعودية</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="px-14 py-10 text-sm text-neutral-700">
        <table class="w-full border-collapse border-spacing-0">
          <thead>
            <tr>
              <td class="border-b-2 border-main pb-3 pr-3 font-bold text-main">#</td>
              <td class="border-b-2 border-main pb-3 pr-2 font-bold text-main">تفاصيل المنتج</td>
              <td class="border-b-2 border-main pb-3 pr-2 text-left font-bold text-main">السعر</td>
              <td class="border-b-2 border-main pb-3 pr-2 text-center font-bold text-main">الكمية</td>
              <td class="border-b-2 border-main pb-3 pr-2 text-left font-bold text-main">الإجمالي</td>
            </tr>
          </thead>
          <tbody>
            @php $total = 0; @endphp
              <tr>
                <td class="border-b py-3 pr-3">{{ 1 }}</td>
                <td class="border-b py-3 pr-2">{{config('company.product_line')}}</td>
                <td class="border-b py-3 pr-2 text-left">
                  <span>
                    {{ number_format($order->amount, 2) }}
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/98/Saudi_Riyal_Symbol.svg/768px-Saudi_Riyal_Symbol.svg.png" class="currency-icon" alt="﷼">
                  </span>
                </td>
                <td class="border-b py-3 pr-2 text-center">1</td>
                <td class="border-b py-3 pr-2 text-left">
                  <span>
                    {{ number_format($order->amount, 2) }}
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/98/Saudi_Riyal_Symbol.svg/768px-Saudi_Riyal_Symbol.svg.png" class="currency-icon" alt="﷼">
                  </span>
                </td>
              </tr>
              @php $total = number_format($order->amount, 2); @endphp

            <tr>
              <td colspan="5">
                <table class="w-full border-collapse border-spacing-0">
                  <tbody>
                    <tr>
                      <td class="w-full"></td>
                      <td>
                        <table class="w-full border-collapse border-spacing-0">
                          <tbody>
                            <tr>
                              <td class="border-b p-3 text-right">
                                <div class="whitespace-nowrap text-slate-400">الإجمالي:</div>
                              </td>
                              <td class="border-b p-3 text-left">
                                <div class="total-price font-bold text-main">
                                  <span>{{ number_format($total, 2) }}</span>
                                  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/98/Saudi_Riyal_Symbol.svg/768px-Saudi_Riyal_Symbol.svg.png" alt="﷼">
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td class="bg-main p-3 text-right">
                                <div class="whitespace-nowrap font-bold text-white">المبلغ المطلوب:</div>
                              </td>
                              <td class="bg-main p-3 text-left">
                                <div class="total-price font-bold text-white">
                                  <span>{{ number_format($total, 2) }}</span>
                                  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/98/Saudi_Riyal_Symbol.svg/768px-Saudi_Riyal_Symbol.svg.png" alt="﷼">
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="px-14 text-sm text-neutral-700">
        <p class="text-main font-bold">بيانات الدفع</p>
        <p>وسيلة الدفع : تحويل بنكي</p>
        <p>مرجع الدفع: LOV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
      </div>

      <div class="px-14 py-10 text-sm text-neutral-700">
        <p class="text-main font-bold">ملاحظات</p>
        <p class="italic">نشكر لكم اختياركم "لوفارد". نأمل أن تنال باقاتنا رضاكم. #لحظة_فريدة</p>
      </div>

    </div>
  </div>
</body>
<script>
  window.addEventListener('load', function () {
    window.print();
  });
</script>

</html>
