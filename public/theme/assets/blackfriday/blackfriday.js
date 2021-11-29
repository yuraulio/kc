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



})


