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
        "afm",
        function(value, element) {       
            var regex = /^[a-z][a-z0-9\s\(+=@!#$%^*)&._-]*$/;  
            var numberRegex = /\d/;
            
            console.log(regex.test(String(value).toLowerCase()) || String(value).toLowerCase() == '' || numberRegex.test(String(value).toLowerCase()));
            
            return regex.test(String(value).toLowerCase()) || String(value).toLowerCase() == '' || numberRegex.test(String(value).toLowerCase()) ;
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


    let messages = {};
    let rules = {};

    $("input[name^='firstname[']").each(function(index) {
        

        messages[`firstname[]`] = {}
        messages[`firstname[]`]['required'] = "This field is required, enter your name."
        messages[`firstname[]`]['lettersonly'] = "Please write everything in English."

        messages[`firstname[${index}]`] = {}
        messages[`firstname[${index}]`]['required'] = "This field is required, enter your name."
        messages[`firstname[${index}]`]['lettersonly'] = "Please write everything in English."

        messages[`lastname[]`] = {}
        messages[`lastname[]`]['required'] = "This field is required, enter your last name."
        messages[`lastname[]`]['lettersonly'] = "Please write everything in English."

        messages[`lastname[${index}]`] = {}
        messages[`lastname[${index}]`]['required'] = "This field is required, enter your last name."
        messages[`lastname[${index}]`]['lettersonly'] = "Please write everything in English."

        messages[`email[]`] = {}
        messages[`email[]`]['required'] = "This field is required, enter your email."

        messages[`email[${index}]`] = {}
        messages[`email[${index}]`]['required'] = "This field is required, enter your email."

        messages[`company[]`] = {}
        messages[`company[]`]['lettersonly'] = "Please write everything in English."

        messages[`company[${index}]`] = {}
        messages[`company[${index}]`]['lettersonly'] = "Please write everything in English."

        messages[`city[]`] = {}
        messages[`city[]`]['lettersonly'] = "Please write everything in English."

        messages[`city[${index}]`] = {}
        messages[`city[${index}]`]['lettersonly'] = "Please write everything in English."

        messages[`mobile[]`] = {}
        messages[`mobile[]`]['required'] = "This field is required, enter your phone."

        messages[`mobile[${index}]`] = {}
        messages[`mobile[${index}]`]['required'] = "This field is required, enter your phone."

        messages[`student_type_id[]`] = {}
        messages[`student_type_id[]`]['required'] = "This field is required, enter your ID."


        messages[`student_type_id[${index}]`] = {}
        messages[`student_type_id[${index}]`]['required'] = "This field is required, enter your ID."


        messages[`country_code[]`] = {}
        messages[`country_code[]`]['required'] = "select country"

        messages[`country_code[${index}]`] = {}
        messages[`country_code[${index}]`]['required'] = "select country"

        messages[`jobtitle[]`] = {}
        messages[`jobtitle[]`]['lettersonly'] = "Please write everything in English."

        messages[`jobtitle[${index}]`] = {}
        messages[`jobtitle[${index}]`]['lettersonly'] = "Please write everything in English."

        //rules
        rules[`firstname[]`] = {}
        rules[`firstname[]`]['required'] = true
        rules[`firstname[]`]['symbols'] = true
        rules[`firstname[]`]['lettersonly'] = true

        rules[`firstname[${index}]`] = {}
        rules[`firstname[${index}]`]['required'] = true
        rules[`firstname[${index}]`]['symbols'] = true
        rules[`firstname[${index}]`]['lettersonly'] = true
       
        rules[`lastname[]`] = {}
        rules[`lastname[]`]['required'] = true
        rules[`lastname[]`]['symbols'] = true
        rules[`lastname[]`]['lettersonly'] = true
        
        rules[`lastname[${index}]`] = {}
        rules[`lastname[${index}]`]['required'] = true
        rules[`lastname[${index}]`]['symbols'] = true
        rules[`lastname[${index}]`]['lettersonly'] = true

        rules[`email[]`] = {}
        rules[`email[]`]['required'] = true
        rules[`email[]`]['lettersonlyEmail'] = true
        rules[`email[]`]['emailWithDot'] = true

        rules[`email[${index}]`] = {}
        rules[`email[${index}]`]['required'] = true
        rules[`email[${index}]`]['lettersonlyEmail'] = true
        rules[`email[${index}]`]['emailWithDot'] = true

        rules[`company[]`] = {}
        rules[`company[]`]['lettersonlyEmail'] = true

        rules[`company[${index}]`] = {}
        rules[`company[${index}]`]['lettersonlyEmail'] = true

        rules[`city[]`] = {}
        rules[`city[]`]['symbols'] = true
        rules[`city[]`]['lettersonly'] = true

        rules[`city[${index}]`] = {}
        rules[`city[${index}]`]['symbols'] = true
        rules[`city[${index}]`]['lettersonly'] = true

        rules[`mobile[]`] = {}
        rules[`mobile[]`]['required'] = true

        rules[`mobile[${index}]`] = {}
        rules[`mobile[${index}]`]['required'] = true

        rules[`student_type_id[]`] = {}
        rules[`student_type_id[]`]['required'] =true
 
        rules[`student_type_id[${index}]`] = {}
        rules[`student_type_id[${index}]`]['required'] =true

        rules[`country_code[]`] = {}
        rules[`country_code[]`]['required'] = true

        rules[`country_code[${index}]`] = {}
        rules[`country_code[${index}]`]['required'] = true

        rules[`jobtitle[]`] = {}
        rules[`jobtitle[]`]['lettersonlyEmail'] = true

        rules[`jobtitle[${index}]`] = {}
        rules[`jobtitle[${index}]`]['lettersonlyEmail'] = true

    });

    messages['terms_condition']={}
    rules['terms_condition']={}
    messages['terms_condition']['required'] = "  Confirm that you accept our terms & conditions and data privacy policy."
    rules['terms_condition']['required'] = true

    messages['terms_condition2']={}
    rules['terms_condition2']={}
    messages['terms_condition2']['required'] = "  You need to consent to proceed."
    rules['terms_condition2']['required'] = true

    $("form[name='participant-form']").validate({

        rules: rules,
        messages: messages,     

        submitHandler: function(form) {
            form.submit();
        }
    });


     $("form[name='billing']").validate({

        rules: {

            'billname': {
                required: true,
                lettersonlyEmail: true,
                
            },

            'billafm': {
                afm: true,
            },
            'billaddress': {
                lettersonly: true,
            },

            'billcity': {
                lettersonly: true,
            },

            'billstate': {
                lettersonly: true,
            },

            'billcountry': {
                lettersonly: true,
            },

            'billemail':{
                billemailGreek:true,
                billemail:true
                

            }

         
        },

        messages: {

            'billname': {
                required: "This field is required, Enter Company or full name.",      
                lettersonly: "Please write everything in English."         
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

            'billemail': {
                required: "This field is required, enter your email.",
            },
           
        },
        submitHandler: function(form) {
            form.submit();
        }
    });


});