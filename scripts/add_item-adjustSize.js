function setSelectSize() {
    if($('.add-series-button').hasClass('active')){
        var height = $("input#form-seriesId").height();
        $("input[type='date']").height(height);
        $('.brdtype select').height(height);
    }
    if($('.add-episode-button').hasClass('active')){
        var height = $("input#form-episodeId").height();
        $("input[type='checkbox']").height(height);
        $('.series select').height(height);
    }
}
$(document).ready(function() {
    setSelectSize()
    $(window).on('resize', function() {
        setSelectSize();
    });
});