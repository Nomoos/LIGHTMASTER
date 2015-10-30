$(function(){
	$.nette.init();
	
	$(document).on('click', '.confirmation', function() {
		return confirm('Are you sure?');
	});
	
	$('.js-hide').hide();
});