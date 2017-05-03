/**
 * For Admin panel, general application settings.
 **/

"use strict";

/* On load */
$(document).ready(function() {
   //get settings
   $.ajax({
     url: '/index.php/settings/get',
     type: 'GET'
   }).done(function($response) {
     //databse lock
     if ($response['database_lock']) $("#database_lock").attr('checked', 'checked');
     //registration
     if ($response['registration']) $("#registration").attr('checked', 'checked');
   });
});


$("#save a").click(function ($event) {
  //prevent defaultDate
  $event.preventDefault();

  //data for settings
  var $data = new Object();
  $data.database_lock = document.getElementById('database_lock').checked;
  $data.registration = document.getElementById('registration').checked;

  //Set false if not checked
  if ($data.database_lock !== true) $data.database_lock = false;
  if ($data.registration !== true) $data.registration = false;

  //ajax call
  $.ajax({
    url: '/index.php/settings/set',
    type: 'POST',
    dataType: 'json',
    contentType: 'application/json',
    data: JSON.stringify($data)
  }).done(function($response) {
    location.reload();
  });
});
