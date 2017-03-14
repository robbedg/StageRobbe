"use strict";
$(document).ready(function() {

  /* COUNTER & TEXTAREA */
  var $length;

	//set original length
	$length = $("#count").text();

	//count
	$("textarea").keyup(function(){
		var $current = $(this).val().length;
		$("#count").text($length - $current);
	});

	//expand area
	$('textarea').on('input', function() {
		$(this).outerHeight(100).outerHeight(this.scrollHeight);
	});
	$('textarea').trigger('input');

  /* SUBMIT NEW */
  $("#submit-new-note").click(function($event) {
    //preventDefault
    $event.preventDefault();

    //set data
    var $data = new Object();
    $data.user_id = $("#user_id").val();
    $data.item_id = $("#item_id").val();
    $data.text = $("#textArea").val();

    //make ajax call
    $.ajax({
      url: '/index.php/usernotes/set',
      type: 'POST',
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify($data)
    })
    .done(function($response) {
      if ($response.success) {
          //reset textarea
          $("#textArea").val('');
          //reset counter
          $("#count").text($length);

      }
    })
    .always(function() {
      getNotes();
    });
  });
});
