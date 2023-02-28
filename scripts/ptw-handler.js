$(document).ready(function() {
    $('.ptw-button').click(function() {
        var series_id = $(this).data('series-id');
        $.ajax({
            url: 'scripts/ptw-handler.php',
            type: 'post',
            data: {series_id: series_id},
            success: function(response) {
                var response = JSON.parse(response);
                if(response.status == "added"){
                    $('.ptw-button').removeClass('mdi-playlist-plus').addClass('mdi-playlist-check');
                    $('.ptw-button').removeClass('series-toadd').addClass('series-added');
                }else if(response.status == "removed"){
                    $('.ptw-button').removeClass('mdi-playlist-check').addClass('mdi-playlist-plus');
                    $('.ptw-button').removeClass('series-added').addClass('series-toadd');
                }
                else{
                    console.log("error");
                }
            }
        });
    });
});