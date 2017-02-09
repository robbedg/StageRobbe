$(document).ready(function(){
	var $rows=[];
	$(".table > tbody > tr").each(function () {
		var $id = $(this).attr('id');
		var $index = $id.indexOf('_') + 1;
		var $name = $id.substr($index);
		$rows.push({name: $name, item: $(this)});
	});

    $(".search").keyup(function(){
		try {
			var $search = $('.search').val();
			$.each($rows, function ($i, $val) {

				if (!$val.name.toLowerCase().match($search.toLowerCase())) {
					$val.item.remove();
				}
				else {
					$('.table > tbody').append($val.item);

				}
			});
		} catch(e) {
			console.log(e);
		}

		var $url = window.location.href;
		var $index = $url.indexOf('.php');
		$url = $url.substring(0, $index);
		$index = $url.lastIndexOf('/');
		$url = $url.substring(0, $index) +'/js/scripts.js';
		//reload script
		$.getScript($url);
    });

});
