$(document).ready(function($) {
    function clickablerow() {
      $(".clickable-row").click(function() {
          window.document.location = $(this).data("href");
      });
    }


    $("#buttonmodal").click(function() {
    	$(".modal").modal('toggle');
    	$(".modal").modal('show');
    });

	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	});
});
