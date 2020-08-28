function updateProfileCallback(response)
{
	//console.log(response);
	toastr['success']('Profile updated  successfully');
	window.location = dashboard_url + 'profile' ;
}
