"use strict";

//show visual representation of issue
$(document).ready(function() {
  var $issue = $("#issue").val();

  if ($issue === '1') {
    $('h2').prepend(' ').prepend($('<span class="fa fa-exclamation-triangle" />'));
    $('h2').css('color', 'orange');

    //toggle tooltip
    $('h2 span').attr('data-toggle', 'tooltip').attr('data-original-title', 'Er is een probleem met dit item.');
    $('[data-toggle="tooltip"]').tooltip();
  }
});

$("#open-report").click(function($event) {
  //prevent default
  $event.preventDefault();

  $("#report-modal").modal('show');
});

$("#report").click(function($event) {
  //prevent default
  $event.preventDefault();

  //ajax call
  if ($('#issue').val() === '1') {
    $.ajax({
      url: '/index.php/items/report/' + $("#item_id").val() + '/0',
      type: 'GET'
    }).done(function() {
      location.reload();
    });
  } else {
    $.ajax({
      url: '/index.php/items/report/' + $("#item_id").val(),
      type: 'GET'
    }).done(function() {
      location.reload();
    });
  }

});
