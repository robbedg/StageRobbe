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

  /**
   * Password
   **/

  /* Change password */
  $("#show-passwd-box").click(function($event) {
    //prevent default
    $event.preventDefault();

    //show box
    $("#new-password").modal('show');
  });

  /* Password checking */
   $(".modal-body input").keyup(function() {
     var $val1 = $("#password-1").val();
     var $val2 = $("#password-2").val();

     if ($val1 !== '' && $val2 !== '') {
       if ($val1 === $val2) {
         $(".modal-body .form-group").removeClass('has-error').addClass('has-success');
         $("#pwd-submit").removeClass('disabled');
         $("#valid").val("true");
       } else {
         $(".modal-body .form-group").removeClass('has-success').addClass('has-error');
         $("#pwd-submit").addClass('disabled');
         $("#valid").val("false");
       }
     } else {
       $(".modal-body .form-group").removeClass('has-error').removeClass('has-success');
       $("#pwd-submit").addClass('disabled');
       $("#valid").val("false");
     }
   });

  /* Click save */
  $("#pwd-submit").click(function($event) {
    //prevent default
    $event.preventDefault();

    //check if input valid
    if ($("#valid").val() === "true") {
      //set data
      var $dataUpdate = new Object();
      $dataUpdate.id = $("#user_id").val();
      $dataUpdate.password = $("#password-1").val();

      $.ajax({
        url: '/index.php/users/update',
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify($dataUpdate)
      }).done(function($response) {
        if ($response['success'] === true) {
          $(".modal-body .form-group").removeClass('has-error').removeClass('has-success').val('');
          $("#pwd-submit").addClass('disabled');
          $("#new-password").modal('hide');
        }
      });
    }
  });


});
