$(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.document.location = $(this).data("href");
    });

    $("#buttonmodal").click(function() {
    	$(".modal").modal('toggle');
    	$(".modal").modal('show');
    });
    
});
