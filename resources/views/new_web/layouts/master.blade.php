<?php //$header_menus = get_header();?>
<!doctype html>
<html lang="en" class="no-js">
    <head>
        <base href="{!! URL::to('/') !!}/" target="_self" />
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag() {
              dataLayer.push(arguments);
          }
          gtag("consent", "default", {
              ad_storage: "denied",
              ad_user_data: "denied",
              ad_personalization: "denied",
              analytics_storage: "denied",
              functionality_storage: "denied",
              personalization_storage: "denied",
              security_storage: "granted",
              wait_for_update: 2000,
          });
          gtag("set", "ads_data_redaction", true);
          gtag("set", "url_passthrough", true);
        </script>

        <!-- Google Tag Manager -->
        @if(!config('app.debug'))
          <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
              new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
              j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
              'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
              })(window,document,'script','dataLayer','GTM-ML7649C');
          </script>
{{--        @elseif(config('app.env') == "development")--}}
{{--          <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':--}}
{{--            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],--}}
{{--            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=--}}
{{--            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);--}}
{{--            })(window,document,'script','dataLayer','GTM-MLLXRGTK');</script>--}}
        @endif

        <!-- Start cookieyes banner --> <script id="cookieyes" type="text/javascript" src="https://cdn-cookieyes.com/client_data/2428d5ba225ff1e2703356e8/script.js"></script> <!-- End cookieyes banner -->


        @yield('metas')
        @yield('css')
        @include('theme.layouts.favicons')
        @yield('header')
        @include('new_web.layouts.header_scripts')


        <!-- OneTrust Cookies Consent Notice (Production CDN, knowcrunch.com, en-GB) start -->
        @if(Agent::isDesktop())
            {{--<script type="text/javascript">
            var _iub = _iub || [];
            _iub.csConfiguration = {"cookiePolicyInOtherWindow":true,"perPurposeConsent":true,"whitelabel":false,"lang":"en","siteId":1874776,"countryDetection":true,"gdprAppliesGlobally":false,"consentOnDocument":true,"cookiePolicyId":76147833,"cookiePolicyUrl":"https://knowcrunch.com/cookies-notice", "banner":{ "acceptButtonDisplay":true,"customizeButtonDisplay":true,"acceptButtonColor":"#c8d151","acceptButtonCaptionColor":"#010000","customizeButtonColor":"#DADADA","customizeButtonCaptionColor":"#4D4D4D","position":"float-top-center","textColor":"black","backgroundColor":"white","rejectButtonColor":"#0073CE","rejectButtonCaptionColor":"white" }};
            </script>
            <script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>--}}

            {{--<script type="text/javascript">
                var _iub = _iub || [];
                    _iub.csConfiguration = {"askConsentAtCookiePolicyUpdate":true,"consentOnContinuedBrowsing":false,"countryDetection":true,"enableLgpd":true,"enableUspr":true,"floatingPreferencesButtonDisplay":"bottom-right","invalidateConsentWithoutLog":true,"lang":"en","lgpdAppliesGlobally":false,"perPurposeConsent":true,"siteId":2409873,"whitelabel":false,"cookiePolicyId":41154288,"cookiePolicyUrl":"https://knowcrunch.com/cookies-notice","privacyPolicyUrl":"https://knowcrunch.com/data-privacy-policy","privacyPolicyNoticeAtCollectionUrl":"https://knowcrunch.com/terms-and-conditions", "banner":{ "acceptButtonColor":"#40CE00","acceptButtonDisplay":true,"closeButtonDisplay":false,"customizeButtonDisplay":true,"explicitWithdrawal":true,"listPurposes":true,"position":"float-top-center","rejectButtonColor":"#C8CFD6","rejectButtonDisplay":true }};
            </script>
            <script type="text/javascript" src="//cdn.iubenda.com/cs/gpp/stub.js"></script>
            <script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>--}}
        @endif

        {{-- Linkedin Code --}}
        <script type="text/javascript">
            _linkedin_data_partner_id = "32143";
        </script>

        @yield('blog-custom-css')

    </head>
    <body style="display: none;">
        <!-- Load Facebook SDK for JavaScript -->
        @yield('fbchat')

        @if(!config('app.debug'))
        {{-- Google Tag Manager (noscript) --}}
        {{--<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML7649C"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>--}}
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML7649C"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        {{-- End Google Tag Manager (noscript) --}}
        @elseif(config('app.env') == "development")
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MLLXRGTK"
          height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        @endif

        <div class="page-wrapper non-pointer">
            @include('new_web.layouts.tickers')
            @include('new_web.layouts.header')
            @include('new_web.layouts.mobile_menu')

            @if (!Auth::check())
                <div  class="login-popup-wrapper">
                    <div id="login-popup" class="login-popup">
                        <a href="#" class="close-btn"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" class="replace-with-svg" alt="Close"></a>
                        <div class="heading">
                            <span>Account login</span>
                            <p>Access your courses, schedule & files.</p>
                        </div>
                        <div class="alert-outer" hidden>
                            <div class="alert-wrapper error-alert">
                                <div class="alert-inner">
                                    <p id="account-error"></p>
                                </div>
                        </div>
                        </div>
                        <form autocomplete="off" class="login-form">
                            <label>Email <span class="required">(*)</span></label>
                            <div class="input-wrapper input-wrapper--text input-wrapper--email">
                                <span class="icon"><img width="14"  alt=""></span>
                                <input type="text" placeholder="" id="email" autocomplete="off">
                            </div>

                            <br>

                            <label> Password <span class="required">(*)</span></label><span data-id="password" class="icon sub"><img width="20" src="{{cdn('/theme/assets/images/icons/eye-password.svg')}}" alt="">Show</span>
                            <div class="input-wrapper input-wrapper--text">
                                <span class="icon"><img width="10"  alt=""></span>
                                <input type="password" placeholder="" id="password" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="remember-me"><input id="remember-me" type="checkbox">Remember me</label>
                                <a id="forgot-pass" href="javascript:void(0)">Reset or create your password.</a>
                            </div>
                            <input id="test-login" type="button" onclick="loginAjaxNew()" value="LOGIN">
                            <div class="row text-center loader d-none"><img class="img-responsive" src="theme/assets/img/ajax-loader-blue.gif" alt="Loader" title="Loader" /> </div>

                        </form>
                    </div>

                    <script>
                        $('.icon.sub').click(function(){
                            let input = $(`#${$(this).data('id')}`);
                            if(input.attr('type') === "password"){
                                input.attr('type','text')
                                $(this).find('img').attr('src', "{{cdn('/theme/assets/images/icons/eye-password-active.svg')}}");
                            }else{
                                input.attr('type','password')
                                $(this).find('img').attr('src', "{{cdn('/theme/assets/images/icons/eye-password.svg')}}");
                            }
                        })
                    </script>


                    <div id="forgot-pass-input" class="login-popup" hidden>
                        <a href="#" class="close-btn"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" class="replace-with-svg" alt="Close"></a>
                        <div class="heading">
                        <span>Password</span>
                            <p>Use your account email to create or change your password.</p>
                        </div>
                        <form autocomplete="off" class="login-form">
                            {!!csrf_field()!!}

                            <div id="error-mail" class="alert-outer" hidden>
                                <div class="alert-wrapper error-alert">
                                    <div class="alert-inner">
                                        <p id="message-error"></p>
                                    </div>
                                </div>
                            </div>

                            <div id="success-mail" class="alert-outer" hidden>
                                <div class="container">
                                    <div class="alert-wrapper success-alert">
                                        <div class="alert-inner">
                                            <p id="message-success"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label>Email <span class="required">(*)</span></label>
                            <div class="input-wrapper input-wrapper--text input-wrapper--email">
                                <div class="input-safe-wrapper">
                                    <input type="email"  placeholder="" name="email" id="email-forgot" class="required">
                                </div>
                            </div>

                            <button type="button" class="btn btn--lg btn--secondary change-password"  value="Change">Create / Change</button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="main_content_include">

                @yield('content')

            </div>

            @if (isset($page) && $page->type == "Knowledge")
                @include('new_web.layouts.footer-small')
            @else
                @include('new_web.layouts.footer')
            @endif

            <a href="#" class="go-top-btn"><i class="icon-up-open"></i></a>
        </div>

        @include('theme.layouts.footer_scripts')
        @include('theme.layouts.flash_notifications')
        @yield('scripts')

        @if(strtotime(date('Y-m-d')) == strtotime(config('services.promotions.BLACKFRIDAY')))
            <script src="{{cdn('theme/assets/blackfriday/blackfriday.js')}}"> </script>
        @endif

        @if(!Auth::check() && request()->login)
            <script>
                $(document).ready(function(){
                    $('li.account-menu a').click();
                });
            </script>
        @endif

        <script>
            $(document).on('click', '.change-password', function(e){
                $('#error-mail').hide()
                $('#success-mail').hide()
                var email = document.getElementById('email-forgot').value

                if(email!=''){
                    $.ajax({ url: '/myaccount/reset', type: "post",
                        data: {"email": email},
                        success: function(data) {
                            if(data['success']){
                                $('#success-mail').show()
                                var p = document.getElementById('message-success').textContent = data['message'];
                            }else{
                                $('#error-mail').show()
                                var p = document.getElementById('message-error').textContent = data['message'];
                            }
                        },
                    });
                }
            });
        </script>


        <script>
            $(document).on('click', '#forgot-pass', function(e){
                $('#login-popup').hide()
                $('#forgot-pass-input').show()
            })

            $(document).on('click', '.close-btn', function(e){
                $('#login-popup').show()
                $('#forgot-pass-input').hide()
            })
        </script>

        @if(Auth::user() && !config('app.debug'))
            <script>
                dataLayer.push({"User_id": "{{Auth::user()->id}}"})
            </script>
        @elseif(!config('app.debug'))
            <script>
                dataLayer.push({'Visitor_id': "{{session()->getId()}}"});
            </script>
        @endif

        @if(isset($tigran) && config('app.debug'))
            <script>
                $(document).ready(function(){
                        @foreach($tigran as $key => $ti)
                            dataLayer.push({"{{$key}}": $.parseHTML("{{$ti}}")[0].data})
                        @endforeach
                });
            </script>
        @endif

        <script>
            let url = window.location.href.split('#')
            if(url[1]){
                url[1] = url[1].split("?")[0]
                let target = "#" + url[1];
                let activeTab = $('.tab-controls .active');
                let self = $('a[href="#'+ url[1] + '"]');

                $('.active-tab').removeClass('active-tab');
                activeTab.toggleClass('active');
                self.toggleClass('active');
                self.next('.tab-controls-list').slideToggle(300);

                $(target).addClass('active-tab');
                $(this).addClass('active');

            }
        </script>

        @stack('components-scripts')

        @yield('blog-custom-js')

        <script>
            setTimeout(function(){
                $("body").show();
            }, 200);
        </script>


        @if(!Auth::check())
            <script>
                $('.footer-menu li a.account-menu').click(function(e) {
                    e.preventDefault();
                    $('.login-popup-wrapper').addClass('active');
                });
            </script>
        @endif

    </body>
</html>
