jQuery(document).ready(function(){

    $(".delete_yes").click(function(){

        var id = $(this).attr('id');
        $(".button_a_delete_yes").attr('href', 'index.php?page=admin&action=delete&id='+id);

        $(".hide_delete_block").animate({height: "show"});
        $(".hide_delete").fadeIn(400);
    });

    $(".delete_no").click(function(){
        $(".button_a_delete_yes").attr('href', '#');
        $(".hide_delete_block").animate({height: "hide"});
        $(".hide_delete").fadeOut(400);
    });

});