"use strict";
/**
 * Created by Robbe on 13/02/2017.
 */
$(document).ready(function(){
	$(".extra-button-remove").click(function ($event) {
		$event.preventDefault();

		var $attribute = $(this).closest('div');
		$attribute.remove();

		//load script
		var $url = window.location.href;
		var $index = $url.indexOf('.php');
		$url = $url.substring(0, $index);
		$index = $url.lastIndexOf('/');
		$url = $url.substring(0, $index) +'/js/RemoveItem.js';
		//reload script
		$.getScript($url);
	});

});
