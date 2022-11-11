<script src="{{ cdn(mix('theme/assets/js/front.js')) }}" > </script>

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
            beforeSend: function beforeSend() {
                $('#test-login').hide()
                $('.loader').show()

            },
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

                            $('#test-login').show()
                            $('.loader').hide()

                        } else {


                        }
                        break;
                    case 1:

                        //setTimeout( function(){
                            window.location.replace(data.redirect);
                        //}, 1000 );

                        break;

                    default:
                        shakeModal();
                        break;
                }



            },
            error: function(data) {
                //shakeModal();
                $('#test-login').show()
                $('.loader').hide()
            }
        });

        }
        else {
          //  shakeModal();

        }


}



$(document).keyup(function(event){
    if($('.login-popup-wrapper').hasClass('active')){

        if(event.keyCode == 13){
            loginAjaxNew()
        }
    }


})


$(".close-alert").on("click", function () {

$('.alert-outer').hide()

});

</script>



