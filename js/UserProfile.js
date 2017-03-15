"use strict";
$(document).ready(function() {
  //call function(s)
  getUserInfo();
  getUserLoans();

  //get user info
  function getUserInfo() {
    //set data
    var $data = new Object();
    $data.id = $("#user_id").val();

    //get info
    $.ajax({
      url: '/index.php/users/get',
      type: 'POST',
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify($data)
    })
    .done(function($response) {
      var $user = $response.data[0];
      $("#db_id").text($user.id);
      $("#uid").text($user.uid);
      $("#firstname").text($user.firstname);
      $("#lastname").text($user.lastname);
      $("#role").text($user.role);
    });
  }

  //get loans of user
  function getUserLoans() {
    //set data
    var $data = new Object();
    $data.user_id = $("#user_id").val();
    $data.item = true;
    $data.limit = 10;
    $data.sort_on = {'column': 'from', 'order': 'asc'};

    //get info
    $.ajax({
      url: '/index.php/loans/get',
      type: 'POST',
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify($data)
    })
    .done(function($response) {
      var $loans = $($response.data);

      $loans.each(function($index, $el) {
        var $rowclass = '';
        var $now = moment().utcOffset(100).toDate();
        var $from = moment($el.from, 'DD/MM/YYYY hh:mm').utcOffset(100).toDate();
        var $until = moment($el.until, 'DD/MM/YYYY hh:mm').utcOffset(100).toDate();

        //set color
        if (($from < $now) && ($until < $now)) $rowclass = 'warning';
        if (($from > $now) && ($until > $now)) $rowclass = 'info';
        if (($from <= $now) && ($until >= $now)) $rowclass = 'success';

        //add data to table
        $("#active-loans tbody")
          .append(
            $('<tr />').addClass($rowclass)
              .append($('<td />').append($el.id))
              .append($('<td />').append($el.item_id))
              .append($('<td />').append('(' + $el.location_id + ') ' + $el.location))
              .append($('<td />').append('(' + $el.category_id + ') ' + $el.category))
              .append($('<td />').append($el.from))
              .append($('<td />').append($el.until))
          );
      });
    });
  }

});
