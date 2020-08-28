$(document).ready(function() {
	$('#changeStatus a').click(function(){
		$('#status').val($(this).attr('data-value'));
		doAjax('admin/changeComplainStatus','changeStatusForm',function(){
			toastr['success']('Status  successfully Updated');
			window.location = "";//dashboard_url + "inbox";	
		});
	});

	$('#changePriority a').click(function(){
		$('#priority').val($(this).attr('data-value'));
		doAjax('admin/changeComplainPriority','changeStatusForm',function(){
			toastr['success']('Priority  successfully Updated');
			window.location = "";
		});
	});
	
	$('#checkbox1').change(function(){
		if(this.checked)
		{
			$('#changeStatusBtn').attr('disabled','disabled');
			$('#status').val('resolved');
			var request = 1;
		}
		else
		{
			$('#changeStatusBtn').removeAttr('disabled');
			$('#status').val('replied');
			var request = 0;
		}
		
		doAjax('admin/changeComplainStatus','changeStatusForm',function(){
			if(request==1)
				toastr['success']('Request successfully sent');
			else
				toastr['success']('Request successfully removed');
			//window.location = "";//dashboard_url + "inbox";	
		});				
	});
		
	$('.image-popup').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-fade',
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		}
	});
});

function replayCallback(response){
	//console.log(response);
	toastr['success']('Reply sent to user  successfully');	
	window.location =  "#message_"+response['data']['id'];
	location.reload();
}

function replayPreset()
{
	setTimeout(function(){ $('#replyForm')[0].reset();},10);
}