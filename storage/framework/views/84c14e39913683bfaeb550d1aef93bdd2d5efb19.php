<script src="<?php echo e(cdn(mix('theme/assets/js/front.js'))); ?>" > </script>

<script>


$(document).ready(function(){

    $('.page-wrapper').removeClass('non-pointer')
   // $('.page-wrapper').css('pointer-events','all'); //activate all pointer-events on page
  //  $('.page-wrapper').css('cursor','pointer');



})


</script>

<script type="text/javascript">
    $.ajaxSetup({headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
</script>


<script type="text/javascript">
    $(function() {

        $("#terms").click(function(){
        var thec = $(this).find('input[type=checkbox]');
            if (thec.prop("checked") === false) {
                thec.prop('checked', true);
                thec.val('1');
            } else {
                thec.prop('checked', false);
                thec.val('0');
            }
        });

        $("#cb-remember").click(function(){
        var thec = $(this).find('input[type=checkbox]');
            if (thec.prop("checked") === false) {
                thec.prop('checked', true);
                thec.val('1');
            } else {
                thec.prop('checked', false);
                thec.val('0');
            }
        });



    })

    /* Newsletter sub */
    $(function () {
        $("#applyToNewsletter").on("click", function (e) {
            e.preventDefault();
            $('.newsletter_form').find("input[type=text]").removeClass('verror');
            handleNewsletterSubmit();
        });
        $('.newsletter_form input[name="email"]').keypress(function (evt) {
            if (evt.which == 13) {
                evt.preventDefault();
                $('.newsletter_form').find("input[type=text]").removeClass('verror');
                handleNewsletterSubmit();
            }
        });
    });

    function handleNewsletterSubmit() {
        $.ajax({ url: routesObj.baseUrl+"newsletter/subscribe", type: "post",
            data: $(".newsletter_form").serialize(),
            success: function(data) {
                var noti = $('.newsletterReponse');
                switch (data.opstatus) {
                    case 0:
                        if (data.opmessage.length > 0) {
                            notify(data.opmessage, 'error', 5000);
                            noti.html(data.opmessage);

                        } else {
                            //console.log(data.errors);
                            $.each(data.errors, function (key, row) {


                                $('.newsletter_form').find('input[name="'+key+'"]').addClass('verror');
                                $('.newsletter_form').find('input[name="'+key+'"]').attr('placeholder', row);


                            });

                            //notify(data.errors[0], 'error', 5000);
                            //noti.html(data.errors[0]);

                        }
                        break;
                    case 1:

                         $('.newsletter_form').find('input[type=text]').val('');
                         $('.newsletter_form').find('input[name="name"]').attr('placeholder', 'Name');
                         $('.newsletter_form').find('input[name="surname"]').attr('placeholder', 'Surname');
                         $('.newsletter_form').find('input[name="email"]').attr('placeholder', 'Email');


                        notify(data.opmessage, 'success', 5000);
                        noti.html(data.opmessage);

                        break;
                }
            }
        });
    }
</script>
<!--
<script>
    !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
</script>
-->


    <script type="text/javascript">


    jQuery(document).ready(function($){
    	$body = $("body");
    		//var $loadingC = $('.loadingCart').hide();
            var $loading = $('#loadingDiv').fadeTo( "slow", 0.0 );
            var $loadingN = $('#loadingDivN').hide();
            var $loadingF = $('#loadingDivF').fadeTo( "slow", 0.0 );//$('#loadingDivF').hide();

            $(document)
            .ajaxStart(function () {
                $loadingN.show();$loadingF.fadeTo( "slow", 1.0 );//
                /*$loadingF.show();*/
                $loading.fadeTo( "slow", 1.0 );
                //$loadingC.fadeTo( "slow", 1.0 );
                //$body.addClass("loading");

            })
            .ajaxStop(function () {
                $loadingN.hide(); $loadingF.fadeTo( "slow", 0.0 );
                $loading.fadeTo( "slow", 0.0 );
                //$loadingF.hide();
                 //$loadingC.fadeTo( "slow", 0.0 );
                  //$body.removeClass("loading");
            });

    });

</script>

<script type="text/javascript">


  function loginAjaxNew(){
    var email = $('#email').val();
    var password = $('#password').val();
    var remember = document.getElementById("remember-me").checked;

    if (email.length > 4 && password.length > 4) {
        
    $.ajax({ 
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
            url: "/studentlogin", type: "post",
            data: {email:email, password:password, remember:remember},
            success: function(data) {

                data = data.data
                
                switch (data.status) {
                    case 0:
                        if (data.message.length > 0) {

                            var p = document.getElementById('account-error').textContent = data['message'];
                          /*  var img = document.createElement('img');
                            img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-error-alert.svg" )
                            img.setAttribute('alt',"Info Alert" )

                            $('#account-error').append(img);*/
                        //	console.log(p);
                            $('.alert-outer').show()

                        } else {


                        }
                        break;
                    case 1:

                        setTimeout( function(){
                            window.location.replace(data.redirect);
                        }, 1000 );

                        break;

                    default:
                        shakeModal();
                        break;
                }



            },
            error: function(data) {
                //shakeModal();
            }
        });

        }
        else {
          //  shakeModal();

        }


}

$(".close-alert").on("click", function () {

$('.alert-outer').hide()

});
/*
function loginAjax(){
    /*   Remove this comments when moving to server
    $.post( "/login", function( data ) {
            if(data == 1){
                window.location.replace("/home");
            } else {
                 shakeModal();
            }
        });

    //data: $(".newsletter_form").serialize(),
    var email = $('input#uemail').val();
    var password = $('input#upassword').val();
    var remember = document.getElementById("remember").checked;

    if (email.length > 4 && password.length > 4) {
    $.ajax({ url: routesObj.baseUrl+"studentlogin", type: "post",
            data: {email:email, password:password, remember:remember},
            success: function(data) {
            //    console.log(data);

                switch (data.status) {
                    case 0:
                        if (data.message.length > 0) {

                            notify(data.message, 'error', 7000);

                        } else {
                           // console.log(data.errors);
                            /*$.each(data.errors, function (key, row) {

                            });

                        }
                        shakeModal();
                        break;
                    case 1:
                        $('.login-form').find('input').val('');

                        $('#loginModal').modal('toggle');
                        $('#fireloginlogout').html('<a class="dropdown-toggle" href="javascript:void(0)"><i class="fa fa-user" aria-hidden="true"></i></a><ul class="dropdown-menu pull-right"><li><a href="/myaccount">My Account <i class="fa fa-id-card" aria-hidden="true"></i></a></li><li><a href="<?php echo e(url('logmeout')); ?>">Sign out <i class="fa fa-sign-out" aria-hidden="true"></i></a></li></ul>');
                        $('#fireloginlogout').addClass('active');


                        $('#fireloginlogout').hover(function() {

                          $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);

                        }, function() {

                          $(this).find('.dropdown-menu').stop(true, true).delay(400).fadeOut(500);

                        });

                        notify(data.message, 'success', 7000);

                        setTimeout( function(){
                            window.location.replace(data.redirect);
                        }, 1000 );


                        break;

                    default:
                        shakeModal();
                        break;
                }
            },
            error: function(data) {
                shakeModal();
            }
        });

        }
        else {
            shakeModal();

        }


/*   Simulate error message from the server   */
     //shakeModal();
//}

/*function shakeModal(){
    $('#loginModal .modal-dialog').addClass('shake');
             $('input[type="password"]').val('');
             setTimeout( function(){
                $('#loginModal .modal-dialog').removeClass('shake');
    }, 700 );
}

/*});*/


//});


/*window.onbeforeunload = function (e) {
  var e = window.event;

  //IE & Firefox
  if (e) {
    $.ajax({ url: '/abanoded/user', type: "get",
            success: function(data) {
                window.close();
            }
        });
  }

};*/


/*window.addEventListener('beforeunload', function (e) {

    console.log(e.target);
    if(window.opener.closed){
        $.ajax({ url: '/abanoded/user', type: "get",
            success: function(data) {
                window.close();
            }
        });
    }


});*/
</script>



<?php /**PATH C:\laragon\www\kcversion8\resources\views/theme/layouts/footer_scripts.blade.php ENDPATH**/ ?>