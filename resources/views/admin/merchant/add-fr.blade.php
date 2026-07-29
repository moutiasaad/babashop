<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Shaieb Store - Ajouter une boutique</title>

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
                <form action="{{ route('admin.merchant.store') }}" method="POST" enctype='multipart/form-data'>
                    @csrf
                    <div class="main__header">
                        <div class="row">
                            <div class="col-6">
                                <h2 class="fs_24 text-black fw-bold fw-normal mb-0">Ajouter une nouvelle boutique</h2>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <a href="{{ route('admin.merchant.index') }}" class="btn btn_sm btn_primary_outline px-sm-4">Annuler</a>
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
                        <!-- Basic Information -->
                        <div class="col-lg-8">
                            <div class="bg-white rounded_16 p_24 mb_24">
                                <h3 class="fs_20 mb_16">Informations de base</h3>
                                <div class="row g_16">
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Nom de la marque *</label>
                                        <input type="text" name="brand_name" class="form-control inp_sm" placeholder="Ex: Café Délice" value="{{ old('brand_name') }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Description *</label>
                                        <textarea name="description" class="form-control inp_sm" rows="4" placeholder="Description de la boutique..." required>{{ old('description') }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs_14 c_dark2">Type de boutique *</label>
                                        <select name="type_merchant_id" class="form-control inp_sm" required>
                                            <option value="">Sélectionner...</option>
                                            <option value="2">Boutique</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs_14 c_dark2">Ordre d'affichage</label>
                                        <input type="number" name="order_item" class="form-control inp_sm" value="{{ old('order_item') }}" min="1">
                                    </div>
                                </div>
                            </div>

                            <!-- Contact & Location -->
                            <div class="bg-white rounded_16 p_24 mb_24">
                                <h3 class="fs_20 mb_16">Contact et localisation</h3>
                                <div class="row g_16">
                                    <div class="col-md-6">
                                        <label class="form-label fs_14 c_dark2">Email de notification</label>
                                        <input type="email" name="notif_email" class="form-control inp_sm" value="{{ old('notif_email') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs_14 c_dark2">Numéro WhatsApp</label>
                                        <input type="text" name="whatsapp_number" class="form-control inp_sm" value="{{ old('whatsapp_number') }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Nom de la rue *</label>
                                        <input type="text" name="street_name" class="form-control inp_sm" value="{{ old('street_name') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Merchant User Account -->
                            <div class="bg-white rounded_16 p_24 mb_24">
                                <h3 class="fs_20 mb_16">Compte utilisateur de la boutique</h3>
                                <div class="row g_16">
                                    <div class="col-md-6">
                                        <label class="form-label fs_14 c_dark2">Nom du responsable *</label>
                                        <input type="text" name="user_name" class="form-control inp_sm" placeholder="Nom complet" value="{{ old('user_name') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs_14 c_dark2">Téléphone</label>
                                        <input type="text" name="user_phone" class="form-control inp_sm" placeholder="Ex: +966xxxxxxxxx" value="{{ old('user_phone') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs_14 c_dark2">Email de connexion *</label>
                                        <input type="email" name="user_email" class="form-control inp_sm" placeholder="email@exemple.com" value="{{ old('user_email') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs_14 c_dark2">Mot de passe *</label>
                                        <input type="password" name="user_password" class="form-control inp_sm" placeholder="Minimum 6 caractères" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Hours & Delivery -->
                            <div class="bg-white rounded_16 p_24">
                                <h3 class="fs_20 mb_16">Horaires et livraison</h3>
                                <div class="row g_16">
                                    <div class="col-md-6">
                                        <label class="form-label fs_14 c_dark2">Heure d'ouverture</label>
                                        <input type="time" name="open_at" class="form-control inp_sm" value="{{ old('open_at') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs_14 c_dark2">Heure de fermeture</label>
                                        <input type="time" name="close_at" class="form-control inp_sm" value="{{ old('close_at') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs_14 c_dark2">Livraison gratuite</label>
                                        <select name="delivery_is_free" class="form-control inp_sm">
                                            <option value="0">Non</option>
                                            <option value="1">Oui</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs_14 c_dark2">Frais de plateforme (%)</label>
                                        <input type="text" name="platform_fee" class="form-control inp_sm" value="{{ old('platform_fee') }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Politique de retour</label>
                                        <textarea name="return_policy" class="form-control inp_sm" rows="3">{{ old('return_policy') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-lg-4">
                            <!-- Image -->
                            <div class="bg-white rounded_16 p_24 mb_24">
                                <h3 class="fs_20 mb_16">Image *</h3>
                                <input type="file" id="image" name="image" style="display: none;" accept="image/*" required />
                                <div id="merchant_dropzone" class="dropzone_area">
                                    <i class="icon_upload"></i>
                                    <p class="mb-0 fs_12">Cliquez ou glissez-déposez</p>
                                </div>
                                <div id="product_preview_container" class="preview_area"></div>
                            </div>

                            <!-- Categories -->
                            <div class="bg-white rounded_16 p_24">
                                <h3 class="fs_20 mb_16">Catégories</h3>
                                <select name="categories[]" id="categories_select" class="form-control inp_sm" multiple>
                                    @foreach($merchantCategories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
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
    <script src="/admin/js/select2.min.js"></script>
    <script src="/admin/js/script.js"></script>
    @include('admin.layouts.footer')

    <script>
        const fileInput = $("#image");
        const previewContainer = $("#product_preview_container");

        $("#merchant_dropzone").on("click", function() {
            fileInput.click();
        });

        fileInput.on("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                previewContainer.empty();
                previewFile(file);
            }
        });

        function previewFile(file) {
            const fileUrl = URL.createObjectURL(file);
            const previewHTML = `
                <div class="file_preview">
                    <img src="${fileUrl}" data-thumbnail />
                    <div class="file_details">
                        <div class="file_name pe-1">${file.name}</div>
                        <div class="file_size">${(file.size / 1024).toFixed(2)} KB</div>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button class="btn text-danger" id="remove_file">
                            <i class="fi fi-rr-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            previewContainer.append(previewHTML);

            $("#remove_file").on("click", function() {
                fileInput.val("");
                previewContainer.empty();
            });
        }

        $(document).ready(function() {
            $('#categories_select').select2({
                placeholder: "Choisir les catégories",
                allowClear: true,
                dir: "ltr"
            });
        });
    </script>

</body>

</html>
