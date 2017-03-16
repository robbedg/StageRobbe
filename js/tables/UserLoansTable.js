"use strict";
$(document).ready(function(){
    //search length
    var $minlength = 1;

    //data with starting values
    var $data = new Object();
    $data.search = null;
    $data.limit = 20;
    $data.offset = 0;
    $data.user_id = $("#user_id").val();
    $data.item = true;
    $data.class = true;
    //use db names
    $data.sort_on = {'column' : 'until', 'order' : 'desc'};

    //check for user preferences
    checkLocalStorage();
    //and set the values on the page
    setValues();

    //current page
    var $page = 1;
    //total amount of pages
    var $totalpages;

    //call db first
    callDB();

    //retrieve localStorage
    function checkLocalStorage() {
      var $pref = localStorage.getItem('loans-user');
      if ($pref !== null) {
        var $prefdata = JSON.parse($pref);
        if ($prefdata.limit !== null) $data.limit = $prefdata.limit;
        if ($prefdata.sort_on !== null) $data.sort_on = $prefdata.sort_on;
      }
    }

    //set standard values
    function setValues() {
      //set limit
      $("#amountselect").val($data.limit);
      //set sort
      $("#sortinput option").each(function($index, $el) {
        if (($($el).val().match($data.sort_on.column)) && ($($el).attr('order').match($data.sort_on.order))) {
          $($el).attr('selected', 'selected');
        } else {
          $($el).removeAttr('selected');
        }
      });
    }

    //save preferences locally
    function setLocalStorage() {
      localStorage.setItem('loans-user', JSON.stringify({'limit' : $data.limit, 'sort_on' : $data.sort_on}));
    }

    //calculatepages
    function calculatepages($resultcount) {
      //total pages
      $totalpages = Math.floor($resultcount / $data.limit);
      //maybe extra page
      if ((($resultcount % $data.limit) !== 0) && ($resultcount !== 0)) $totalpages++;
    }

    //add pages
    function loadpager() {
      //delete
      $(".pagination li").each(function($index, $el) {
        if($el.hasAttribute('added')) {
          $el.remove();
        }
      });

      //add
      for (var $i = 1; $i < $totalpages; $i++) {

        $("#page_" + $i)
          .after($('<li class id="page_' + ($i + 1) + '"/>').attr('added', 'added').append($('<a class="clickable-page" href=# />').append($i + 1)));
      }

      $(".clickable-page").each(function($index, $el) {

        //set active
        if (parseInt($($el).text()) === $page) {
          $($el).parent().addClass('active');
          //set non-active
        } else {
          $($el).parent().removeClass('active');
        }
      });
    }

    //attatch events to pagingbuttons
    function pagingbuttons() {
      //click on page number.
      $(".clickable-page").click(function($event) {
        $event.preventDefault();
        var $clicked = $(this);

        //get old values
        var $oldoffset = $data.offset;
        var $oldlimit = $data.limit;
        //new items
        $data.offset = (parseInt($clicked.text()) - 1) * $data.limit;
        //set page
        $page = parseInt($clicked.text());

        //call DB if offset or limit is different
        if (($oldoffset !== $data.offset) || ($oldlimit !== $data.limit)) {
          callDB();
        }
      });
    }

    //load DB
    function callDB() {

      //ajax call
      $.ajax({
        url: '/index.php/loans/get',
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify($data)
      })
      .done(function($response) {

        //empty table
        $("#listingpage tbody tr").each(function($index, $el) {
          $el.remove();
        });

        //get locations
        var $loans = $response.data;
        //fill db
        $($loans).each(function($index, $el) {
          $("#listingpage tbody").
            append($('<tr />').addClass($el['class'])
              .append($('<td />').append($el['id']))
              .append($('<td />').append($('<a href="/index.php/items/view/' + $el['item_id'] + '"/>').append($el['item_id'])))
              .append($('<td />').append($('<a href="/index.php/categories/' + $el['location_id'] + '"/>').append($el['location'])))
              .append($('<td />').append($('<a href="/index.php/items/' + $el['location_id'] + '/' + $el['category_id'] + '"/>').append($el['category'])))
              .append($('<td />').append($el['from_string']))
              .append($('<td />').append($el['until_string']))
            );
        });
        calculatepages($response.count);
      })
      .always(function() {
        loadpager();
        pagingbuttons();
      });
    }

    //search
    $("#search").keyup(function($event) {
      if ($("#search").val().length >= $minlength) {
        //reset pages
        $page = 1;
        $data.offset = 0;

        $data.search = $(this).val();
        callDB();
      } else {
        $data.search = null;
        callDB();
      }
    });

    //paging
    $("#next").click(function($event) {
      $event.preventDefault();
      if ($page < $totalpages) {
        $data.offset += $data.limit;
        $page++;
        callDB();
      }
    });

    $("#previous").click(function($event) {
      $event.preventDefault();
      if ($page > 1) {
        $data.offset -= $data.limit;
        $page--;
        callDB();
      }
    });

    /**
     * SORTING
     **/
     $("#sortinput").change(function($event) {
       var $selected = $("#sortinput option:selected");
       $data.sort_on = {'column' : $selected.val(), 'order' : $selected.attr('order')};
       $data.offset = 0;
       $page = 1;
       //save preferences
       setLocalStorage();
       //call to DB
       callDB();
     });

     /**
      * LIMIT
      **/
      $("#amountselect").change(function(event) {
        if (parseInt($(this).val()) > 0) {
          $data.limit = parseInt($(this).val());
          $data.offset = 0;
          $page = 1;
          //save preferences
          setLocalStorage();
          //call to DB
          callDB();
        }
      });

});
