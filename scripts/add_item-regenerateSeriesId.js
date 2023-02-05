$(document).ready(function() {
    $.ajax({
    type: "GET",
    url: "scripts/generate_uid.php",
    success: function(data) {
    $("#form-seriesId").val(data);
    }
    });
    
    $(".regenerate-id-button").click(function() {
    $.ajax({
    type: "GET",
    url: "scripts/generate_uid.php",
    success: function(data) {
    $("#form-seriesId").val(data);
    }
    });
    });
});