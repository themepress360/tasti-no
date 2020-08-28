var canvas_url = base_url;
var dashboard_url = canvas_url + "administration/";
//var api_url = canvas_url + "api/v1/";
var api_url = canvas_url;
var global_arr = [];


$.ajaxSetup({
  	global: false,
	processData: false,
	contentType: false,
  	type: "POST"  
});

/*********** Generate Password ********/

$.random = function(min, max) {
    return min + parseInt(Math.random() * (max - min + 1), 10);
}
/*
$.unique = function(min, max) {
    return Math.random().toString(36).substr(min, max);
}
*/
$.generatePassword = function() {
    var length = 12,
        charset = 'AaBbCcDdEeFfGgHhiJjKkLMmNnoPpQqRrSsTtUuVvWwXxYyZz23456789!?$%#&@+-*=_.,:;(){}',
        password = "";
    while (length > 0) {
        length -= 1;
        password += charset[$.random(0, charset.length - 1)];
    }
    return password;
}
$.generateCode = function() {
    var length = 6,
        charset = 'ABCDEFGHKMNPQRSTUVWXYZ23456789',
        password = "";
    while (length > 0) {
        length -= 1;
        password += charset[$.random(0, charset.length - 1)];
    }
    return password;
}

$.getNewPassword = function() {
    $('#user_password').val($.generatePassword());
}

/**************** Current time interval *************/
/*  ([\d]+:[\d]{2}) - Hour:Minute
    (:[\d]{2}) - Seconds
    (.*) - the space and period (Period is the official name for AM/PM)
    Then it displays the 1st and 3rd groups.*/

$.currentTime = function(id) {
    var time = new Date().toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
    $(id).html(time);
}

/**************** pagination function *************/

$.pagination = function(pagination) {
    var paginationHtml = '';
    if (pagination.last > 1) {
        if (pagination.next == null) {
            paginationHtml += '<span class="btn btn-default right"><a href="javascript:;">Next <i class="glyphicon size1 glyphicon-chevron-right"></i></a></span>';
        }
        if (pagination.next > 0 && (pagination.next <= pagination.last)) {
            paginationHtml += '<span class="btn btn-primary right"><a href="javascript:;" data-page="' + pagination.next + '" class="target-page">Next <i class="glyphicon size1 glyphicon-chevron-right"></i></a></span>';
        }
        if (pagination.previous != pagination.current) {
            paginationHtml += '<span class="btn btn-primary right margin-right-10px"><a href="javascript:;" data-page="' + pagination.previous + '" class="target-page"><i class="glyphicon size1 glyphicon-chevron-left"></i> Previous</a></span>';
        }
        if (pagination.previous == pagination.current) {
            paginationHtml += '<span class="btn btn-default right margin-right-10px"><a href="javascript:;"><i class="glyphicon size1 glyphicon-chevron-left"></i> Previous</a></span>';
        }
    } else {
        paginationHtml += '<span class="btn btn-default right"><a alt="Disable" title="Disable" href="javascript:;">Next <i class="glyphicon size1 glyphicon-chevron-right"></i></a></span> <span class="btn btn-default right margin-right-10px"><a alt="Disable" title="Disable"  href="javascript:;"><i class="glyphicon size1 glyphicon-chevron-left"></i> Previous</a></span>';
    }
    $('#pagination-holder').html(paginationHtml);
}


toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-center",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}
/*
//script
toastr['success']('message..');
toastr['info']('message..');
toastr['warning']('message..');
toastr['error']('message..');
*/

/**************** remove error *************/

$.removeError = function(id) {
	var color = $(id).attr('brcolr')!=null?$(id).attr('brcolr'):'#eee';
    if($(id).hasClass('chk') || $(id).hasClass('emailchk'))
		$(id).css('border', 'solid thin ' + color);
}

/**************** remove all errors *************/

