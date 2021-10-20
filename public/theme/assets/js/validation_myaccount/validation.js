   
$(function() {  
jQuery.validator.addMethod(
    "emailWithDot",
    function(value, element) {
        var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regex.test(String(value).toLowerCase());
    },
    "Enter valid email address."
); 

jQuery.validator.addMethod(
    "lettersonly",
    function(value, element) {
        var regex = /^[a-z][a-z\s]*$/;
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
            'country': {
                lettersonly: true                
            },
            'city': {
                lettersonly: true                
            },
            'company': {
                lettersonly: true                
            },
            'job_title': {
                lettersonly: true                
            },
            'mobile': {
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
            
            'student_type_id': {
                required: "This field is required, enter your ID",
            },
            'country': {               
                required: "select country",
            },

            'city': {
                lettersonly: "Please write everything in English"               
            },


            'company': {
                lettersonly: "Please write everything in English"               
            },
            'job_title': {
                lettersonly: "Please write everything in English"                
            },
            
        },     
        submitHandler: function(form) {
            form.submit();
        }
    });


     $("#billing-data-form").validate({

        rules: {

            'billname': {
                required: true,
                lettersonly: true
            },
            'billafm': {
                required: true,  
            },
           
            'billaddress': {
                lettersonly: true                
            },
            'billcity': {
                lettersonly: true                
            },
            'billstate': {
                lettersonly: true                
            },
            'billcountry': {
                lettersonly: true                
            },
           
    
        },

        messages: {

            'billname': {
                required: "This field is required, Enter Company or full name", 
                lettersonly: "Please write everything in English"            
            },
            'billafm': {
                required: "This field is required, Enter VAT or tax ID",               
            },    
            
            'billemail': {
                required: "This field is required, enter your email",
            },

            'billaddress': {
                lettersonly: "Please write everything in English"
            },

            'billcity': {
                lettersonly: "Please write everything in English"
            },
            'billstate': {
                lettersonly: "Please write everything in English"
            },
            'billcountry': {
                lettersonly: "Please write everything in English"
            },
        
        },
        submitHandler: function(form) {
            form.submit();
        }
    });


});