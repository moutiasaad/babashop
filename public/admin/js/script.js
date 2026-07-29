$(document).ready(function(){
    /* ___________________________ start common for all pages  ___________________________ */
    
    // ----- select2 js (custom select)
    $(".select_2").each(function(ind,elm){
        var parentModal = $(elm).closest(".modal");
        $(elm).select2({
            minimumResultsForSearch: $(elm).hasClass("searchable") ? 0 : Infinity,
            dropdownParent: parentModal.length ? $(parentModal).find(".modal-body") : $("body"),
            templateResult: formatState,
            templateSelection: formatState,
            width: '100%'
        });
    });
    function formatState(state) {
        var baseUrl = $(state.element).data("image");
        var $state = baseUrl ? $(`<span><img src="${baseUrl}" class="opt__icon"/>${state.text}</span>`):state.text;
        return $state;
    }

    // ----- smooth scrollbar
    Scrollbar.initAll();

    // ----- side menu toggle
    $(".header_tglBtn,.header_backdrop").click(function(){
        $(".header").toggleClass("active_header");
    });

    /* ___________________________ end common for all pages  ___________________________ */

    /* ___________________________ start dashboard pages  ___________________________ */
    

    
    /* ___________________________ start setting page ___________________________ */

    // ----- image preview
    $("[data-img_preview_target]").change(function(){
        const preview_tar=$(this).attr("data-img_preview_target");
        var imagePreviewer = $(`[data-img_preview="${preview_tar}"]`)[0];
        var file = $(this)[0].files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                imagePreviewer.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            imagePreviewer.src = "assets/img/image.svg";
        }
    });

    // ----- edit payment method
    function edit_paymentMethod(query_id){
        new bootstrap.Offcanvas('#edit_payementMethodOffcanvas').show();
    }
    window.edit_paymentMethod=edit_paymentMethod;
    /* ___________________________ end setting page ___________________________ */


    /* ___________________________ start products page ___________________________ */

    // ----- product description rich editor
    $(".cke_rich_editor").each((ind,elm)=>{
        ClassicEditor
        .create(elm,{
            language: 'ar',
            toolbar:{
                shouldNotGroupWhenFull: true
            },
            
        })
        .catch( error => {
            console.error( error );
        });
    });

    // ---- drag drop image upload

    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#product_dropzone", {
        url: "/file/upload", // Set the URL for file upload
        paramName: "file", // The name of the file input (can be adjusted based on your backend)
        maxFilesize: 2, // Maximum file size in MB
        acceptedFiles: "image/*", // Specify acceptable file types
        clickable: true, // Make the dropzone clickable for file selection
        previewsContainer: false, // Disable default previews
        init: function () {
            var previewContainer = $("#product_preview_container"); // jQuery reference to the preview container
            this.on("addedfile", function (file) {
                preview_file(previewContainer, file); // Call the preview_file function
            });
        }
    });
    function preview_file(previewContainer, file) {
        var fileUrl = URL.createObjectURL(file);
        var previewHTML = `
            <div class="file_preview">
                <img src="${fileUrl}" data-dz-thumbnail />
                <div class="file_details">
                    <div class="file_name pe-1" data-dz-name>${file.name}</div>
                    <div class="file_size" data-dz-size>${(file.size / 1024).toFixed(2)} KB</div>
                </div>
                <div class="btn-group btn-group-sm">
                    <a class="btn text-primary" href="${fileUrl}" download>
                        <i class="fi fi-rr-down-to-line"></i>
                    </a>
                    <a class="btn" href="#">
                        <i class="fi fi-rr-pen-clip"></i>
                    </a>
                    <button class="btn text-danger" data-dz-remove>
                        <i class="fi fi-rr-trash"></i>
                    </button>
                </div>
            </div>
        `;
        previewContainer.append(previewHTML);
        previewContainer.find(".file_preview:last-child [data-dz-remove]").on("click", function () {
            myDropzone.removeFile(file); 
            $(this).closest('.file_preview').remove(); 
        });
    }



    // ----- add new category ( modal )
    $(".add_new_ctg__modalOpener").click(function(){
        $("#add_new_ctg_modal").modal("show");
    });

    // ------ show product delete comfirm details 
    function delete_product(product_id){
        $(".temp_product_id").val(product_id);
        $("#confirm_product_delete_modal").modal("show");
    }
    window.delete_product=delete_product;
    /* ___________________________ end products page ___________________________ */

    /* ___________________________ end theme customization page ___________________________ */
    
    // control iframe 
    $("[data-mode-btn]").click(function(){
        var mode=$(this).attr("data-mode-btn");
        $("[data-mode-btn]").removeClass("active");
        $(this).addClass("active");
        $(".iframe_wrapper").removeClass("iframe_mobile iframe_tablet");
        $(".iframe_wrapper").addClass(`iframe_${mode}`);
    });

    // hide option box
    $(".hide_option_btn").click(function(){
        $(this).parents(".option_box").removeClass("show_option_box");
    });


    // show option box
    $("[data-show-options]").click(function(){
        var target_value = $(this).data("value");

        var target_options = $(this).data("show-options");
        $(`[data-options-for="${target_options}"]`).addClass("show_option_box");
    });

    // section list rearrange 
    $("#section_list").sortable({
        handle:".handle",
        animation:150,
        onEnd: function (evt) {
            console.log(evt);
        }
    });

    // delete section rearrange item
    var tarSection=null;
    $("[data-delete-section]").click(function(){
        tarSection=$(this).parents(".section_item");
        var section_selector = $(this).data("delete-section");
        $(".target_section").val(section_selector);

        $("#comfirm_delete_section_modal").modal("show");
    });

    $("#comfirm_delete_section_modal .confirm_btn").click(function(){
        tarSection.remove();
    });

    // banner image upload
    $("#banner_image").imageUploader({
        imagesInputName: $("#banner_image").attr("data-hide-input-name"), 
        label:"1260px * 285px",
        maxFiles: 1
    });
    /* ___________________________ end theme customization page ___________________________ */

    /* ___________________________ start help desk page ___________________________ */
    $("#report_images").imageUploader({
        imagesInputName: $("#report_images").attr("data-hide-input-name"), 
        label: "افلت الملف هنا او اختر من جهازك",
        maxFiles: 10
    });
    /* ___________________________ end help desk page ___________________________ */



    // --- alerts
    function showAlert(alertType,alertTitle,alertDesc){
        $("#alert__modal").attr("data-alert_type",alertType);
        $(".alert__title").text(alertTitle);
        $(".alert__desc").text(alertDesc);
        $("#alert__modal").modal("show");
    }  

    // showAlert("primary","عنوان التنبيه","تأكيد عملية تغيير حالة الطلب !");
    // showAlert("warning","عنوان التنبيه","تأكيد عملية تغيير حالة الطلب !");
    // showAlert("danger","عنوان التنبيه","تأكيد عملية تغيير حالة الطلب !");

});
  



