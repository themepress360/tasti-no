function  addQuesCallback(response)
{
	//console.log(response);
	toastr['success']('Question added  successfully');
	window.location = dashboard_url + 'questions' ;
}


function  editQuesCallback(response)
{
	//console.log(response);
	toastr['success']('Question updated  successfully');
	window.location = dashboard_url + 'questions' ;
}