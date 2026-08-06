<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>ShaiebExpo - Ajouter un produit</title>

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
                <form action="{{ route('admin.products.store') }}" method="POST" enctype='multipart/form-data'>
                    @csrf
                    <div class="main__header">
                        <div class="row">
                            <div class="col-6">
                                <h2 class="fs_24 text-black fw-bold fw-normal mb-0">Ajouter un nouveau produit</h2>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <a href="{{ route('admin.product.index') }}" class="btn btn_sm btn_primary_outline px-sm-4">Annuler</a>
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
                                        <input type="text" name="name" class="form-control inp_sm" placeholder="Ex: T-shirt bleu" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Type *</label>
                                        <input type="text" name="type" class="form-control inp_sm" placeholder="Ex: Vêtements" value="{{ old('type') }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">SKU *</label>
                                        <input type="text" name="sku" class="form-control inp_sm" placeholder="Ex: SKU-001" value="{{ old('sku') }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Description *</label>
                                        <textarea name="description" class="form-control inp_sm" rows="4" placeholder="Description du produit..." required>{{ old('description') }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2 mb_4">Image principale *</label>
                                        <input type="file" id="image" name="image" style="display: none;" accept="image/*" required />
                                        <div id="merchant_dropzone" class="dropzone_area">
                                            <i class="icon_upload"></i>
                                            <p class="mb-0 fs_12">Cliquez ou glissez-déposez</p>
                                        </div>
                                        <div id="product_preview_container" class="preview_area"></div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2 mb_4">Images supplémentaires (galerie)</label>
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
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Catégories supplémentaires</label>
                                        <select class="form-select select_sm" id="other_categories" name="other_categories[]" multiple>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if (auth()->guard('admin')->check() && auth()->guard('admin')->user()->role_id == 1)
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Boutique *</label>
                                        <select class="form-select select_sm" name="merchant_id" required>
                                            @foreach ($merchants as $merchant)
                                                <option selected value="{{ $merchant->id }}" {{ old('merchant_id') == $merchant->id ? 'selected' : '' }}>{{ $merchant->brand_name }}</option>
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
                                        <input type="number" name="price" value="{{ old('price') }}" class="form-control inp_sm" step="0.01" id="productPrice" placeholder="Ex: 29.99" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Réduction</label>
                                        <select class="form-control inp_sm" id="discountSelect">
                                            <option value="">Sans réduction</option>
                                            <option value="10">10%</option>
                                            <option value="20">20%</option>
                                            <option value="30">30%</option>
                                            <option value="40">40%</option>
                                            <option value="50">50%</option>
                                        </select>
                                        <input type="hidden" name="discount_price" id="discountPrice" value="">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Quantité *</label>
                                        <input type="number" name="qty" class="form-control inp_sm" value="{{ old('qty', 1) }}" min="0" required>
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
                                        <label class="form-label fs_14 c_dark2">Début de la réduction</label>
                                        <input type="datetime-local" name="discount_start" class="form-control inp_sm" value="{{ old('discount_start') }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs_14 c_dark2">Fin de la réduction</label>
                                        <input type="datetime-local" name="discount_end" class="form-control inp_sm" value="{{ old('discount_end') }}">
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

                                <div id="optionsContainer"></div>

                                <div id="emptyState" class="text-center py-4 text-muted border rounded-3" style="border-style:dashed!important;">
                                    <i class="fi fi-rr-layers fs_24 d-block mb-2 opacity-50"></i>
                                    <p class="fs_12 mb-0">Aucune option pour ce produit.<br>Cliquez sur <strong>Nouvelle option</strong> ou choisissez une suggestion rapide.</p>
                                </div>

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
    // ── Product Options Manager ────────────────────────────────────────────────

    let optionIndex = 0;

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
        return str.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g,'');
    }

    function getPresetValues(name) {
        const key = normalize(name);
        for (const [k, vals] of Object.entries(PRESETS)) {
            if (key.includes(k)) return vals;
        }
        return [];
    }

    let valueCounters = {};

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
                <input type="text" class="form-control tag-input" placeholder="Valeur (ex : 40, Rouge)…" autocomplete="off">
                <input type="number" class="form-control tag-qty-input" min="0" value="1" placeholder="Qté" style="max-width:80px;">
                <button type="button" class="btn btn-outline-primary add-tag-btn">+ Ajouter</button>
            </div>
        </div>`;
    }

    function addTag(row, rawValue, rawQty) {
        const value = rawValue.trim();
        if (!value) return;
        const qty = Math.max(0, parseInt(rawQty ?? 1) || 0);
        const display  = row.find('.tags-display');
        const optIndex = display.data('index');
        const existing = display.find('[name*="[value]"]').map(function(){ return this.value; }).get();
        if (existing.includes(value)) return;
        display.append(makeTagHtml(optIndex, value, qty, '', '', '', undefined));
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
        const rows = [];
        $('#optionsContainer .option-row').each(function() {
            const optName = $(this).find('.option-name-input').val().trim() || '—';
            $(this).find('.opt-tag').each(function() {
                const value = $(this).data('value');
                const qty   = parseInt($(this).data('qty')) || 0;
                const img   = $(this).data('image') || '';
                if (!value) return;
                if (qty === 0 && !img) return;
                rows.push({ optName, value, qty, img });
            });
        });
        if (rows.length === 0) { $('#optionsSummary').hide(); return; }
        let html = '';
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
            html += `<tr>
                <td>${esc(r.optName)}</td>
                <td>${esc(String(r.value))}</td>
                <td>${r.qty}</td>
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
            alert('Veuillez ajouter une image pour chaque valeur de l\'option Couleur avec une quantité.');
            return false;
        }
    });

    $(document).ready(function () {

        toggleEmpty();
        renderSummaryTable();

        $('#addOptionBtn').on('click', function () {
            const idx = optionIndex++;
            $('#optionsContainer').append(buildOptionRow(idx, '', []));
            $('#optionsContainer .option-row').last().find('.option-name-input').focus();
            toggleEmpty();
        });

        // Preset now only creates a row with the option NAME. The individual
        // preset values (36, 37, ...) become suggestion chips — admin picks
        // one, sets qty, then clicks + Ajouter. No auto-add.
        $(document).on('click', '.preset-btn', function () {
            const name = $(this).data('name');

            let existingRow = null;
            $('.option-name-input').each(function () {
                if (normalize($(this).val()) === normalize(name)) existingRow = $(this).closest('.option-row');
            });

            if (existingRow) {
                existingRow[0].scrollIntoView({ behavior:'smooth', block:'center' });
                existingRow.css('outline','2px solid #3d5af1');
                setTimeout(function() { existingRow.css('outline',''); }, 800);
                existingRow.find('.tag-input').focus();
            } else {
                const idx = optionIndex++;
                $('#optionsContainer').append(buildOptionRow(idx, name, []));
                const newRow = $('#optionsContainer .option-row').last();
                refreshValueSugs(newRow);
                newRow.find('.tag-input').focus();
                toggleEmpty();
            }
        });

        $(document).on('click', '.removeOptionBtn', function () {
            $(this).closest('.option-row').remove();
            toggleEmpty();
            renderSummaryTable();
        });

        $(document).on('click', '.remove-tag', function (e) {
            e.stopPropagation();
            const row = $(this).closest('.option-row');
            const tag = $(this).closest('.opt-tag');
            if (tag.hasClass('tag-selected')) row.find('.value-detail-panel').hide();
            tag.remove();
            refreshValueSugs(row);
            renderSummaryTable();
        });

        $(document).on('click', '.opt-tag', function (e) {
            if ($(e.target).hasClass('remove-tag')) return;
            const tag = $(this);
            const row = tag.closest('.option-row');
            const panel = row.find('.value-detail-panel');

            if (tag.hasClass('tag-selected')) {
                tag.removeClass('tag-selected').css('outline','');
                panel.hide();
                return;
            }

            row.find('.opt-tag').removeClass('tag-selected').css('outline','');
            tag.addClass('tag-selected').css('outline','2px solid #3d5af1');

            panel.find('.active-val-name').text(tag.data('value'));
            panel.find('.val-qty-edit').val(tag.data('qty') || 0);
            const imgSrc = tag.data('image') || '';
            const preview = panel.find('.val-img-preview');
            if (imgSrc) { preview.attr('src', imgSrc).show(); } else { preview.hide(); }
            panel.find('.val-img-file').val('');
            panel.data('active-tag', tag);
            panel.show();
        });

        $(document).on('change', '.val-img-file', function () {
            const input = $(this);
            const file  = input[0].files[0];
            if (!file) return;
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
                    panel.find('.val-img-preview').attr('src', res.url).show();
                    panel.find('.color-img-error').remove();
                    tag.data('image', res.url).data('image-path', res.path);
                    tag.find('.val-img-input').val(res.path);
                    tag.find('img').remove();
                    tag.prepend(`<img src="${res.url}" style="width:16px;height:16px;border-radius:50%;object-fit:cover;">`);
                    renderSummaryTable();
                },
                error: function () { alert('Erreur lors de l\'upload de l\'image.'); }
            });
        });

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
                    '<span class="color-img-error d-block mt-1" style="color:#e53935;font-size:11px;">Image requise pour les options couleur.</span>'
                );
                return;
            }
            panel.find('.color-img-error').remove();

            tag.data('qty', qty);
            tag.find('.val-qty-input').val(qty);
            tag.find('small').remove();
            const qtyLabel = qty > 0
                ? `<small style="opacity:.6;font-size:10px;">(${qty})</small>`
                : `<small style="opacity:.5;font-size:10px;color:#e53935;">(0)</small>`;
            tag.find('.remove-tag').before(qtyLabel);
            tag.removeClass('tag-selected').css('outline','');
            panel.hide();
            renderSummaryTable();
        });

        $(document).on('click', '.add-tag-btn', function () {
            const row      = $(this).closest('.option-row');
            const valInput = row.find('.tag-input');
            const qtyInput = row.find('.tag-qty-input');
            addTag(row, valInput.val(), qtyInput.val());
            valInput.val('').focus();
            qtyInput.val('1');
            refreshValueSugs(row);
        });

        $(document).on('keydown', '.tag-input', function (e) {
            if (e.key !== 'Enter') return;
            e.preventDefault();
            const row      = $(this).closest('.option-row');
            const qtyInput = row.find('.tag-qty-input');
            addTag(row, $(this).val(), qtyInput.val());
            $(this).val('');
            qtyInput.val('1');
            refreshValueSugs(row);
        });

        $(document).on('keydown', '.tag-qty-input', function (e) {
            if (e.key !== 'Enter') return;
            e.preventDefault();
            const row = $(this).closest('.option-row');
            row.find('.add-tag-btn').trigger('click');
        });

        // Suggestion chip → fill the value input, focus qty (no auto-add)
        $(document).on('click', '.sug-val-btn', function () {
            const row = $(this).closest('.option-row');
            row.find('.tag-input').val($(this).data('value'));
            row.find('.tag-qty-input').focus().select();
        });

        $(document).on('input', '.option-name-input', function () {
            const row = $(this).closest('.option-row');
            refreshNameSugs(row);
            refreshValueSugs(row);
            renderSummaryTable();
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

        $(document).on('click', '.name-sug-btn', function () {
            const row = $(this).closest('.option-row');
            row.find('.option-name-input').val($(this).data('value'));
            row.find('.name-suggestions').empty();
            refreshValueSugs(row);
        });
    });
    </script>

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

</body>

</html>
