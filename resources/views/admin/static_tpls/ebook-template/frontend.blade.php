@extends('theme.layouts.master')
@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
@include('theme.preview.preview_warning', ["id" => $content->id, "type" => "content", "status" => $content->status])
<main id="main-area" class="with-hero" role="main">
   @if (!empty($content['featured']) && isset($content['featured'][0]) &&isset($content['featured'][0]['media']) && !empty($content['featured'][0]['media']))
   <section class="section-hero" style="background-image:url({{ $frontHelp->pImg($content, 'header-image') }})">
      <div class="overlay"></div>
      <div class="container">
         <div class="hero-message pad-r-col-6">
            <h1>{{ $content->title }}</h1>
            <h2>{{ $content->subtitle }}</h2>
         </div>
      </div>
   </section>
   @else
   <section class="section-hero section-hero-small section-hero-blue-bg">
      <div class="container">
         <div class="hero-message">
            <h1>{{ $content->title }}</h1>
         </div>
      </div>
   </section>
   @endif
   <section class="form-section">
      <div class="container">
         <div class="row">
            <div class="col6 col-sm-12">
               <div class="text-area">
                  {!! $content->body !!}
               </div>
            </div>
            <div class="col6 col-sm-12">
               <div class="form-area-wrapper">
                  <div class="form-wrapper blue-form">
                     <form method="post" action='/send-ebook-email' id="doall" novalidate>
					 {{ csrf_field() }}
                        <h3 class="form-h3">Request for corporate training quotation</h3>
                        <label>First name <span>*</span></label>
                        <div class="input-safe-wrapper">
                           <input class="required" type="text" id="cfirstname" name="cfirstname"/>
                        </div>
                        <label>Last name <span>*</span></label>
                        <div class="input-safe-wrapper">
                           <input class="required" type="text"  id="csurname" name="csurname">
                        </div>
                        <label>Email <span>*</span></label>
                        <div class="input-safe-wrapper">
                           <input class="required" type="email" id="cemail" name="cemail">
                        </div>
                        <label>Mobile phone <span>*</span></label>
                        <div class="input-safe-wrapper">
                           <input class="required"type="text" id="ctel" name="ctel" >
                        </div>
                        <div class="checkbox-row custom-checkbox-wrapper">
                           <div class="custom-checkbox">
                              <input type="checkbox" id="accept" name="accept" value="accept">
                              <span></span>
                           </div>
                           <label for="receive-messages">I have read, agree upon & accept the <a href="/terms" target="_blank">terms & <br/>conditions</a> and <a href="/data-privacy-policy" target="_blank">data privacy policy</a>. </label>
                        </div>
                        <div class="submit-area-custom">
                           <button id="sendme1" type="button" class="btn btn--md btn--secondary contactUsSubmit1">Send me my guide!</button>
                        </div>
                     </form>
                     <div class="alert-outer" hidden>
                        <div class="container">
                           <div class="alert-wrapper success-alert">
                              <div class="alert-inner">
                                 <p id="coorporate-success"></p>
                                 <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
                              </div>
                           </div>
                        </div>
                        <!-- /.alert-outer -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- /.form-section -->
   </section>
</main>
@endsection
@section('scripts')
<script>
   document.getElementById('header').classList.add('header-transparent');
</script>
<script>
   /*@if (!empty($content['featured']) && isset($content['featured'][0]) &&isset($content['featured'][0]['media']) && !empty($content['featured'][0]['media']))
   $('.section-hero').css({background:url({{ $frontHelp->pImg($content, 'header-image') }})})
   @endif*/

</script>
<script type="text/javascript">
   $.fn.inputFilter = function(inputFilter) {
       return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
         if (inputFilter(this.value)) {
           this.oldValue = this.value;
           this.oldSelectionStart = this.selectionStart;
           this.oldSelectionEnd = this.selectionEnd;
         } else if (this.hasOwnProperty("oldValue")) {
           this.value = this.oldValue;
           this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
         }
       });
     };
     $("#ctel").inputFilter(function(value) {
     return /^\d*$/.test(value);
   });
   //email function
   $("#sendme1").click(function() {
   		var firstname = $("#cfirstname").val();
   		var surname = $("#csurname").val();

   		var email = $("#cemail").val();
   		//var comment = $("#comment").val();
   		var tel = $("#ctel").val();
   		$("#returnmessage").empty(); // To empty previous error/success message.
   		// Checking for blank fields.
   		var thec = $('input#accept');
        if (thec.prop("checked") === false) {

          alert('Please accept our data privacy policy');

        }else {
   			if (email == '' || surname == '' || firstname == ''   || tel == ''  ) {
   				alert("Please Fill Required Fields");
   			} else {
   			// Returns successful data submission message when the entered information is stored in database.
   			$("#doall").submit();

   				$("#doall")[0].reset(); // To reset form fields on success.
   				bq('track', 'Lead');
   			}
		}

   });


</script>
{{--<script> (function(){ window.ldfdr = window.ldfdr || {}; (function(d, s, ss, fs){ fs = d.getElementsByTagName(s)[0]; function ce(src){ var cs = d.createElement(s); cs.src = src; setTimeout(function(){fs.parentNode.insertBefore(cs,fs)}, 1); } ce(ss); })(document, 'script', 'https://sc.lfeeder.com/lftracker_v1_kn9Eq4RdQXY8RlvP.js'); })(); </script>--}}
@stop
