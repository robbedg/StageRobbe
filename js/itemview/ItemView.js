/**
 * BASIC FUNCTIONS FOR ITEMS/VIEW
 **/
"use strict";

//draw chart
function drawChart() {
  //where
  var $container = document.getElementById('timeline');
  //empty
  $container.innerHTML = '';
  //set data
  var $chartData = [];

  //char options
  var $chartOptions = {
    locale: 'nl_BE',
    stack: false,
    showCurrentTime: true,
    rollingMode: true
  };

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

    $("#error").remove();

    //set chart
    if ($loans.length > 0) {
      /* CHART */
      $($response.data).each(function($i, $el) {
          $chartData.push({id: $el['id'], content: $el['lastname'] + ' ' + $el['firstname'], start: new Date($el['from']), end: new Date($el['until'])});
      });

      //draw chart
      var $timeline = new vis.Timeline($container, $chartData, $chartOptions);

      $timeline.on('select', function ($selected) {
        //get result
        var $loan = $.grep($loans, function ($e) { return $e.id === $selected.items[0]});
        //show result
        if ($loan.length !== 0) {
          $("#selected-timeline p").html(
            '<strong>' + $loan[0]['lastname'] +  ' ' + $loan[0]['firstname'] + '</strong>' + '<br />' +
            $loan[0]['from_string'] + ' ' + '<span class="fa fa-arrow-right"></span>' + ' ' + $loan[0]['until_string']
          );
        } else {
          $("#selected-timeline p").html('');
        }
      });


    } else {
      $("#availability").append($('<p id="error">No loans active.</p>').css('text-align', 'center'));
    }
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
        $el.append($('<a class="delete-note" href="#" />').append('<span class="fa fa-close"></span>'))
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

function generateQR() {
  var $qrCode = new QRCode("qrcode", {
    correctLevel : QRCode.CorrectLevel.M,
    width: 75,
    height: 75
  });
  $qrCode.makeCode($("#item_id").val());
  $("#qrcode").removeAttr('title');

  //print
  $("#print-label").click(function($event) {
    $event.preventDefault();
    var $popup = window.open();
    $popup.document.write($("#qrcode").html());
    $popup.focus();
    $popup.print();
    $popup.close();
  });
}

//Load
$(document).ready(function() {
  drawChart();
  getNotes();
  generateQR();

});
