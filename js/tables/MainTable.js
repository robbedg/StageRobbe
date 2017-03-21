"use strict";
//retrieve localStorage
function checkLocalStorage($data, $storename) {
  var $pref = localStorage.getItem($storename);
  if ($pref !== null) {
    var $prefdata = JSON.parse($pref);
    if ($prefdata.limit !== null) $data.limit = $prefdata.limit;
    if ($prefdata.sort_on !== null) $data.sort_on = $prefdata.sort_on;
  }
}

//set standard values
function setValues($data) {
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
function setLocalStorage($data, $storename) {
  localStorage.setItem($storename, JSON.stringify({'limit' : $data.limit, 'sort_on' : $data.sort_on}));
}

//calculatepages
function calculatepages($data, $pageInfo, $resultcount) {
  //total pages
  $pageInfo.totalpages = Math.floor($resultcount / $data.limit);
  console.log($pageInfo.totalpages);
  //maybe extra page
  if ((($resultcount % $data.limit) !== 0) && ($resultcount !== 0)) $pageInfo.totalpages++;

}

//make rows clickable
function clickablerow() {
  $(".clickable-row").click(function() {
      window.document.location = $(this).attr("href");
  });
}

//add pages
function loadpager($pageInfo) {
  //delete
  $(".pagination li").each(function($index, $el) {
    if($el.hasAttribute('added')) {
      $el.remove();
    }
  });

  //add
  for (var $i = 1; $i < $pageInfo.totalpages; $i++) {

    $("#page_" + $i)
      .after($('<li class id="page_' + ($i + 1) + '"/>').attr('added', 'added').append($('<a class="clickable-page" href=# />').append($i + 1)));
  }

  $(".clickable-page").each(function($index, $el) {

    //set active
    if (parseInt($($el).text()) === $pageInfo.page) {
      $($el).parent().addClass('active');
      //set non-active
    } else {
      $($el).parent().removeClass('active');
    }
  });
}

//attatch events to pagingbuttons
function pagingbuttons($data, $pageInfo, callDB) {
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
    $pageInfo.page = parseInt($clicked.text());

    //call DB if offset or limit is different
    if (($oldoffset !== $data.offset) || ($oldlimit !== $data.limit)) {
      callDB();
    }
  });
}

/**
 * events
 **/
function loadEvents($data, $storename, $pageInfo, $minlength, callDB) {
  //search
  $("#search").keyup(function($event) {
    if ($("#search").val().length >= $minlength) {
      //reset pages
      $pageInfo.page = 1;
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
    if ($pageInfo.page < $pageInfo.totalpages) {
      $data.offset += $data.limit;
      $pageInfo.page++;
      callDB();
    }
  });

  $("#previous").click(function($event) {
    $event.preventDefault();
    if ($pageInfo.page > 1) {
      $data.offset -= $data.limit;
      $pageInfo.page--;
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
     $pageInfo.page = 1;
     //save preferences
     setLocalStorage($data, $storename);
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
        $pageInfo.page = 1;
        //save preferences
        setLocalStorage($data, $storename);
        //call to DB
        callDB();
      }
    });
}
