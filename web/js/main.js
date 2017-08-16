$(function(){
	$('#modalButton').click(function(){
		$('#task-modal').modal('show')
				   .find('#modalContent')
				   .load($(this).attr('value'));
	});

	$('.language').click(function(){
		var lang = $(this).attr('id');		
		$.post('index.php?r=tasks/language', {'lang':lang},function(data){
			location.reload();
		})
	});
});