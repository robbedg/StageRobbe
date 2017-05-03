"use strict";

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
});

//search in navigation
$("nav form button").click(function($event) {
  //prevent default
  $event.preventDefault();

  var $data = new Object();
  $data.id = $("nav form input").val();
  $data.limit = 1;

  $.ajax({
    url: '/index.php/items/get',
    type: 'POST',
    dataType: 'json',
    contentType: 'application/json',
    data: JSON.stringify($data)
  }).done(function($result) {
    if ($result['count'] === 1) {
      window.location = '/index.php/items/view/' + $data.id;
    }
  });
})
