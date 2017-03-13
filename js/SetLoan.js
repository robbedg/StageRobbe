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
      dataType: 'application/json',
      data: JSON.stringify($data)
    })
    .fail(function($error) {
      //console.log($error);
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
});
