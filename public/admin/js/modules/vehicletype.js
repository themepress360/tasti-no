function  addVehicleTypeCallback(response)
{
	//console.log(response);
	toastr['success']('Vehicle Type added  successfully');
	window.location = dashboard_url + 'vehicleType' ;
}


function  editVehicleTypeCallback(response)
{
	//console.log(response);
	toastr['success']('Vehicle Type updated  successfully');
	window.location = dashboard_url + 'vehicleType' ;
}