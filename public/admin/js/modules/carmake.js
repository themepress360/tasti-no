function  addCarMakeCallback(response)
{
	//console.log(response);
	toastr['success']('Car Make added  successfully');
	window.location = dashboard_url + 'carMake' ;
}


function  editCarMakeCallback(response)
{
	//console.log(response);
	toastr['success']('Car Make updated  successfully');
	window.location = dashboard_url + 'carMake' ;
}

function statusChangeCallback(response)
{
	//console.log(response);
	toastr['success']('Car Make Status updated  successfully');
}

function setIframe(make_id)
{
	$('#carModelsIframe').html('');
	$('#carModelsIframe').html('<iframe src="'+dashboard_url + 'vehicleModels/'+make_id+'"  width="100%" height="450" frameborder="0" ></iframe>');
}