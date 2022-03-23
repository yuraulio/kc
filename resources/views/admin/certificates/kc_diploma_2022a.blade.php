<!doctype html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
      <title>Certificate</title>
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
         	margin-top:70px;
         	padding: 0 70px;
         }
       
         
			.user-info {
         	margin-top: 70px;
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
				padding: 20px 0;

			}

			p{
				padding: 0;
				margin: 0 !important;;
			}

			.knowcrunch-logo{
				width:200px;
			}

			.signature{
				font-size:20px;
				letter-spacing:2px;
			}
			.signature-table {

				position: absolute;
				top: 65%;
			}

			.footer {

				position: absolute;
				top: 90%;
			}

			.footer p{
				letter-spacing:2px;
				text-transform: uppercase;

         }

			.footer span{
				margin:0 10px;
         }

			.background-element-left{
				position: absolute;
				max-width: 220px;
				top:10%;
			}

			.background-element-right{
				position: absolute;
				max-width: 220px;
				transform: rotate(180deg);
				top:10%;
				left:81%
			}

			.signature-img{
			   max-width:150px;

			}
        
      </style>
   </head>
   <body>
		
		<img class="background-element-left" src="{{asset('theme/assets/images/certificates2022/half-background-element.png')}}">
		<img class="background-element-right" src="{{asset('theme/assets/images/certificates2022/half-background-element.png')}}">

		<div class="certificate">

         <table width="100%">
            
            <tbody>
               <tr>
						<td  align="center"> <img class="knowcrunch-logo" src="{{asset('theme/assets/images/certificates2022/knowcrunch-logo.png')}}"></td>
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
            	   <td align="center">
							<p class="info-title">
               
								{{$certificate['certification_title']}}

			  				</p>
            	   </td>
            	</tr>
					
				</tbody>
         </table>

         <table class="signature-table" width="100%">
            <tbody>
					<tr>
						<td  align="center"><img class="signature-img" src="{{asset('theme/assets/images/certificates2021/knowcrunch-signature.png')}}"></td>
               </tr>
               <tr>
						<td  align="center"> <img src="{{asset('theme/assets/images/certificates2022/signature-line.png')}}"></td>
               </tr>
					<tr>
						<td  class="signature" align="center"> APOSTOLOS AIVALIS</td>
               </tr>
					<tr>
						<td class="signature"  align="center"> HEAD OF CURRICULUM, KNOWCRUNCH </td>
               </tr>
            </tbody>
         </table>

			<table class="footer" width="100%">
            <tbody>
               <tr>
						<td  align="center"> 
							<p > 
								Issue date: {{$certificate['certification_date']}}  @if($certificate['expiration_date']) <span> | </span> Expiration date: {{$certificate['expiration_date']}} @endif <span> | </span>    Credential: {{$certificate['credential']}} 
							</p>
						</td>
               </tr>
					
            </tbody>
         </table>

      </div>
     
   </body>
</html>