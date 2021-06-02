function ajaxCount(instance)
{
	//alert(instance);
	var target = $('.'+instance+'Count');

	$.ajax({
		url: 'cart/count'
	}).done(function(res) {
		//target.addClass('alert-success');
		target.text(res);

		/*if (res > 0) {
			$('#cartstatusindicator').removeClass('fa-folder').addClass('fa-folder-open');
			//console.log('open');
		}
		else {
			$('#cartstatusindicator').removeClass('fa-folder-open').addClass('fa-folder');
			//console.log('close');
		}*/

		/*setTimeout(function() {
			target.removeClass('alert-success');
		}, 1000);*/
	});
}

$(document).on('click', '.btn-add', function(e) {

	e.preventDefault();

   // console.log('AddToCart');

   if($(this).hasClass('takequiz')) {
        //alert('QUIZ');
        var link = $(this).attr('href');
        var self = $(this);
        $('#final-answer').attr('href', link);
        $('#advancedModal').modal('show');
   }
   else {
        //alert('NO QUIZ');
        var link = $(this).attr('href');
        var self = $(this);

        $.ajax({
            url: link
        }).done(function(res) {


            ajaxCount('default');
            //fbq('track', 'AddToCart');
            window.location.replace('cart/');

        });
   }

});

//$("").on("click", function(e)

 $(document).on('click', '.update-cart', function(e){
	e.preventDefault();
	//console.log('update');
        $.ajax({ url: '/cart', type: "post",
            data: $(".cartForm").serialize(),
            success: function(data) {

            	ajaxCount('default');
				window.location.replace('cart/');
                //$('.contactUsForm').find("input[type=text], textarea").removeClass('verror');
                //if (Number(data.status) === 0) {
                	//console.log('update done');
                    /*var html = '<ul>';
                    $.each(data.errors, function (key, row) {
                        $('.contactUsForm').find('input[name="'+key+'"], textarea[name="'+key+'"]').addClass('verror');
                        html += '<li>';
                        $.each(row, function (idx, val) {
                            html += val;
                        });
                        html += '</li>';
                    });
                    html += '</ul>';*/
                    //$('.contactUsForm .contactUsReponse').html(html);
                //} else {
                	//console.log('update not done');
                   /* $('.contactUsForm .contactUsReponse').html(data.message);
                    $(".contactUsForm .form-group").hide();
                    $(".contactUsForm .contactUsSubmit").hide();*/
                //}
            }
        });
    });


$(document).on('click', '.btn-remove', function(e) {
	e.preventDefault();
	//alert();

    //console.log('edwdww');
   // return;

	var link = $(this).attr('href');
	var self = $(this);
	self.hide();

	//alert(link);

	$.ajax({
		url: 'cart/remove', type: "post", data: {id: link}
	}).done(function(res) {
		//console.log(res);
		if (res.message === 'success')
		{


			/*self.removeClass('btn-danger').addClass('btn-info').removeClass('btn-remove').addClass('btn-add').text('Add Cart');
			self.attr('href', 'cart/' + res.id + '/add');*/

			ajaxCount('default');
			window.location.replace('cart/');
		}
		else {
			self.show();
			//console.log('update not done');
			//alert(res);
		}
	});
});

$(document).on('click', '.btn-remove-coupon', function(e) {
	e.preventDefault();
	//alert();

    console.log('edwdww');
   // return;

	var link = $(this).attr('href');
	var self = $(this);
	self.hide();

	//alert(link);

	$.ajax({
		url: 'free-event-cart/remove', type: "post", data: {id: link}
	}).done(function(res) {
		//console.log(res);
		if (res.message === 'success')
		{


			/*self.removeClass('btn-danger').addClass('btn-info').removeClass('btn-remove').addClass('btn-add').text('Add Cart');
			self.attr('href', 'cart/' + res.id + '/add');*/

			ajaxCount('default');
			window.location.replace('cart/');
		}
		else {
			self.show();
			//console.log('update not done');
			//alert(res);
		}
	});
});

