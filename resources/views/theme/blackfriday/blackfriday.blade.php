<head>
		<meta charset="utf-8">
		<title>Know Crunch</title>
		<meta name="description" content="Know Crunch">
		<meta name="author" content="Epsilon-8">
		<meta name="ad.size" content="width=300,height=250">
		
		<style>
		
         #myModal #container-modal {				
				/*width: 400px!important;
				height: 400px!important;*/
				width: 700px!important;
    			height: 392px!important;
				overflow: hidden!important;
				position: relative!important;
				box-sizing: border-box!important;
				box-shadow: 0 0 2px #cccccc!important;
				cursor: pointer!important;
			}
			#myModal #container-modal:hover #cta {
				filter: brightness(1.1)!important;
			}
			#myModal .screen {
				width: 100%!important;
				height: 100%!important;
				position: absolute!important;
				left: 0!important; top: 0!important;
				background-repeat: no-repeat!important;
			}
			#myModal img {position: absolute;}
			#myModal #container-modal {
				background-image: url(blackfriday/cyberMonth21/kc-website-cyber-monday-modal-2x.jpg); 
				background-size: contain;
				background-repeat: no-repeat;
			}
           
			#myModal .close-btn {
			    top: -40px!important;
			    position: absolute!important;
			    right: 10px!important;
			    z-index: 10;
			}

            .modal-body{
                background:#419cff;
            }
            @media(min-width: 981px){
                #myModal{
                	outline: none;
    				position: fixed;
    				top: 50%;
    				left: 50%;
    				transform: translate(-50%, -50%);
    				display: none;
    				overflow: unset!important;
            	}
                .modal-border{
					display: block;
    				/* margin: 10px 80px; */
    				margin: 0 auto;
    				box-shadow: 0px 0px 13px #0000009e;
    				width: fit-content;
    				height: fit-content;
                }
            }      

            @media(max-width: 980px){
                #myModal{
                    outline: none;
                    position: fixed;
                    left: 50%;
                    top: 50%;
                    transform: translate(-50%, -50%); 
                    display: none;
					overflow: unset!important;
                }

                .modal-border{
                    margin: 0 auto;
                    box-shadow: 0px 0px 13px #0000009e;
					width: fit-content;
    				height: fit-content;
                }

				#myModal #container-modal {
    				width: 402px!important;
    				height: 225px!important;
				}

				#myModal #container-modal {
				    background-size: contain;
				    background-repeat: no-repeat;
				}

            }

		</style>
    </head><!-- Modal -->
    
    
        <div id="myModal" class="modal hide fade modal-border" tabindex="-1" role="dialog" data-backdrop="true" aria-labelledby="myModalLabel" aria-hidden="true" hidden>
        <a href="javascript:void(0)" class="close-btn"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" class="replace-with-svg" alt="Close"></a>

            <div class="modal-body">
                <div id="container-modal">
        	    	
        	    </div>
            </div>

        </div>

	
        
        <script>
            $("#myModal .close-btn").click(function(){
				
                var favDialog = document.getElementById('myModal');
                favDialog.style.display = "none";

            })
                
        </script>

