@extends("new_web.layouts.master")

@section('metas')

    @if(isset($dynamic_page_data['event']) && !$dynamic_page_data['event']['index'] )
        <meta name="robots" content="noindex, nofollow" />
    @endif

@stop
@section('blog-custom-css')
    <link type="text/css" href="{{ asset('binshops-blog.css') }}" rel="stylesheet">

    <style>
        #chatbase-bubble-button{
            bottom: 0.5rem !important;
            right: 4rem !important;
        }
    </style>
@endsection

@section("content")
    @if(config("binshopsblog.reading_progress_bar"))
        <div id="scrollbar">
            <div id="scrollbar-bg"></div>
        </div>
    @endif

    <main id="main-area" role="main" class="bootstrap-classes">
        <div id="app">
            @foreach ($content as $data)
                @include("new_web.layouts.rows")

            @endforeach
        </div>
    </main>
@endsection

@section('blog-custom-js')
    <script src="{{asset('binshops-blog.js')}}"></script>
    <script src="{{asset('js/blog.js')}}"></script>
@endsection

@if(isset($renderFbChat) && $renderFbChat)
@section('fbchat')
<script>
  window.chatbaseConfig = {
    chatbotId: "XsnNyFmqIh9qjjBBG7JUp",
  }
</script>
<script
  src="https://www.chatbase.co/embed.min.js"
  id="XsnNyFmqIh9qjjBBG7JUp"
  defer>
</script>
        {{--<div id="fb-root"></div>
        <script>
            window.fbAsyncInit = function() {
            FB.init({
                xfbml            : true,
                version          : 'v5.0'
            });
            };
            (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <!-- Your customer chat code -->
        <div class="fb-customerchat"
        attribution=install_email
        page_id="486868751386439">
        </div>--}}
@endsection
@endif


@push('components-scripts')

    @if(isset($dynamic_page_data['new_event']) && !env('APP_DEBUG'))
    

    <script>
        $(document).ready(function(){
            dataLayer.push({ 'event': "{{$dynamic_page_data['new_event']['event']}}"})

        })
    </script>
    @endif


    @if(isset($dynamic_page_data['tigran']) && !env('APP_DEBUG'))
        <script>
            $(document).ready(function(){
                dataLayer.push({'Event_ID':"{{$dynamic_page_data['tigran']['Event_ID']}}v", 'event': 'ViewContent', 'Product_id' : "{{$dynamic_page_data['tigran']['Product_id']}}", 'Price': "{{$dynamic_page_data['tigran']['Price']}}",
                                'ProductCategory':"{{$dynamic_page_data['tigran']['ProductCategory']}}","product":"product","ProductName":$.parseHTML("{{ $dynamic_page_data['tigran']['ProductName'] }}")[0].data});
            })
            </script>

            <script>

            $(document).ready(function(){
                dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
                dataLayer.push({
                    'ecommerce': {
                    'detail': {
                        'products': [{
                        'name': $.parseHTML("{{ $dynamic_page_data['tigran']['ProductName'] }}")[0].data,
                        'id': "{{$dynamic_page_data['tigran']['Product_id']}}",
                        'price': "{{$dynamic_page_data['tigran']['Price']}}",
                        'brand': 'Knowcrunch',
                        'category': "{{$dynamic_page_data['tigran']['ProductCategory']}}",
                        }]
                    }
                    }
                });

                dataLayer.push({
                    'event': 'view_item',
                    'currency': 'EUR',
                    'value': "{{$dynamic_page_data['tigran']['Price']}}",
                    'items': [{
                        'item_id': "{{$dynamic_page_data['tigran']['Product_id']}}",
                        'item_name': $.parseHTML("{{ $dynamic_page_data['tigran']['ProductName'] }}")[0].data,
                        'item_brand': "Knowcrunch",
                        'item_category': "{{$dynamic_page_data['tigran']['ProductCategory']}}",
                        'price': "{{$dynamic_page_data['tigran']['Price']}}",
                        'quantity': 1
                    }]
                });
                                
            })

        </script>

    @endif


    {{--@if(isset($thankyouData['tigran']) && isset($thankyouData['tigran']['Price']) &&$thankyouData['tigran']['Price'] > 0 && !env('APP_DEBUG'))
        <script>
        $(document).ready(function(){
           @foreach($thankyouData['tigran'] as $key => $ti)
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

           //dataLayer.push({"event": 'purchase'})

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

           //dataLayer.push({"ecommerce": ecommerce})


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


    @endif--}}


    @if(isset($thankyouData['gt3']) && isset($thankyouData['gt3']['gt3']['transactionTotal']) && $thankyouData['gt3']['gt3']['transactionTotal'] > 0 && !env('APP_DEBUG'))
       {{--<script>
          $(document).ready(function(){
          let gt3 = {};
          let products = {};

          @foreach($thankyouData['gt3']['gt3'] as $key => $ti)

            @if($ti != '')
                gt3["{{$key}}"] = "{{$ti}}"

             @endif

          @endforeach

          @foreach($thankyouData['gt3']['transactionProducts'] as $key => $ti)

            @if($ti != '')
                products["{{$key}}"] = "{{$ti}}"

             @endif

          @endforeach

          gt3['transactionProducts'] = products;
          dataLayer.push(gt3);
          })
       </script>--}}
    @endif


@endpush
