$(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.document.location = $(this).data("href");
    });

    $("#delete").click(function() {
    	$(".modal").modal('toggle');
    	$(".modal").modal('show');
    });
    
});