<!doctype html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
      <title>{{$certificate['meta_title']}}</title>
      <meta name="author" content="Knowcrunch">
      <style type="text/css">
         @font-face {
         font-family: 'Foco';
         	/*src: url("{{ storage_path('fonts\Foco_Lt.ttf') }}") format("truetype");*/
         	src: url("{{ asset('/fonts/Foco_Lt.ttf') }}") format("truetype");
         }
         @font-face {
         font-family: 'Foco-Bold';
         	/*src: url("{{ storage_path('fonts\Foco_Lt.ttf') }}") format("truetype");*/
         	src: url("{{ asset('/fonts/Foco_Bd.ttf') }}") format("truetype");
			 font-weight: bold;
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
         	margin-top: 100px;
         	margin-bottom: 10px;
         }


         .info-title p{
			font-size:25px!important;
			line-height:15px;
			letter-spacing:0.5px;
			/*text-transform: uppercase;*/
		}


        .award p{

        line-height:0.8;
        margin: 0;
        display:inline;
        float:none;
        text-transform: lowercase;

        }

			p.certi-title{
				line-height:40px;
			}


			.certi-title-new p{

			font-size:27px;
			line-height:35px;
			text-transform: uppercase;
			letter-spacing:3.8px;
			margin-top: -3.3px!important;
			margin-left: 20px!important;
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
				margin-right:10px!important;
			}

			.name-signature-deree{
				font-size:16px;
				letter-spacing:3px;
				margin-top:10px!important;
				margin-left:25px!important;
			}

			.after-name-signature{
				font-size:12px!important;
				letter-spacing:2px!important;
				margin-top:-5.3px!important;
				margin-right:18px!important;
				line-height:0!important;
			}

			.after-name-signature-deree{
				font-size:12px!important;
				letter-spacing:2px!important;
				margin-left:18px!important;
				line-height:0.9!important;
			}
			.signature-line{
				max-width: 280px;
				margin-right:25px;
				margin-top:0px;

			}

			.signature-line-deree{
				max-width: 280px;
				margin-top:0px;
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
			   margin-right:26px;

			}

            .signature-img-deree{
			   max-width:150px;
			   margin-left:25px;

			}

            .knowcrunch-signature{
                padding-left:120px;
            }

            .deree-signature{
                padding-right:120px;
            }

            .bold {
			font-family: 'Foco-Bold'!important;
			font-weight:bold!important;

		}

        .user-name{
			margin-top:-12px!important;
			margin-left:10px!important;
		    font-size:42px;
		    letter-spacing:5px;
			text-transform: uppercase;
		}
		.signature-table {

			position: absolute;
			top: 68%;
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
						<p class="user-name">{{$certificate['firstname']}} {{$certificate['lastname']}} </p>
            	   </td>
            	</tr>

				<tr>
            	    <td class="info-title" align="center">
							<p > has attended our course </p>
					</td>


            	</tr>

				<tr align="center">


					<td class="certi-title-new">
						<p>{!! $certificate['certificate_event_title'] !!}</p>
					</td>





            	</tr>

				<tr align="center">

					<td  class="info-title award">
						<p > and is awarded this  </p><p class="bold">{{$certificate['certification_title']}}.</p>
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
