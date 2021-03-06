/**
 * Created by Robbe on 9/02/2017.
 */
$(document).ready(function($) {

	/**
	 * No double values
	 */
	jQuery.validator.addMethod("unique", function($value, $element, $params) {
		var $prefix = $params;
		var $selector = jQuery.validator.format("[identifier!='{0}'][unique='{1}']", $($element).attr('identifier'), $prefix);
		var $matches = new Array();
		$($selector).each(function($index, $item) {
			if ($value == $($item).val()) {
				$matches.push($item);
			}
		});
		return $matches.length == 0;
	}, "De waarde is niet uniek.");

	jQuery.validator.classRuleSettings.unique = {
		unique: true
	};

	/**
	 * add fields
	 * @type {number}
	 */
	//counter
	var $i=0;

	$("#extra-button-append button").click(function ($event) {
		//prevent default
		$event.preventDefault();

		$i++;

		$("<div class='form-group' id='extra_" + $i +"' />").appendTo("#extra");

		$("<label />")
			.attr('class', 'control-label')
			.append('Attribuut')
			.appendTo("#extra_" + $i);

		$("<br />").appendTo("#extra_" + $i);

		$("<input type='text' placeholder='Label...' unique='true' />")
			.attr('id', 'focused-input')
			.attr('class', 'form-control new-form')
			.attr('name', 'label[]')
			.attr('identifier', $i)
			.appendTo("#extra_" + $i);

		$("<input type='text' placeholder='Waarde...' />")
			.attr('id', 'focused-input')
			.attr('class', 'form-control new-form')
			.attr('name', 'value[]')
			.appendTo("#extra_" + $i);

		$("<button type='button' />")
			.attr('id', 'extra-button-remove_' + $i)
			.attr('class', 'extra-button-remove btn btn-danger')
			.append('<span class="fa fa-close"></span>')
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

	/**
	 * validate form
	 */
	 $("#submit").click(function ($event) {
		 //prevent default
		 $event.preventDefault();

		 //validate
		 $("#form").validate({
			 rules: {
		 		"label[]": {
					required: true,
					maxlength: 45
				},
				"value[]": {
					required: true,
					maxlength: 45
				},
				"name": {
					required: true,
					maxlength: 45
				}
			 },
			 showErrors: function($errorMap, $errorList) {
			 	$("span.error").remove();

			 	$("input.error").each(function ($index, $element) {
					$class = $($element).attr('class');
					$class = $class.replace(' error','');
					$($element).attr('class', $class);
				});

				$errorList.forEach(function ($element) {
					$('<span />').attr('class', 'error').html($element['message']).insertAfter($element['element']);
					$origClass = $($element['element']).attr('class');
					$($element['element']).attr('class', $origClass + " error");
				});
			 }
		 });
		 $("#form").submit();
	 });

	 /**
	  * Back button
		*/
		$("#back-button").click(function() {
			var $id = $("#item_id").val();
			window.location = "/index.php/items/view/" + $id;
		});

		/**
		 * Add picture
		 **/
		 $("#button-picture-modal").click(function($event) {
			 //prevent default
			 $event.preventDefault();

			 $("#picture-modal").modal('show');

		 });
});
