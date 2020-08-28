$(function(){
	  $(".numberspin").TouchSpin();
})

function  addAgencyCallback(response)
{
	//console.log(response);
	toastr['success']('Agency added  successfully');
	window.location = dashboard_url ;	
}

function editAgencyCallback(response)
{
	//console.log(response);
	toastr['success']('Agency updated  successfully');
	window.location = dashboard_url ;	
}

function statusChangeCallback(response)
{
	//console.log(response);
	toastr['success']('Agency Status updated  successfully');
}

function login_callback(response)
{
	var day = 1;
	createCookie("agency_access_token",response.access_token,day);
	location = base_url+'agency-app-manager/';
}