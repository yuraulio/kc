<script>
  $(document).ready(function () {


    var navbar = $("#extramenu");
    var extramenu = document.getElementById("extramenu");
    var imageH = $("#event-banner");
    var mainmenu;
    var top ;
    var z;
    extramenu.style.display='block';
    if($(window).width() > 991){
        
        mainmenu =$("#mainmenu");
        z = imageH.height() - mainmenu.height() - navbar.height();
        if(window.pageYOffset >= z){
            
            top = mainmenu.height();
            navbar.css({top:top+'px', position:'fixed'});
           
        }else{
    
            top = imageH.innerHeight() - navbar.height();
            navbar.css({top:top+'px', position:'absolute'});
           
        
        }

    }else{

        mainmenu =$(".mobile-nav");
        z = imageH.height() - mainmenu.height() ;

        if(window.pageYOffset >= z){
            top = 69;
            navbar.css({top:top+'px', position:'fixed'});
        }else{
            
            top = imageH.height() + mainmenu.height();
            navbar.css({top:top+'px', position:'absolute'});
        }
    }
    
  });

  </script>




<script>
$(document).ready(function(){


var navbar = $("#extramenu");
var extramenu = document.getElementById("extramenu");
var imageH = $("#event-banner");
var mainmenu;
var top ;
var z;

$(window).resize(function(){
   // console.log('hello');
    if($(window).width() > 991){
      
        mainmenu =$("#mainmenu");
           
        z = imageH.height() - mainmenu.height() - navbar.height();
        if(window.pageYOffset >= z){
            
            top = mainmenu.height();
            navbar.css({top:top+'px', position:'fixed'});
    
        }else{
         
            top = imageH.innerHeight() - navbar.height();
            navbar.css({top:top+'px', position:'absolute'});
        }

    }else{

        mainmenu =$(".mobile-nav");
        z = imageH.height() - mainmenu.height() ;


        if(window.pageYOffset >= z){
            top = 69;
            navbar.css({top:top+'px', position:'fixed'});
        }else{
            
            top = imageH.height() + mainmenu.height();
            navbar.css({top:top+'px', position:'absolute'});
        }
    }

});

});
</script>



<script>

$(document).ready(function(){

    var navbar = $("#extramenu");
    var extramenu = document.getElementById("extramenu");
    var imageH = $("#event-banner");
    var mainmenu;
    var top ;
    var z;
    
    $(window).scroll(function(){
             
        if($(window).width() > 991){
          
            mainmenu =$("#mainmenu");
               
            z = imageH.height() - mainmenu.height() - navbar.height();
            if(window.pageYOffset >= z){
                
                top = mainmenu.height();
                navbar.css({top:top+'px', position:'fixed'});
        
            }else{
             
                top = imageH.innerHeight() - navbar.height();
                navbar.css({top:top+'px', position:'absolute'});
            }

        }else{

            mainmenu =$(".mobile-nav");
            z = imageH.height() - mainmenu.height() ;


            if(window.pageYOffset >= z){
                top = 69;
                navbar.css({top:top+'px', position:'fixed'});
            }else{
                
                top = imageH.height() + mainmenu.height();
                navbar.css({top:top+'px', position:'absolute'});
            }
        }

        
    });

    $("#vtab a").click(function(evn){
        
        var navbar = $("#extramenu");
        var main;
        var imageH = $("#event-banner");

        if($(window).width() > 991){
            main = $("#mainmenu")

        }else{ 
            main=$(".mobile-nav")
        }
        
        $('html, body').animate({
            scrollTop: imageH.height() - (main.height()+navbar.height())
        }, 500);
    });

});
</script>