/**
 * Created by Robbe on 23/02/2017.
 */
$(document).ready(function($) {

  /** At page load **/

  var $allusers = [];
  var $currentuser = [];

  $(".users").each(function() {
    var $name = $(this).attr('search');
		$allusers.push({name: $name, user: $(this)});
  });

  $currentusers = $allusers.slice();

  /** Events **/

 //Search
  $("#searchbox").keyup(function(){
    try {
      var $search = $("#searchbox").val();
      $.each($currentusers, function ($i, $val) {

        //make all lowercase & delete all spaces
        if (!$val.name.toLowerCase().replace(/ /g,'').match($search.toLowerCase().replace(/ /g,''))) {
          $val.user.remove();
        }
        else {
          $('#userselect > ul').append($val.user);
        }
      });
    } catch(e) {
      console.log(e);
    }
  });

  //Filter
  $(".filterlist").click(function($event) {
    /* Act on the event */
    //prevent default
    $event.preventDefault();
    $filter = $(this);

    //clean search
    $("#searchbox").val("");

    //filter out correct roles
    try {
      $.each($allusers, function($i, $val) {
        if ($filter.attr('data').match('0')) {

          $currentusers = $allusers.slice();
          $("#userselect > ul").append($val.user);

        } else if (!($val.user.attr('data').match($filter.attr('data')))) {

          var $x = $currentusers.indexOf($val);

          //if in array, remove
          if ($x !== -1) {

            $val.user.remove();
            $currentusers.splice($x, 1);

          }


        } else {

          var $x = $currentusers.indexOf($val);

          //if not already in array, push
          if ($x === -1) {
            $currentusers.push($val);
            $("#userselect > ul").append($val.user)
          }

        }
      });

    } catch ($e) {
      console.log($e);
    }
  });

});
