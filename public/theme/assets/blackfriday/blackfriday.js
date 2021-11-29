$(document).ready(function(){ 


    if(blackFriday() === false){
        var favDialog = document.getElementById('myModal');
        favDialog.style.display = "block";
    }

    function blackFriday(){
    
        if(localStorage.getItem("CyberMonthCooκie2021")){ 
            return true;
        }
        localStorage.setItem("CyberMonthCooκie2021",true)
        return false;
        
    }


    $(document).click(function(e){
        
        console.log($(e.target).hasClass('modal-cont'))
        
        if(!$(e.target).hasClass('modal-cont')){
            var favDialog = document.getElementById('myModal');
            favDialog.style.display = "none";
        }
        
      });


})


