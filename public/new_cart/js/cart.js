
$(document).on('click', '.registration', function(e) {
	e.preventDefault();
    var favDialogCard = document.getElementById('favDialogCard');

    var checkoutUrl = 'mobile-check';
    var fdata = $("#participant-form").serialize();
    var elementsHeight
    var firstError = false;

   
    $("#mobile-error").hide();
    
	/*if ($('#customCheck1').prop("checked") === false) {
        $('#customCheck1').addClass('error');
        return;
    }*/

    $.ajax({ url: checkoutUrl, type: "post",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     },
            data: fdata,
            success: function(data) {
                //alert('HO');
             //   return;
                $('#participant-form').find("input[type=text]").removeClass('verror');
                if (Number(data.status) === 0) {
                    //var html = '<ul>';
                    $.each(data.errors, function (key, row) {
                        
                        $("#mobile-error").html(row);
                        $("#mobile-error").show()

                    });


                } else {

                    $("#participant-form").submit();
        
                }
            }
            
    });

	
});








