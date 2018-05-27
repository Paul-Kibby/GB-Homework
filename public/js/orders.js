
$(".order_status").change(function(){

    var id = $(this).attr('id');
    var value = $(this).val();


    $.ajax({
        url: "applications/core/orderAjax.php",
        type: "POST",
        data: ({id: id, value: value}),
        dataType: "html"
    });

    setTimeout(function() {window.location.reload();}, 120);
});