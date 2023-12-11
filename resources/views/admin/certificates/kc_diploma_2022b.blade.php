<!doctype html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
	  <title>{{ $certificate['meta_title'] }}</title>
      <meta name="author" content="Knowcrunch">
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
         	padding: 0 70px;
         }


		.user-info {
         	margin-top: 52px;
         	margin-bottom: 10px;
         }


			.info-title{
				margin-top:6px!important;
				margin-left:9px!important;
		    	font-size:42px;
		    	letter-spacing:6px;
				text-transform: uppercase;
			}


			.award{
   				font-size:25px;
   				line-height:1;
				letter-spacing:0.4px;
				margin-top:2.5px!important;
				margin-left:10px!important;
			}


			.certi-title-new{
				margin-top:10px!important;
				margin-left:17px!important;
				line-height:0.9;
			}

			.certi-title-new p{

				font-size:42px;
				/* text-transform: uppercase; */
				letter-spacing:6.2px;

			}

			p{
				padding: 0!important;
				margin: 0 !important;;
			}

			.knowcrunch-logo{
				margin-top: 5px;
				margin-right: 25px;
				width:195px;
			}

			.signature{
				font-size:17px;
				letter-spacing:2px;
				line-height:20px;

			}

			.name-signature{
				font-size:22px;
				letter-spacing:3px;
				margin-top:-5px!important;
			}
			.after-name-signature{
				margin-top: -3px!important;
				font-size:14px;;
			}
			.signature-table {

				position: absolute;
				top: 68%;
			}
			.signature-line{
				margin-top:-2px;
				padding: 10px 0;
				max-width: 340px;
			}
			.footer {
				font-size:7px;
				position: absolute;
				top: 92%;
				margin-left:20px!important;
				margin-top:-13px!important;

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
				top:13%;
			}

			.background-element-right{
				position: absolute;
				max-width: 220px;
				/*transform: rotate(180deg);*/
				top:12%;
				left:81%
			}

			.signature-img{
			   max-width:200px;

			}

      </style>
   </head>
   <body>

		<img class="background-element-left" src="{{asset('theme/assets/images/certificates2022b/bg-image-left.png')}}">
		<img class="background-element-right" src="{{asset('theme/assets/images/certificates2022b/bg-image-right.png')}}">

		<div class="certificate">

         <table width="100%">

            <tbody>
               <tr>
					<td  align="center"> <img class="knowcrunch-logo" src="{{asset('theme/assets/images/certificates2022b/logo-knowcrunch.png')}}"></td>
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
						<p class="certi-title">

							{!! mb_strtoupper($certificate['certification_title']) !!}

			  			</p>
            	   </td>
            	</tr>

				</tbody>
         </table>

         <table class="signature-table" width="100%">
            <tbody>
					<tr>
						<td  align="center"><img class="signature-img" src="{{asset('theme/assets/images/certificates2022b/signature-aivalis.png')}}"></td>
               </tr>
               <tr>
						<td  align="center"> <img class="signature-line" src="{{asset('theme/assets/images/certificates2022b/line-signature.png')}}"></td>
               </tr>
				<tr>
					<td  class="signature" align="center"><p class="name-signature">TOLIS AIVALIS</p></td>
               </tr>
				<tr>
					<td class="signature"  align="center"><p class="after-name-signature"> HEAD OF CURRICULUM, KNOWCRUNCH</p> </td>
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
