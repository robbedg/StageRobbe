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
    $data.user_id = $("#user_id").val();
    $data.item = true;
    $data.class = true;
    //use db names
    $data.sort_on = {'column' : 'until', 'order' : 'desc'};

    //check for user preferences
    checkLocalStorage($data, 'loans-user');
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
              .append($('<td />').append($('<a href="/index.php/items/view/' + $el['item_id'] + '"/>').append($el['item_id'])))
              .append($('<td />').append($('<a href="/index.php/categories/' + $el['location_id'] + '"/>').append($el['location'])))
              .append($('<td />').append($('<a href="/index.php/items/' + $el['location_id'] + '/' + $el['category_id'] + '"/>').append($el['category'])))
              .append($('<td />').append($el['from_string']))
              .append($('<td />').append($el['until_string']))
              .append($('<td />').addClass('align-right').append(
                ($el['class'] === 'info' ? '<a href="#" data-toggle="tooltip" data-original-title="Verwijderen" class="btn btn-danger btn-sm" data-id="' + $el['id'] + '" data-function="delete"><span class="fa fa-trash" ></span></a>' : '') +
                ($el['class'] === 'success' ? '<a href="#" data-toggle="tooltip" data-original-title="Terugbrengen" class="btn btn-success btn-sm" data-id="' + $el['id'] + '" data-function="return"><span class="fa fa-undo" ></span></a>' : '')
              ))
            );
        });
        calculatepages($data, $pageInfo, $response.count);
      })
      .always(function() {
        //tooltip
        $('[data-toggle="tooltip"]').tooltip();
        //pagination buttons
        loadpager($pageInfo);
        pagingbuttons($data, $pageInfo, callDB);

        //load buttons
        loadButtons();
      });
    }

    //load events
    loadEvents($data, 'loans-user', $pageInfo, $minlength, callDB);

    //load buttons (Delete & Return)
    function loadButtons() {
      $("table tbody tr td a.btn").click(function($event) {
        $event.preventDefault();
        //set url
        var $url = '';
        var $id = $(this).attr('data-id');
        //check function
        if ($(this).attr('data-function').match('delete')) {
          $url = '/index.php/loans/delete/'
        }
        if ($(this).attr('data-function').match('return')) {
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
