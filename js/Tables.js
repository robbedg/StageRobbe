"use strict";
$(document).ready(function(){
    //search length
    var $minlength = 1;

    //data
    var $data = new Object();
    $data.search = null;
    $data.limit = 10;
    $data.offset = 0;

    //current page
    var $page = 1;
    //total amount of pages
    var $totalpages;

    //call db first
    callDB();

    //calculatepages
    function calculatepages($resultcount) {
      //total pages
      $totalpages = Math.floor($resultcount / $data.limit);
      //maybe extra page
      if (($resultcount % $data.limit) !== 0) $totalpages++;
    }

    //make rows clickable
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
          calculatepages($response.count);
        });
      })
      .fail(function() {
        console.log("error");
      });
    }

    //search
    $("#search").keyup(function($event) {
      if ($("#search").val().length >= $minlength) {
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
});
