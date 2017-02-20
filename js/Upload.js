/**
 * Created by Robbe on 15/02/2017.
 */
Dropzone.autoDiscover = false;
$(document).ready(function(){

	$("#dropzone").dropzone({
		paramName: 'file',
		maxFilesize: 2, //MB
		maxFiles: 1,
		addRemoveLinks: true,
		acceptedFiles: 'image/*',
		dictDefaultMessage: 'Drop image here to upload.<br/>Or click to browse.',
		success: function($file, $response) {
			this.removeAllFiles();
			$(".modal").modal('toggle');
			$(".modal").modal('hide');
			$("#successdiv").removeClass('hidden');
			setTimeout("$('#successdiv').addClass('hidden');", 3000);
		}
	});

});
