$(document).ready(function(){ 


    if(blackFriday() === false){
        var favDialog = document.getElementById('myModal');
        favDialog.style.display = "block";
    }

    function blackFriday(){
    
        if(localStorage.getItem("blackFridayCooκie2021")){ 
            return true;
        }
        localStorage.setItem("blackFridayCooκie2021",true)
        return false;
        
    }



})


