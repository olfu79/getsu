
  $(document).ready(function(){
    $("#report-form").submit(function(e){
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: "scripts/report-handle.php",
        data: $("#report-form").serialize(),
        success: function(response) {
          $('.form-popup-bg').removeClass('is-visible');
          //dodać popup że zgłoszono
        }
      });
    });
  });