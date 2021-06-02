<!doctype html>
<html lang="en" class="no-js">
   <head>
      <base href="{!! URL::to('/') !!}/" target="_self" />
      <meta charset="UTF-8">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      @include('theme.layouts.favicons')
      @include('theme.layouts.header_scripts')
      </script>
   </head>
   <body>
      <div class="page-wrapper non-pointer">
         <div  class="login-popup-wrapper active">
            <div id="login-popup" class="login-popup">
               <div class="heading">
                  <p class="text-center">Seems you are trying to access your account from a new device or browser. Enter the code you will get by SMS.</p>
               </div>
					@if (\Session::has('errors'))
               <div class="alert-outer">
                  <div class="alert-wrapper error-alert">
                     <div class="alert-inner">
                        <p id="account-error">{!! \Session::get('errors') !!}</p>
                     </div>
                  </div>
                  <!-- /.alert-outer -->
               </div>
					@endif
               <form method="post" action="/smsVerification">

					{{ csrf_field() }}
						<div class="input-wrapper input-wrapper--text input-wrapper--email">
							{{--<span class="icon"><img width="14" src="{{cdn('/theme/assets/images/icons/icon-email.svg')}}" alt=""></span>--}}
							<input type="text" onblur="this.placeholder = '4 digit code'" onfocus="this.placeholder = ''" placeholder="4 digit code" id="sms" name="sms">
						</div>
						<input name="cookie_value" value="{{$slug}}" hidden>
						<input type="submit"  value="Verify">
                  </br>
						<input id="logout-sms" class="btn btn--lg btn--primary top-15" type="button" onclick="" value="LOGOUT">
					</form>
            </div>
            <!-- ./login-popup -->
         </div>
         <!-- ./login-popup-wrapper -->
         <!--<script src="{{ URL::to('/assets/colorbox/jquery.colorbox.js') }}">></script>-->
      </div>
      @include('theme.layouts.footer_scripts')
      </div>
     

   <script>

      $("#logout-sms").click(function(){
         window.location='/logmeout';
      })

   </script>

   </body>
</html>