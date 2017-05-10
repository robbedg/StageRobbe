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
    //give location id
    $data.location_id = $("#location_id").val();
    //give category id
    $data.category_id = $("#category_id").val();
    //give attributes
    $data.attributes = true;

    //check for user preferences
    checkLocalStorage($data, 'items');
    //and set the values on the page
    setValues($data);

    //add extra attribute
    $("#listingpage thead tr").append($('<th />').append('Attributes'));

    //add extra button
    $('<a href="#" class="btn btn-primary" id="print-button" />')
      .insertAfter("h2")
      .append('<span class="fa fa-print" />')
      .css('float', 'right')
      .css('margin-top', '-4.2rem');

    //add hidden div
    $("#wrapper").append('<div id="qrcode" class="hidden" />'),

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
            append($('<tr class="clickable-row" href="/index.php/items/view/' + $el['id'] + '"/>')
              .append($('<td />').append($el['id']))
              .append($('<td />').append($el['name']))
              .append($('<td />').append($el['created_on']))
              .append($('<td />').append($('<textarea readonly class="form-control" rows="3" id="textArea" />')))
            );

           //add attributes
           var $i = 0;
           $.each($el['attributes'], function ($key, $val) {
              $("#listingpage tbody").find('textarea').last()
                .append(($i === 0 ? '' : '\n') + $key + ': ' + $val);
                $i++;
           });

        });
        calculatepages($data, $pageInfo, $response.count);
      })
      .always(function() {
        clickablerow();
        loadpager($pageInfo);
        pagingbuttons($data, $pageInfo, callDB);
      });
    }

    //load events
    loadEvents($data, 'items', $pageInfo, $minlength, callDB);

    //print page
    $("#print-button").click(function($event) {
      //prevent default
      $event.preventDefault();


      //ajax call
      $.ajax({
        url: '/index.php/items/get',
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify($data)
      })
      .done(function($response) {

        //get locations
        var $items = $response.data;


        var $codes = new Array();


        //fill db
        $($items).each(function($index, $el) {

          $("#qrcode").append($('<div />').attr('class', 'codes').attr('id', 'code_' + $index).attr('data-name', $el['name']));
          new QRCode('code_' + $index, {width: 75, height: 75, correctLevel: QRCode.CorrectLevel.H, useSVG: true, text: $el['id']});

          $("#code_" + $index).removeAttr('title');

          //if last go on
          if ($index === $items.length - 1) {
              printInPopUp($codes);
          }
        });


      });

      function printInPopUp($codes) {
        //create string
        var $string = '';
        $string += '<style> body { margin: 0px; }</style>';
        //wait 1 sec until al QR codes are drawn
        setTimeout(function () {
          $(".codes").each(function($index, $code) {
            $($code).find('img')
              .css('display', 'inline-block');

            var $label = $('<div />').append(
              $('<div />')
                .append($code.innerHTML)
                .append($('<p />').append(
                  $($code).attr('data-name').toUpperCase()
                )
                .css('font-family', 'arial, sans-serif')
                .css('display', 'inline-block')
                .css('float', 'right')
                .css('word-break', 'break-all')
                .css('overflow', 'hidden')
                .css('text-overflow', 'clip')
                .css('width', '105px')
                .css('height', '55px')
                .css('padding-left', '10px')
                .css('margin-top', '10px')
                .css('margin-bottom', '10px')
              )
              .css('position', 'relative')
              .css('width', '190px')
              .css('padding-top', '10px')
              .css('padding-left', '10px')
            ).html();

            $string += $label;
          });

          //open window
          var $popup = window.open();
          $popup.document.write($string);
          $popup.focus();

          //no automatic printing for IE
          if (!(/*@cc_on!@*/false || !!document.documentMode)) {
            $popup.print();
            $popup.close();
          }
          //delete
          $("#qrcode").empty();

        }, 1000);
      }
    });
});

//set pause
function pause(milliseconds) {
	var dt = new Date();
	while ((new Date()) - dt <= milliseconds) { /* Do nothing */ }
}
