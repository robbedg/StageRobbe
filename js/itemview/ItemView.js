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
  //set user_id
  var $user_id = $("#user_id").val();
  //set role_id
  var $role_id = parseInt($("#role_id").val());
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
      //create notes
      $("#notes")
        .append(
          $('<div class="note" />').attr('id', $index)
            .append($('<strong class="username" />').append($el['lastname'].toUpperCase() + ' ' + $el['firstname'].toUpperCase()))
            .append($('<span class="links" />').attr('data-user-id', $el['user_id']).attr('data-note-id', $el['id']).attr('data-item-id', $el['item_id']))
            .append($('<p />').append($el['text']))
            .append($('<span class="date" />').append($el['created_on']))
        );
    });

    //set links
    $(".links").each(function($index, $el) {
      $el = $($el);
      if (($el.attr('data-user-id').match($user_id)) || ($role_id >= 3)) {
        $el.append($('<a />').attr('href', '/index.php/usernotes/remove/' + $el.attr('data-item-id') + '/' + $el.attr('data-note-id')).append('Delete'))
      }
    });
  });
}

//Load
$(document).ready(function() {

  getAvailability();
  getNotes();

});
