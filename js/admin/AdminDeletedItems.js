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
    $data.deleted = true;
    $data.location = true;
    $data.category = true;
    //use db names
    $data.sort_on = {'column' : 'id', 'order' : 'asc'};

    //check for user preferences
    checkLocalStorage($data, 'admin-deleted-items');
    //and set the values on the page
    setValues($data);

    //call db first
    callDB();

    //load DB
    function callDB() {

      //ajax call
      $.ajax({
        url: '/index.php/items/get',
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
        var $items = $response.data;
        //fill db
        $($items).each(function($index, $el) {
          $("#listingpage tbody").
            append($('<tr />')
              .append($('<td />').append($el['id']))
              .append($('<td />').append($el['location']))
              .append($('<td />').append($el['category']))
              .append($('<td />').append($el['created_on']))
              .append($('<td />')
                .append($('<a href="#" data-function="restore" />').addClass('btn btn-success btn-sm').append('<span class="fa fa-undo"></span>').attr('data-id', $el['id']))
                .append(' ')
                .append($('<a href="#" data-function="delete" />').addClass('btn btn-danger btn-sm').append('<span class="fa fa-trash"></span>').attr('data-id', $el['id']))
                .addClass('align-right')
              )
            );
        });
        calculatepages($data, $pageInfo, $response.count);
      })
      .always(function() {
        restore_delete();
        loadpager($pageInfo);
        pagingbuttons($data, $pageInfo, callDB);
      });
    }

    //load events
    loadEvents($data, 'admin-deleted-items', $pageInfo, $minlength, callDB);

    /**
     * RESTORE & DELETE
     **/
     function restore_delete() {
       $("#deleted-items table tbody tr td a").click(function($event) {
         $event.preventDefault();

         var $item = $(this);
         //restore
         if ($item.attr('data-function').match('restore')) {
           $.ajax({
             url: '/index.php/items/restore/' + $item.attr('data-id'),
             type: 'GET'
           })
           .done(function() {
             callDB();
           });
         //Delete
         } else if ($item.attr('data-function').match('delete')) {
           $.ajax({
             url: '/index.php/items/delete/' + $item.attr('data-id'),
             type: 'GET'
           })
           .done(function() {
             callDB();
           });
         }
       });
     }
});
