$(document).ready(function(){
     // ---- button ripple
     $(document).on('mouseenter', '.btn_hover_ripple', function(e) {
        var parentOffset = $(this).offset(),
            relX = e.pageX - parentOffset.left,
            relY = e.pageY - parentOffset.top;
        $(this).find('.btn_ripple').css({top:relY, left:relX})
    })
    .on('mouseout', '.btn_hover_ripple', function(e) {
        var parentOffset = $(this).offset(),
            relX = e.pageX - parentOffset.left,
            relY = e.pageY - parentOffset.top;
        $(this).find('.btn_ripple').css({top:relY, left:relX})
    });

    // ---- password view toggle
    $(".pwd_inpTglBtn").click(function(){
        const cInp=$(this).parents(".pwd_inpWrapper").find("input");
        if($(cInp).attr("type")==="password"){
            $(cInp).attr("type","text");
        }else{
            $(cInp).attr("type","password");
        }
    });

    // ---- login form validation and submit
    $("#login_form").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            email: {
                required: "البريد الإلكتروني مطلوب",
                email: "الرجاء إدخال بريد إلكتروني صحيح"
            },
            password: {
                required: "كلمة المرور مطلوبة",
                minlength: "يجب أن تتكون كلمة المرور من 6 أحرف على الأقل"
            }
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
            if (element.attr("name") === "password") {
                error.appendTo($("#pwd_inpErr"));
            } else {
                error.insertAfter(element); // Default error placement for other fields
            }
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).addClass('is-valid').removeClass('is-invalid');
        },
        submitHandler: function(form) {
            form.submit(); // This line actually submits the form
        }
    });

    // ---- phone number input
    $(".intl_telNumberInp").each((ind, elm) => {
        create_intlTelInp(elm);
    });
    function create_intlTelInp(elm) {
        window.intlTelInput(elm, {
            initialCountry: "tn",
            nationalMode:false,
            strictMode: true,
            separateDialCode: false,
            utilsScript: "/assets/js/utils.js"
        });
    };

    // ---- otp form
    $("#otp").pincodeInput({
        hidedigits: false, 
        inputs: 6,
        keydown: function(){
            //
        },
        complete: function (value, e, errorElement) {
            // 
        }
    });
})