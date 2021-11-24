<head>
		<meta charset="utf-8">
		<title>Know Crunch</title>
		<meta name="description" content="Know Crunch">
		<meta name="author" content="Epsilon-8">
		<meta name="ad.size" content="width=300,height=250">
		
	   <script type="text/javascript">
		  	var clickTag = window.location.origin + '/e-learning-digital-social-media-marketing?utm_source=PopUp&utm_medium=Banner&utm_campaign=DIGITAL_ELEARNING_BLACKFRIDAY';
	   </script>
		
		<style>
		
         #myModal #container {				
				width: 300px;
				height: 250px;
				overflow: hidden;
				position: relative;
				box-sizing: border-box;
				box-shadow: 0 0 2px #cccccc;
				cursor: pointer;
			}
			#myModal #container:hover #cta {
				filter: brightness(1.1);
			}
			#myModal .screen {
				width: 100%;
				height: 100%;
				position: absolute;
				left: 0; top: 0;
				background-repeat: no-repeat;
			}
			#myModal img {position: absolute;}
			#myModal #container {
				background-image: url(blackfriday/images/bg.jpg); 
				background-size: cover;
			}
			#myModal #text1 {
				left: 0px;
				top: 125px;
			}
			#myModal #text2 {
				left: 20px;
				top: 135px;
			}
			#myModal #cta {
				left: 20px;
				top: 200px;
			}
           
            #myModal .close-btn{
                top: 0;
                position: absolute;
                right: -30px;
            }

            .modal-body{
                background:#419cff;
            }
            @media(min-width: 981px){
                #myModal{
                outline: none;
                position: fixed;
                right: 0;
                bottom: 0;
                /* transform: translate(-50%, 50%); */
                display: none;
            }
                .modal-border{
                    display: block;
                    margin: 10px 80px;
                    box-shadow: 0px 0px 13px #0000009e;
                }
            }      

            @media(max-width: 980px){
                #myModal{
                    outline: none;
                    position: absolute;
                    left: 50%;
                    top: 50%;
                    transform: translate(-50%, 50%); 
                    display: none;
                }

                .modal-border{
                    margin: 10px -10px;
                    box-shadow: 0px 0px 13px #0000009e;
                }
            }

		</style>
    </head><!-- Modal -->
    
    
        <div id="myModal" class="modal hide fade modal-border" tabindex="-1" role="dialog" data-backdrop="true" aria-labelledby="myModalLabel" aria-hidden="true" hidden>
        <a href="#" class="close-btn"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" class="replace-with-svg" alt="Close"></a>

            <div class="modal-body">
                <div id="container" onclick="javascript:void(window.location.href = clickTag)">
        	    	<div class="screen" id="s1">
        	    		<img id="text1" src="./blackfriday/images/text1.png" width="230" alt="ΠΡΟΣΦΟΡΑ"/>
        	    		<img id="text2" src="./blackfriday/images/text2.png" width="211" alt="ΠΡΟΣΦΟΡΑ"/>
        	    		<img id="cta" src="./blackfriday/images/cta.png" width="164" alt="ΠΡΟΣΦΟΡΑ"/>
        	    	</div>
        	    </div>
            </div>

        </div>

		
		<script src="./blackfriday/js.js"></script>
		<script type="text/javascript">
			var sceneDuration = 4;
			var tl = gsap.timeline({repeat: -1});
			tl.from("#s1", 1, {opacity:0} )				
				.from("#text1", 0.5, {x:-20, opacity:0, ease:Sine.easeInOut} )
				.from("#cta", 0.5, {scale:0, opacity:0, ease:Back.easeOut }, "-=0.2" )
				.from("#text2", 0.5, {x:-20, opacity:0, ease:Sine.easeInOut}, "+=4" )
				.to("#text1", 0.5, {opacity:0, ease:Sine.easeInOut}, "-=0.5" )
				.to("#s1", 0.2, {autoAlpha: 0, delay: sceneDuration })
				.play();
        </script>
        
        <script>
            $("#myModal .close-btn").click(function(){
    
                var favDialog = document.getElementById('myModal');
                favDialog.style.display = "none";

            })
                
        </script>

