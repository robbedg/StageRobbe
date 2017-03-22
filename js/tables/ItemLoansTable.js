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
    $data.item_id = $("#item_id").val();
    $data.user = true;
    $data.class = true;
    //use db names
    $data.sort_on = {'column' : 'until', 'order' : 'desc'};

    //check for user preferences
    checkLocalStorage($data, 'loans-item');
    //and set the values on the page
    setValues($data);

    //call db first
    callDB();

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
              .append($('<td />').append($el['uid']))
              .append($('<td />').append($el['lastname'] + ' ' + $el['firstname']))
              .append($('<td />').append($el['from_string']))
              .append($('<td />').append($el['until_string']))
              .append($('<td />').addClass('align-right').append(
                (($el['class'] === 'info' && $el['user_id'] === $("#user_id").val()) ? '<a href="#" class="btn btn-danger btn-xs" data-id="' + $el['id'] + '">Delete</a>' : '') +
                (($el['class'] === 'success' && $el['user_id'] === $("#user_id").val()) ? '<a href="#" class="btn btn-success btn-xs" data-id="' + $el['id'] + '">Return</a>' : '')
              ))
            );
        });
        calculatepages($data, $pageInfo, $response.count);
      })
      .always(function() {
        loadpager($pageInfo);
        pagingbuttons($data, $pageInfo, callDB);
        
        //load buttons
        loadButtons();
      });
    }

    //load events
    loadEvents($data, 'loans-item', $pageInfo, $minlength, callDB);

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
          callDB();
        });
      });
    }
});
