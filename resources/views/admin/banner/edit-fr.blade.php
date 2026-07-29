<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Shaieb Store - Modifier une bannière</title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/admin/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="/admin/css/style.css">
</head>

<body>
    @include('admin.layouts.sidebar-fr')
    @include('admin.layouts.header-fr')

    <!-- Main Content -->
    <main class="main">
        <section class="mb_16">
            <div class="container-fluid px-0">
                <form action="{{ route('admin.banner.update', $banner->id) }}" method="POST" enctype='multipart/form-data'>
                    @csrf
                    @method('PUT')
                    <div class="main__header">
                        <div class="row">
                            <div class="col-6">
                                <h2 class="fs_24 text-black fw-bold fw-normal mb-0">Modifier la bannière</h2>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <a href="{{ route('admin.banner.index') }}" class="btn btn_sm btn_primary_outline px-sm-4" type="reset">Annuler</a>
                                    </div>
                                    <div>
                                        <button class="btn btn_sm btn_primary" type="submit">Mettre à jour</button>
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
                                <h3 class="fs_20 mb_16">Détails de la bannière</h3>
                                <div class="row g_16">
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">Nom de la bannière *</label>
                                            <input type="text" name="name" class="form-control inp_sm" placeholder="Ex: Bannière Promo Été" value="{{ old('name', $banner->name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">Lien (optionnel)</label>
                                            <input type="text" name="link" class="form-control inp_sm" placeholder="Ex: /products/promo-summer" value="{{ old('link', $banner->link) }}">
                                            <small class="text-muted">URL vers laquelle la bannière redirigera au clic</small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <input type="file" id="image" name="image" style="display: none;" accept="image/*" />

                                            <label class="form-label fs_14 c_dark2 mb_4">Image</label>

                                            @if($banner->image)
                                            <div id="current_image" class="mb-3">
                                                <p class="text-muted">Image actuelle:</p>
                                                <img src="{{ $banner->image }}" alt="{{ $banner->name }}" style="max-width: 300px; height: auto; border-radius: 8px;">
                                            </div>
                                            @endif

                                            <div id="merchant_dropzone" class="dropzone_area">
                                                <i class="icon_upload"></i>
                                                <p class="mb-0 fs_12">Cliquez ou glissez-déposez une nouvelle image</p>
                                            </div>
                                            <div id="product_preview_container" class="preview_area"></div>
                                            <small class="text-muted">Laissez vide pour conserver l'image actuelle</small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label class="form-label fs_14 c_dark2">Ordre d'affichage</label>
                                            <input type="number" name="order_item" class="form-control inp_sm" value="{{ old('order_item', $banner->order_item) }}" placeholder="Ex: 1" min="1">
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

    <!-- Scripts -->
    <script src="/admin/js/jquery-3.7.0.min.js"></script>
    <script src="/admin/js/bootstrap.bundle.min.js"></script>
    @include('admin.layouts.footer')

    <script>
        $(document).ready(function() {
            // Handle dropzone click
            $('#merchant_dropzone').on('click', function() {
                $('#image').trigger('click');
            });

            // Handle file selection
            $('#image').on('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#product_preview_container').html(`
                            <div class="position-relative d-inline-block">
                                <img src="${e.target.result}" class="preview_image" />
                                <button type="button" class="btn btn-sm btn-danger remove_preview" onclick="removeImage()">
                                    <i class="fi fi-rr-cross-small"></i>
                                </button>
                            </div>
                        `);
                        $('#merchant_dropzone').hide();
                        $('#current_image').hide();
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        function removeImage() {
            $('#image').val('');
            $('#product_preview_container').empty();
            $('#merchant_dropzone').show();
            $('#current_image').show();
        }
    </script>

</body>

</html>
