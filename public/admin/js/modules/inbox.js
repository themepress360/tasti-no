$(document).ready(function() {
	$('#prioritySel a').click(function(){
		$('#p').val($(this).attr('data-value'));
		$('#srchForm').submit();		
	});
		
});