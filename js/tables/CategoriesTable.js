"use strict";
$(document).ready(function(){
    //search length
    var $minlength = 1;

    //object for keeping page info
    var $pageInfo = new Object();
    $pageInfo.page = 1;
    $pageInfo.totalpages = 1;

    //data with starting values
    var $data = new Object();
    $data.search = null;
    $data.limit = 20;
    $data.offset = 0;
    //use db names
    $data.sort_on = {'column' : 'id', 'order' : 'asc'};
    //give location id
    $data.location_id = $("#location_id").val();

    //check for user preferences
    checkLocalStorage($data, 'categories');
    //and set the values on the page
    setValues($data);

    //call db first
    callDB();

    //load DB
    function callDB() {

      //ajax call
      $.ajax({
        url: '/index.php/categories/get',
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
        var $categories = $response.data;
        //fill db
        $($categories).each(function($index, $el) {
          $("#listingpage tbody").
            append($('<tr class="clickable-row" href="/index.php/items/' + $data.location_id + '/' + $el['id'] + '"/>')
              .append($('<td />').append($el['id']))
              .append($('<td />').append($el['name']))
              .append($('<td />').append($el['item_count'])));
        });
        calculatepages($data, $pageInfo, $response.count);
      })
      .always(function() {
        clickablerow();
        loadpager($pageInfo);
        pagingbuttons($data, $pageInfo, callDB);
      });
    }

    //load events
    loadEvents($data, 'categories', $pageInfo, $minlength, callDB);
});
