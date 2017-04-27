"use strict";

$("#database_lock").change(function () {
  var $data = new Object();

  //ajax call
  $.ajax({
    url: '',
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
            .append($('<a href="#" data-function="save" />').addClass('btn btn-primary').append('<span class="fa fa-save"></span>').attr('data-id', $el['id']).attr('identifier', $index))
            .append(' ')
            .append($('<a href="#" data-function="delete" />').addClass('btn btn-danger').append('<span class="fa fa-trash"></span>').attr('data-id', $el['id']).attr('identifier', $index))
            .addClass('align-right')
          )
        );
    });
    calculatepages($data, $pageInfo, $response.count);
  })
  .always(function() {
    save_delete();
    loadpager($pageInfo);
    pagingbuttons($data, $pageInfo, callDB);
  });
});
