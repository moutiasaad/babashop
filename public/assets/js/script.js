$(document).ready(function(){
    // ---- control side menu
    $(".menu_toggleBtn,.header_backDrop").click(function() {
        $(".header").toggleClass("active_header");
    });

    // ---- header background change on scroll
    function updateHeaderBackground() {
        var scroll = $(window).scrollTop();
        if (scroll > 80) {
            $(".header").addClass("active__header");
        } else {
            $(".header").removeClass("active__header");
        }
    }
    updateHeaderBackground();
    $(window).scroll(function() {
        updateHeaderBackground();
    });

    // ---- brand list (slider)
    $(".brand_list").owlCarousel({
        dots:false,
        nav:false,
        loop:true,
        margin:12,
        rtl:true,
        navText:['<i class="fi fi-rr-angle-small-left"></i>','<i class="fi fi-rr-angle-small-right"></i>'],
        responsive:{
            0:{
                items:3,
                margin:14
            },
            576:{
                items:3,
                margin:18,
                nav:true
            },
            768:{
                items:4,
                margin:20,
                nav:true
            },
            992:{
                items:5,
                margin:30,
                nav:true
            }
        }
    });
    

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
})