function checkKnowcrunchId(){


    var knowcrunchInput = document.getElementById("knowcrunch");
	var id = document.getElementById("knowcrunchId").value;
    
	if(id.length !=0){
       
		return $.ajax({url:"/cart/checkKnocrunchId", type:'post', data:{'id':id},
			success: function(data) {
				if (data === 'false'){
					knowcrunchInput.classList.add("knowcrunchId");
                    $('#knowcrunchId').attr('placeholder', 'Knowcrunch Id or Deree Id is required' );
                    return false; 
                    
                } 
                
                $body.addClass("loading");
     
                var checkoutUrl = 'cart/checkoutcheck';
                //routesObj.baseUrl+'{{ $frontHelp->pRoute("contact", $_ENV["LANG"], $_ENV["WEBSITE"],  "post") }}';
        
                var fdata = $("#billing-setting, #user-info, #sbt-pay").serialize();
        
               // console.log(fdata);
        
                //return;
                $.ajax({ url: checkoutUrl, type: "post",
                    data: fdata,
                    success: function(data) {
                        //alert('HO');
                     //   return;
                        $('.small-form').find("input[type=text]").removeClass('verror');
                        if (Number(data.status) === 0) {
                            //var html = '<ul>';
                            $.each(data.errors, function (key, row) {
                                //console.log(data.errors);
                                var newkey = key.replace('.', '');
                                //console.log(newkey);
        
                                $('.small-form').find('input#'+newkey).addClass('verror');
        
                                if(newkey.startsWith("student")) {
        
                                    var s = $('.small-form').find('input#'+newkey).attr('placeholder');
        
                                    var pl = 'The '+s+' is required';
        
                                    $('.small-form').find('input#'+newkey).attr('placeholder', pl);
        
                                }
                                else {
                                    $('.small-form').find('input#'+newkey).attr('placeholder', row);
                                //$('.cartForm').find('textarea[name="'+key+'"]').attr('placeholder', row);
                                }
                            });
        
                            $body.removeClass("loading");
        
                        } else {
        
                          
                            $('#sbt-pay :input').not(':submit').clone().hide().appendTo('#billing-setting')
                            $('#user-info :input').not(':submit').clone().hide().appendTo('#billing-setting')
                           
                            $("#billing-setting").submit();
        
                            
        
        
                        }
                    }
                });
        

                return true;
			},
		
		});
        
	}else {
        //knowcrunchInput.classList.add("knowcrunchId");
        $('#knowcrunchId').attr('placeholder', 'Knowcrunch Id or Deree Id is required' );
		return false; 
    }
    
}

function checkStudentId(){

    //var knowcrunchInput = document.getElementById("student");
	var id = document.getElementById("studentId").value;
    //console.log('id = ',id);
	if(id.length === 0 ){
     //   knowcrunchInput.classList.add("studentId");
        var newPlaceholder = document.getElementById("studentId").placeholder + ' field is required';
        newPlaceholder  = newPlaceholder.replace('*','');
        $('#studentId').attr('placeholder', newPlaceholder );
		return false;
        
    }
//    knowcrunchInput.classList.remove("studentId");
    return true;
}


function checkForId(){

    return checkStudentId();

    /*
    if(type == 3){
        
         checkKnowcrunchId()
        
    }else if(type== 1 || type == 2 || type == 5 || type == 0){
        
        return checkStudentId()
          
    }*/  
}


$(document).on('click', '.regmeup', function(e) {

    

    var thec = $('input#regaccept');
    if (thec.prop("checked") === false) {
        e.preventDefault();
        alert('Please accept the terms, conditions & data privacy in order to complete your registration.');

    }
    else {

        //$(".regmeupform").submit();
    }

});

