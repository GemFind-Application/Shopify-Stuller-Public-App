$(document).ready(function() {
   $("#search-diamonds-form #submit").trigger("click");
   $('.loading-mask.gemfind-loading-mask').css('display', 'none');
});

function formSubmit(e,url,id){
            
            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                modalClass: 'custom-modal',
                buttons: [],
                opened: function($Event) {
                    $(".modal-footer").hide();
                }
            };
            
            var dataFormHint = $('#'+id);

            dataFormHint.validate({
                rules: {        
                  name: {
                    required: true
                  },
                  email:{
                    required: true,
                    emailcustom:true,
                  },
                  recipient_name:{
                    required: true
                  },
                  recipient_email:{
                    required: true,
                    emailcustom:true,
                  },
                  gift_reason:{
                    required: true
                  },
                  hint_message:{
                    required: true
                  },
                  friend_name:{
                    required: true
                  },
                  friend_email:{
                    required: true,
                    emailcustom:true,
                  },
                  message:{
                    required: true
                  },
                  gift_deadline:{
                    required: true,

                  },
                  phone:{
                    required: true,
                    phoneno: true
                  },
                  location:{
                    required: true,

                  },
                  avail_date:{
                    required: true,
                  },
                  appnt_time:{
                    required: true,
                  },
                  contact_pref:{
                    required: true,
                  }
                },
                messages: {
                    gift_deadline: "Select the Gift Deadline.",
                    avail_date: "Select your availability.",
                    contact_pref: "Please select one of the options.",
                },
                errorPlacement: function(error, element) 
                {
                    if ( element.is(":radio") ) 
                    {
                        error.appendTo( element.parents('.pref_container') );
                    }
                    else 
                    { // This is the default behavior 
                        error.insertAfter( element );
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: $('#'+id).serialize(),
                        dataType: 'json',
                        beforeSend: function(settings) {
                                    $('.loading-mask.gemfind-loading-mask').css('display', 'block');
                                },
                        success: function(response) {
                            console.log(response);
                            if(response.output.status == 1){
                                console.log('email send');

                                var parId = $('#' + id).parent().attr('id');
                                //$('#' + parId + ' .note').html(response.output.msg);
                                $('.loading-mask.gemfind-loading-mask').css('display', 'none');
                                //$('#' + parId + ' .note').css('display', 'block');
                               // $('#' + parId + ' .note').css('color', 'green');
                                //$('#' + parId + ' .note').css('background', '#c6efd5');
                                $('#popup-modal .note').html(response.output.msg);
                                $('#popup-modal .note').css('display', 'block');
                                $('#popup-modal .note').css('color', 'green');
                                //$('#popup-modal .note').css('background', '#c6efd5');
                                $("#popup-modal").modal('show');

                                $('#popup-modal').on('hidden.bs.modal', function () {
                                	console.log('close modal');
									$('.cancel.preference-btn').click();
								}); 

                                setTimeout(function(){ $('#' + parId + ' .note').html(''); $('#' + parId + ' .note').css('display', 'none'); $('#' + parId + ' .note').css('background', '#fff');}, 5000);
                            } else {
                                console.log('some error');
                                var parId = $('#' + id).parent().attr('id');
                                //$('#' + parId + ' .note').html(response.output.msg);
                                $('#popup-modal .note').html(response.output.msg);
                                $('#popup-modal .modal-title').html('Error');
                                $('.loading-mask.gemfind-loading-mask').css('display', 'none');
                                $('#popup-modal .note').css('display', 'block');
                                $('#popup-modal .note').css('color', 'red');
                                $('#popup-modal .note').css('background', '#f7c6c6');
                                $("#popup-modal").modal('show');
                                setTimeout(function(){ $('#' + parId + ' .note').html(''); $('#' + parId + ' .note').css('display', 'none'); $('#' + parId + ' .note').css('background', '#fff');}, 5000);
                            }
                            document.getElementById(id).reset();
                            return true;
                        }
                    });
                }
            });


                jQuery.validator.addMethod("emailcustom",function(value,element) {
                    return this.optional(element) || /^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/i.test(value);
                },"Please enter valid email address");

                jQuery.validator.addMethod("phoneno", function(phone_number, element) {
                    phone_number = phone_number.replace(/\s+/g, "");
                    return this.optional(element) || phone_number.length > 9 && 
                    phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
                }, "<br />Please specify a valid phone number");

        
    }

function CallSpecification() {
    document.getElementById("diamond-data").style.display = "none";
    document.getElementById("diamond-specification").style.display = "block";
}

