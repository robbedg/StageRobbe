$(document).ready(function(){
    var $table = $('#listingpage').DataTable({
      "dom": ""
    });

    //search
    $("#search").keyup(function() {
      $table.search($(this).val()).draw();
    });

    /** pagination **/
    //previous page
    $("#previous").click(function() {
      $table.page('previous').draw('page');
    });

    //next page
    $("#next").click(function() {
      $table.page('next').draw('page');
    });

    //set paginator
    $(function() {
      var $info = $table.page.info();

      for (var $i = 1; $i < $info.pages; $i++) {
        $("<li />")
    			.attr('id', 'page_' + ($i + 1))
    			.insertAfter("#page_" + $i)
          .append('<a href="#">' + ($i + 1) + '</a>');
      }
    });

    //page buttons
    $(".pagination li a").click(function($event) {
      $event.preventDefault();
      $id = $(this).parent().attr('id');

      if ($id != null) {
        $index = $id.indexOf('_') + 1;
        $id = $id.substr($index);

        $table.page($id - 1).draw('page');
      }
    });
});