$(document).on('click', '.do-checkout', function(e) {
	e.preventDefault();
    var hasCard = false
    var favDialogCard = document.getElementById('favDialogCard');

	var thec = $('input#read-accept-tcp');
	if (thec.prop("checked") === false) {

        //alert('Please accept the terms, conditions & data privacy in order to complete your registration.');
        //$('.alert-wrapper').dialog();
        var favDialog = document.getElementById('favDialog');
        
       favDialog.style.display = "block";
        $("body").css("overflow-y", "hidden")
        if($('#last4').text() == '-'){
            favDialogCard.style.display = "block";
        }
    }
    
   else if($('#last4').text() == '-'){
    favDialogCard.style.display = "block";
   }
    
    else if(!checkForId()){
        
        
            $(window).scrollTop(0);
          //  alert('id field required');
        
    }   

	else {

        $body.addClass("loading");
     
        var checkoutUrl = 'cart/checkoutcheck';
        
        //routesObj.baseUrl+'{{ $frontHelp->pRoute("contact", $_ENV["LANG"], $_ENV["WEBSITE"],  "post") }}';

        var fdata = $("#billing-setting, #user-info, #sbt-pay").serialize();
        var elementsHeight
        var firstError = false;
       // console.log(fdata);

        //return;
        $.ajax({ url: checkoutUrl, type: "post",
            data: fdata,
            success: function(data) {
                //alert('HO');
             //   return;
                $('.small-form').find("input[type=text]").removeClass('verror');
                if (Number(data.status) === 0) {
                    //var html = '<ul>';
                    $.each(data.errors, function (key, row) {
                        //console.log(data.errors);
                        var newkey = key.replace('.', '');

                        $('.small-form').find('input#'+newkey).addClass(['verror','validate-error']);
                        
                        if(!firstError){                       
                            elementsHeight = Math.round($('#header').outerHeight()) - document.getElementById(newkey).getBoundingClientRect().top +30  
                            firstError = true;
                         
                            $('html, body').animate({
                                scrollTop: elementsHeight
                            }, 300);
                        }
                       

                        if(newkey.startsWith("student")) {

                            if(!firstError){ 
                                                 
                                elementsHeight = Math.round($('#header').outerHeight()) - document.getElementById(newkey).getBoundingClientRect().top +30
                                firstError = true;
                            }

                            var s = $('.small-form').find('input#'+newkey).attr('placeholder');
                            var pl = 'The '+s+' is required';

                            $('.small-form').find('input#'+newkey).attr('placeholder', pl);

                        }
                        else {
                            $('.small-form').find('input#'+newkey).attr('placeholder', row);
                        //$('.cartForm').find('textarea[name="'+key+'"]').attr('placeholder', row);
                        }
                    });
                                   

                    $body.removeClass("loading");

                } else {

                  
                    $('#sbt-pay :input').not(':submit').clone().hide().appendTo('#billing-setting')
                    $('#user-info :input').not(':submit').clone().hide().appendTo('#billing-setting')
                   
                	$("#billing-setting").submit();

                    


                }
            }
        });

	}
});


$(document).on('click', '.do-checkout-free', function(e) {
	e.preventDefault();
	var thec = $('input#read-accept-tcp');
	if (thec.prop("checked") === false) {

        //alert('Please accept the terms, conditions & data privacy in order to complete your registration.');
        //$('.alert-wrapper').dialog();
        var favDialog = document.getElementById('favDialog');
       // favDialog.showModal();
       favDialog.style.display = "block";
        $("body").css("overflow-y", "hidden")
    } 

	else {

        $body.addClass("loading");
     
        var checkoutUrl = 'complete-registration-validation';
        
        //routesObj.baseUrl+'{{ $frontHelp->pRoute("contact", $_ENV["LANG"], $_ENV["WEBSITE"],  "post") }}';

        var fdata = $("#user-info, #code-reg").serialize();
        var elementsHeight
        var firstError = false;
       // console.log(fdata);

        //return;
        $.ajax({ url: checkoutUrl, type: "post",
            data: fdata,
            success: function(data) {
                //alert('HO');
             //   return;
                $('.small-form').find("input[type=text]").removeClass('verror');
                if (Number(data.status) === 0) {
                    //var html = '<ul>';
                    $.each(data.errors, function (key, row) {
                        //console.log(data.errors);
                        var newkey = key.replace('.', '');

                        $('.small-form').find('input#'+newkey).addClass(['verror','validate-error']);
                        
                        if(!firstError){                       
                            elementsHeight = Math.round($('#header').outerHeight()) - document.getElementById(newkey).getBoundingClientRect().top +30  
                            firstError = true;
                         
                            $('html, body').animate({
                                scrollTop: elementsHeight
                            }, 300);
                        }
                       
                        console.log("newkey = ", newkey)
                        if(newkey.startsWith("student")) {

                            if(!firstError){ 
                                                 
                                elementsHeight = Math.round($('#header').outerHeight()) - document.getElementById(newkey).getBoundingClientRect().top +30
                                firstError = true;
                            }

                            var s = $('.small-form').find('input#'+newkey).attr('placeholder');
                            var pl = 'The '+s+' is required';

                            $('.small-form').find('input#'+newkey).attr('placeholder', pl);

                        }
                        else {
                            $('.small-form').find('input#'+newkey).attr('placeholder', row);
                        //$('.cartForm').find('textarea[name="'+key+'"]').attr('placeholder', row);
                        }
                    });
                                   

                    $body.removeClass("loading");

                } else {

                  
                    $('#code-reg :input').not(':submit').clone().hide().appendTo('#user-info')
                   
                	$("#user-info").submit();

                    


                }
            }
        });

	}
});

