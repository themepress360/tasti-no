$(function(){
	$('.entryCheck').change(function(){
		$('.entryCheck').each(function(index,ele){
		if(!$(ele).prop('checked'))
		{
			$('#agd2').prop('checked',false);
			return false;
		}
		else
		{
			$('#agd2').prop('checked',true);
		}
			
	})	
	});
	
});

function  addAdminCallback(response)
{
	//console.log(response);
	toastr['success']('Admin added  successfully');
	window.location = dashboard_url + 'admin' ;	
}

function editAdminCallback(response)
{
	//console.log(response);
	toastr['success']('Admin updated  successfully');
	window.location = dashboard_url + 'admin' ;	
}

function statusChangeCallback(response)
{
	//console.log(response);
	toastr['success']('Admin Status updated  successfully');
}