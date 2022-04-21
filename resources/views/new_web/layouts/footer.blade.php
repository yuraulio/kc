<?php
    $social_media = get_social_media();
?>

<footer id="footer">
    @if(strtotime(date('Y-m-d')) == strtotime(env('BLACKFRIDAY')))
        @include('theme.blackfriday.blackfriday')
    @endif
    <div class="container">
        <div class="footer-row">
            <div class="col12 award-section">
                <h4 class="footer-title award-title">Our awards</h4>
                <div class="mobile-toggle">
                    <div class="row clearfix footer-award-menu">
                        <div class="col4 col-sm-12 award-div">
                            <div><img alt_text="social media award" width="58" height="37" src="{{cdn('/awards/knowcrunch-award-marketing-education-leaders-2x.png')}}"></div>
                            <p> Best Digital Marketing E-learning
                                Award by <span class="text-highlight2"> Education Leaders </span> </p>
                        </div>
                        <div class="col4 col-sm-12 award-div">
                            <div><img alt_text="social media award" width="41" height="38"  src="{{cdn('/awards/knowcrunch-award-marketing-education-best-social-media-learning-program-2x.png')}}"></div>
                            <p> Best Social Media Learning Program
                            Award by <span class="text-highlight2"> Social Media World </span></p>
                        </div>
                        <div class="col4 col-sm-12 award-div">
                            <div><img alt_text="social media award" width="62" height="35"  src="{{cdn('/awards/knowcrunch-award-marketing-best-content-strategy-2x.png')}}"></div>
                            <p> Best Multi-Channel Content Strategy & Best Use of Multichannel Social Media in Content Marketing by <span class="text-highlight2">Digital Marketing Awards </span> </p>
                        </div>
                    </div>
                </div>
        </div>
        <div class="footer-col-1">
            <h4 class="footer-title">Get our news</h4>
            <div class="mobile-toggle">
                <div class="footer-form">
                    <div class="newsletterReponse"></div>
                    <div id="mc_embed_signup">
                        <form  class="form-control" action="//knowcrunch.us15.list-manage.com/subscribe/post?u=312b4ca8015cf92c92eeb4dbb&amp;id=e427673a3f" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                            <div id="mc_embed_signup_scroll">
                                <input type="text" id="mce-FNAME" name="FNAME" placeholder="First name"/>
                                <input type="text" id="mce-LNAME" name="LNAME" placeholder="Surname"/>
                                <div class="input-safe-wrapper">
                                    <input type="text" class="required" id="mce-EMAIL" name="EMAIL" placeholder="E-mail address *"  />
                                </div>
                                <input type="text" name="mobile-num" placeholder="Mobile number" />
                                <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_312b4ca8015cf92c92eeb4dbb_e427673a3f" tabindex="-1" value=""></div>
                                <button type="submit" class="btn btn--md btn--secondary">Subscribe</button>
                                <div id="mce-responses" class="clear">
                                    <div class="response" id="mce-error-response" style="display:none"></div>
                                    <div class="response" id="mce-success-response" style="display:none"></div>
                                </div>
                                <p>We respect your personal data. By subscribing, you agree that we can contact you to share our news or for marketing purposes according to our <a href="/data-privacy-policy" class="dark-bg">Data Privacy Policy.</a></p>
                                <script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';});</script>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div id="mce-responses" class="clear">
                                        <div class="response" id="mce-error-response" style="display:none"></div>
                                        <div class="response" id="mce-success-response" style="display:none"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';});</script>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-col-2">
            <div class="row clearfix">
            <div class="col6 col-sm-12">

                {{-- footer menu 1 desktop --}}
                @yield('footer_menu_1_title_desktop')
                <div class="mobile-toggle menu-desktop">
                    <ul class="footer-menu">
                        @yield('footer_menu_1_desktop')
                    </ul>
                </div>

                {{-- footer menu 1 mobile --}}
                @yield('footer_menu_1_title_mobile')
                <div class="mobile-toggle menu-mobile">
                    <ul class="footer-menu">
                        @yield('footer_menu_1_mobile')
                    </ul>
                </div>

            </div>
            <div class="col6 col-sm-12">

                {{-- footer menu 2 desktop --}}
                @yield('footer_menu_2_title_desktop')
                <div class="mobile-toggle menu-desktop">
                    <ul class="footer-menu">
                        @yield('footer_menu_2_desktop')
                    </ul>
                </div>

                {{-- footer menu 2 mobile --}}
                @yield('footer_menu_2_title_mobile')
                <div class="mobile-toggle menu-mobile">
                    <ul class="footer-menu">
                        @yield('footer_menu_2_mobile')
                    </ul>
                </div>

                {{-- footer menu 3 desktop --}}
                @yield('footer_menu_3_title_desktop')
                <div class="mobile-toggle menu-desktop">
                    <ul class="footer-menu">
                        @yield('footer_menu_3_desktop')
                    </ul>
                </div>

                {{-- footer menu 3 mobile --}}
                @yield('footer_menu_3_title_mobile')
                <div class="mobile-toggle menu-mobile">
                    <ul class="footer-menu">
                        @yield('footer_menu_3_mobile')
                    </ul>
                </div>

            </div>
            </div>
        </div>
        <div class="footer-col-3">
            <div class="clearfix">
                <ul class="footer-social-menu">
                    @if($social_media['facebook']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['facebook']['title'] }}" href="{{ $social_media['facebook']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Facebook.svg')}}" height="23" width="23" alt="{{ $social_media['facebook']['title'] }}">
                            </a>
                        </li>
                    @endif
                    @if($social_media['twitter']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['twitter']['title'] }}" href="{{ $social_media['twitter']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Twitter.svg')}}" height="23" width="23" alt="{{ $social_media['twitter']['title'] }}">
                            </a>
                        </li>
                    @endif
                    @if($social_media['instagram']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['instagram']['title'] }}" href="{{ $social_media['instagram']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Instagram.svg')}}" height="23" width="23" alt="{{ $social_media['instagram']['title'] }}">
                            </a>
                        </li>
                    @endif
                    @if($social_media['linkedin']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['linkedin']['title'] }}" href="{{ $social_media['linkedin']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Linkedin.svg')}}" height="23" width="23" alt="{{ $social_media['linkedin']['title'] }}">
                            </a>
                        </li>
                    @endif
                    @if($social_media['youtube']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['youtube']['title'] }}" href="{{ $social_media['youtube']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Youtube.svg')}}" height="23" width="23" alt="{{ $social_media['youtube']['title'] }}">
                            </a>
                        </li>
                    @endif

                    @if($social_media['tiktok']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['tiktok']['title'] }}" href="{{ $social_media['tiktok']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Knowcrunch-TikTok.svg')}}" height="23" width="23" alt="{{ $social_media['tiktok']['title'] }}"> </a>
                        </li>
                    @endif

                    @if($social_media['medium']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['medium']['title'] }}" href="{{ $social_media['medium']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Knowcrunch-Medium-Blog.svg')}}" height="23" width="23" alt="{{ $social_media['medium']['title'] }}"> </a>
                        </li>
                    @endif

                    @if($social_media['pinterest']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['pinterest']['title'] }}" href="{{ $social_media['pinterest']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Knowcrunch-Pinterest.svg')}}" height="23" width="23" alt="{{ $social_media['pinterest']['title'] }}"> </a>
                        </li>
                    @endif

                    @if($social_media['spotify']['url'] != '')
                        <li>
                            <a target="_blank" title="{{ $social_media['spotify']['title'] }}" href="{{ $social_media['spotify']['url'] }}">
                                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Knowcrunch-Spotify.svg')}}" height="23" width="23" alt="{{ $social_media['spotify']['title'] }}"> </a>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="copyright-wrapper">
                <p>Knowcrunch Inc., <br/>2035 Sunset Lake Road, <br/>Delaware, USA.</p>
                <p>Knowcrunch Inc. © <?php echo date('Y')?></p>
            </div>
        </div>
    </div>
</div>
</footer>
