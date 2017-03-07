"use strict";
$(document).ready(function(){
  //search length
  var $minlength = 1;

  //roledata with starting values
  var $roledata = new Object();
  $roledata.user_count = true;
  $roledata.user_search = null;
  $roledata.sort_on = {'column' : 'name', 'order' : 'ASC'};

  //userdata with starting values
  var $userdata = new Object();
  $userdata.search = null;
  $userdata.limit = 20;
  $userdata.offset = null;
  $userdata.role_id = null;
  $userdata.sorton = {'column' : 'lastname', 'order' : 'ASC'};   //use db names

  //call db first
  callDB();

  //color of label
  function determineLabel($roleid) {
    switch ($roleid) {
      case "1":
      return 'label-default';
      break;
      case "2":
      return 'label-primary';
      break;
      case "3":
      return 'label-success';
      break;
      case "4":
      return 'label-warning';
      break;
      default:
      return 'label-danger';
    }
  }

  //load DB
  function callDB() {

    /* ajax call roles */
    $.ajax({
      url: '/index.php/roles/get',
      type: 'POST',
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify($roledata)
    })
    .done(function($response) {
      //set filter
      var $roles = $response.data;
      //set counts
      $("#filter .badge").each(function($x, $span) {
        if ($($span).parent().parent().attr('id') !== 'all') $($span).text(0);
        $($roles).each(function($y, $role) {

          if ($($span).parent().attr('data-id') === $role['id']) {
            $($span).text($role['user_count']);
          }

        });
      });
    })
    .fail(function($error) {
      console.log($error);
    });

    /* ajax call users */
    $.ajax({
      url: '/index.php/users/get',
      type: 'POST',
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify($userdata)
    })
    .done(function($response) {

      //empty table
      $("#userselect ul li").each(function($index, $el) {
        $el.remove();
      });

      //get locations
      var $users = $response.data;
      //fill db
      $($users).each(function($index, $el) {
        $("#userselect ul")
        .append($('<li class="users" />').attr('data-id', $el['role_id']).attr('data-firstname', $el['firstname']).attr('data-lastname', $el['lastname'])
        .append($('<input type="radio" name="user" />').val($el['id']).attr('id', $index))
        .append($('<label />').attr('for', $index).text($el['lastname'] + ' ' + $el['firstname'])
        .append(
          $('<span class="label" />').text($el['role']).addClass(determineLabel($el['role_id']))
        )
      )
    );
  });

  //set total count
  $("#all a span").text($response.count);
  userclick();
})
.fail(function($error) {
  console.log($error);
});
}

//search
$("#searchusers").keyup(function($event) {
  if ($("#searchusers").val().length >= $minlength) {
    $userdata.search = $(this).val();
    $roledata.user_search = $(this).val();
    callDB();
  } else {
    if (($userdata.search !== null) && ($roledata.user_search !== null)) {
      $userdata.search = null;
      $roledata.user_search = null;
      callDB();
    }
  }
});

//filter
$(".filterlist").click(function($event) {
  /* Act on the event */
  //prevent default
  $event.preventDefault();
  var $filter = $(this);

  //set inactive
  $(".filterlist").each(function($index, $el) {
    $($el).removeClass('active');
  });
  //set active
  $filter.addClass('active');

  //if not all selected
  if ($filter.parent().attr('id') !== 'all') {
    $userdata.role_id = $filter.attr('data-id');
    callDB();
  } else {
    $userdata.role_id = null;
    callDB();
  }
});

function userclick() {
  /** User clicked **/
  $(".users input").click(function($event) {
    //get clicked user
    var $user = $(this);

    //activate role selector
    $("#roleselect select").removeAttr('disabled');

    //set selected role.
    try {
      $("#roleselect select option").each(function() {
        if ($(this).val() === $user.parent().attr('data-id')) {
          $(this).attr('selected', 'selected');
        } else {
          $(this).removeAttr('selected');
        }
      });
    } catch ($e) {
      console.log($e);
    }

    //set hidden field id
    $("#userid input").val($user.val());

    //set firstname
    $("#firstname input").removeAttr('disabled');
    $("#firstname input").val($user.parent().attr('data-firstname'));

    //set lastname
    $("#lastname input").removeAttr('disabled');
    $("#lastname input").val($user.parent().attr('data-lastname'));

    //activate button
    $("#submit button").removeAttr('disabled');
  });
}
});
