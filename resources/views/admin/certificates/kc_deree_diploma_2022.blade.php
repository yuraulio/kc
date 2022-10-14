<!doctype html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
      <title>{{$certificate['meta_title']}}</title>
      <style type="text/css">
         @font-face {
         font-family: 'Foco';
         	/*src: url("{{ storage_path('fonts\Foco_Lt.ttf') }}") format("truetype");*/
         	src: url("{{ asset('/fonts/Foco_Lt.ttf') }}") format("truetype");
         }
         @page {
         	margin: 0px;
         }
         body {
         	margin: 0px;
				background:#2e58a6;
				color:#fff;
         }

         *{
         	font-family: 'Foco';
         }
         
         
         table {
         	font-size: x-small;
         }
         tfoot tr td {
         	font-weight: bold;
         	font-size: x-small;
         }
         
         .certificate{
         	margin-top:100px;
         }
       
         
		.user-info {
         	margin-top: 80px;
         	margin-bottom: 10px;
         }


			.info-title{
		    	font-size:42px;
		    	letter-spacing:5px;
				text-transform: uppercase;
			}


			.award{
   				font-size:25px;
   				line-height:1;
				padding: 10px 0;
				

			}

			p.certi-title{
				line-height:40px;
			}
			
			
			.certi-title-new p{
				font-size:42px;
				line-height:40px;
				text-transform: uppercase;
				letter-spacing:5px;
			}

			p{
				padding: 0;
				margin: 0 !important;;
			}

			.knowcrunch-logo{
				width:200px;            
                margin-left:80px;
			}

            .deree-logo{
                max-width: 70px;
                position:absolute;
                top:75px;
                left:670px;
            }

			.signature{
				font-size:13px;
				letter-spacing:2px;
				line-height:13px;
			}

			.name-signature{
				font-size:16px;
				letter-spacing:3px;
			}

			.signature-table {

				position: absolute;
				top: 68%;
			}
			.signature-line{
				padding: 10px 0;
				max-width: 300px;
			}
			.footer {
				font-size:8px;
				position: absolute;
				top: 92%;
			}

			.footer p{
				letter-spacing:2px;
				text-transform: uppercase;

         }

			.footer span{
				margin:0 0;
				color:#fff;
				font-size: 11px;
				
         }

			.background-element-left{
				position: absolute;
				max-width: 210px;
				top:15%;
			}

			.background-element-right{
				position: absolute;
				max-width: 210px;
				/*transform: rotate(180deg);*/
				top:15%;
				left:82%
			}

			.signature-img{
			   max-width:200px;

			}

            .signature-img-deree{
			   max-width:150px;

			}

            .knowcrunch-signature{
                padding-left:120px;
            }

            .deree-signature{
                padding-right:120px;
            }

        
      </style>
   </head>
   <body>
		
		<img class="background-element-left" src="{{asset('theme/assets/images/certificates2022b/bg-image-left.png')}}">
		<img class="background-element-right" src="{{asset('theme/assets/images/certificates2022b/bg-image-right.png')}}">

		<div class="certificate">

            <table width="100%">
               
               <tbody>
                  <tr >
		    		    <td  align="center"> <img class="knowcrunch-logo" src="{{asset('theme/assets/images/certificates2022b/logo-knowcrunch.png')}}"></td>
                        <td  align="center"> <img class="deree-logo" src="{{asset('theme/assets/images/certificates2022b/logo-deree.png')}}"></td>
		    		</tr>
                 
               </tbody>
            </table>

         <table class="user-info" width="100%">
				<tbody>
            	<tr class="">
            	   <td align="center">
							<p class="info-title"> {{$certificate['firstname']}} {{$certificate['lastname']}} </p>
            	   </td>
            	</tr>

					<tr>
            	   <td align="center">
							<p class="award">
               
								has successfully completed all exams and is hereby awarded this

			  				</p>
            	   </td>
            	</tr>

					<tr>
            	   <td class="certi-title-new" align="center">
							<p class="info-title certi-title">
               
								{!! $certificate['certification_title'] !!}

			  				</p>
            	   </td>
            	</tr>
					
				</tbody>
         </table>

         <table class="signature-table" width="100%">
            <tbody>
				<tr >
					<td class="knowcrunch-signature" align="center"><img class="signature-img" src="{{asset('theme/assets/images/certificates2022b/signature-aivalis.png')}}"></td>
                    <td  class="deree-signature" align="center"><img class="signature-img-deree" src="{{asset('theme/assets/images/certificates2022b/signature-krepapa.png')}}"></td>
               </tr>
               <tr >
					<td class="knowcrunch-signature" align="center"> <img class="signature-line" src="{{asset('theme/assets/images/certificates2022b/line-signature.png')}}"></td>
                    <td class="deree-signature" align="center"> <img class="signature-line" src="{{asset('theme/assets/images/certificates2022b/line-signature.png')}}"></td>
               </tr>
				<tr>
					<td class="knowcrunch-signature signature name-signature" align="center">TOLIS AIVALIS</td>
                    <td class="signature name-signature deree-signature" align="center">ARETI KREPAPA, PHD</td>
               </tr>
				<tr>
					<td class="knowcrunch-signature signature" align="center"> <p style="line-height:0;"> HEAD OF CURRICULUM, KNOWCRUNCH</p> </td>
                    <td class="signature deree-signature"  align="center" style="line-height:13px"> DEAN OF DEREE SCHOOL OF GRADUATE </br>AND PROFESSIONAL EDUCATION </td>
               </tr>
            </tbody>
         </table>

			<table class="footer" width="100%">
            <tbody>
               <tr>
					<td  align="center"> 
						<p > 
							ISSUED: {{$certificate['certification_date']}} <span> | </span>    Credential NO.: {{$certificate['credential']}} 
						</p>
					</td>
               </tr>
					
            </tbody>
         </table>

      </div>
     
   </body>
</html>