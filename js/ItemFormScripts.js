/**
 * Created by Robbe on 9/02/2017.
 */
$(document).ready(function($) {
	$('#extra-button button').click(function ($event) {
		$event.preventDefault();
		$('#extra').append('<input />').attr('class', 'form-control')
			.attr('id', 'focused-input');
	})
});
