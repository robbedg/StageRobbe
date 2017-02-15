/**
 * Created by Robbe on 15/02/2017.
 */
Dropzone.autoDiscover = false;
$(document).ready(function(){

	$("#dropzone").dropzone({
		paramName: 'file',
		maxFilesize: 2,
		maxFiles: 1,
		success: function($file, $response) {
			document.write($response);
		}
	});

});