$.removeAllError = function(idstr) {
    var idarr = idstr.split(",");
    idarr.reverse();
    for (i in idarr) {
        if ($('#' + idarr[i]).val().trim() == '') {
            $.removeError('#' + idarr[i]);
        }
    }
}

/**************** focus on error *************/

$.focusError = function(id) {
    $(id).focus();
    $(id).css('border', 'solid thin red');
}

/**************** Message div show *************/


/**************** Get form elements ids *************/

$.getFormElementsIds = function(formId) {
    var ids = Array();
    var elements = document.getElementById(formId).elements;
    var size = elements.length;
    for (i = 0; i < size; i++) {
        ids[i] = elements[i].getAttribute('id');
    }
    return ids;
}

/**************** Validate Number Feild *************/

$.numKeyValidate = function(evt, type) {
    type = type == null || type == '' ? 'n' : type;
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    if (evt.ctrlKey == true && (key == 118 || key == 99 )) {
        return true;
    } else if(key == 32 && (type=='alphabet' || type=='alphadot')){
        return true;
    }else if (key != 8 && key != 9 && key != 37 && key != 39 ) {
        key = String.fromCharCode(key);
        if (type == 'n') var regex = /[0-9]/;
        else if (type == 'alphabet') var regex = /^([A-Za-z]|\s)+$/;
        else if (type == 'alphadot') var regex = /^([A-Za-z]|\.|\s)+$/;
        else if (type == 'alphanumericdot') var regex = /^([0-9A-Za-z]|\.|\s)+$/;
        else if (type == 'alphanumericdash') var regex = /^([0-9A-Za-z]|-)+$/;     
        else if (type == '.') var regex = /[0-9]|\./;
        else if (type == 'n-') var regex = /^([0-9]|-)+$/;
        else var regex = /[0-9]|-|\+/;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }
}

$.numStringValidate = function(str, type) {
    if (type == 'n') var regex = /^([0-9])+$/;
    else if (type == 'alphabet') var regex = /^([A-Za-z]|\s)+$/;
    else if (type == 'alphadot') var regex = /^([A-Za-z]|\.|\s)+$/;
    else if (type == 'alphanumericdot') var regex = /^([0-9A-Za-z]|\.|\s)+$/;
    else if (type == 'alphanumericdash') var regex = /^([0-9A-Za-z]|-)+$/;
    else if (type == '.') var regex = /^([0-9]|\.)+$/;
    else if (type == 'n-') var regex = /^([0-9]|-)+$/;
    else var regex = /^([0-9]|-|\+)+$/;
    if (!regex.test(str)) return false;
    else return true;
}


/**************** Remove the error class from all text , textarea and select feilds*************/

$.errorClassOutToAllInput = function() {
    $('input,textarea,select').change(function() {
        $.removeError(this)
    }).keypress(function() {
        $.removeError(this)
    });
}

/**    Description: field validation function (comma seperated form elements ids given as a param like 'name,address,phone,email') **/

$.validateEle = function(idstr) {
	$('input,textarea,select').each(function(){$.removeError(this)});
    var idarr = idstr.split(",");
    idarr.reverse();
    var chk = true;
    for (i in idarr) {
		if($('#' + idarr[i]).hasClass('chk'))
		{
			if ($.trim($('#' + idarr[i]).val()) == '') {
				$.focusError('#' + idarr[i]);
				chk = false;
			}
		}
		if($('#' + idarr[i]).hasClass('emailchk'))
		{
			if (!$.emailChk($.trim($('#' + idarr[i]).val()))) {
				$.focusError('#' + idarr[i]);
				chk = false;
			}
		}			
    }
    return chk;
}

function logout_callback(response){
	location = dashboard_url;
	//location = dashboard_url;
}


function change_location()
{
	location = dashboard_url;
}


