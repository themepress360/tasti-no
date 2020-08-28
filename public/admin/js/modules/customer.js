function  addCustomerCallback(response)
{
	//console.log(response);
	toastr['success']('Customer added  successfully');
	window.location = dashboard_url + 'customers' ;	
}

function  editCustomerUserCallback(response)
{
	toastr['success']('Edit Customer User added  successfully');
	window.location = '';	
}

function  addCustomerUserCallback(response)
{
	toastr['success']('Add Customer User added  successfully');
	window.location = '';	
}
function CustomerConfirmedCallback(response)
{
	toastr['success']('Customer Confirmed successfully');
	window.location = '';	
}
function CustomerRejectedCallback(response)
{
	toastr['success']('Customer Rejected successfully');
	window.location = '';	
}
