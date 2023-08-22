
@extends('theme.layouts.master')
@section('metas')
<title>Thank you</title>
<meta name="robots" content="noindex, nofollow" />
@endsection
@section('content')


   
<main id="main-area" class="no-pad-top" role="main">
   <section class="section-text-img-blue">
      <div class="container">
         <div class="columns-wrapper">
            <div class="row row-flex">
               <div class="col-7 col-sm-12">
                  <div class="text-area">
                     <p>TEST TEST TEST SEPA</p>
                     <?php echo ((isset($info) && isset($info['title'])) ? $info['title'] : 'Info') ?>
                     <?php ///$res = json_decode($info['transaction']['payment_response'], true); ?>
                     {!! $info['message'] !!}

                     {{--<p>Proceed to <a href="/myaccount" class="dark-bg">your account</a>.</p>--}}
                  </div>
                  <div class="text-area social-icons">
                     <p></p>
                  <p>Share your experience with the world!</p>


                     <ul class="clearfix">
                        <li><a target="_blank" title="Share on facebook" href="http://www.facebook.com/sharer.php?u={{$event['facebook']}}" onclick="javascript:window.open(this.href,
                            '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=300');return false;">
                            <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Facebook.svg')}}" width="23" alt="Share on facebook"></a></li>

                        <li><a target="_blank" title="Share on Twitter" href="http://twitter.com/share?text={{$event['twitter']}}&url={{ url('/') }}/{{$event['slug']}}?utm_source=Twitter&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING" onclick="javascript:window.open(this.href,
                           '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                           <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Twitter.svg')}}" width="23" alt="Share on Twitter"></a></li>

                        <li><a target="_blank" title="Share on LinkedIn" href="https://www.linkedin.com/shareArticle?mini=true&url={{$event['linkedin']}};" onclick="javascript:window.open(this.href,
                              '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                              <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Linkedin.svg')}}" width="23" alt="Share on LinkedIn"></a></li>
                     </ul>

                  </div>
               </div>
               <div class="col-5 col-sm-12">
                  <div class="image-wrapper">
                     <img src="{{cdn('/theme/assets/images/thank-you-img.png')}}" alt="Thank You"/>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</main>


@stop

