$(document).ready(function() {
	$('#block-selected,#hakko-credit,#hakko-logo').disableTextSelect();	
	$("#hakko-credit").noContext();
			
	//$('.textbox').enableTextSelect();
	$.ajaxSetup({ 
		type: 'GET', 
		dataType: 'json',
	});	
});