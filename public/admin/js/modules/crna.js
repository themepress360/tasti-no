function  addCrnaCallback(response)
{
	//console.log(response);
	toastr['success']('Customer added  successfully');
	window.location = dashboard_url + 'crnas' ;	
}

function  addCrnaLocationCallback(response)
{
	toastr['success']('Crna Location added  successfully');
	window.location = '' ;	
}

function addsetAvailabilityback(response)
{
	toastr['success']('Crna Scedule added  successfully');
	window.location = '' ;		
}
function SendEmailCallback(response)
{
	toastr['success']('Successfully Sent Crna Email');		
}

