"use strict";
$(document).ready(function(){

    //data
    var $data = new Object();
    $data.search = '';
    $data.limit = 10;
    $data.offset = 0;

    //current page
    var $page = 1;
    
    //total pages
    var $totalpages = Math.floor($("#count").val() / $data.limit);
    //maybe extra page
    if (($("#count").val() % $data.limit) !== 0) $totalpages++;

    //call db first
    callDB();

    function clickablerow() {
      $(".clickable-row").click(function() {
          window.document.location = $(this).attr("href");
      });
    }

    //load DB
    function callDB() {


      //ajax call
      $.ajax({
        url: '/index.php/locations/get',
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
        var $locations = $response.data;
        //fill db
        $($locations).each(function($index, $el) {
          $("#listingpage tbody").
            append($('<tr class="clickable-row" href="/index.php/items/location/' + $el['ID'] + '"/>')
              .append($('<td />').append($el['ID']))
              .append($('<td />').append($el['Name']))
              .append($('<td />').append($el['Amount Of Items'])));
          clickablerow();
        });
      })
      .fail(function() {
        console.log("error");
      });
    }

    //search
    $("#search").keyup(function($event) {
      $data.search = $(this).val();
      callDB();
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
});
