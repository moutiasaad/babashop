@php
$Model_PLURAL = 'منتجات';
$Model_SINGULAR = 'منتج';
$Model_API_ROUTE = 'product';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>

<body>
    @include('admin.layouts.sidebar')
    @include('admin.layouts.header')

    <!-- Main Content -->
    <main class="main">
        <section class="mb_16">
            <div class="container-fluid px-0">
                <form action="{{ route('admin.products.update', $product->id) }}?{{ http_build_query(request()->query()) ?: 't=1' }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="main__header">
                        <div class="row">
                            <div class="col-6">
                                <h2 class="fs_24 text-black fw-bold fw-normal mb-0">تعديل المنتج</h2>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <a class="btn btn_sm btn_primary_outline px-sm-4" href="/dashboard/product/{{ $product->product_type }}">الغاء</a>
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
                                <h3 class="fs_20 mb_16">تفاصيل المنتج</h3>
                                <div class="row g_16">
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">اسم المنتج</label>
                                            <input type="text" name="name" class="form-control inp_sm" value="{{ $product->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">النوع</label>
                                            <input type="text" name="type" class="form-control inp_sm" value="{{ $product->type }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">رقم المنتج</label>
                                            <input type="text" name="sku" class="form-control inp_sm" value="{{ $product->sku }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">الوصف</label>
                                            <textarea class="form-control" name="description" rows="4" required>{{ $product->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <input type="file" id="image" name="image" style="display: none;">
                                        <input type="hidden" id="ready_prdItem" name="ready_prdItem" value="{{ $product->primary_image }}" style="display: none;">
    
                                        <label class="form-label fs_14 c_dark2 mb_4">الصورة</label>
                                        <div id="merchant_dropzone" class="dropzone_area">
                                            <i class="icon_upload"></i>
                                            <p class="mb-0 fs_12">حمل أو الصق أعمال مشابهة</p>
                                        </div>
                                        <div id="product_preview_container" class="preview_area">
                                            @if(count($product->attachments) == 0)
                                            <div class="file_preview" id="preview_478457">
                                                <img src="{{ env('FILES_CDN') }}/{{ $product->getRawOriginal('image') }}" data-thumbnail />
                                                <div class="file_details">
                                                    <div class="file_name pe-1"></div>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <label>
                                                        <input type="hidden" name="attachments_1[]" >
                                                    </label>
                                                    <a class="btn text-primary" href="{{ env('FILES_CDN') }}/{{ $product->getRawOriginal('image') }}" download="">
                                                                    <i class="fi fi-rr-down-to-line"></i>
                                                    </a>
                                                        <button class="btn text-danger remove_file" data-preview-id="preview_478457" type="button">
                                                            <i class="fi fi-rr-trash"></i>
                                                        </button>
                                                </div>
                                            </div>
    
                                            @endif
                                            @foreach($product->attachments as $attachment)
                                            <div class="file_preview" id="preview_{{ $attachment->id }}">
                                                <img src="{{ env('FILES_CDN') }}/uploads/{{ $attachment->folder }}/{{ $attachment->name }}" data-thumbnail />
                                                <div class="file_details">
                                                    <div class="file_name pe-1">{{ $attachment->name }}</div>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <label>
                                                        <input type="hidden" name="attachments[]" value="{{ $attachment->id }}">
                                                    </label>
                                                    <a class="btn text-primary" href="{{ env('FILES_CDN') }}/uploads/{{ $attachment->folder }}/{{ $attachment->name }}" download="">
                                                                    <i class="fi fi-rr-down-to-line"></i>
                                                    </a>
                                                        <button class="btn text-danger remove_file" data-preview-id="preview_{{ $attachment->id }}" type="button">
                                                            <i class="fi fi-rr-trash"></i>
                                                        </button>
                                                </div>
                                            </div>
                                        @endforeach
                                        </div>
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">التصنيف الرئيسي</label>
                                            <select class="form-select select_sm" name="category_id" required>
                                                <option selected disabled>اختر التصنيف الرئيسي</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">تصنيفات إضافية</label>
                                            <select class="form-select select_sm" id="other_categories" name="other_categories[]" multiple>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" 
                                                        {{ in_array($category->id, $product->other_categories ?? []) ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                     @if (auth()->guard('admin')->check() && auth()->guard('admin')->user()->role_id == 1)
                                        <div class="col-12">
                                            <div>
                                                <label class="form-label fs_14 c_dark2">المتجر</label>
                                                <select class="form-select select_sm" name="merchant_id" required>
                                                    <option selected disabled>اختر المتجر</option>
                                                    @foreach ($merchants as $merchant)
                                                        <option value="{{ $merchant->id }}" {{ $product->merchant_id == $merchant->id ? 'selected' : '' }}>
                                                            {{ $merchant->brand_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="bg-white rounded_16 p_24">
                                <h3 class="fs_20 mb_16">تفاصيل السعر</h3>
                                <div class="row g_16">
                            <div class="col-12">
                                <div>
                                    <label class="form-label fs_14 c_dark2">السعر</label>
                                    <input type="number" name="price" class="form-control inp_sm" step="0.01" value="{{ $product->price }}" id="productPrice" required>
                                </div>
                            </div>
                            <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">الكمية</label>
                                            <input type="text" name="qty"  class="form-control inp_sm" value="{{ $product->qty }}" required>
                                        </div>
                                    </div>

                        <div class="col-12">
                            <div>
                                <label class="form-label fs_14 c_dark2">الخصم</label>
                                <select class="form-control inp_sm" id="discountSelect">
                                    <option value="" {{ $product->discount_price == 0 || is_null($product->discount_price) ? 'selected' : '' }}>بدون خصم</option>
                                    <option value="10" {{ $product->discount_price == $product->price * 0.9 ? 'selected' : '' }}>10%</option>
                                    <option value="20" {{ $product->discount_price == $product->price * 0.8 ? 'selected' : '' }}>20%</option>
                                    <option value="30" {{ $product->discount_price == $product->price * 0.7 ? 'selected' : '' }}>30%</option>
                                    <option value="40" {{ $product->discount_price == $product->price * 0.6 ? 'selected' : '' }}>40%</option>
                                    <option value="50" {{ $product->discount_price == $product->price * 0.5 ? 'selected' : '' }}>50%</option>
                                </select>
                                <input type="hidden" name="discount_price" id="discountPrice" value="{{ $product->discount_price }}">
                            </div>
                        </div>
                            
                            <script>
                                document.getElementById('discountSelect').addEventListener('change', function () {
                                    const discountPercentage = this.value;
                                    const originalPrice = parseFloat(document.getElementById('productPrice').value) || 0;
                                    const discountedPrice = discountPercentage ? (originalPrice - (originalPrice * (discountPercentage / 100))).toFixed(2) : originalPrice;
                                    document.getElementById('discountPrice').value = discountedPrice;
                                });
                            </script>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">بداية الخصم</label>
                                            <input type="datetime-local" name="discount_start" class="form-control inp_sm" value="{{ $product->discount_start }}">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">نهاية الخصم</label>
                                            <input type="datetime-local" name="discount_end" class="form-control inp_sm" value="{{ $product->discount_end }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($product->product_type == '1')
                            <div class="bg-white rounded_16 p_24 mt-4">
                                    <div class="border-bottom py_12 px_16">
                                        <div class="row align-items-center gy_16">
                                            <div class="col-6">
                                                <h2 class="fs_18 fw-normal mb-0">المنتجات </h2>
                                            </div>
                                            <div class="col-6 text-end">
                                                <a class="btn btn_primary" href="{{route('admin.design_options.add',$product->id) }}" type="normal" id="addCodeBtn">إضافة منتج</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="table table-hover def_table lastTr_borderless mb-0">
                                            <thead class="c_gray4">
                                                <tr>
                                                    <th></th>
                                                    <th>المنتج</th>
                                                    <th>نوع المنتج</th>
                                                    <th>تاريخ الإضافة</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($options as $option)
                                                <tr>
                                                    <td><img src="/{{ $option->img }}" width="52"/> </td>
                                                    <td>{{ $option->title }} </td>
                                                    <td>{{ $option->type === 'flower' ? 'نوع الزهور' : ($option->type === 'card' ? 'بطاقة إهداء' : $option->type) }}</td>

                                                    <td>{{ $option->created_at }}</td>
                                                    <td class="pdt_actBtns text-start">
                                                      <a class="btn pdt_actBtn" onclick="showDeleteModal({{ $option->id }})"><i class="fi fi-rr-trash"></i></a>
                                                    </td>

                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </form>
            </div>
        </section>
            <div class="modal fade modal_sm" id="alert__modal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded_16">
                <div class="modal-body p-3">
                    <div class="alert__icon">
                        <i class="fi fi-rr-check-circle"></i>
                        <i class="fi fi-rr-triangle-warning"></i>
                        <i class="fi fi-rr-info"></i>
                    </div>
                    <h6 class="alert__title"></h6>
                    <p class="alert__desc"></p>

                    <div>
                        <div class="d-flex justify-content-center">
                            <div>
                                <button class="btn btn_sm btn_primary alert__submitBtn" type="submit"
                                    aria-label="Close">نعم، تأكيد</button>
                            </div>
                            <div class="ms-2">
                                <button class="btn btn_sm btn-light alert__resetBtn" type="reset"
                                    data-bs-dismiss="modal" aria-label="Close">لا، تراجع</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

    @include("admin.layouts.footer")

    <script>
            function showDeleteModal(productId) {
            productIdToDelete = productId;
            $('#alert__modal').modal('show');
            $('.alert__title').text('هل أنت متأكد أنك تريد الحذف؟');
            $('.alert__desc').text('لن تتمكن من التراجع عن هذا الإجراء.');
        }
                $('.alert__submitBtn').on('click', function() {
            delete_product(productIdToDelete);
        });

        function delete_product(productId) {
            // Redirect to the delete route
            window.location.href = '/dashboard/design-options/' + productId + '/delete';
        }
        $(document).ready(function() {
            $('#other_categories').select2({
                placeholder: "اختر التصنيفات الإضافية",
                allowClear: true,
                width: '100%'
            });
        });

// Reference elements
const fileInput = $("#image"); // Hidden file input
const previewContainer = $("#product_preview_container"); // Preview container

// Click event to open file input
$("#merchant_dropzone").on("click", function () {
    fileInput.click();
});
    $(".remove_file").on("click", function () {
        const previewId = $(this).data("preview-id");
        $(`#${previewId}`).remove();
        inputElement.val(""); // Clear the file input
    });

// Change event to handle file input selection
fileInput.on("change", function (event) {
    const file = event.target.files[0]; // Get the selected file

    if (file) {
        uploadImageToServer(file); // Clear any previous preview
    }
});
function uploadImageToServer(file) {
    let formData = new FormData();
    formData.append('file', file);

    $.ajax({
        url: '/dashboard/attachment/products',
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
                    previewContainer.find('.progress-bar').css('width', percentComplete + '%');
                    if (percentComplete === 100) {
                        setTimeout(() => previewContainer.find('.progress-bar').css('background-color', 'green'), 500);
                    }
                }
            }, false);
            return xhr;
        },
        success: function (response) {
            console.log('Image uploaded successfully' + response);
            previewFile(file,response)
        },
        error: function (error) {
            console.log('Error uploading image:', error);
        }
    });
}

// Function to generate file preview
function previewFile(file,response) {
    const uniqueId = Date.now(); // Unique ID for each preview
    const fileUrl = URL.createObjectURL(file);
            const previewHTML = `
       <div class="file_preview" id="preview_${uniqueId}">
                    <img src="{{ env('FILES_CDN') }}${response.url}" data-thumbnail />
                    <div class="file_details">
                        <div class="file_name pe-1">${file.name}</div>
                        <div class="file_size">${(file.size / 1024).toFixed(2)} KB</div>
                       <input class="form-check-input me-1" type="hidden" value="${response.id}"  name="attachments[]">
                       <input class="form-check-input me-1" type="hidden" value="${response.id}"  name="ready_prdItem">

                    </div>
                    <div class="btn-group btn-group-sm">
                        <button class="btn text-danger remove_file" data-preview-id="preview_${uniqueId}">
                            <i class="fi fi-rr-trash"></i>
                        </button>
                    </div>
                </div>
            `;
    previewContainer.append(previewHTML);

    // Remove file event
    $(".remove_file").on("click", function () {
        const previewId = $(this).data("preview-id");
        $(`#${previewId}`).remove();
        inputElement.val(""); // Clear the file input
    });
}

    </script>


</body>

</html>
