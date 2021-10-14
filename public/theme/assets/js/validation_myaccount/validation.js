   
$(function() {  
jQuery.validator.addMethod(
    "emailWithDot",
    function(value, element) {
        var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regex.test(String(value).toLowerCase());
    },
    "Enter valid email address."
); 

    $("#update-form").validate({

        rules: {

            'firstname': {
                required: true,
                lettersonly: true
            },
            'lastname': {
                required: true,
                lettersonly: true
            },
            'email': {
                required: true,
                emailWithDot: true
            },
            'billemail': {
                required: true,
                emailWithDot: true
            },
            'country': {
                required: true                
            },
            'number': {
                required: true
            },
            'student_type_id': {
                required: true
            },
            
        },

        messages: {
            'firstname': {
                required: "This field is required, enter your name",
                lettersonly: "Please write everything in English"                
            },
            'lastname': {
                required: "This field is required, enter your last name",
                lettersonly: "Please write everything in English"               
            },

            'email': {
                required: "This field is required, enter your email",
            },
            'billemail': {
                required: "This field is required, enter your email",
            },
            'student_type_id': {
                required: "This field is required, enter your ID",
            },
            'country': {               
                required: "select country",
            },
            
        },     
        submitHandler: function(form) {
            form.submit();
        }
    });


     $("form[name='billing']").validate({

        messages: {

            'billname': {
                required: "This field is required, Enter billed name or company",             
            },
            'billafm': {
                required: "This field is required, Enter VAT or tax ID",                
            },            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });


});