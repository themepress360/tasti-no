function statusChangeCallback(response)
{
	//console.log(response);
	toastr['success']('Account Status updated  successfully');
}

function logincallback(response){
		//console.log(response);
	/*if($('#checkboxsignup').prop('checked'))
		var day = 365;
	else*/
	var day = 1;
	createCookie("user_access_token",response.access_token,day);
	location = base_url+'dashboard/';
}