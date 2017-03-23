"use strict";
$(document).ready(function() {
  //call function(s)
  getUserInfo();
  getUserLoans();

  /**
   * FUNCTIONS
   **/
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
    $data.class = true;
    $data.start_date = '-1 week';
    $data.end_date = '+ 1 month';
    $data.sort_on = {'column': 'until', 'order': 'desc'};

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

      $("#active-loans table > tbody").empty();

      $loans.each(function($index, $el) {
        //add data to table
        $("#active-loans tbody")
          .append(
            $('<tr />').addClass($el.class)
              .append($('<td />').append($el.id))
              .append($('<td />').append($('<a href="/index.php/items/view/' + $el.item_id + '"/>').append($el.item_id)))
              .append($('<td />').append($('<a href="/index.php/categories/' + $el.location_id + '"/>').append($el.location)))
              .append($('<td />').append($('<a href="/index.php/items/' + $el.location_id + '/' + $el.category_id + '"/>').append($el.category)))
              .append($('<td />').append($el.from_string))
              .append($('<td />').append($el.until_string))
              .append($('<td />').addClass('align-right').append(
                ($el['class'] === 'info' ? '<a href="#" class="btn btn-danger btn-sm" data-id="' + $el['id'] + '"><span class="fa fa-trash"></span></a>' : '') +
                ($el['class'] === 'success' ? '<a href="#" class="btn btn-success btn-sm" data-id="' + $el['id'] + '"><span class="fa fa-share"></span></a>' : '')
              ))
          );
      });
    })
    .always(function() {
      loadButtons();
    });
  }

  //load buttons (Delete & Return)
  function loadButtons() {
    $("table tbody tr td a.btn").click(function($event) {
      $event.preventDefault();
      //set url
      var $url = '';
      var $id = $(this).attr('data-id');
      //check function
      if ($(this).text().match('Delete')) {
        $url = '/index.php/loans/delete/'
      }
      if ($(this).text().match('Return')) {
        $url = '/index.php/loans/close/'
      }
      $.ajax({
        url: $url + $id,
        type: 'GET',
        dataType: 'json',
        contentType: 'application/json',
      })
      .always(function() {
        getUserLoans();
      });
    });
  }
});
