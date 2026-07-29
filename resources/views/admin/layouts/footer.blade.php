<script>
    $(document).ready(function(){
        $(".header_tglBtn,.header_backdrop").click(function(){
        $(".header").toggleClass("active_header");
    });
});

</script>

<script src="/admin/js/toastr.js"></script>
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3">
    <div id="alert__toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex ps-3">
            <div class="alert__icon">
                <i></i>
            </div>
            <div class="flex-grow-1">
                <div class="toast-header border-0 pb-1">
                    <h6 class="alert__title me-auto mb-0">Alert Title</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body pt-0 alert__desc text-left">
                    Alert Description
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var alertToast = new bootstrap.Toast($('#alert__toast'), {
    autohide: true,
    delay: 3000  // Customize the delay time if needed
});
function show_alert_toast(alertType,alertTitle,alertDesc){
    $("#alert__toast").addClass(alertType);
    $(".alert__title").text(alertTitle);
    $(".alert__desc").text(alertDesc);
    alertToast.show();
}  

    @if (session('success'))           
        show_alert_toast("alert_primary", "Succès", "{{ session('success') }}");
    @endif
    @if (session('error'))           
        show_alert_toast("alert_danger", "Erreur", "{{ session('error') }}");
    @endif

    @if($errors->any())
    show_alert_toast("alert_danger", "Erreur", "{{ $errors->first()  }}");
    @endif
 </script>   
<script>
    $(function () {
        // Toast when page is loaded
        window.onload = function() {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-center',
                showDuration: 400,
                hideDuration: 1000,
                timeOut: 5000,
                extendedTimeOut: 1000,
                showEasing: 'swing',
                hideEasing: 'linear',
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut'
            };

        //     @if($errors->any())
        //         toastr.error("{{ $errors->first() }}", 'حدث خطأ');
        //     @endif
        //       @if (session('success'))        
        //    toastr.success("{{ session('success') }}", 'تم بنجاح');
        //     @endif

        };
    });

</script>