function doAjax(method,formId,callback){
	
	if (access_token !='') {
		var params = '?access_token='+access_token ;
	} else {
		var params = '';
	}
	$("#spinnerMain").show();	
	var form = $('#'+formId).get(0);
	var formData = new FormData(form);
	$.post(api_url+method+params,
						formData,
						function(data){
							$("#spinnerMain").hide();
							if(data.status == 'FAILED')
								{
									$('#spinner').hide();
									$('#paybtn').show();
									$('#unpaybtn').hide();													
									toastr['error'](data.message);
								}
							else
								{
								  if(callback==null)
								  {
									if(data.message!=null)toastr['success'](data.message);
								  }
								  else
								  {
									  callback(data);
								  }
								}	
	
						}) .fail(function( jqXHR, textStatus, errorThrow) {$("#spinnerMain").hide();toastr['error']( jqXHR.responseJSON.message );});					
		
}



function ajax()
{
		var method = $(this).attr('method');
		var formId = $(this).attr('formid');
		var callback = $(this).attr('callback');
		var prefunction = $(this).attr('prefunction');
		var validate = $(this).attr('validate');

	if (access_token !='' && false) {
		var params = '?access_token='+access_token ;
	} else {
		var params = '';
	}
		if(validate!=null)
		{
			 if(!$.validateEle($.getFormElementsIds(formId).join()))
				return false;
		}
	
		if(method!=null)
		{
			$("#spinnerMain").show();
			
			// find object
			var fnp = window[prefunction];
			// is object a function?
			if (typeof fnp === "function") fnp.apply(null);  
						
			if(formId==null)
			{
				$.post(api_url+method+params,
					function(data){
						$("#spinnerMain").hide();
						if(data.status == 'FAILED')
							{
								$('#spinner').hide();
								$('#paybtn').show();
								$('#unpaybtn').hide();					
								toastr['error'](data.message);
							}
						else
							{
							  if(callback==null)
							  {
							  	if(data.message!=null)toastr['success'](data.message);
							  }
							  else
							  {
								var fnparams = [data];
								// find object
								var fn = window[callback];
								// is object a function?
								if (typeof fn === "function") fn.apply(null, fnparams);  
							  }
							}
					})  .fail(function( jqXHR, textStatus, errorThrow) {$("#spinnerMain").hide();toastr['error'](  jqXHR.responseJSON.message );});			
			}
			else
			{
				var form = $('#'+formId).get(0);
				var formData = new FormData(form);
				//console.log(formData);
				$.post(api_url+method+params,
					//$( '#'+formId ).serialize(),
					formData,
					function(data){
						$("#spinnerMain").hide();
						if(data.status == 'FAILED')
							{
								$('#spinner').hide();
								$('#paybtn').show();
								$('#unpaybtn').hide();													
								toastr['error'](data.message);
							}
						else
							{
							  if(callback==null)
							  {
							  	if(data.message!=null)toastr['success'](data.message);
							  }
							  else
							  {
								var fnparams = [data];
								// find object
								var fn = window[callback];
								// is object a function?
								if (typeof fn === "function") fn.apply(null, fnparams);
							  }
							}	

					}) .fail(function( jqXHR, textStatus, errorThrow) {$("#spinnerMain").hide();toastr['error']( jqXHR.responseJSON.message );});					
			}
		}
		else
			console.log('method is not defined ' + formId);
	}


function deletePopup()
{
	$('.delpop').click(function(){
		
		var method = $(this).attr('method');
		console.log(method);
		if(method != null)
		{
			$('.delPopupInput').remove();	
			if($(this).attr('form-data')!=null)
			{
				delformData = jQuery.parseJSON( $(this).attr('form-data') );
				for(name in delformData)
				{
					$('#delete_popup_form').append('<input type="hidden" name="'+name+'" class="delPopupInput" value="'+delformData[name]+'"/>');
				}
				
			}
			if($(this).attr('message')!=null)$("#delete_txt").html($(this).attr('message'));
			$(".ajax_delete_btn").attr('method',method);
			if($(this).attr('callback')!=null)$(".ajax_delete_btn").attr('callback',$(this).attr('callback'));
			$.fancybox({
					'content' : $("#delete_container").html()
				});
				
			$(".ajax_delete_btn").click(ajax);	
		}
		else
		console.log('method is not define!');
	});	
}

