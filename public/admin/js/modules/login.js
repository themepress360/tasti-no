function login_callback(response)
{
	//console.log(response);
	if($('#checkboxsignup').prop('checked'))
		var day = 365;
	else
		var day = 1;
	createCookie("admin_access_token",response.access_token,day);
	location = dashboard_url;
}


function forgot_callback(response)
{
	$('#con-close-modal').modal('hide');
	toastr['success'](response.message);
}