@extends('theme.layouts.master')
@section('content')

<main id="main-area" class="no-pad-top" role="main">
   <section class="section-text-img-blue">
      <div class="container">
         <div class="columns-wrapper">
            <div class="row row-flex">
               <div class="col-7 col-sm-12">
                  <div class="text-area">
                     <?php echo ((isset($info) && isset($info['title'])) ? $info['title'] : 'Info') ?>

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


@stop

@section('scripts')

@if(isset($tigran) && isset($tigran['Price']) && $tigran['Price'] > 0 && !config('app.debug'))

<script>
$(document).ready(function(){
   @foreach($tigran as $key => $ti)
      dataLayer.push({"{{$key}}": $.parseHTML("{{$ti}}")[0].data})
   @endforeach
})
</script>

@endif

@if(isset($ecommerce) && isset($ecommerce['actionField']['value']) && $ecommerce['actionField']['value'] > 0 && !config('app.debug'))

<script>
   $(document).ready(function(){
   dataLayer.push({ ecommerce: null });
   let actionField = {};
   let products = {};

   //dataLayer.push({"event": 'purchase'})

   @foreach($ecommerce['actionField'] as $key => $ti)

      @if($ti != '')
         actionField["{{$key}}"] =  $.parseHTML("{{$ti}}")[0].data

      @endif

   @endforeach

   @foreach($ecommerce['products'] as $key => $ti)
      @if($ti != '')
         products["{{$key}}"] = $.parseHTML("{{$ti}}")[0].data
      @endif

   @endforeach

   dataLayer.push({"ecommerce": ecommerce})

   dataLayer.push({
      'event': 'purchase',
      'ecommerce': {
         'purchase': {
            'actionField': actionField,
            'products': [products]
         }
      }
   });

   })
</script>


@endif

@if(isset($new_event) && isset($new_event['value']) && count($new_event['items']) > 0 && !config('app.debug'))
<script>
   $(document).ready(function(){
      dataLayer.push({ });
      let a = {};
      let items = {};

      @foreach($new_event as $key => $ti)


         @if($ti != '' && gettype($ti) != 'array')
            a["{{$key}}"] =  $.parseHTML("{{$ti}}")[0].data

         @endif

      @endforeach

      @foreach($new_event['items'] as $key => $ti)
         @if($ti != '')
         items["{{$key}}"] = $.parseHTML("{{$ti}}")[0].data
         @endif

      @endforeach

      let data = {
         'event': 'purchase',
         'items': [items]
        }

        data = {...data,...a}



      data.value = Number(data.value)

      data['items'].forEach((item, index) => {


         if(item.price !== undefined){
            data['items'][index].price = Number(item.price)
         }
         if(item.quantity !== undefined){
            data['items'][index].quantity = Number(item.quantity)
         }


      });


      dataLayer.push(data);


   });

</script>
@endif


@stop
