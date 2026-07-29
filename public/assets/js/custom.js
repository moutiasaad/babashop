$('.item').on('click', function() {
  $('.item').removeClass('selected');
  $(this).addClass('selected');
  $('.button ').removeClass('disabled')
});

$('.custom-select').select2({
  minimumResultsForSearch: -1
});

// Password type toggle
$(document).ready(function () {
  $(".togglePassword").on("click", function () {
      var targetId = $(this).data("target");
      var passwordInput = $("#" + targetId);
      var icon = $(this).find("i");

      if (passwordInput.attr("type") === "password") {
          passwordInput.attr("type", "text");
          icon.removeClass("fa-eye-slash").addClass("fa-eye");
      } else {
          passwordInput.attr("type", "password");
          icon.removeClass("fa-eye").addClass("fa-eye-slash");
      }
  });
});
// Password type toggle


$('.stepLast').click(function(){
  $('.stepLast-hide').hide();
  $('.stepLast-show').show();
});


// 
$('.mobClick').click(function() {
  $(this).toggleClass('open');
  $('.site-nav').toggleClass('act');
});


$(window).on("load",function(){
  $(".scrollbar").mCustomScrollbar({
    autoDraggerLength: false,
    axis:"y"
  });
});


$(window).on("load",function(){
    $(".modal-dialog-scrollable .modal-body").mCustomScrollbar({
      autoDraggerLength: false,
      axis:"y"
    });
});
