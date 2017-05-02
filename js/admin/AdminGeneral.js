"use strict";

$("#database_lock").change(function () {
  var $data = new Object();
  $data.database_lock = document.getElementById('database_lock').checked;

  //Set false if not checked
  if ($data.database_lock !== true) {
    $data.database_lock = false;
  }

  //ajax call
  $.ajax({
    url: '/index.php/settings/set',
    type: 'POST',
    dataType: 'json',
    contentType: 'application/json',
    data: JSON.stringify($data)
  }).done(function($response) {
      //none
  }).always(function() {
      //none
  });
});
