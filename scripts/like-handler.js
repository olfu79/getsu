$(document).ready(function() {
    $('.like-button').click(function() {
        var video_id = $(this).data('video-id');
        $.ajax({
            url: 'scripts/like-handler.php',
            type: 'post',
            data: {video_id: video_id},
            success: function(response) {
                var response = JSON.parse(response);
                if(response.status == "liked"){
                    $('.like-button').removeClass('mdi-heart-outline').addClass('mdi-heart');
                    $('.video-interactive p')[0].innerHTML = parseInt($('.video-interactive p').first().text())+1;
                }else if(response.status == "unliked"){
                    $('.like-button').removeClass('mdi-heart').addClass('mdi-heart-outline');
                    $('.video-interactive p')[0].innerHTML = parseInt($('.video-interactive p').first().text())-1;
                }
                else{
                    console.log("error");
                }
            }
        });
    });
});