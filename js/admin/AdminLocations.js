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

    //check for user preferences
    checkLocalStorage($data, 'admin-locations');
    //and set the values on the page
    setValues($data);

    //call db first
    callDB();

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
            append($('<tr />')
              .append($('<td />').append($el['id']))
              .append($('<td />').append($('<input type="text" class="form-control input-sm" />').val($el['name']).attr('id', $index)))
              .append($('<td />').append($el['item_count']))
              .append($('<td />')
                .append($('<a href="#" data-function="save" data-toggle="tooltip" data-original-title="Opslaan" />').addClass('btn btn-primary').append('<span class="fa fa-save"></span>').attr('data-id', $el['id']).attr('identifier', $index))
                .append(' ')
                .append($('<a href="#" data-function="delete" data-toggle="tooltip" data-original-title="Verwijderen" />').addClass('btn btn-danger').append('<span class="fa fa-trash"></span>').attr('data-id', $el['id']).attr('identifier', $index))
                .addClass('align-right')
              )
            );
        });
        calculatepages($data, $pageInfo, $response.count);
      })
      .always(function() {
        //tooltip
        $('[data-toggle="tooltip"]').tooltip();

        //events
        save_delete();
        loadpager($pageInfo);
        pagingbuttons($data, $pageInfo, callDB);
      });
    }

    //load events
    loadEvents($data, 'admin-locations', $pageInfo, $minlength, callDB);

    /**
     * SAVE & DELETE
     **/
     function save_delete() {
       $("#locations table tbody tr td a").click(function($event) {
        //prevent default
        $event.preventDefault();

        //button pushed
        var $item = $(this);

        //Save
        if ($item.attr('data-function').match('save')) {
          $.ajax({
            url: '/index.php/locations/update/' + $item.attr('data-id'),
            type: 'POST',
            data: {'name' : $("#" + $item.attr('identifier')).val()}
          })
          .done(function($response) {
              if ($response['success'] === false) {
                //show errors
                $("#errors").empty();
                $($response['errors']).each(function ($index, $el) {
                  $("#errors").append($('<p />').append($el));
                });

                //show for 3 seconds
                $("#errors").removeClass('hidden');
                setTimeout(function () {
                  $("#errors").addClass('hidden');
                  $("#errors").empty();
                }, 3000);
              }
              callDB();
          });

       //remove
       } else if ($item.attr('data-function').match('delete')) {
         //dialog
         $("#modal-submit").unbind();
         $("#modal-delete-location").modal('show');

         //submit
         $("#modal-submit").click(function($event) {
           //prevent default
           $event.preventDefault();

           //ajax call
           $.ajax({
             url: '/index.php/locations/delete/' + $item.attr('data-id'),
             type: 'GET'
           })
           .done(function() {
             //hide
             $("#modal-delete-location").modal('hide');
             //unbind
             $("#modal-submit").unbind();
             //DB
             callDB();
           });
         });
        }
       });
     }
});
