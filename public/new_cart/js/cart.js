
$(document).on('click', '.registration', function(e) {
	e.preventDefault();
    var favDialogCard = document.getElementById('favDialogCard');

    var checkoutUrl = 'mobile-check';
    var fdata = $("#participant-form").serialize();
    var elementsHeight
    var firstError = false;

    
    
    $(".error-mobile").hide();
	

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
                        
                        key = Number(key.split('.')[1]);
                        
                        $("#mobile-error"+(key+1)).html(row);
                        $("#mobile-error"+(key+1)).show()

                    });


                } else {

                    $("#participant-form").submit();
        
                }
            }
            
    });

	
});








