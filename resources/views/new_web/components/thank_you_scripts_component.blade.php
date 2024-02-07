<script>
  console.log('Stating dataLayer process purchase');
</script>

@php
    $thankyouData = $_SESSION["thankyouData"] ?? null;
    $_SESSION["thankyouData"] = null;
@endphp

@if(isset($thankyouData['tigran']) && isset($thankyouData['tigran']['Price']) &&$thankyouData['tigran']['Price'] > 0 && !env('APP_DEBUG'))

    <script>
    $(document).ready(function(){
        @foreach($thankyouData['tigran'] as $key => $ti)
            dataLayer.push({"{{$key}}": $.parseHTML("{{$ti}}")[0].data})
        @endforeach
    })
    </script>

@endif

@if(isset($thankyouData['customer']) && !env('APP_DEBUG'))

    <script>
    $(document).ready(function(){
        @foreach($thankyouData['customer'] as $key => $ti)
            dataLayer.push({"{{$key}}": $.parseHTML("{{$ti}}")[0].data})
        @endforeach
    })
    </script>

@endif

@if(isset($thankyouData['ecommerce']) && isset($thankyouData['ecommerce']['actionField']['value']) && $thankyouData['ecommerce']['actionField']['value'] > 0 && !env('APP_DEBUG'))

    <script>
    $(document).ready(function(){
        dataLayer.push({ ecommerce: null });
        let actionField = {};
        let products = {};

        @foreach($thankyouData['ecommerce']['actionField'] as $key => $ti)
            @if($ti != '')
                actionField["{{$key}}"] =  $.parseHTML("{{$ti}}")[0].data

            @endif
        @endforeach

        @foreach($thankyouData['ecommerce']['products'] as $key => $ti)
            @if($ti != '')
                products["{{$key}}"] = $.parseHTML("{{$ti}}")[0].data
            @endif
        @endforeach

        // dataLayer.push({
        //     'event': 'purchase',
        //     'ecommerce': {
        //         'purchase': {
        //         'actionField': actionField,
        //         'products': [products]
        //         }
        //     }
        // });
    })
    </script>
@endif


@if(isset($thankyouData['new_event']) && isset($thankyouData['new_event']['value']) && count($thankyouData['new_event']['items']) > 0 && !env('APP_DEBUG'))
<script>
   $(document).ready(function(){
      let a = {};
      let items = {};

      try{
        @foreach($thankyouData['new_event'] as $key => $ti)


          @if(gettype($ti) != 'array' && $ti != '')
              a["{{$key}}"] =  $.parseHTML("{{$ti}}")[0].data

          @endif

        @endforeach

        @foreach($thankyouData['new_event']['items'] as $key => $ti)
          @if($ti != '')
              items["{{$key}}"] = $.parseHTML("{{$ti}}")[0].data
          @endif

        @endforeach
      }catch(e){
        console.log('There is an error adding items 1');
      }

        let data = {
            'event': 'purchase',
            'items': [items]
        };

      try{
        @foreach($thankyouData['customer'] as $key => $value)
          @if($ti != '')
              data["{{$key}}"] = $.parseHTML("{{$value}}")[0].data
          @endif
        @endforeach
      }catch(e){
        console.log('There is an error adding items 2');
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
        console.log('dataLayer:', data);

   });

</script>
@endif

@if(isset($tigran) && isset($tigran['Price']) && $tigran['Price'] > 0 && !env('APP_DEBUG'))
    <script>
        $(document).ready(function(){
            @foreach($tigran as $key => $ti)
                dataLayer.push({"{{$key}}": $.parseHTML("{{$ti}}")[0].data})
            @endforeach
        })
    </script>
@endif

@if(isset($ecommerce) && isset($ecommerce['actionField']['value']) && $ecommerce['actionField']['value'] > 0 && !env('APP_DEBUG'))
    <script>
        $(document).ready(function(){
            dataLayer.push({ ecommerce: null });
            let actionField = {};
            let products = {};

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

            console.log('test 23: ')
           console.log(dataLayer)

            // dataLayer.push({
            //     'event': 'purchase',
            //     'ecommerce': {
            //         'purchase': {
            //         'actionField': actionField,
            //         'products': [products]
            //         }
            //     }
            // });
        })
    </script>


@endif