$(document).on('click', '.do-checkout-subscription', function(e) {
    e.preventDefault();
	var thec = $('input#read-accept-tcp');
    var favDialogCard = document.getElementById('favDialogCard');
    
	if (thec.prop("checked") === false) {
        

        //alert('Please accept the terms, conditions & data privacy in order to complete your registration.');
        //$('.alert-wrapper').dialog();
        var favDialog = document.getElementById('favDialog');
       // favDialog.showModal();
       favDialog.style.display = "block";
        $("body").css("overflow-y", "hidden")
    }
    
   
    else if($('#last4').val() == '-'){
        favDialogCard.style.display = "block";
    }

	else {

        $body.addClass("loading");
     
        var checkoutUrl = 'cart/checkoutcheck';
        
        //routesObj.baseUrl+'{{ $frontHelp->pRoute("contact", $_ENV["LANG"], $_ENV["WEBSITE"],  "post") }}';

        var fdata = $("#billing-setting, #user-info, #sbt-pay").serialize();
        var elementsHeight
        var firstError = false;
       // console.log(fdata);

        //return;
        $.ajax({ url: checkoutUrl, type: "post",
            data: fdata,
            success: function(data) {
                //alert('HO');
             //   return;
                $('.small-form').find("input[type=text]").removeClass('verror');
                if (Number(data.status) === 0) {
                    //var html = '<ul>';
                    $.each(data.errors, function (key, row) {
                        //console.log(data.errors);
                        var newkey = key.replace('.', '');

                        $('.small-form').find('input#'+newkey).addClass(['verror','validate-error']);
                        
                        if(!firstError){                       
                            elementsHeight = Math.round($('#header').outerHeight()) - document.getElementById(newkey).getBoundingClientRect().top +30  
                            firstError = true;
                         
                            $('html, body').animate({
                                scrollTop: elementsHeight
                            }, 300);
                        }
                       

                        if(newkey.startsWith("student")) {

                            if(!firstError){ 
                                                 
                                elementsHeight = Math.round($('#header').outerHeight()) - document.getElementById(newkey).getBoundingClientRect().top +30
                                firstError = true;
                            }

                            var s = $('.small-form').find('input#'+newkey).attr('placeholder');
                            var pl = 'The '+s+' is required';

                            $('.small-form').find('input#'+newkey).attr('placeholder', pl);

                        }
                        else {
                            $('.small-form').find('input#'+newkey).attr('placeholder', row);
                        //$('.cartForm').find('textarea[name="'+key+'"]').attr('placeholder', row);
                        }
                    });
                                   

                    $body.removeClass("loading");

                } else {

                  
                    $('#sbt-pay :input').not(':submit').clone().hide().appendTo('#billing-setting')
                    $('#user-info :input').not(':submit').clone().hide().appendTo('#billing-setting')
                   
                	$("#billing-setting").submit();

                    


                }
            }
        });

	}

});




