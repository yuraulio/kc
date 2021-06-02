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


	var link = $(this).attr('href');
	var self = $(this);

	$.ajax({
		url: link
	}).done(function(res) {
		//alert(res.rowId);
		//console.log(res);
		ajaxCount('default');
		window.location.replace('admin/mcart/');
		//window.location.href = "/cart";
	});
});

//$("").on("click", function(e)

 $(document).on('click', '.update-cart', function(e){
	e.preventDefault();
	//console.log('update');
        $.ajax({ url: '/cart', type: "post",
            data: $(".cartForm").serialize(),
            success: function(data) {

            	ajaxCount('default');
				window.location.replace('admin/mcart/');
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
			window.location.replace('admin/mcart/');
		}
		else {
			self.show();
			//console.log('update not done');
			//alert(res);
		}
	});
});

$(document).on('click', '.do-checkout', function(e) {
	e.preventDefault();

	var thec = $('input#accept');
	if (thec.prop("checked") === false) {

		alert('Please accept the terms and condition');

	}
	else {


        var checkoutUrl = 'cart/checkoutcheck';
        //routesObj.baseUrl+'{{ $frontHelp->pRoute("contact", $_ENV["LANG"], $_ENV["WEBSITE"],  "post") }}';

         /*$.ajax({ url: '/cart/', type: "post",
            data: $(".cartForm").serialize(),
            success: function(data) {*/
        //console.log($(".cartForm").serialize());
        var fdata = $(".cartForm").serialize();

        $.ajax({ url: checkoutUrl, type: "post",
            data: fdata,
            success: function(data) {
                //alert('HO');
                $('.cartForm').find("input[type=text]").removeClass('verror');
                if (Number(data.status) === 0) {
                    //var html = '<ul>';
                    $.each(data.errors, function (key, row) {
                        //console.log(data.errors);
                        var newkey = key.replace('.', '');
                        //console.log(newkey);

                        $('.cartForm').find('input#'+newkey).addClass('verror');

                        if(newkey.startsWith("student")) {

                            var s = $('.cartForm').find('input#'+newkey).attr('placeholder');

                            var pl = 'The '+s+' is required';

                            $('.cartForm').find('input#'+newkey).attr('placeholder', pl);

                        }
                        else {
                            $('.cartForm').find('input#'+newkey).attr('placeholder', row);
                        //$('.cartForm').find('textarea[name="'+key+'"]').attr('placeholder', row);
                        }
                    });

                } else {

                   
                    //dataLayer.push({'event' : 'checkoutSubmitted', 'formName' : 'incheckout'});

                	$(".cartForm").submit();
                   //alert('Should go checkout');

                   
                  // window.location.replace('cart/checkout');


                }
            }
        });

	}
    });