$(document).ready(function(){	
	$('#gallery_image, #bus_logo').css({'display':'block','opacity':'0.1','position':'absolute','top':'-5000px','left':'-5000px'});
	$("a[title='Close']").click(function(){$("form")[0].reset()});
		
	$.errorClassOutToAllInput();
	
	$(".ajax").click(ajax);
	
	$("form").submit(function(){
		var formid = $(this).attr('id');	
		$("#"+formid+" .ajax").trigger('click');
		return false;	
	});
	
	// numeric key validation
	$('.numchk').keypress(function(e) {$.numKeyValidate(e, '.')});	

	deletePopup();
	
	jQuery("img").one('error', function () {
		jQuery(this).attr("src", canvas_url+"public/images/default_profile_pics/item_default_img.png"); //.unbind("error") is useless here
	}).each(function () {
		if (this.complete && !this.naturalHeight && !this.naturalWidth) {
			$(this).triggerHandler('error');
		}
	});
	$('.modal').on('hidden.bs.modal', function () {$(this).find("form")[0].reset();$('input,textarea,select').each(function(){$.removeError(this)});})
	$('.deletePopup').click(function(){
		var delmethod = $(this).attr('method');
		var delformid =  $(this).attr('formid');
		var buttontxt =  $(this).attr('popup-btn');
		var message =  $(this).attr('popup-msg');
		swal({   
			title: "Are you sure?",   
			text: message!=''?message:"You will not be able to recover this!",   
			type: "warning",
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: buttontxt!=''?buttontxt:"Yes, delete it!",   
			closeOnConfirm: false 
		}, function(){
			doAjax(delmethod,delformid,deleteCallback);	 
		});
	});
});

function deleteCallback(response)
{
	swal("Done!", "Succesfully Done...", "success");
	location = "";
}




function readURL(ele,id) {	
	var file = $('form #'+ele.id)[0].files;
	if (file){
        var reader = new FileReader();
        reader.onload = function (e) {$('#'+id).attr('src', e.target.result);}
        reader.readAsDataURL(file[0]);
    }
}


function lessCount(ele,id,total)
{
	var reminder = total - ele.value.length;
	if(reminder>0) $('#'+id).html(reminder); else  $('#'+id).html(0); 
}


function addNum(id,operation,num)
{
	var val = $('#'+id).val();
	
	if(operation == 'minus' && val<2)
		return false;
			
	if(operation == 'sum')
		$('#'+id).val(parseInt(val) + num); 
	else
		$('#'+id).val(parseInt(val) - num); 		
}

function share(type,data)
{   
      // console.log(data);
		var	shareUrl = '';
		var fbShareUrl= canvas_url+data.pagename;
		var title = data.title;
		var description = data.metadescription;
		var siteurl = canvas_url+data.pagename;
		var image = data.image;
		
	if(type == 'facebook'){
	
		var	shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + fbShareUrl;
		
	}
	else if(type == 'twitter'){
		var	shareUrl = 'https://twitter.com/intent/tweet?url='+fbShareUrl+'&via='+'womloyalty.com'+'&text='+title;
	}
	else if(type == 'printest'){
		var	shareUrl = 'https://pinterest.com/pin/create/button/?url='+fbShareUrl+'&media='+image+'&description='+description;
	}
	else if(type == 'googleplus'){
		var	shareUrl = 'https://plus.google.com/share?url='+fbShareUrl+'&gpsrc=frameless&btmpl=popup#identifier';
	}
	newwindow=window.open(shareUrl,type + 'Share','height=480,width=640');
	if (window.focus) {newwindow.focus()}
	return false;	
	
}


$.emailChk = function(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function createCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}