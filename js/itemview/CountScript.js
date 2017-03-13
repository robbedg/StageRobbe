/**
 * Created by Robbe on 21/02/2017.
 */
$(document).ready(function($) {
	var $length;

	//set original length
	$(function () {
		$length = $("#count").text();
	});

	//count
	$("textarea").keyup(function(){
		$current = $(this).val().length;
		$("#count").text($length - $current);
	});

	//expand area
	$('textarea').on('input', function() {
		$(this).outerHeight(100).outerHeight(this.scrollHeight);
	});
	$('textarea').trigger('input');
});
