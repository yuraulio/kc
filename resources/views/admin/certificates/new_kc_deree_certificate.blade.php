<!doctype html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
      <title>{!!$certificate['meta_title']!!}</title>
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
         }


		.user-info {
         	margin-top: 80px;
         	margin-bottom: 10px;
         }


			.info-title{
				margin-top:-11px!important;
				margin-left:15px!important;
		    	font-size:42px;
		    	letter-spacing:5px;
				text-transform: uppercase;
			}


			.award{
   				font-size:25px;
   				line-height:1;
				letter-spacing:0.4px;
				margin-top:-2px!important;


			}

			.certi-title-new{
				margin-top:10px!important;
				margin-left:17px!important;
				line-height:0.9;
			}

			.certi-title-new p{

				font-size:40px;
				text-transform: uppercase;
				letter-spacing:5.8px;

			}

			p{
				padding: 0;
				margin: 0 !important;;
			}

			.knowcrunch-logo{
				width:200px;
                margin-left:111px;
				margin-top:18px
			}

            .deree-logo{
                max-width: 70px;
                position:absolute;
                top:90px;
                left:680px;
            }

			.signature{
				font-size:13px;
				letter-spacing:2px;
				line-height:13px;
			}

			.name-signature{
				font-size:16px;
				letter-spacing:3px;
				margin-top:10px!important;
				margin-right:15px!important;
			}

			.name-signature-deree{
				font-size:16px;
				letter-spacing:3px;
				margin-top:10px!important;
				margin-left:20px!important;
			}

			.after-name-signature{
				font-size:12px!important;
				letter-spacing:2px!important;
				margin-top:-5.5px!important;
				margin-right:18px!important;
				line-height:0!important;
			}

			.after-name-signature-deree{
				font-size:12px!important;
				letter-spacing:2px!important;
				margin-left:18px!important;
				line-height:0.8!important;
			}

			.signature-table {

				position: absolute;
				top: 68%;
			}
			.signature-line{
				max-width: 280px;
				margin-right:25px;
				margin-top:5px;

			}

			.signature-line-deree{
				max-width: 280px;
				margin-top:5px;
			}
			.footer {
				font-size:7px;
				position: absolute;
				top: 92.3%;
				margin-left:40px!important;
				margin-top:-13px!important;

			}

			.footer p{
				letter-spacing:2.2px;
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
			top:13%;
			left:80.5%
		}

		.signature-img{
		   max-width:200px;
		   margin-right:25px;

		}

        .signature-img-deree{
		   max-width:150px;

		}

        .knowcrunch-signature{
            padding-left:130px;
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

								has completed our training and is hereby awarded this

			  				</p>
            	   </td>
            	</tr>

					<tr>
            	   <td class="certi-title-new" align="center">
							<p class="certi-title">

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
                    <td class="deree-signature" align="center"> <img class="signature-line-deree" src="{{asset('theme/assets/images/certificates2022b/line-signature.png')}}"></td>
               </tr>
				<tr>
					<td class="knowcrunch-signature signature name-signature" align="center">TOLIS AIVALIS</td>
                    <td class="signature name-signature deree-signature name-signature-deree" align="center">ARETI KREPAPA, PHD</td>
               </tr>
				<tr>
					<td class="knowcrunch-signature signature after-name-signature" align="center"> <p > HEAD OF CURRICULUM, KNOWCRUNCH</p> </td>
                    <td class="signature deree-signature after-name-signature-deree"  align="center" > DEAN OF DEREE SCHOOL OF GRADUATE </br>AND PROFESSIONAL EDUCATION </td>
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
