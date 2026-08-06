<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>ShaiebExpo - Modifier une catégorie</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/admin/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="/admin/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    @include('admin.layouts.sidebar-fr')
    @include('admin.layouts.header-fr')

    <!-- Main Content -->
    <main class="main">
        <section class="mb_16">
            <div class="container-fluid px-0">
                <form action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="main__header">
                        <div class="row">
                            <div class="col-6">
                                <h2 class="fs_24 text-black fw-bold fw-normal mb-0">Modifier la catégorie</h2>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <a href="{{ route('admin.category.index') }}" class="btn btn_sm btn_primary_outline px-sm-4" type="reset">Annuler</a>
                                    </div>
                                    <div>
                                        <button class="btn btn_sm btn_primary" type="submit">Enregistrer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="row g_24">
                        <div class="col-lg-12">
                            <div class="bg-white rounded_16 p_24">
                                <h3 class="fs_20 mb_16">Détails de la catégorie</h3>
                                <div class="row g_16">
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">Nom de la catégorie *</label>
                                            <input type="text" name="name" class="form-control inp_sm" value="{{ old('name', $category->name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">Description</label>
                                            <textarea name="description" class="form-control inp_sm" rows="3" placeholder="Description de la catégorie...">{{ old('description', $category->description) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <input type="file" id="image" name="image" style="display: none;" accept="image/*" />
                                            <label class="form-label fs_14 c_dark2 mb_4">Image</label>
                                            <div id="merchant_dropzone" class="dropzone_area">
                                                <i class="icon_upload"></i>
                                                <p class="mb-0 fs_12">Cliquez ou glissez-déposez une nouvelle image</p>
                                            </div>
                                            <div id="product_preview_container" class="preview_area">
                                                @if ($category->image)
                                                <div class="file_preview" id="existing_image">
                                                    @php
                                                        $imageUrl = $category->image;
                                                        // Remove CDN prefix if exists
                                                        if (strpos($imageUrl, env('FILES_CDN')) === 0) {
                                                            $imageUrl = str_replace(env('FILES_CDN'), '', $imageUrl);
                                                        }
                                                    @endphp
                                                    <img src="{{ $imageUrl }}" data-thumbnail style="max-height: 150px; object-fit: cover;" />
                                                    <div class="file_details">
                                                        <div class="file_name pe-1">Image actuelle</div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">Ordre d'affichage *</label>
                                            <input type="number" name="order_item" class="form-control inp_sm" value="{{ old('order_item', $category->order_item) }}" required min="1">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">Sous-catégories (optionnel)</label>
                                            <select name="subcategories[]" id="subcategories_select" class="form-control inp_sm" multiple>
                                                @php
                                                    $allCategories = \App\Models\Category::where('visibility', 1)
                                                        ->where('deleted', 0)
                                                        ->where('id', '!=', $category->id) // Exclude current category from list
                                                        ->orderBy('name')
                                                        ->get();
                                                    $currentSubcategoryIds = $category->subcategories->pluck('id')->toArray();
                                                @endphp
                                                @foreach($allCategories as $cat)
                                                    <option value="{{ $cat->id }}" {{ in_array($cat->id, $currentSubcategoryIds) ? 'selected' : '' }}>
                                                        {{ $cat->name }}
                                                    </option>
                                                @endforeach
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

    <script>
        // Reference elements
        const fileInput = $("#image"); // Hidden file input
        const previewContainer = $("#product_preview_container"); // Preview container

        // Click event to open file input
        $("#merchant_dropzone").on("click", function() {
            fileInput.click();
        });

        // Change event to handle file input selection
        fileInput.on("change", function(event) {
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
            <img src="${fileUrl}" data-thumbnail style="max-height: 150px; object-fit: cover;" />
            <div class="file_details">
                <div class="file_name pe-1">${file.name}</div>
                <div class="file_size">${(file.size / 1024).toFixed(2)} KB</div>
            </div>
            <div class="btn-group btn-group-sm">
                <a class="btn text-primary" href="${fileUrl}" download>
                    <i class="fi fi-rr-down-to-line"></i>
                </a>
                <button class="btn text-danger" id="remove_file">
                    <i class="fi fi-rr-trash"></i>
                </button>
            </div>
        </div>
    `;
            previewContainer.append(previewHTML);

            // Remove file event
            $("#remove_file").on("click", function() {
                fileInput.val(""); // Clear the file input
                previewContainer.empty(); // Remove the preview
                // Show existing image again if it exists
                @if($category->image)
                    $('#existing_image').show();
                @endif
            });
        }

        // Initialize Select2 for better multi-select experience
        $(document).ready(function() {
            $('#subcategories_select').select2({
                placeholder: "Choisir les sous-catégories",
                allowClear: true,
                dir: "ltr"
            });
        });
    </script>

</body>

</html>
