   
$(function() {  
jQuery.validator.addMethod(
    "emailWithDot",
    function(value, element) {
        var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regex.test(String(value).toLowerCase());
    },
    "Enter valid email address."
); 

    $("form[name='participant-form']").validate({

        rules: {

            firstname: {
                required: true,
                lettersonly: true
            },
            lastname: {
                required: true,
                lettersonly: true
            },
            'email[]': {
                required: true,
                emailWithDot: true
            },
             country: {
                required: true                
            },
            number: {
                required: true
            },
            terms_condition:{
                required: true
            }
        },

        messages: {
            'firstname': {
                required: "This field is required, enter your name",
                lettersonly: "only letters allowed"                
            },
            'lastname': {
                required: "This field is required, enter your last name",
                lettersonly: "only letters allowed"               
            },

            'email[]': {
                required: "This field is required, enter your email",
            },
            'country': {               
                required: "select country",
            },
            'terms_condition': {               
                required: " Confirm that you accept our terms & conditions and data privacy policy",
            },
        },     
        submitHandler: function(form) {
            form.submit();
        }
    });


     $("form[name='billing']").validate({

        messages: {

            'company': {
                required: "This field is required, Enter billed name or company",             
            },
            'vat': {
                required: "This field is required, Enter VAT or tax ID",                
            },            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });


});