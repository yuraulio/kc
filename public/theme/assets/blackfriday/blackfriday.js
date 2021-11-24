$(document).ready(function(){ 


    if(blackFriday() === false){
        var favDialog = document.getElementById('myModal');
        favDialog.style.display = "block";
    }

    function blackFriday(){
    
        if(localStorage.getItem("blackFridayCooκie")){ 
            return true;
        }
        localStorage.setItem("blackFridayCooκie",true)
        return false;
        
    }



})


