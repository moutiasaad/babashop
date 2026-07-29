<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Shaieb Store - Modifier un produit</title>

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
                <form action="{{ route('admin.products.update', $product->id) }}?{{ http_build_query(request()->query()) ?: 't=1' }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="main__header">
                        <div class="row">
                            <div class="col-6">
                                <h2 class="fs_24 text-black fw-bold fw-normal mb-0">Modifier le produit</h2>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <a href="{{ route('admin.product.index', $product->product_type) }}" class="btn btn_sm btn_primary_outline px-sm-4">Annuler</a>
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
                        <!-- Product Details -->
                        <div class="col-lg-6">
                            <div class="bg-white rounded_16 p_24 mb_24">
                                <h3 class="fs_20 mb_16">Détails du produit</h3>
                                <div class="row g_16">
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Nom du produit *</label>
                                        <input type="text" name="name" class="form-control inp_sm" value="{{ old('name', $product->name) }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Type *</label>
                                        <input type="text" name="type" class="form-control inp_sm" value="{{ old('type', $product->type) }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">SKU *</label>
                                        <input type="text" name="sku" class="form-control inp_sm" value="{{ old('sku', $product->sku) }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Description *</label>
                                        <textarea name="description" class="form-control inp_sm" rows="4" required>{{ old('description', $product->description) }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2 mb_4">Image principale</label>
                                        @if(is_array($product->image) && count($product->image) > 0)
                                        <div class="mb-3">
                                            <p class="fs_12 c_dark2 mb-2">Image actuelle:</p>
                                            @php $mainImg = $product->image[0] ?? ''; $mainImg = str_starts_with($mainImg,'http') ? $mainImg : asset($mainImg); @endphp
                                            <img src="{{ $mainImg }}" alt="{{ $product->name }}" style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 8px;">
                                        </div>
                                        @endif
                                        <p class="fs_12 c_dark2 mb-2">{{ is_array($product->image) && count($product->image) > 0 ? 'Changer l\'image:' : 'Ajouter une image:' }}</p>
                                        <input type="file" id="image" name="image" style="display: none;" accept="image/*" />
                                        <div id="merchant_dropzone" class="dropzone_area">
                                            <i class="icon_upload"></i>
                                            <p class="mb-0 fs_12">Cliquez ou glissez-déposez</p>
                                        </div>
                                        <div id="product_preview_container" class="preview_area"></div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2 mb_4">Images supplémentaires (galerie)</label>
                                        @if(isset($productImages) && $productImages->count() > 0)
                                        <div class="mb-3">
                                            <p class="fs_12 c_dark2 mb-2">Images actuelles (cochez pour supprimer):</p>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($productImages as $img)
                                                <div class="position-relative" style="width: 100px; height: 100px;">
                                                    @php $gPath = str_starts_with($img->image_path,'http') ? $img->image_path : asset($img->image_path); @endphp
                                                    <img src="{{ $gPath }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                                                    <div class="position-absolute top-0 start-0 p-1">
                                                        <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" class="form-check-input" style="width: 18px; height: 18px;">
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <small class="text-muted">Cochez les images à supprimer</small>
                                        </div>
                                        @endif
                                        <p class="fs_12 c_dark2 mb-2">Ajouter de nouvelles images:</p>
                                        <input type="file" id="gallery_images" name="gallery_images[]" style="display: none;" accept="image/*" multiple />
                                        <div id="gallery_dropzone" class="dropzone_area">
                                            <i class="icon_upload"></i>
                                            <p class="mb-0 fs_12">Cliquez pour ajouter plusieurs images</p>
                                        </div>
                                        <div id="gallery_preview_container" class="preview_area d-flex flex-wrap gap-2 mt-2"></div>
                                        <small class="text-muted">Vous pouvez sélectionner plusieurs images à la fois</small>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Catégorie principale *</label>
                                        <select class="form-select select_sm" name="category_id" required>
                                            <option selected disabled>Choisir une catégorie</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Catégories supplémentaires</label>
                                        <select class="form-select select_sm" id="other_categories" name="other_categories[]" multiple>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ in_array($category->id, old('other_categories', is_array($product->other_categories) ? $product->other_categories : [])) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if (auth()->guard('admin')->check() && auth()->guard('admin')->user()->role_id == 1)
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Boutique *</label>
                                        <select class="form-select select_sm" name="merchant_id" required>
                                            <option selected disabled>Choisir une boutique</option>
                                            @foreach ($merchants as $merchant)
                                                <option value="{{ $merchant->id }}" {{ old('merchant_id', $product->merchant_id) == $merchant->id ? 'selected' : '' }}>{{ $merchant->brand_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Price Details -->
                        <div class="col-lg-6">
                            <div class="bg-white rounded_16 p_24 mb_24">
                                <h3 class="fs_20 mb_16">Détails du prix</h3>
                                <div class="row g_16">
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Prix *</label>
                                        <input type="number" name="price" value="{{ old('price', $product->price) }}" class="form-control inp_sm" step="0.01" id="productPrice" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Prix de réduction</label>
                                        <input type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" class="form-control inp_sm" step="0.01" id="discountPrice">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Quantité *</label>
                                        <input type="number" name="qty" class="form-control inp_sm" value="{{ old('qty', $product->qty) }}" min="0" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Début de la réduction</label>
                                        <input type="datetime-local" name="discount_start" class="form-control inp_sm" value="{{ old('discount_start', $product->discount_start ? date('Y-m-d\TH:i', strtotime($product->discount_start)) : '') }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Fin de la réduction</label>
                                        <input type="datetime-local" name="discount_end" class="form-control inp_sm" value="{{ old('discount_end', $product->discount_end ? date('Y-m-d\TH:i', strtotime($product->discount_end)) : '') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- ── Product Options ───────────────────────────────── -->
                            <div class="bg-white rounded_16 p_24">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h3 class="fs_20 mb-0">Options du produit</h3>
                                    <button type="button" class="btn btn_sm btn_primary" id="addOptionBtn">
                                        <i class="fi fi-rr-plus me-1"></i> Nouvelle option
                                    </button>
                                </div>
                                <p class="fs_12 c_dark2 mb-3">Ajoutez des variantes comme la taille, la couleur, etc.</p>

                                {{-- Quick presets --}}
                                <div class="mb-3">
                                    <p class="fs_12 fw-semibold c_dark2 mb-2">⚡ Suggestions rapides :</p>
                                    <div class="d-flex flex-wrap gap-2" id="presetBtns">
                                        <button type="button" class="preset-btn btn btn-sm btn-outline-secondary rounded-pill" data-name="Taille" data-values="XS,S,M,L,XL,XXL,XXXL">👕 Taille</button>
                                        <button type="button" class="preset-btn btn btn-sm btn-outline-secondary rounded-pill" data-name="Pointure" data-values="36,37,38,39,40,41,42,43,44,45">👟 Pointure</button>
                                        <button type="button" class="preset-btn btn btn-sm btn-outline-secondary rounded-pill" data-name="Couleur" data-values="Noir,Blanc,Rouge,Bleu,Vert,Jaune,Rose,Gris,Marron,Beige">🎨 Couleur</button>
                                        <button type="button" class="preset-btn btn btn-sm btn-outline-secondary rounded-pill" data-name="Matière" data-values="Coton,Polyester,Lin,Soie,Cuir,Laine,Denim">🧵 Matière</button>
                                        <button type="button" class="preset-btn btn btn-sm btn-outline-secondary rounded-pill" data-name="Capacité" data-values="64 Go,128 Go,256 Go,512 Go,1 To">💾 Capacité</button>
                                        <button type="button" class="preset-btn btn btn-sm btn-outline-secondary rounded-pill" data-name="Poids" data-values="250g,500g,1 kg,2 kg,5 kg">⚖️ Poids</button>
                                    </div>
                                </div>

                                {{-- Summary table --}}
                                <div id="optionsSummary" class="mb-3" style="display:none;">
                                    <p class="fs_12 fw-semibold c_dark2 mb-1">Récapitulatif :</p>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0" style="font-size:12px;">
                                            <thead style="background:#f0f3ff;color:#3d5af1;">
                                                <tr>
                                                    <th>Option</th>
                                                    <th>Valeur</th>
                                                    <th>Quantité</th>
                                                    <th>Image</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="optionsSummaryBody"></tbody>
                                        </table>
                                    </div>
                                </div>

                                <div id="optionsContainer">
                                    @foreach($product->options as $i => $option)
                                    <div class="option-row border rounded-3 p-3 mb-3" data-index="{{ $i }}" style="background:#f8f9fc;">
                                        <input type="hidden" name="options[{{ $i }}][id]" value="{{ $option->id }}">

                                        {{-- Name row --}}
                                        <div class="d-flex gap-2 align-items-center mb-1">
                                            <span class="fs_12 fw-semibold c_dark2 text-nowrap">Nom :</span>
                                            <input type="text"
                                                   name="options[{{ $i }}][name]"
                                                   class="form-control inp_sm option-name-input flex-grow-1"
                                                   placeholder="Ex : Taille, Couleur…"
                                                   value="{{ $option->name }}"
                                                   autocomplete="off" required>
                                            @php $usageCount = \App\Models\ProductOption::where('name', $option->name)->count(); @endphp
                                            <span class="badge usage-badge text-nowrap flex-shrink-0"
                                                  style="background:#e8eeff;color:#3d5af1;border:1px solid #c5cfff;font-size:10px;">
                                                {{ $usageCount }} produit{{ $usageCount > 1 ? 's' : '' }}
                                            </span>
                                            <button type="button" class="btn btn-sm btn-outline-danger removeOptionBtn flex-shrink-0">
                                                <i class="fi fi-rr-trash"></i>
                                            </button>
                                        </div>

                                        <div class="name-suggestions d-flex flex-wrap gap-1 mb-2"></div>

                                        {{-- Value chips --}}
                                        <div class="tags-display d-flex flex-wrap gap-1 mb-2" data-index="{{ $i }}">
                                            @foreach($option->optionValues as $j => $ov)
                                            <span class="opt-tag d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill fs_12 fw-medium"
                                                  style="background:#e8eeff;color:#3d5af1;border:1px solid #c5cfff;cursor:pointer;"
                                                  data-vid="{{ $ov->id }}"
                                                  data-value="{{ $ov->value }}"
                                                  data-qty="{{ $ov->qty }}"
                                                  data-image="{{ $ov->image_path ? url($ov->image_path) : '' }}"
                                                  data-image-path="{{ $ov->image_path ?? '' }}"
                                                  data-vi="{{ $j }}">
                                                @if($ov->image_path)
                                                <img src="{{ url($ov->image_path) }}" style="width:16px;height:16px;border-radius:50%;object-fit:cover;">
                                                @endif
                                                {{ $ov->value }}
                                                @if($ov->qty > 0)
                                                <small style="opacity:.6;font-size:10px;">({{ $ov->qty }})</small>
                                                @else
                                                <small style="opacity:.5;font-size:10px;color:#e53935;">(0)</small>
                                                @endif
                                                <input type="hidden" name="options[{{ $i }}][values][{{ $j }}][id]" value="{{ $ov->id }}">
                                                <input type="hidden" name="options[{{ $i }}][values][{{ $j }}][value]" value="{{ $ov->value }}">
                                                <input type="hidden" name="options[{{ $i }}][values][{{ $j }}][qty]" value="{{ $ov->qty }}" class="val-qty-input">
                                                <input type="hidden" name="options[{{ $i }}][values][{{ $j }}][image_path]" value="{{ $ov->image_path ?? '' }}" class="val-img-input">
                                                <span class="remove-tag" style="cursor:pointer;font-size:15px;line-height:1;opacity:.5;" title="Retirer">&times;</span>
                                            </span>
                                            @endforeach
                                        </div>

                                        {{-- Inline value detail panel (one shared per option-row) --}}
                                        <div class="value-detail-panel border rounded-2 p-2 mb-2" style="display:none;background:#fff;">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <span class="fw-semibold fs_12 active-val-name" style="min-width:60px;color:#3d5af1;"></span>
                                                <div class="d-flex align-items-center gap-1">
                                                    <label class="fs_12 mb-0">Qté :</label>
                                                    <input type="number" class="form-control form-control-sm val-qty-edit"
                                                           min="0" style="width:80px;" placeholder="0">
                                                    <span class="qty-combo-note fs_12" style="display:none;color:#ff9800;">→ géré par combinaison</span>
                                                </div>
                                                <div class="d-flex align-items-center gap-1 flex-grow-1">
                                                    <label class="fs_12 mb-0">Image :</label>
                                                    <input type="file" class="form-control form-control-sm val-img-file" accept="image/*" style="max-width:200px;">
                                                    <img class="val-img-preview rounded" src="" style="height:32px;width:32px;object-fit:cover;display:none;border:1px solid #ddd;">
                                                </div>
                                                <button type="button" class="btn btn-sm btn_primary val-detail-save">✓ OK</button>
                                            </div>
                                        </div>

                                        {{-- Suggestions appear directly above the input, no gap --}}
                                        <div class="value-suggestions d-flex flex-wrap gap-1"></div>
                                        <div class="input-group input-group-sm mt-1">
                                            <input type="text" class="form-control tag-input" placeholder="Ajouter une valeur…" autocomplete="off">
                                            <button type="button" class="btn btn-outline-primary add-tag-btn">+ Ajouter</button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                @if($product->options->isEmpty())
                                <div id="emptyState" class="text-center py-4 text-muted border rounded-3" style="border-style:dashed!important;">
                                    <i class="fi fi-rr-layers fs_24 d-block mb-2 opacity-50"></i>
                                    <p class="fs_12 mb-0">Aucune option pour ce produit.<br>Cliquez sur <strong>Nouvelle option</strong> ou choisissez une suggestion rapide.</p>
                                </div>
                                @else
                                <div id="emptyState" style="display:none;" class="text-center py-4 text-muted border rounded-3" style="border-style:dashed!important;">
                                    <i class="fi fi-rr-layers fs_24 d-block mb-2 opacity-50"></i>
                                    <p class="fs_12 mb-0">Aucune option pour ce produit.<br>Cliquez sur <strong>Nouvelle option</strong> ou choisissez une suggestion rapide.</p>
                                </div>
                                @endif

                                <p class="fs_12 text-muted mt-3 mb-0">
                                    <i class="fi fi-rr-info me-1"></i>
                                    Appuyez sur <kbd>Entrée</kbd> ou cliquez <strong>+ Ajouter</strong> pour valider une valeur. Cliquez <strong>&times;</strong> sur un tag pour le retirer.
                                </p>
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
        const galleryInput = $("#gallery_images");
        const galleryPreviewContainer = $("#gallery_preview_container");

        $(document).ready(function() {
            $('#other_categories').select2({
                placeholder: "Choisir les catégories supplémentaires",
                allowClear: true,
                width: '100%',
                dir: "ltr"
            });
        });

        // Main image handling
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
                        <button class="btn text-danger" id="remove_file" type="button">
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

        // Gallery images handling
        $("#gallery_dropzone").on("click", function() {
            galleryInput.click();
        });

        galleryInput.on("change", function(event) {
            const files = event.target.files;
            galleryPreviewContainer.empty();
            for (let i = 0; i < files.length; i++) {
                previewGalleryFile(files[i], i);
            }
        });

        function previewGalleryFile(file, index) {
            const fileUrl = URL.createObjectURL(file);
            const previewHTML = `
                <div class="gallery_preview_item" style="position: relative; width: 100px; height: 100px;">
                    <img src="${fileUrl}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;" />
                    <span style="position: absolute; bottom: 2px; left: 2px; background: rgba(0,0,0,0.6); color: white; font-size: 10px; padding: 2px 5px; border-radius: 4px;">${file.name.substring(0, 10)}...</span>
                </div>
            `;
            galleryPreviewContainer.append(previewHTML);
        }
    </script>

    <script>
    // ── Product Options Manager ────────────────────────────────────────────────

    let optionIndex = {{ $product->options->count() }};

    const PRESETS = {
        'taille':   ['XS','S','M','L','XL','XXL','XXXL'],
        'pointure': ['35','36','37','38','39','40','41','42','43','44','45','46'],
        'couleur':  ['Noir','Blanc','Rouge','Bleu','Vert','Jaune','Rose','Gris','Marron','Beige','Orange','Violet'],
        'matiere':  ['Coton','Polyester','Lin','Soie','Cuir','Laine','Denim','Viscose','Nylon'],
        'capacite': ['32 Go','64 Go','128 Go','256 Go','512 Go','1 To','2 To'],
        'poids':    ['100g','250g','500g','1 kg','2 kg','5 kg','10 kg'],
        'longueur': ['30 cm','50 cm','1 m','1.5 m','2 m','3 m'],
        'voltage':  ['5V','12V','24V','110V','220V'],
    };

    const NAME_SUGGESTIONS = ['Taille','Couleur','Pointure','Matière','Capacité','Poids','Longueur','Voltage','Modèle','Style','Format'];

    function esc(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function normalize(str) {
        return str.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'');
    }

    function getPresetValues(name) {
        const key = normalize(name);
        for (const [k, vals] of Object.entries(PRESETS)) {
            if (key.includes(k)) return vals;
        }
        return [];
    }

    let valueCounters = {}; // tracks per-option value index

    function makeTagHtml(optIndex, value, qty, imagePath, imageUrl, vid, vi) {
        qty       = qty       ?? 0;
        imagePath = imagePath ?? '';
        imageUrl  = imageUrl  ?? '';
        vid       = vid       ?? '';
        const vi_ = vi !== undefined ? vi : (valueCounters[optIndex] = (valueCounters[optIndex] ?? -1) + 1, valueCounters[optIndex]);
        const qtyLabel = qty > 0
            ? `<small style="opacity:.6;font-size:10px;">(${qty})</small>`
            : `<small style="opacity:.5;font-size:10px;color:#e53935;">(0)</small>`;
        const imgThumb = imageUrl
            ? `<img src="${esc(imageUrl)}" style="width:16px;height:16px;border-radius:50%;object-fit:cover;">`
            : '';
        return `<span class="opt-tag d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill fs_12 fw-medium"
                      style="background:#e8eeff;color:#3d5af1;border:1px solid #c5cfff;cursor:pointer;"
                      data-vid="${esc(String(vid))}" data-value="${esc(value)}"
                      data-qty="${qty}" data-image="${esc(imageUrl)}" data-image-path="${esc(imagePath)}" data-vi="${vi_}">
                  ${imgThumb}${esc(value)}${qtyLabel}
                  <input type="hidden" name="options[${optIndex}][values][${vi_}][id]"         value="${esc(String(vid))}">
                  <input type="hidden" name="options[${optIndex}][values][${vi_}][value]"      value="${esc(value)}">
                  <input type="hidden" name="options[${optIndex}][values][${vi_}][qty]"        value="${qty}" class="val-qty-input">
                  <input type="hidden" name="options[${optIndex}][values][${vi_}][image_path]" value="${esc(imagePath)}" class="val-img-input">
                  <span class="remove-tag" style="cursor:pointer;font-size:15px;line-height:1;opacity:.5;">&times;</span>
                </span>`;
    }

    function makeSugChip(value) {
        return `<button type="button" class="sug-val-btn btn rounded-pill flex-shrink-0"
                    style="background:#f0f3ff;color:#3d5af1;border:1px solid #c5cfff;font-size:11px;padding:1px 9px;line-height:1.6;"
                    data-value="${esc(value)}">${esc(value)}</button>`;
    }

    function buildOptionRow(index, name, values) {
        valueCounters[index] = -1;
        const tags = (values || []).map(v => makeTagHtml(index, typeof v === 'string' ? v : v.value, v.qty, v.image_path, v.image_url)).join('');
        return `
        <div class="option-row border rounded-3 p-3 mb-3" data-index="${index}" style="background:#f8f9fc;">
            <div class="d-flex gap-2 align-items-center mb-1">
                <span class="fs_12 fw-semibold c_dark2 text-nowrap">Nom :</span>
                <input type="text"
                       name="options[${index}][name]"
                       class="form-control inp_sm option-name-input flex-grow-1"
                       placeholder="Ex : Taille, Couleur…"
                       value="${esc(name || '')}"
                       autocomplete="off" required>
                <span class="badge usage-badge text-nowrap flex-shrink-0"
                      style="background:#e8eeff;color:#3d5af1;border:1px solid #c5cfff;font-size:10px;">
                    — produits
                </span>
                <button type="button" class="btn btn-sm btn-outline-danger removeOptionBtn flex-shrink-0">
                    <i class="fi fi-rr-trash"></i>
                </button>
            </div>
            <div class="name-suggestions d-flex flex-wrap gap-1 mb-2"></div>
            <div class="tags-display d-flex flex-wrap gap-1 mb-2" data-index="${index}">${tags}</div>
            <div class="value-detail-panel border rounded-2 p-2 mb-2" style="display:none;background:#fff;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="fw-semibold fs_12 active-val-name" style="min-width:60px;color:#3d5af1;"></span>
                    <div class="d-flex align-items-center gap-1">
                        <label class="fs_12 mb-0">Qté :</label>
                        <input type="number" class="form-control form-control-sm val-qty-edit" min="0" style="width:80px;" placeholder="0">
                        <span class="qty-combo-note fs_12" style="display:none;color:#ff9800;">→ géré par combinaison</span>
                    </div>
                    <div class="d-flex align-items-center gap-1 flex-grow-1">
                        <label class="fs_12 mb-0">Image :</label>
                        <input type="file" class="form-control form-control-sm val-img-file" accept="image/*" style="max-width:200px;">
                        <img class="val-img-preview rounded" src="" style="height:32px;width:32px;object-fit:cover;display:none;border:1px solid #ddd;">
                    </div>
                    <button type="button" class="btn btn-sm btn_primary val-detail-save">✓ OK</button>
                </div>
            </div>
            <div class="value-suggestions d-flex flex-wrap gap-1"></div>
            <div class="input-group input-group-sm mt-1">
                <input type="text" class="form-control tag-input" placeholder="Ajouter une valeur…" autocomplete="off">
                <button type="button" class="btn btn-outline-primary add-tag-btn">+ Ajouter</button>
            </div>
        </div>`;
    }

    function fetchAndShowUsage(row) {
        const name = row.find('.option-name-input').val().trim();
        const badge = row.find('.usage-badge');
        if (!name) { badge.text('— produits'); return; }
        $.get('/dashboard/products/option-usage', { name: name }, function(data) {
            const c = data.count || 0;
            badge.text(c + ' produit' + (c !== 1 ? 's' : ''));
        });
    }

    function addTag(row, rawValue) {
        const value = rawValue.trim();
        if (!value) return;
        const display  = row.find('.tags-display');
        const optIndex = display.data('index');
        // Prevent duplicates (check .val-qty-input siblings only within this option)
        const existing = display.find('[name*="[value]"]').map(function(){ return this.value; }).get();
        if (existing.includes(value)) return;
        display.append(makeTagHtml(optIndex, value, 0, '', '', '', undefined));
        renderSummaryTable();
    }

    function refreshValueSugs(row) {
        const name     = row.find('.option-name-input').val().trim();
        const presets  = getPresetValues(name);
        const existing = row.find('.tags-display input[type=hidden]').map(function(){ return this.value; }).get();
        const sugBox   = row.find('.value-suggestions').empty();
        presets.forEach(function(v) {
            if (!existing.includes(v)) sugBox.append(makeSugChip(v));
        });
    }

    function refreshNameSugs(row) {
        const val    = row.find('.option-name-input').val().trim();
        const sugBox = row.find('.name-suggestions').empty();
        if (val.length < 1) return;
        const matches = NAME_SUGGESTIONS.filter(function(s) {
            return normalize(s).startsWith(normalize(val)) && normalize(s) !== normalize(val);
        });
        matches.forEach(function(m) {
            sugBox.append(`<button type="button" class="name-sug-btn btn btn-sm rounded-pill"
                style="background:#fff3e0;color:#e65100;border:1px solid #ffcc80;font-size:11px;padding:2px 10px;"
                data-value="${esc(m)}">${esc(m)}</button>`);
        });
    }

    function toggleEmpty() {
        $('#emptyState').toggle($('#optionsContainer .option-row').length === 0);
    }

    function isColorOption(row) {
        const n = row.find('.option-name-input').val().toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');
        return n.includes('couleur') || n.includes('color');
    }

    function renderSummaryTable() {
        const multiOpt = $('#optionsContainer .option-row').length >= 2;
        const rows = [];
        $('#optionsContainer .option-row').each(function() {
            const optName = $(this).find('.option-name-input').val().trim() || '—';
            $(this).find('.opt-tag').each(function() {
                const value = $(this).data('value');
                const qty   = parseInt($(this).data('qty')) || 0;
                const img   = $(this).data('image') || '';
                if (!value) return;
                // Single-option: skip rows with no data. Multi-option: always show (qty managed by combo matrix).
                if (!multiOpt && qty === 0 && !img) return;
                rows.push({ optName, value, qty, img });
            });
        });
        if (rows.length === 0 && !multiOpt) { $('#optionsSummary').hide(); return; }

        let html = '';

        if (multiOpt) {
            html += `<tr><td colspan="5" style="background:#fff3e0;border-left:3px solid #ff9800;color:#bf360c;font-size:11px;padding:7px 10px;">
                ⚠️ <strong>Plusieurs types d'options détectés.</strong>
                Les quantités individuelles par valeur sont <u>ignorées</u> par le panier.
                Définissez le stock <strong>par combinaison</strong> (Couleur + Taille, etc.)
                dans la section <strong>Stock par combinaison</strong> ci-dessous.
            </td></tr>`;
        }

        rows.forEach(function(r) {
            const imgCell = r.img
                ? `<img src="${esc(r.img)}" style="width:28px;height:28px;border-radius:4px;object-fit:cover;">`
                : '<span style="color:#aaa;">—</span>';
            const editBtn = `<button type="button" class="summary-edit-btn btn btn-sm me-1"
                style="padding:1px 7px;font-size:11px;background:#e8eeff;color:#3d5af1;border:1px solid #c5cfff;"
                data-opt="${esc(r.optName)}" data-val="${esc(String(r.value))}">
                <i class="fi fi-rr-edit"></i></button>`;
            const delBtn  = `<button type="button" class="summary-del-btn btn btn-sm"
                style="padding:1px 7px;font-size:11px;background:#fff0f0;color:#e53935;border:1px solid #ffcdd2;"
                data-opt="${esc(r.optName)}" data-val="${esc(String(r.value))}">
                <i class="fi fi-rr-trash"></i></button>`;
            // Multi-option: qty per value is not used — show a visual cue instead
            const qtyCell = multiOpt
                ? `<span style="color:#aaa;font-size:11px;" title="Géré par combinaison">→ combo</span>`
                : r.qty;
            html += `<tr>
                <td>${esc(r.optName)}</td>
                <td>${esc(String(r.value))}</td>
                <td>${qtyCell}</td>
                <td>${imgCell}</td>
                <td class="text-nowrap">${editBtn}${delBtn}</td>
            </tr>`;
        });

        $('#optionsSummaryBody').html(html);
        $('#optionsSummary').show();

    }

    function findTag(optName, value) {
        let found = null;
        $('#optionsContainer .option-row').each(function() {
            if ($(this).find('.option-name-input').val().trim() !== optName) return;
            $(this).find('.opt-tag').each(function() {
                if (String($(this).data('value')) === String(value)) { found = $(this); return false; }
            });
            if (found) return false;
        });
        return found;
    }

    // Block form submit only if a NEW color value has qty > 0 but no image
    $('form').on('submit', function () {
        let missing = false;
        $('#optionsContainer .option-row').each(function() {
            if (!isColorOption($(this))) return;
            $(this).find('.opt-tag').each(function() {
                const isNew = !$(this).data('vid') || String($(this).data('vid')).trim() === '';
                const qty   = parseInt($(this).data('qty')) || 0;
                const img   = $(this).find('.val-img-input').val();
                if (isNew && qty > 0 && !img) { missing = true; }
            });
        });
        if (missing) {
            alert('Veuillez ajouter une image pour chaque nouvelle valeur de l\'option Couleur avec une quantité.');
            return false;
        }
    });

    $(document).ready(function () {

        toggleEmpty();
        renderSummaryTable();

        // Render suggestions + usage counts for server-rendered rows
        $('.option-row').each(function () {
            refreshValueSugs($(this));
            fetchAndShowUsage($(this));
        });

        // ── Add blank row
        $('#addOptionBtn').on('click', function () {
            const idx = optionIndex++;
            $('#optionsContainer').append(buildOptionRow(idx, '', []));
            $('#optionsContainer .option-row').last().find('.option-name-input').focus();
            toggleEmpty();
        });

        // ── Quick preset
        $(document).on('click', '.preset-btn', function () {
            const name   = $(this).data('name');
            const values = String($(this).data('values')).split(',');

            // Merge into existing row if name already used
            let existingRow = null;
            $('.option-name-input').each(function () {
                if (normalize($(this).val()) === normalize(name)) existingRow = $(this).closest('.option-row');
            });

            if (existingRow) {
                values.forEach(function(v) { addTag(existingRow, v); });
                refreshValueSugs(existingRow);
                existingRow[0].scrollIntoView({ behavior:'smooth', block:'center' });
                existingRow.css('outline','2px solid #3d5af1');
                setTimeout(function() { existingRow.css('outline',''); }, 800);
            } else {
                const idx = optionIndex++;
                $('#optionsContainer').append(buildOptionRow(idx, name, values));
                const newRow = $('#optionsContainer .option-row').last();
                refreshValueSugs(newRow);
                fetchAndShowUsage(newRow);
                toggleEmpty();
            }
        });

        // ── Remove row
        $(document).on('click', '.removeOptionBtn', function () {
            $(this).closest('.option-row').remove();
            toggleEmpty();
            renderSummaryTable();
        });

        // ── Remove tag
        $(document).on('click', '.remove-tag', function (e) {
            e.stopPropagation();
            const row = $(this).closest('.option-row');
            const tag = $(this).closest('.opt-tag');
            // hide detail panel if this tag was selected
            if (tag.hasClass('tag-selected')) row.find('.value-detail-panel').hide();
            tag.remove();
            refreshValueSugs(row);
            renderSummaryTable();
        });

        // ── Click tag chip → open detail panel
        $(document).on('click', '.opt-tag', function (e) {
            if ($(e.target).hasClass('remove-tag')) return;
            const tag = $(this);
            const row = tag.closest('.option-row');
            const panel = row.find('.value-detail-panel');

            // If clicking the same active tag, toggle close
            if (tag.hasClass('tag-selected')) {
                tag.removeClass('tag-selected').css('outline','');
                panel.hide();
                return;
            }

            // Deselect others in this row
            row.find('.opt-tag').removeClass('tag-selected').css('outline','');
            tag.addClass('tag-selected').css('outline','2px solid #3d5af1');

            // Populate panel
            panel.find('.active-val-name').text(tag.data('value'));
            panel.find('.val-qty-edit').val(tag.data('qty') || 0);
            const imgSrc = tag.data('image') || '';
            const preview = panel.find('.val-img-preview');
            if (imgSrc) { preview.attr('src', imgSrc).show(); } else { preview.hide(); }
            panel.find('.val-img-file').val(''); // reset file input
            panel.data('active-tag', tag);
            // When multi-option: dim qty field and show a note — qty is managed by the combo matrix
            const isMulti = $('#optionsContainer .option-row').length >= 2;
            panel.find('.val-qty-edit').prop('disabled', isMulti).css('opacity', isMulti ? .4 : 1);
            panel.find('.qty-combo-note').toggle(isMulti);
            panel.show();
        });

        // ── Image file selected → upload via AJAX and store path in tag
        $(document).on('change', '.val-img-file', function () {
            const input = $(this);
            const file  = input[0].files[0];
            if (!file) return;
            const row   = input.closest('.option-row');
            const panel = input.closest('.value-detail-panel');
            const tag   = panel.data('active-tag');
            if (!tag) return;

            const fd = new FormData();
            fd.append('image', file);
            fd.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '/dashboard/products/upload-option-image',
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                success: function (res) {
                    // Update panel preview
                    panel.find('.val-img-preview').attr('src', res.url).show();
                    panel.find('.color-img-error').remove();
                    // Update tag data + hidden input
                    tag.data('image', res.url).data('image-path', res.path);
                    tag.find('.val-img-input').val(res.path);
                    // Add/update thumb in chip
                    tag.find('img').remove();
                    tag.prepend(`<img src="${res.url}" style="width:16px;height:16px;border-radius:50%;object-fit:cover;">`);
                    renderSummaryTable();
                },
                error: function () { alert('Erreur lors de l\'upload de l\'image.'); }
            });
        });

        // ── Save value detail (qty) back to tag
        $(document).on('click', '.val-detail-save', function () {
            const panel   = $(this).closest('.value-detail-panel');
            const tag     = panel.data('active-tag');
            if (!tag) return;
            const row     = panel.closest('.option-row');
            const qty     = parseInt(panel.find('.val-qty-edit').val()) || 0;

            // Required image only for NEW color values with qty > 0
            const isNewVal = !tag.data('vid') || String(tag.data('vid')).trim() === '';
            if (isColorOption(row) && isNewVal && qty > 0 && !tag.find('.val-img-input').val()) {
                panel.find('.color-img-error').remove();
                panel.find('.val-img-file').after(
                    '<span class="color-img-error d-block mt-1" style="color:#e53935;font-size:11px;">Image requise pour les nouvelles valeurs couleur.</span>'
                );
                return;
            }
            panel.find('.color-img-error').remove();

            // Update tag data + hidden inputs
            tag.data('qty', qty);
            tag.find('.val-qty-input').val(qty);
            // Refresh qty label in chip
            tag.find('small').remove();
            const qtyLabel = qty > 0
                ? `<small style="opacity:.6;font-size:10px;">(${qty})</small>`
                : `<small style="opacity:.5;font-size:10px;color:#e53935;">(0)</small>`;
            tag.find('.remove-tag').before(qtyLabel);
            // Close panel
            tag.removeClass('tag-selected').css('outline','');
            panel.hide();
            renderSummaryTable();
        });

        // ── Add tag via button
        $(document).on('click', '.add-tag-btn', function () {
            const row   = $(this).closest('.option-row');
            const input = row.find('.tag-input');
            addTag(row, input.val());
            input.val('').focus();
            refreshValueSugs(row);
        });

        // ── Add tag via Enter
        $(document).on('keydown', '.tag-input', function (e) {
            if (e.key !== 'Enter') return;
            e.preventDefault();
            const row = $(this).closest('.option-row');
            addTag(row, $(this).val());
            $(this).val('');
            refreshValueSugs(row);
        });

        // ── Click value suggestion chip → add as tag
        $(document).on('click', '.sug-val-btn', function () {
            const row = $(this).closest('.option-row');
            addTag(row, $(this).data('value'));
            $(this).remove();
        });

        // ── Name input → suggestions + value hints + usage count
        let usageTimer = null;
        $(document).on('input', '.option-name-input', function () {
            const row = $(this).closest('.option-row');
            refreshNameSugs(row);
            refreshValueSugs(row);
            renderSummaryTable();
            clearTimeout(usageTimer);
            usageTimer = setTimeout(function() { fetchAndShowUsage(row); }, 500);
        });

        // ── Summary table: Edit button → open detail panel for that tag
        $(document).on('click', '.summary-edit-btn', function () {
            const tag = findTag($(this).data('opt'), $(this).data('val'));
            if (tag) tag.trigger('click');
        });

        // ── Summary table: Delete button → remove that tag
        $(document).on('click', '.summary-del-btn', function () {
            const tag = findTag($(this).data('opt'), $(this).data('val'));
            if (!tag) return;
            const row = tag.closest('.option-row');
            if (tag.hasClass('tag-selected')) row.find('.value-detail-panel').hide();
            tag.remove();
            refreshValueSugs(row);
            renderSummaryTable();
        });

        // ── Click name suggestion chip → fill name + refresh all
        $(document).on('click', '.name-sug-btn', function () {
            const row = $(this).closest('.option-row');
            row.find('.option-name-input').val($(this).data('value'));
            row.find('.name-suggestions').empty();
            refreshValueSugs(row);
            fetchAndShowUsage(row);
        });
    });
    </script>


</body>

</html>
