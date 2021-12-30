$(document).ready(function() {
    console.log( "init jstree" );
    // $('#jstree_div').jstree();
    $('#jstree_div_demo').jstree();
});

// переходим по ссылке на документ
$(document).on("click", ".document", function (event) {
    event.preventDefault();
    document.location.href = $(this).attr('data-href');
}); 