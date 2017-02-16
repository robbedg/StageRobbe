/**
 * Created by Robbe on 15/02/2017.
 */
Dropzone.autoDiscover = false;
$(document).ready(function(){

	$("#dropzone").dropzone({
		paramName: 'file',
		maxFilesize: 2,
		maxFiles: 1,
		addRemoveLinks: true,
		acceptedFiles: 'image/*',
		dictDefaultMessage: 'Drop image here to upload.<br/>Or click to browse.',
		success: function($file, $response) {
			this.removeFile($file);
			$(".modal").modal('toggle');
			$(".modal").modal('hide');
		}
	});

});
