@extends('theme.layouts.master')
@section('metas')

    <title>{{ $page['name'] }}</title>
   {!! $page->metable->getMetas() !!}

@endsection
@section('content')

<main id="" role="main">
   <section class="section-hero section-hero-small section-hero-blue-bg">
      <div class="container">
         <div class="hero-message">
         <h1>{{ $page['name'] }}</h1>
                    <h2>{{ $page['title'] }}</h2>
         </div>
      </div>
      <!-- /.section-hero -->
   </section>
   <section class="section-page-content">
        <div class="container">
            <div class="content-text-area">
               {!! $page['content'] !!}
            <!-- /.text-area -->
            <!-- /.container -->
            </div>
        </div>


   </section>

   @if(Auth::user() && !Auth::user()->terms)
   <section class="section-page-content">
   <div class="container">
        <form id="acceptconsent" method="POST" action="" class="content-text-area">
               {{ csrf_field() }}
               <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <div class="form-group text-center">
                        <div id="botview" class="form-group">
                           <label for="accept"><input id="accept" type="checkbox">
                            I have read, agree & accept the data privacy policy above.</label>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <div class="text-center">
                        <button title="I DON'T AGREE" class="btn btn--lg btn--primary conotSubmit mar-top-15" id="conotSubmit">I DON'T AGREE</button>
                        <button title="I AGREE & ACCEPT" class="btn btn--lg btn--secondary coSubmit mar-top-15" id="coSubmit">I AGREE & ACCEPT</button>
                        <!-- <br /> -->
                     </div>
                  </div>
               </div>

         </form>
        </div>

</section>
@endif
   <!-- /.section-page-content -->
</main>
@endsection
@section('scripts')
@if(Auth::user() && !Auth::user()->terms)
<script type="text/javascript">
   $(document).on('click', '.conotSubmit', function(e) {
       e.preventDefault();

       if(!$(this).hasClass('inconsent')) {
            window.location.replace('/logout');
       }
       else {
            alert('Please read all way down and accept our data privacy policy');
       }

   });

   $(document).on('click', '.coSubmit', function(e) {
       e.preventDefault();

       var thec = $('input#accept');
       if (thec.prop("checked") === false) {

           alert('Please read all way down and accept our data privacy policy');

       }
       else {


           var consentUrl = '/update-consent';

           var fdata = $("#acceptconsent").serialize();
         //  console.log(fdata);
           
           $.ajax({ url: consentUrl, type: "post",
               data: fdata,
               success: function(data) {
                   //alert('HO');
                   $('#acceptconsent').find("input[type=text]").removeClass('verror');
                   if (Number(data.status) === 0) {

                       $('.errorReponse').html(data.message);

                   } else {
                     
                      window.location.replace('/myaccount');
                   }
               }
           });

       }
       });

   /*$(window).on("scroll", function() {

       if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
           $('#botview').css('opacity', '1');
           $('#conotSubmit').css('opacity', '1');
            $('#coSubmit').css('opacity', '1');
         //  $('#coSubmit').removeClass('btn btn--lg btn--secondary btn--completed');
          // $('#conotSubmit').removeClass('btn btn--lg btn--secondary btn--completed');
        // $('#conotSubmit').addClass('btn btn--lg btn--primary')
        // $('#coSubmit').addClass('btn btn--lg btn--secondary');
       }
       else {
            $('#botview').css('opacity', '0');
            $('#conotSubmit').css('opacity', '0');
            $('#coSubmit').css('opacity', '0');
          //  $('#coSubmit').removeClass('btn btn--lg btn--secondary');
          //  $('#conotSubmit').removeClass('btn btn--lg btn--primary');
          //  $('#conotSubmit').addClass('btn btn--lg btn--secondary btn--completed')
        // $('#coSubmit').addClass('btn btn--lg btn--secondary btn--completed');
       }
   });*/

</script>
@endif
@stop
