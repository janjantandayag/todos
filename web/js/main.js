$(function(){
	$('#modalButton').click(function(){
		$('#task-modal').modal('show')
				   .find('#modalContent')
				   .load($(this).attr('value'));
	});

});