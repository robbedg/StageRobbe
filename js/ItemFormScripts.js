/**
 * Created by Robbe on 9/02/2017.
 */
$(document).ready(function($) {
	var $i=0;

	$("#extra-button-append button").click(function ($event) {
		$event.preventDefault();

		$i++;

		$("<div class='form-group' id='extra_" + $i +"' />").appendTo("#extra");

		$("<input type='text' value='' placeholder='Label...' />")
			.attr('id', 'focused-input')
			.attr('class', 'form-control')
			.attr('name', 'label_'+$i)
			.appendTo("#extra_" + $i);

		$("<input type='text' value='' placeholder='Value...' />")
			.attr('id', 'focused-input')
			.attr('class', 'form-control')
			.attr('name', 'value_'+$i)
			.appendTo("#extra_" + $i);

		$("<button type='button' />")
			.attr('id', 'extra-button-remove_' + $i)
			.attr('class', 'extra-button-remove btn btn-danger btn-sm')
			.append('-')
			.appendTo("#extra_" +$i);

		//reload script
		var $url = window.location.href;
		var $index = $url.indexOf('.php');
		$url = $url.substring(0, $index);
		$index = $url.lastIndexOf('/');
		$url = $url.substring(0, $index) +'/js/RemoveItem.js';
		//reload script
		$.getScript($url);
	});
});
