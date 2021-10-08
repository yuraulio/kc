
@extends('theme.layouts.master')
@section('metas')
<title>Cart</title>
@endsection
@section('content')


    @if((isset($info) && isset($info['success'])) && $info['success'] === true)
<main id="main-area" class="no-pad-top" role="main">
   <section class="section-text-img-blue">
      <div class="container">
         <div class="columns-wrapper">
            <div class="row row-flex">
               <div class="col-7 col-sm-12">
                  <div class="text-area">
                     <?php echo ((isset($info) && isset($info['title'])) ? $info['title'] : 'Info') ?>
                     <?php $res = json_decode($info['transaction']['payment_response'], true); ?>
                     {!! $info['message'] !!}

                     <p>Proceed to <a href="/myaccount" class="dark-bg">your account</a>.</p>
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
@endif

@stop