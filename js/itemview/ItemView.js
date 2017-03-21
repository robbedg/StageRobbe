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
  $data.class = true;
  $data.start_date = '- 1 week';
  $data.end_date = '+ 1 month';
  $data.sort_on = {'column': 'until', 'order': 'desc'};
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
        .append($('<tr/>').addClass($el['class'])
          .append($('<td />').append($el['uid']))
          .append($('<td />').append($el['lastname'] + ' ' + $el['firstname']))
          .append($('<td />').append($el['from_string']))
          .append($('<td />').append($el['until_string']))
          .append($('<td />').addClass('align-right').append(
            (($el['class'] === 'info' && $el['user_id'] === $("#user_id").val()) ? '<a href="#" class="btn btn-danger btn-xs" data-id="' + $el['id'] + '">Delete</a>' : '') +
            (($el['class'] === 'success' && $el['user_id'] === $("#user_id").val()) ? '<a href="#" class="btn btn-success btn-xs" data-id="' + $el['id'] + '">Return</a>' : '')
          ))
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
        $el.append($('<a class="delete-note" href="#" />').append('Delete'))
      }
    });
  })
  .always(function() {
    //load delete links
    deleteNote();
  })
}

//Make possible to delete Notes
function deleteNote() {
  //click event
  $(".delete-note").click(function($event) {
    //prevent default
    $event.preventDefault();

    var $note_id = $(this).parent().attr('data-note-id');

    //AJAX call
    $.ajax({
      url: '/index.php/usernotes/remove/' + $note_id,
      type: 'GET',
      dataType: 'json',
      contentType: 'application/json'
    })
    .always(function() {
      //reload notes
      getNotes();
    });
  });
}

//Load
$(document).ready(function() {

  getAvailability();
  getNotes();

});
