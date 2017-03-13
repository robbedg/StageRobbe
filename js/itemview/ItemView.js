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
  //get rresponse
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

//Load
$(document).ready(function() {

  getAvailability();

});
