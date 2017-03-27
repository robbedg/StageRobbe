"use strict";
$(document).ready(function() {
  /* DATA */
  //set data
  var $data = new Object();
  $data.user_id = $("#user_id").val();
  $data.item_id = $("#item_id").val();
  $data.from = '';
  $data.until = '';

  /* FUNCTIONS */
  //Send to DB.
  function callDB() {
    $.ajax({
      url: '/index.php/loans/set',
      type: 'POST',
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify($data)
    })
    .done(function($response) {
      //remove errors
      $("#loan-error-list").empty();
      //set errors
      if ($response.success === false) {
        $("#loan-errors").removeClass('hidden');
        $($response.errors).each(function($index, $error) {
            $("#loan-error-list").append($('<li />').append($error));
        });
      } else {
        $("#loan-errors").addClass('hidden');
      }
    })
    .always(function() {
        reDrawChart();
    });
  }

  /* EVENTS */
  //click button
  $("#loan_button a").click(function($event) {
    //prevent default
    $event.preventDefault();

    //get date from
    var $DateStringFrom = $("#datetimepicker_from").datetimepicker('date');
    $data.from = moment($DateStringFrom).format();
    //get date until
    var $DateStringUntil = $("#datetimepicker_until").datetimepicker('date');
    $data.until = moment($DateStringUntil).format();

    //send to db
    callDB();
  });

  //click dismiss errors
  $("#loan-errors button").click(function($event) {
    //prevent default
    $event.preventDefault();
    $("#loan-errors").addClass('hidden');
  });
});
