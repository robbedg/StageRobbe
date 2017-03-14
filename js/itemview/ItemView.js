/**
 * BASIC FUNCTIONS FOR ITEMS/VIEW
 **/
"use strict";

//load Availability table
function getAvailability() {
  //set data
  var $data = new Object();
  $data.item_id = $("#item_id").val();
  $data.user = true;
  $data.current = true;
  $data.sort_on = {'column': 'until', 'order': 'ASC'};
  //get response
  $.ajax({
    url: '/index.php/loans/get',
    type: 'POST',
    dataType: 'json',
    contentType: 'application/json',
    data: JSON.stringify($data)
  })
  .done(function($response) {
    //get results
    var $loans = $response.data;
    //empty
    $("#availability-table tbody").empty();

    //loans
    $($loans).each(function($index, $el) {
      $("#availability-table tbody")
        .append($('<tr/>')
          .append($('<td />').append($el['uid']))
          .append($('<td />').append($el['lastname'] + ' ' + $el['firstname']))
          .append($('<td />').append($el['from']))
          .append($('<td />').append($el['until']))
        );
    });
  });
}

//load comments
function getNotes() {
  //set data
  var $data = new Object();
  $data.user = true;
  $data.item_id = $("#item_id").val();
  $data.sort_on = {'column' : 'created_on', 'order': 'DESC'};
  //get response
  $.ajax({
    url: '/index.php/usernotes/get',
    type: 'POST',
    dataType: 'json',
    contentType: 'application/json',
    data: JSON.stringify($data)
  })
  .done(function($response) {
    //get results
    var $usernotes = $response.data;

    //empty
    $("#notes").empty();

    //usernotes
    $($usernotes).each(function($index, $el) {
      $("#notes")
        .append(
          $('<div class="note" />').attr('id', $index)
            .append($('<strong class="username" />').append($el['lastname'].toUpperCase() + ' ' + $el['firstname'].toUpperCase()))
            .append($('<span class="links" />')
              .append($('<a />').attr('href', '/index.php/usernotes/remove/' + $el['id']).append('Delete'))
            )
            .append($('<p />').append($el['text']))
            .append($('<span class="date" />').append($el['created_on']))
        );
    });
  });
}

//Load
$(document).ready(function() {

  getAvailability();
  getNotes();

});
