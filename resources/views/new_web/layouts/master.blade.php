<?php $header_menus = get_header();?>

<!doctype html>

<html lang="en" class="no-js">
    <head>
        <base href="{!! URL::to('/') !!}/" target="_self" />
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @yield('metas')
        @yield('css')
        @include('theme.layouts.favicons')
        @yield('header')
        @include('new_web.layouts.header_scripts')

        <!-- Google Tag Manager -->
        @if(!env('APP_DEBUG'))
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                })(window,document,'script','dataLayer','GTM-ML7649C');
            </script>
        @endif

        <!-- OneTrust Cookies Consent Notice (Production CDN, knowcrunch.com, en-GB) start -->
        @if(Agent::isDesktop())
            <script type="text/javascript">
            var _iub = _iub || [];
            _iub.csConfiguration = {"cookiePolicyInOtherWindow":true,"perPurposeConsent":true,"whitelabel":false,"lang":"en","siteId":1874776,"countryDetection":true,"gdprAppliesGlobally":false,"consentOnDocument":true,"cookiePolicyId":76147833,"cookiePolicyUrl":"https://knowcrunch.com/cookies-notice", "banner":{ "acceptButtonDisplay":true,"customizeButtonDisplay":true,"acceptButtonColor":"#c8d151","acceptButtonCaptionColor":"#010000","customizeButtonColor":"#DADADA","customizeButtonCaptionColor":"#4D4D4D","position":"float-top-center","textColor":"black","backgroundColor":"white","rejectButtonColor":"#0073CE","rejectButtonCaptionColor":"white" }};
            </script>
            <script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>
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

        @if(!env('APP_DEBUG'))
        {{-- Google Tag Manager (noscript) --}}
        {{--<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML7649C"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>--}}
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML7649C"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        {{-- End Google Tag Manager (noscript) --}}
        @endif

        <div class="page-wrapper non-pointer">
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
                                <input type="text" placeholder="Email" id="email" autocomplete="off">
                            </div>

                            <br>

                            <label> Password <span class="required">(*)</span></label><span data-id="password" class="icon sub"><img width="20" src="{{cdn('/theme/assets/images/icons/eye-password.svg')}}" alt="">Show</span>
                            <div class="input-wrapper input-wrapper--text">
                                <span class="icon"><img width="10"  alt=""></span>
                                <input type="password" placeholder="Password" id="password" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="remember-me"><input id="remember-me" type="checkbox">Remember me</label>
                                <a id="forgot-pass" href="javascript:void(0)">Forgot password?</a>
                            </div>
                            <input type="button" onclick="loginAjaxNew()" value="LOGIN">
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
                        <span>Change your Password</span>
                            <p>Use your account email to change your password</p>
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

                            <div class="input-wrapper input-wrapper--text input-wrapper--email">
                                <div class="input-safe-wrapper">	
                                    <span class="icon"><img width="14" src="{{cdn('/theme/assets/images/icons/icon-email.svg')}}" alt=""></span>
                                    <input type="email"  placeholder="Email" name="email" id="email-forgot" class="required"> 
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn--lg btn--secondary change-password"  value="Change">Change</button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="main_content_include">

                @yield('content')

            </div>

            @include('new_web.layouts.footer')
            
            <a href="#" class="go-top-btn"><i class="icon-up-open"></i></a>
        </div>

        @include('theme.layouts.footer_scripts')
        @include('theme.layouts.flash_notifications')
        @yield('scripts')

        @if(strtotime(date('Y-m-d')) == strtotime(env('BLACKFRIDAY')))
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

        @if(Auth::user() && !env('APP_DEBUG'))
            <script>
                dataLayer.push({"User_id": "{{Auth::user()->id}}"})
            </script>
        @elseif(!env('APP_DEBUG'))
            <script>
                dataLayer.push({'Visitor_id': "{{session()->getId()}}"});
            </script>
        @endif

        @if(isset($tigran) && env('APP_DEBUG'))
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
                $this.addClass('active');

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
                $('.footer-menu li:first-child a').click(function(e) {
                    e.preventDefault();
                    $('.login-popup-wrapper').addClass('active');
                });
            </script>
        @endif

    </body>
</html>
