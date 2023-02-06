function setSelectSize() {
    var inputHeight = $(".fullname input").height();
    $("input").not("[type='submit'],[type='reset']").height(inputHeight);
    $('.brdtype select').height(inputHeight);
    $('.series select').height(inputHeight);
}
$(document).ready(function() {
    setSelectSize()
    $(window).on('resize', function() {
        setSelectSize();
    });
});