
$(".basket_col").change(function(){

    var col = $(this).val();
    var id = $(this).attr('id');

    if( col <= 0 )
    {
        col = 1;
        $(this).val('1');
    }

    $.ajax({
        url: "applications/core/basketAjax.php",
        type: "POST",
        data: ({id: id, col: col}),
        dataType: "html"
    });

    setTimeout(function() {window.location.reload();}, 120);
});