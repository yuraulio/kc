   
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
        "symbols",
        function(value, element) {
            
            var numberRegex = /\d/;
            return !String(value).toLowerCase().match(/[-!$%^&*()_+|~=`{}\[\]:";'<>?,.\/]/) && !numberRegex.test(String(value).toLowerCase());
        },
        "Enter valid data. Special characters are not allowed."
    );
    
    jQuery.validator.addMethod(
        "lettersonly",
        function(value, element) {
            var regex = /^[a-z][a-z\s]*$/;
            return regex.test(String(value).toLowerCase()) || String(value).toLowerCase() == '';
        },
        "Enter valid email address."
    );


    jQuery.validator.addMethod(
        "lettersonlyEmail",
        function(value, element) {       
            var regex = /^[a-z][a-z0-9\s\(+=@!#$%^*)&._-]*$/;  
            return regex.test(String(value).toLowerCase()) || String(value).toLowerCase() == '' ;
        },
        "Please write everything in English."
    );


    jQuery.validator.addMethod(
        "billemail",
        function(value, element) {
            
            var regex = /^[a-z][a-z0-9\s\(+=@!#$%^*)&._-]*$/;
            var regex1 = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

            
            return (regex1.test(String(value).toLowerCase())) || String(value).toLowerCase() == '' ;
        },
        "Enter valid email address."
    );


    jQuery.validator.addMethod(
        "billemailGreek",
        function(value, element) {
            
            var regex = /^[a-z][a-z0-9\s\(+=@!#$%^*)&._-]*$/;

            
            return (regex.test(String(value).toLowerCase())) || String(value).toLowerCase() == '' ;
        },
        "Please write everything in English."
    );

    jQuery.validator.addMethod(
        "afm",
        function(value, element) {       
            var regex = /^[a-z][a-z0-9\s\(+=@!#$%^*)&._-]*$/;  
            var numberRegex = /\d/;
            
            console.log(regex.test(String(value).toLowerCase()) || String(value).toLowerCase() == '' || numberRegex.test(String(value).toLowerCase()));
            
            return regex.test(String(value).toLowerCase()) || String(value).toLowerCase() == '' || numberRegex.test(String(value).toLowerCase()) ;
        },
        "Please write everything in English."
    );




    $("#update-form").validate({

        rules: {

            'firstname': {
                required: true,
                symbols: true,
                lettersonly: true
                
            },
            'lastname': {
                required: true,
                symbols: true,
                lettersonly: true
            },
            'email': {
                required: true,
                lettersonlyEmail: true,
                emailWithDot: true
                
            },
            'country': {
                lettersonly: true                
            },
            'city': {
                symbols: true,
                lettersonly: true                
            },
            'company': {
                lettersonlyEmail: true                
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
                required: "Required field, please enter your first name.",
                lettersonly: "Please write everything in English."
            },

            'lastname': {
                required: "Required field, please enter your last name.",
                lettersonly: "Please write everything in English."               
            },

            'email': {
                required: "Required field, please enter your email address.",
            },
            
            'student_type_id': {
                required: "This field is required, enter your ID.",
            },
            'country': {               
                required: "select country",
            },

            'city': {
                lettersonly: "Please write everything in English."               
            },

            'mobile': {
                required: "Required field, please enter your mobile phone.",
            },

            'company': {
                lettersonly: "Please write everything in English."               
            },
            'job_title': {
                lettersonly: "Please write everything in English."                
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
                lettersonlyEmail: true
            },
            'billafm': {
                afm: true,  
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

            'billemail':{
                billemailGreek:true,
                billemail:true
                

            }
           
    
        },

        messages: {

            'billname': {
                required: "This field is required, Enter Company or full name.", 
                lettersonlyEmail: "Please write everything in English."            
            },
            'billafm': {
                required: "This field is required, Enter VAT or tax ID.",               
            },    
            
            'billemail': {
                required: "This field is required, enter your email.",
            },

            'billaddress': {
                lettersonly: "Please write everything in English."
            },

            'billcity': {
                lettersonly: "Please write everything in English."
            },
            'billstate': {
                lettersonly: "Please write everything in English."
            },
            'billcountry': {
                lettersonly: "Please write everything in English."
            },
        
        },
        submitHandler: function(form) {
            form.submit();
        }
    });


});