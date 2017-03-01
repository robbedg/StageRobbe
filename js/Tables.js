"use strict";
$(document).ready(function(){

    var $data = new Object();
    $data.limit = 10;
    $data.offset = 0;

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
    });
});
