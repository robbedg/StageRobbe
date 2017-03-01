"use strict";
$(document).ready(function(){

    var $data = new Object();
    $data.limit = 10;
    $data.offset = 0;
    console.log(JSON.stringify($data));
    callDB();

    function clickablerow() {
      $(".clickable-row").click(function() {
          window.document.location = $(this).attr("href");
      });
    }

    //fill db
    function callDB() {

      $.ajax({
        url: '/index.php/locations/get',
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify($data)
      })
      .done(function($response) {
        //get locations
        var $locations = $response.data;

        $($locations).each(function($index, $el) {
          $("#listingpage tbody").
            append($('<tr class="clickable-row" href="/index.php/items/location/' + $el['ID'] + '"/>')
              .append($('<td />').append($el['ID']))
              .append($('<td />').append($el['Name']))
              .append($('<td />').append($el['Amount Of Items'])));
          clickablerow();
        });


        console.log("success");
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
    }

    /**

    var $table = $('#listingpage').DataTable({
      'ajax': {
        'url' : '/index.php/locations/get',
        'dataType' : 'JSON',
        'type' : 'POST',
        'data' : JSON.stringify($data)
      },
      'columns' : [
        {'data' : 'ID'},
        {'data' : 'Name'},
        {'data' : 'Amount Of Items'}
      ]
    });*/
});
