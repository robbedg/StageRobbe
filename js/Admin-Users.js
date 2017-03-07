/**
 * Created by Robbe on 23/02/2017.
 */
$(document).ready(function($) {

// /**
//  * USERS
//  **/
//   //all users available, users currently displayed on screen.
//   var $allusers = [];
//   var $currentuser = [];
//
//   //Grabs all users from page.
//   $(".users").each(function() {
//     var $name = $(this).attr('search');
// 		$allusers.push({name: $name, user: $(this)});
//   });
//
//   $currentusers = $allusers.slice();
//
//   /**
//    * Events
//    **/
//
//  /** Search **/
//   $("#searchbox").keyup(function(){
//     try {
//       var $search = $("#searchbox").val();
//       $.each($currentusers, function ($i, $val) {
//
//         //make all lowercase & delete all spaces
//         if (!$val.name.toLowerCase().replace(/ /g,'').match($search.toLowerCase().replace(/ /g,''))) {
//           $val.user.hide();
//         }
//         else {
//           $val.user.show();
//         }
//       });
//     } catch(e) {
//       console.log(e);
//     }
//   });
//
//   /** Filter **/
//   $(".filterlist").click(function($event) {
//     /* Act on the event */
//     //prevent default
//     $event.preventDefault();
//     $filter = $(this);
//
//     //clean search
//     $("#searchbox").val("");
//
//     //filter out correct roles
//     try {
//
//       //remove or add user where appropriate
//       $.each($allusers, function($i, $val) {
//         if ($filter.attr('data-id').match('0')) {
//
//           $currentusers = $allusers.slice();
//           $val.user.show();
//
//         } else if (!($val.user.attr('data-id').match($filter.attr('data-id')))) {
//
//           var $x = $currentusers.indexOf($val);
//
//           //if in array, remove
//           if ($x !== -1) {
//
//             $val.user.hide();
//             $currentusers.splice($x, 1);
//
//           }
//         } else {
//
//           var $x = $currentusers.indexOf($val);
//
//           //if not already in array, push
//           if ($x === -1) {
//             $currentusers.push($val);
//             $val.user.show();
//           }
//         }
//       });
//
//       //set button active / not-active
//       $("#filter li").each(function() {
//         $(this).removeClass('active');
//       });
//
//       $filter.parent().addClass('active');
//
//       //log error(s)
//     } catch ($e) {
//       console.log($e);
//     }
//   });
//
//   /** User clicked **/
//   $(".users input").click(function($event) {
//     //get clicked user
//     var $user = $(this);
//
//     //activate role selector
//     $("#roleselect select").removeAttr('disabled');
//
//     //set selected role.
//     try {
//       $("#roleselect select option").each(function() {
//         if ($(this).val() === $user.parent().attr('data-id')) {
//           $(this).attr('selected', 'selected');
//         } else {
//           $(this).removeAttr('selected');
//         }
//       });
//     } catch ($e) {
//       console.log($e);
//     }
//
//     //set hidden field id
//     $("#userid input").val($user.val());
//
//     //set firstname
//     $("#firstname input").removeAttr('disabled');
//     $("#firstname input").val($user.parent().attr('data-firstname'));
//
//     //set lastname
//     $("#lastname input").removeAttr('disabled');
//     $("#lastname input").val($user.parent().attr('data-lastname'));
// 
//     //activate button
//     $("#submit button").removeAttr('disabled');
//   });

/**
 * DELETED ITEMS
 **/
//DataTable
 $('#table-deleted').DataTable({
   'paging': false,
   'searching': false,
   'info': false,
   'columnDefs' : [
     {
       'targets' : [ 4 ],
       'orderable' : false
     }
   ]
 });

//restore & delete
  $("#table-deleted-items table tbody tr td a").click(function($event) {
    $event.preventDefault();

    var $item = $(this);
    //restore
    if ($item.attr('data-function').match('restore')) {
      $.ajax({
        url: '/index.php/items/restore/' + $item.attr('data-id'),
        type: 'GET'
      })
      .done(function() {
        $item.parent().parent().remove();
      });
    //Delete
    } else if ($item.attr('data-function').match('delete')) {
      $.ajax({
        url: '/index.php/items/delete/' + $item.attr('data-id'),
        type: 'GET'
      })
      .done(function() {
        $item.parent().parent().remove();
      });
    }
  });

/**
 * LOCATIONS
 **/
   //DataTable
   $("#table-locations").DataTable({
     'paging' : false,
     'searching' : false,
     'info' : false,
     'columnDefs' : [
       {
         'targets' : [ 2 ],
         'orderable' : false
       }
     ]
   });

   //Save & delete
   $("#table-locations tbody tr td a").click(function($event) {
     //prevent default
     $event.preventDefault();

     var $item = $(this);

     //Save
     if ($item.attr('data-function').match('save')) {
       $.ajax({
         url: '/index.php/locations/update/' + $item.attr('data-id'),
         type: 'POST',
         data: {'name' : $("#" + $item.attr('identifier')).val()}
       })
       .done(function() {

       });
      //remove
    } else if ($item.attr('data-function').match('delete')) {
       $.ajax({
         url: '/index.php/locations/delete/' + $item.attr('data-id'),
         type: 'GET'
       })
       .done(function() {
         $item.parent().parent().remove();
       });
     }
   });
/**
  * CATEGORIES
  **/

  //DataTable
   $("#table-categories").DataTable({
     'paging' : false,
     'searching' : false,
     'info' : false,
     'columnDefs' : [
       {
         'targets' : [ 2 ],
         'orderable' : false
       }
     ]
   });

   //Save & delete
   $("#table-categories tbody tr td a").click(function($event) {
     //prevent default
     $event.preventDefault();

     var $item = $(this);

     //Save
     if ($item.attr('data-function').match('save')) {
       $.ajax({
         url: '/index.php/categories/update/' + $item.attr('data-id'),
         type: 'POST',
         data: {'name' : $("#" + $item.attr('identifier')).val()}
       })
       .done(function() {

       });
      //remove
     } else if ($item.attr('data-function').match('delete')) {
       $.ajax({
         url: '/index.php/categories/delete/' + $item.attr('data-id'),
         type: 'GET'
       })
       .done(function() {
         $item.parent().parent().remove();
       });
     }
   });
});