function CallDiamondDetail() {
    document.getElementById("diamond-data").style.display = "block";
    document.getElementById("diamond-content-data").style.display = "block";
    document.getElementById("diamond-specification").style.display = "none";
    var el1 = document.getElementById("drop-hint-main");
    if(el1){
        el1.style.display = "none";    
        document.getElementById("form-drop-hint").reset();
    }
    var el2 = document.getElementById("email-friend-main");
    if(el2){
        el2.style.display = "none";    
        document.getElementById("form-email-friend").reset();
    }
    var el3 = document.getElementById("req-info-main");
    if(el3){
        el3.style.display = "none";    
        document.getElementById("form-request-info").reset();
    }
    var el4 = document.getElementById("schedule-view-main");
    if(el4){
        el4.style.display = "none";    
        document.getElementById("form-schedule-view").reset();
    }
}


function CallShowform(e) {
    console.log('CallShowform');

    document.getElementById("diamond-specification").style.display = "none";
    var el1 = document.getElementById("drop-hint-main");
    if(el1){
        el1.style.display = "none";    
        document.getElementById("form-drop-hint").reset();
    }
    var el2 = document.getElementById("email-friend-main");
    if(el2){
        el2.style.display = "none";    
        document.getElementById("form-email-friend").reset();
    }
    var el3 = document.getElementById("req-info-main");
    if(el3){
        el3.style.display = "none";    
        document.getElementById("form-request-info").reset();

    }
    var el4 = document.getElementById("schedule-view-main");
    if(el4){
        el4.style.display = "none";    
        document.getElementById("form-schedule-view").reset();
    }
    document.getElementById("diamond-content-data").style.display = "none";
    var x = e.target.getAttribute("data-target");
    document.getElementById(x).style.display = "block";
    
            $('#gift_deadline').datepicker({minDate: 0});
            $('#avail_date').datepicker({minDate: 0});
    
}

function Videorun(e){
    document.getElementById("diamondimg").style.display = "none";
    document.getElementById("diamondvideo").style.display = "block"; 
    //document.getElementById('diamondmainimage').setAttribute('src', document.getElementById('diamondimg').getAttribute('data-loadimg')); 
    setTimeout(function(){ 
		$(".main_slider_loader").hide();
		$( '#iframevideo' ).show();		
		document.getElementById('iframevideo').setAttribute('src', document.getElementById('iframevideo').getAttribute('src'));
	}, 1000);
	$(".main_slider_loader").show();
	$( '#iframevideo' ).hide();		
}



function Imageswitch2(e){
        document.getElementById("diamondimg").style.display = "block";
        document.getElementById("diamondvideo").style.display = "none";      
        setTimeout(function(){ 
			document.getElementById('diamondmainimage').setAttribute('src', document.getElementById('thumbimg2').getAttribute('src'));
	    }, 500);              
        document.getElementById('diamondmainimage').setAttribute('src', document.getElementById('diamondimg').getAttribute('data-loadimg'));
}
function Imageswitch1(e){	
        console.log(document.getElementById('diamondimg').getAttribute('data-loadimg'));
        document.getElementById("diamondimg").style.display = "block";
        document.getElementById("diamondvideo").style.display = "none";    
        setTimeout(function(){ 
			document.getElementById('diamondmainimage').setAttribute('src', document.getElementById('thumbimg1').getAttribute('src'));
	    }, 500);
	    document.getElementById('diamondmainimage').setAttribute('src', document.getElementById('diamondimg').getAttribute('data-loadimg'));
}

function Closeform(e){
        var x = e.target.getAttribute("data-target");
        var el1 = document.getElementById("form-drop-hint");
        if(el1){  
            el1.reset();
            $('#form-drop-hint label.error').remove();
        }
        var el2 = document.getElementById("form-email-friend");
        if(el2){  
            el2.reset();
            $('#form-email-friend label.error').remove();
        }
        var el3 = document.getElementById("form-request-info");
        if(el3){  
            el3.reset();
            $('#form-request-info label.error').remove();
        }
        var el4 = document.getElementById("form-schedule-view");
        if(el4){  
            el4.reset();
            $('#form-schedule-view label.error').remove();
        }
        document.getElementById(x).style.display = "none";
        document.getElementById("diamond-content-data").style.display = "block";
}

function focusFunction(e){
    
        if(!e.value){
        $(e).parent().addClass('moveUp');
        $(e).nextAll('span:first').addClass('moveUp'); 
        }    
    
}

function focusoutFunction(e){
    
        if(!e.value){
            $(e).parent().removeClass('moveUp');
            $(e).nextAll('span:first').removeClass('moveUp');
        }
    
}