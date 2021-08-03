@extends('theme.layouts.master')
@section('content')

<?php $thetype =0;
//$info=null;
if(!isset($info)){

   $info['success'] = 'true';
}else{
   $info = $info;
}
?>

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
<!--   NOT LOGIN    -->
@elseif (!Auth::check() && sizeof(Cart::content()) != 0)
<main id="main-area" role="main">

   <section class="booking-login-create">

      <div class="container">
         <h1 class="page-title">Book your ticket</h1>
         <div class="tickets-wrapper">
            <div class="row">
               <div class="col8 col-md-7 col-sm-12">
                  <div class="tabs-forms">
                     <div class="tab-controls">
                        <ul>
                           <li><a href="#existing-customer" @if(!session()->has('create_tab') && (!isset($couponEvent) || !$couponEvent )) class="active" @endif>Login</a></li>
                           <li><a href="#new-customer" @if(session()->has('create_tab') || (isset($couponEvent) && $couponEvent )) class="active" @endif>Create account</a></li>
                        </ul>
                        <!-- /.tab-controls -->
                     </div>
                     <div class="tabs-content">
                        <div id="existing-customer" class="tab-content-wrapper @if(!session()->has('create_tab') && (!isset($couponEvent) || !$couponEvent ) ) active-tab @endif ">
                           <div class="form-wrapper">
                           @if (session()->has('message') && !session()->has('status') )
                        <div class="alert-outer">
                           <div class="container">
                              <div class="alert-wrapper success-alert">
                                 <div class="alert-inner">
                                    <p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-success-alert.svg')}}" alt="Info Alert">{{ session()->get('message') }}</p>
                                    <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
                                 </div>
                              </div>
                           </div>
                        <!-- /.alert-outer -->
                        </div>
                        <?php Session::forget('message') ?>
                           <div class="alert alert-success alert-dismissable"></div>

                        @elseif(session()->has('message') && session()->has('status'))
                        <div class="alert-outer">
                           <div class="container">
                              <div class="alert-wrapper error-alert">
                                 <div class="alert-inner">
                                    <p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-success-alert.svg')}}" alt="Info Alert">{{ session()->get('message') }}</p>
                                    <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
                                 </div>
                              </div>
                           </div>
                        <!-- /.alert-outer -->
                        </div>
                        <?php Session::forget('message');
                         Session::forget('status');
                         ?>
                           <div class="alert alert-success alert-dismissable"></div>

                        @elseif(session()->has('message') && session()->has('status'))
                        <div class="alert-outer">
                           <div class="container">
                              <div class="alert-wrapper error-alert">
                                 <div class="alert-inner">
                                    <p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-success-alert.svg')}}" alt="Info Alert">{{ session()->get('message') }}</p>
                                    <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
                                 </div>
                              </div>
                           </div>
                        <!-- /.alert-outer -->
                        </div>
                        <?php Session::forget('message');
                         Session::forget('status');
                         ?>
                           <div class="alert alert-success alert-dismissable"></div>
                        @endif




                              <form action="{{ URL::to('checkoutlogin') }}" method="post" class="login-form" id="loginf"
                                 data-bv-message="This value is not valid"
                                 data-bv-feedbackicons-valid="fa fa-check-sqaure-o fa-lg"
                                 data-bv-feedbackicons-invalid="fa fa-warning"
                                 data-bv-feedbackicons-validating="fa fa-refresh">
                                 {!! csrf_field() !!}
                                 <div class="input-wrapper input-wrapper--text input-wrapper--email">
                                    <span class="icon"><img width="12" src="{{cdn('/theme/assets/images/icons/icon-email.svg')}}" alt=""></span>
                                    <input name="email" id="email" type="email" placeholder="Email" required autofocus data-bv-emailaddress-message="The input is not a valid email address" />
                                 </div>
                                 <div class="input-wrapper input-wrapper--text">
                                    <span class="icon"><img width="10" src="{{cdn('/theme/assets/images/icons/icon-lock.svg')}}" alt=""></span>
                                    <input type="password" placeholder="Password"  name="password" id="password" required data-bv-password-message="A password is required" />
                                 </div>
                                 <div class="login-form-actions clearfix">
                                    <div class="remember-wrapper">
                                       <div class="custom-checkbox-wrapper clearfix">
                                          <div class="custom-checkbox">
                                             <input type="checkbox" id="remember-user" name="remember-user" value="true"/>
                                             <span></span>
                                          </div>
                                          <label for="remember-user">Remember me</label>
                                       </div>
                                       <!-- /.remember-wrapper -->
                                    </div>
                                    <div class="forgot-pass-wrapper">
                                       <a id="forgot-pass2" href="javascript:void(0)">Forgot password?</a>
                                    </div>
                                 </div>
                                 <button type="submit" class="btn btn--md btn--secondary">Login</button>
                              </form>
                              <form id="forgotf" method="post" action="/cart/reset" autocomplete="off" class="login-form" hidden>
                                 {!! csrf_field() !!}
                                 <div class="input-wrapper input-wrapper--text input-wrapper--email">
                                    <span class="icon"><img width="14" src="{{cdn('/theme/assets/images/icons/icon-email.svg')}}" alt=""></span>
                                    <input type="text" placeholder="Email" name="email" >
                                 </div>
                                 <button type="submit" class="btn btn--md btn--secondary">Change</button>
                              </form>
                           </div>
                        </div>
                        <div id="new-customer" class="tab-content-wrapper @if(session()->has('create_tab') || (isset($couponEvent) && $couponEvent )) ) active-tab @endif">

                        <div id="favDialog" hidden>

                           <div class="alert-wrapper error-alert">
                              <div class="alert-inner">
                                 <p><img src="{{cdn('theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert">Please accept the terms, conditions & data privacy in order to complete your registration.</p>
                                 <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
                              </div>

                           <!-- /.alert-outer -->
                           </div>
                           </div>
                           <div class="form-wrapper">
                           @if($errors->any())
                        <div class="alert-outer">
                           <div class="container">
                              <div class="alert-wrapper error-alert">
                                 <div class="alert-inner">
                                    <p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-success-alert.svg')}}" alt="Info Alert">{{ $errors->first() }}</p>
                                    <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
                                 </div>
                              </div>
                           </div>
                        <!-- /.alert-outer -->
                        </div>
                        @endif
                              <form id="new-customer-form" action="{{ URL::to('kcregister') }}" method="post" class="register-form" novalidate autocomplete="off" >
                                 {!! csrf_field() !!}
                                 <div class="row">
                                    <div class="col6 col-sm-12">
                                       <label for="r-first-name">First name <span>*</span></label>
                                       <div class="input-safe-wrapper">
                                          <input class="required" id="first_name" type="text" name="first_name" value="{{old('first_name')}}" required/>
                                       </div>
                                    </div>
                                    <div class="col6 col-sm-12">
                                       <label for="r-last-name">Last name <span>*</span></label>
                                       <div class="input-safe-wrapper">
                                          <input class="required" id="last_name" type="text" name="last_name" value="{{old('last_name')}}"/>
                                       </div>
                                    </div>
                                    <div class="col6 col-sm-12">
                                       <label for="r-email">Email <span>*</span></label>
                                       <div class="input-safe-wrapper">
                                          <input class="required" id="r-email" type="email" name="email" value="{{old('email')}}"/>
                                       </div>
                                    </div>
                                    <div class="col6 col-sm-12">
                                       <label for="r-mobile-phone">Mobile phone <span>*</span></label>
                                       <div class="input-safe-wrapper is-flex full-width">

                                          <select name="countryCode" id="selectCountry-reg" class="select2 form-control mb-3 custom-select country-select-reg">


                                                <option data-countryCode="DZ" value="213">DZ (+213)</option>
                                                <option data-countryCode="AD" value="376">AD (+376)</option>
                                                <option data-countryCode="AO" value="244">AO (+244)</option>
                                                <option data-countryCode="AI" value="1264">AI (+1264)</option>
                                                <option data-countryCode="AG" value="1268">AG (+1268)</option>
                                                <option data-countryCode="AR" value="54">AR (+54)</option>
                                                <option data-countryCode="AM" value="374">AM (+374)</option>
                                                <option data-countryCode="AW" value="297">AW (+297)</option>
                                                <option data-countryCode="AU" value="61">AU (+61)</option>
                                                <option data-countryCode="AT" value="43">AT (+43)</option>
                                                <option data-countryCode="AZ" value="994">AZ (+994)</option>
                                                <option data-countryCode="BS" value="1242">BS (+1242)</option>
                                                <option data-countryCode="BH" value="973">BH (+973)</option>
                                                <option data-countryCode="BD" value="880">BD (+880)</option>
                                                <option data-countryCode="BB" value="1246">BB (+1246)</option>
                                                <option data-countryCode="BY" value="375">BY (+375)</option>
                                                <option data-countryCode="BE" value="32">BE (+32)</option>
                                                <option data-countryCode="BZ" value="501">BZ (+501)</option>
                                                <option data-countryCode="BJ" value="229">BJ (+229)</option>
                                                <option data-countryCode="BM" value="1441">BM (+1441)</option>
                                                <option data-countryCode="BT" value="975">BT (+975)</option>
                                                <option data-countryCode="BO" value="591">BO (+591)</option>
                                                <option data-countryCode="BA" value="387">BA (+387)</option>
                                                <option data-countryCode="BW" value="267">BW (+267)</option>
                                                <option data-countryCode="BR" value="55">BR (+55)</option>
                                                <option data-countryCode="BN" value="673">BN (+673)</option>
                                                <option data-countryCode="BG" value="359">BG (+359)</option>
                                                <option data-countryCode="BF" value="226">BF (+226)</option>
                                                <option data-countryCode="BI" value="257">BI (+257)</option>
                                                <option data-countryCode="KH" value="855">KH (+855)</option>
                                                <option data-countryCode="CM" value="237">CM (+237)</option>
                                                <option data-countryCode="CA" value="1">CA (+1)</option>
                                                <option data-countryCode="CV" value="238">CV (+238)</option>
                                                <option data-countryCode="KY" value="1345">KY (+1345)</option>
                                                <option data-countryCode="CF" value="236">CF (+236)</option>
                                                <option data-countryCode="CL" value="56">CL (+56)</option>
                                                <option data-countryCode="CN" value="86">CN (+86)</option>
                                                <option data-countryCode="CO" value="57">CO (+57)</option>
                                                <option data-countryCode="KM" value="269">KM (+269)</option>
                                                <option data-countryCode="CG" value="242">CG (+242)</option>
                                                <option data-countryCode="CK" value="682">CK (+682)</option>
                                                <option data-countryCode="CR" value="506">CR (+506)</option>
                                                <option data-countryCode="HR" value="385">HR (+385)</option>
                                                <option data-countryCode="CU" value="53">CU (+53)</option>
                                                <option data-countryCode="CY" value="90392">CY (+90392)</option>
                                                <option data-countryCode="CY" value="357">CY (+357)</option>
                                                <option data-countryCode="CZ" value="42">CZ (+42)</option>
                                                <option data-countryCode="DK" value="45">DK (+45)</option>
                                                <option data-countryCode="DJ" value="253">DJ (+253)</option>
                                                <option data-countryCode="DM" value="1809">DM (+1809)</option>
                                                <option data-countryCode="DO" value="1809">DO (+1809)</option>
                                                <option data-countryCode="EC" value="593">EC (+593)</option>
                                                <option data-countryCode="EG" value="20">EG (+20)</option>
                                                <option data-countryCode="SV" value="503">SV (+503)</option>
                                                <option data-countryCode="GQ" value="240">GQ (+240)</option>
                                                <option data-countryCode="ER" value="291">ER (+291)</option>
                                                <option data-countryCode="EE" value="372">EE (+372)</option>
                                                <option data-countryCode="ET" value="251">ET (+251)</option>
                                                <option data-countryCode="FK" value="500">FK (+500)</option>
                                                <option data-countryCode="FO" value="298">FO (+298)</option>
                                                <option data-countryCode="FJ" value="679">FJ (+679)</option>
                                                <option data-countryCode="FI" value="358">FI (+358)</option>
                                                <option data-countryCode="FR" value="33">FR (+33)</option>
                                                <option data-countryCode="GF" value="594">GF (+594)</option>
                                                <option data-countryCode="PF" value="689">PF (+689)</option>
                                                <option data-countryCode="GA" value="241">GA (+241)</option>
                                                <option data-countryCode="GM" value="220">GM (+220)</option>
                                                <option data-countryCode="GE" value="7880">GE (+7880)</option>
                                                <option data-countryCode="DE" value="49">DE (+49)</option>
                                                <option data-countryCode="GH" value="233">GH (+233)</option>
                                                <option data-countryCode="GI" value="350">GI (+350)</option>
                                                <option data-countryCode="GR" value="30" selected>GR (+30)</option>
                                                <option data-countryCode="GL" value="299">GL (+299)</option>
                                                <option data-countryCode="GD" value="1473">GD (+1473)</option>
                                                <option data-countryCode="GP" value="590">GP (+590)</option>
                                                <option data-countryCode="GU" value="671">GU (+671)</option>
                                                <option data-countryCode="GT" value="502">GT (+502)</option>
                                                <option data-countryCode="GN" value="224">GN (+224)</option>
                                                <option data-countryCode="GW" value="245">GW (+245)</option>
                                                <option data-countryCode="GY" value="592">GY (+592)</option>
                                                <option data-countryCode="HT" value="509">HT (+509)</option>
                                                <option data-countryCode="HN" value="504">HN (+504)</option>
                                                <option data-countryCode="HK" value="852">HK (+852)</option>
                                                <option data-countryCode="HU" value="36">HU (+36)</option>
                                                <option data-countryCode="IS" value="354">IS (+354)</option>
                                                <option data-countryCode="IN" value="91">IN (+91)</option>
                                                <option data-countryCode="ID" value="62">ID (+62)</option>
                                                <option data-countryCode="IR" value="98">IR (+98)</option>
                                                <option data-countryCode="IQ" value="964">IQ (+964)</option>
                                                <option data-countryCode="IE" value="353">IE (+353)</option>
                                                <option data-countryCode="IL" value="972">IL (+972)</option>
                                                <option data-countryCode="IT" value="39">IT (+39)</option>
                                                <option data-countryCode="JM" value="1876">JM (+1876)</option>
                                                <option data-countryCode="JP" value="81">JP (+81)</option>
                                                <option data-countryCode="JO" value="962">JO (+962)</option>
                                                <option data-countryCode="KZ" value="7">KZ (+7)</option>
                                                <option data-countryCode="KE" value="254">KE (+254)</option>
                                                <option data-countryCode="KI" value="686">KI (+686)</option>
                                                <option data-countryCode="KP" value="850">KP (+850)</option>
                                                <option data-countryCode="KR" value="82">KR (+82)</option>
                                                <option data-countryCode="KW" value="965">KW (+965)</option>
                                                <option data-countryCode="KG" value="996">KG (+996)</option>
                                                <option data-countryCode="LA" value="856">LA (+856)</option>
                                                <option data-countryCode="LV" value="371">LV (+371)</option>
                                                <option data-countryCode="LB" value="961">LB (+961)</option>
                                                <option data-countryCode="LS" value="266">LS (+266)</option>
                                                <option data-countryCode="LR" value="231">LR (+231)</option>
                                                <option data-countryCode="LY" value="218">LY (+218)</option>
                                                <option data-countryCode="LI" value="417">LI (+417)</option>
                                                <option data-countryCode="LT" value="370">LT (+370)</option>
                                                <option data-countryCode="LU" value="352">LU (+352)</option>
                                                <option data-countryCode="MO" value="853">MO (+853)</option>
                                                <option data-countryCode="SK" value="389">SK (+389)</option>
                                                <option data-countryCode="MG" value="261">MG (+261)</option>
                                                <option data-countryCode="MW" value="265">MW (+265)</option>
                                                <option data-countryCode="MY" value="60">MY (+60)</option>
                                                <option data-countryCode="MV" value="960">MV (+960)</option>
                                                <option data-countryCode="ML" value="223">ML (+223)</option>
                                                <option data-countryCode="MT" value="356">MT (+356)</option>
                                                <option data-countryCode="MH" value="692">MH (+692)</option>
                                                <option data-countryCode="MQ" value="596">MQ (+596)</option>
                                                <option data-countryCode="MR" value="222">MR (+222)</option>
                                                <option data-countryCode="YT" value="269">YT (+269)</option>
                                                <option data-countryCode="MX" value="52">MX (+52)</option>
                                                <option data-countryCode="FM" value="691">FM (+691)</option>
                                                <option data-countryCode="MD" value="373">MD (+373)</option>
                                                <option data-countryCode="MC" value="377">MC (+377)</option>
                                                <option data-countryCode="MN" value="976">MN (+976)</option>
                                                <option data-countryCode="MS" value="1664">MS (+1664)</option>
                                                <option data-countryCode="MA" value="212">MA (+212)</option>
                                                <option data-countryCode="MZ" value="258">MZ (+258)</option>
                                                <option data-countryCode="MN" value="95">MN (+95)</option>
                                                <option data-countryCode="NA" value="264">NA (+264)</option>
                                                <option data-countryCode="NR" value="674">NR (+674)</option>
                                                <option data-countryCode="NP" value="977">NP (+977)</option>
                                                <option data-countryCode="NL" value="31">NL (+31)</option>
                                                <option data-countryCode="NC" value="687">NC (+687)</option>
                                                <option data-countryCode="NZ" value="64">NZ (+64)</option>
                                                <option data-countryCode="NI" value="505">NI (+505)</option>
                                                <option data-countryCode="NE" value="227">NE (+227)</option>
                                                <option data-countryCode="NG" value="234">NG (+234)</option>
                                                <option data-countryCode="NU" value="683">NU (+683)</option>
                                                <option data-countryCode="NF" value="672">NF (+672)</option>
                                                <option data-countryCode="NP" value="670">NP (+670)</option>
                                                <option data-countryCode="NO" value="47">NO (+47)</option>
                                                <option data-countryCode="OM" value="968">OM (+968)</option>
                                                <option data-countryCode="PW" value="680">PW (+680)</option>
                                                <option data-countryCode="PA" value="507">PA (+507)</option>
                                                <option data-countryCode="PG" value="675">PG (+675)</option>
                                                <option data-countryCode="PY" value="595">PY (+595)</option>
                                                <option data-countryCode="PE" value="51">PE (+51)</option>
                                                <option data-countryCode="PH" value="63">PH (+63)</option>
                                                <option data-countryCode="PL" value="48">PL (+48)</option>
                                                <option data-countryCode="PT" value="351">PT (+351)</option>
                                                <option data-countryCode="PR" value="1787">PR (+1787)</option>
                                                <option data-countryCode="QA" value="974">QA (+974)</option>
                                                <option data-countryCode="RE" value="262">RE (+262)</option>
                                                <option data-countryCode="RO" value="40">RO (+40)</option>
                                                <option data-countryCode="RU" value="7">RU (+7)</option>
                                                <option data-countryCode="RW" value="250">RW (+250)</option>
                                                <option data-countryCode="SM" value="378">SM (+378)</option>
                                                <option data-countryCode="ST" value="239">ST (+239)</option>
                                                <option data-countryCode="SA" value="966">SA (+966)</option>
                                                <option data-countryCode="SN" value="221">SN (+221)</option>
                                                <option data-countryCode="CS" value="381">CS (+381)</option>
                                                <option data-countryCode="SC" value="248">SC (+248)</option>
                                                <option data-countryCode="SL" value="232">SL (+232)</option>
                                                <option data-countryCode="SG" value="65">SG (+65)</option>
                                                <option data-countryCode="SK" value="421">SK (+421)</option>
                                                <option data-countryCode="SI" value="386">SI (+386)</option>
                                                <option data-countryCode="SB" value="677">SB (+677)</option>
                                                <option data-countryCode="SO" value="252">SO (+252)</option>
                                                <option data-countryCode="ZA" value="27">ZA (+27)</option>
                                                <option data-countryCode="ES" value="34">ES (+34)</option>
                                                <option data-countryCode="LK" value="94">LK (+94)</option>
                                                <option data-countryCode="SH" value="290">SH (+290)</option>
                                                <option data-countryCode="KN" value="1869">KN (+1869)</option>
                                                <option data-countryCode="SC" value="1758">SC (+1758)</option>
                                                <option data-countryCode="SD" value="249">SD (+249)</option>
                                                <option data-countryCode="SR" value="597">SR (+597)</option>
                                                <option data-countryCode="SZ" value="268">SZ (+268)</option>
                                                <option data-countryCode="SE" value="46">SE (+46)</option>
                                                <option data-countryCode="CH" value="41">CH (+41)</option>
                                                <option data-countryCode="SI" value="963">SI (+963)</option>
                                                <option data-countryCode="TW" value="886">TW (+886)</option>
                                                <option data-countryCode="TJ" value="7">TJ (+7)</option>
                                                <option data-countryCode="TH" value="66">Thailand (+66)</option>
                                                <option data-countryCode="TG" value="228">TH (+228)</option>
                                                <option data-countryCode="TO" value="676">TO (+676)</option>
                                                <option data-countryCode="TT" value="1868">TT (+1868)</option>
                                                <option data-countryCode="TN" value="216">TN (+216)</option>
                                                <option data-countryCode="TR" value="90">TR (+90)</option>
                                                <option data-countryCode="TM" value="7">TM (+7)</option>
                                                <option data-countryCode="TM" value="993">TM (+993)</option>
                                                <option data-countryCode="TC" value="1649">TC</option>
                                                <option data-countryCode="TV" value="688">TV (+688)</option>
                                                <option data-countryCode="UG" value="256">UG (+256)</option>
                                                <option data-countryCode="GB" value="44">UK (+44)</option>
                                                <option data-countryCode="UA" value="380">UA (+380)</option>
                                                <option data-countryCode="AE" value="971">"AE (+971)</option>
                                                <option data-countryCode="UY" value="598">UY (+598)</option>
                                                <option data-countryCode="US" value="1">USA (+1)</option>
                                                <option data-countryCode="UZ" value="7">UZ (+7)</option>
                                                <option data-countryCode="VU" value="678">VU (+678)</option>
                                                <option data-countryCode="VA" value="379">VA (+379)</option>
                                                <option data-countryCode="VE" value="58">VE (+58)</option>
                                                <option data-countryCode="VN" value="84">VN (+84)</option>
                                                <option data-countryCode="VG" value="84">VG (+1284)</option>
                                                <option data-countryCode="VI" value="84">VI (+1340)</option>
                                                <option data-countryCode="WF" value="681">WF (+681)</option>
                                                <option data-countryCode="YE" value="969">YE (North)(+969)</option>
                                                <option data-countryCode="YE" value="967">YE (South)(+967)</option>
                                                <option data-countryCode="ZM" value="260">ZM (+260)</option>
                                                <option data-countryCode="ZW" value="263">ZW (+263)</option>

                                          </select>
                                          <input class="required" id="mobile" onkeyup="checkPhoneNumber(this)" type="text"  name="mobile" value="{{old('mobile')}}"/>
                                          <input type="hidden" name="mobileCheck" id="mobileCheck" value="{{ old('mobile') }}">

                                       </div>
                                    </div>
                                    <div class="col6 col-sm-12">
                                       <label for="r-password">Password <span>*</span></label>
                                       <div class="input-safe-wrapper">
                                          <input class="required" id="r-password" type="password" name="password" />
                                       </div>
                                    </div>
                                    <div class="col6 col-sm-12">
                                       <label for="r-password-confirm">Confirm password <span>*</span></label>
                                       <div class="input-safe-wrapper">
                                          <input class="required" id="r-password-confirm" type="password" name="password_confirm"/>
                                       </div>
                                    </div>
                                    <div class="col12 clearfix">
                                       <div class="custom-checkbox-wrapper clearfix">
                                          <div class="custom-checkbox">
                                             <input type="checkbox" id="regaccept" name="accept" value="0" />
                                             <span></span>
                                          </div>
                                          <label for="r-accept-terms">I have read, agree upon & accept the <a target="_blank" title="View the Terms and conditions" href="/terms-privacy">terms & conditions</a> and <a target="_blank" title="View the data privacy policy" href="/data-privacy-policy">data privacy policy</a>. </label>
                                       </div>
                                    </div>
                                    <div class="col12">
                                       <div class="submit-area">
                                          <button type="button" class="btn btn--md btn--secondary newCustomer">Create Account</button>
                                       </div>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>

                     </div>
                  </div>
               </div>
               <div class="col4 col-sm-12">
                  <div class="clearfix">
                     <div class="ticket-sidebar">
                        <h6>Your selection:</h6>
                        @foreach (Cart::content() as $item)
                        <h3>{{ $item->name }}</h3>
                        <div class="ticket-infos">
                           <ul>
                              @if($city)<li><img src="{{cdn('/theme/assets/images/icons/icon-marker.svg')}}" width="16" alt="" class="icon" />{{$city}}</li>@endif
                              @if(isset($ev_date_help)) <li><img src="{{cdn('/theme/assets/images/icons/icon-calendar.svg')}}" width="16" alt="" class="icon" /> {{$ev_date_help}}</li>@endif
                              @if($duration)<li><img src="{{ cdn('/theme/assets/images/icons/Start-Finish.svg')}}" width="16" alt="" class="icon" />{{$duration}}</li>@endif

                           </ul>
                        </div>
                        <div class="remove-area ">
                           <img src="{{cdn('/theme/assets/images/icons/icon-delete.svg')}}" class="replace-with-svg" width="16" alt=""/>
                           <a class="btn-remove" href="{{ $item->rowId }}">Remove</a>
                        </div>
                        @endforeach
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</main>
@elseif( sizeof(Cart::content()) != 0 || ((isset($info)) && isset($info['success']) && !$info['success']) || \Session('dperror'))
<main id="main-area" role="main">
<div id="favDialog" hidden>

   <div class="alert-wrapper error-alert">
      <div class="alert-inner">
         <p><img src="{{cdn('theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert">Please accept the terms, conditions & data privacy in order to complete your registration.</p>
         <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
      </div>

   <!-- /.alert-outer -->
   </div>
</div>


<div id="favDialogCardNumberFailed" hidden>

   <div class="alert-wrapper error-alert">
      <div class="alert-inner">
         <p id="card-message"></p>
         <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
      </div>

   <!-- /.alert-outer -->
   </div>
</div>

<div id="favDialogCard" hidden>

   <div class="alert-wrapper error-alert">
      <div class="alert-inner">
         <p><img src="{{cdn('theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert">Add card to process payment.</p>
         <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
      </div>

   <!-- /.alert-outer -->
   </div>
</div>
<div id="couponDialog" hidden>
   <div class="alert-wrapper">
      <div class="alert-inner">

      </div>

				<!-- /.alert-outer -->
	</div>
</div>
   @if($info['success']==false)
   <div class="alert-outer">
					<div class="container">
						<div class="alert-wrapper error-alert">
							<div class="alert-inner">

								<p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert">{{$info['message']}}.</p>
								<a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
							</div>
						</div>
					</div>
				<!-- /.alert-outer -->
				</div>

   @elseif(\Session('dperror'))
   <div class="alert-outer">
					<div class="container">
						<div class="alert-wrapper error-alert">
							<div class="alert-inner">

								<p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert">{{\Session('dperror')}}.</p>
								<a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
							</div>
						</div>
					</div>
				<!-- /.alert-outer -->
				</div>
            <?php Session::forget('dperror') ?>
   @endif

   <section class="selection-section">
      <div class="container">
         <h1 class="page-title">Checkout</h1>
         <h2>Your selection</h2>
         <?php
            $totalitems = 0;?>
         @foreach (Cart::content() as $item)
         <?php
            $mod = $item->model;
            //dd($item);
            			//get stock of this ticket
            			$curStock = 1;
                     $price = 'free';
            			foreach ($eventtickets as $tkey => $tvalue) {
            				if ($tvalue->pivot->event_id == $item->options->event && $tvalue->ticket_id == $item->id) {
            					$curStock = $tvalue->pivot->quantity;
                           $price = $tvalue->pivot->price;

            				}

            			}
            if(isset($maxseats)) {
            	$themax = $maxseats;
            }
            else {
            	$themax = $curStock;
            }
            ?>
         <?php $totalitems = $totalitems + $item->qty; ?>
         <?php
            //$lockids = 0;
            	if($item->options->has('type')) {
            		$thetype = $item->options->type;
            		switch ($thetype) {
                     case 0:
            				$type ='';
            				$placeid = '';
            				break;
            			case 1:
            				$type ='UNEMPLOYED';
            				$placeid = 'Unemployed Id';
            				break;
            			case 2:
            				$type ='STUDENT';
            				$placeid = 'Student Id';
            				break;
            			case 3:
            				$type ='KNOWCRUNCH ALUMNI';
            				$placeid = 'Knowcrunch Id';
            				$maxseats = 1;
            				//$lockids = 1;
            				break;
            			case 4:
            				$type ='DEREE ALUMNI';
            				$placeid = 'Deree Id';
            				$maxseats = 1;
            				//$lockids = 1;
            				break;
            			case 5:
            				$type ='GROUP OF 2+';
            				$placeid = '';
                        break;

                     case 7:
                        $type ='';
            				$placeid = '';
                        break;


            default:
            $type ='';
            				$placeid = '';
            				break;
            		}

            	}
            	else {
            $type ='';
            		$placeid = '';
            }

            ?>
         <div class="your-selection">
            <div class="item">
               <div class="remove-area">
                  <a class="btn-remove" href="{{ $item->rowId }}"><img src="{{cdn('/theme/assets/images/icons/icon-delete.svg')}}" class="replace-with-svg" alt=""/><span>Remove</span></a>
               </div>
               <div class="left">
                  <h3>{{ $item->name }}</h3>
                  <div class="bottom">
                     <div class="location"><img src="{{cdn('/theme/assets/images/icons/marker.svg')}}" alt="">{{$city}}</div>
                     <div class="duration"><img src="{{cdn('/theme/assets/images/icons/icon-calendar.svg')}}" alt="">@if(isset($ev_date_help)) {{$ev_date_help}}@endif</div>
                     @if($duration)<div class="expire-date"><img src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt="">{{$duration}}</div>@endif

                  </div>
               </div>
               <div class="right">
                  <ul class="checkout-infos">
                     <li>
                        <span class="label">Ticket:</span>

                        <span class="value">{{ $item->model->type }}</span>
                     </li>
                     <li>
                        <span class="label">Price:</span>
                        @if($price > 0)
                        <span class="value ticket-coupon">€{{ $price }}</span>
                        @else
                        <span class="value">Free</span>
                        @endif
                     </li>
                     <li>
                        <span class="label">Participants:</span>
                        <span class="value">{{ $item->qty }}</span>
                     </li>
                     <li class="total-amount">
                        <span class="label">Total amount:</span>
                        <span class="value ticket-coupon">€{{ $item->subtotal }}</span>
                     </li>
                  </ul>
               </div>
            </div>
            <!-- ./item -->
         </div>
         @endforeach

      </div>
   </section>
   <section class="section-checkout checkout-participant">
      <div class="container">
      @if($errors->any())
      <div class="alert-outer">
         <div class="container">
            <div class="alert-wrapper error-alert">
               <div class="alert-inner">
                  <p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-success-alert.svg')}}" alt="Info Alert">{{ $errors->first() }}</p>
                  <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
               </div>
            </div>
         </div>
      <!-- /.alert-outer -->
      </div>
      @endif
         <div class="checkout-wrapper">
            <div class="row">
               <div class="col8 col-sm-12">
                  <h2>Participant</h2>
                  <div class="form-wrapper">
                     <form method="post" id='user-info' action="/pay-sbt" class="small-form @if($thetype==5) participant-form-group @endif">
                        {{csrf_field()}}
                        @if(Cart::instance('default')->subtotal() > 0)
                        <input id="item-quantity" type="hidden" value="{{ $item->qty }}" name="update[{{ $item->rowId }}][quantity]" value="{{{ $item->qty }}}" />
                        @endif
                        <?php for ($i=0; $i < $totalitems; $i++) : ?>
                        @if($i>0)
                        <div class="extra-participant-area">
                           <?php $participants = $i + 1; ?>
                           <h2>
                              <span>Participant {{$participants}}</span>
                              <a href="#" class="remove-participant">
                                 <svg version="1.1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <!--Generated by IJSVG (https://github.com/iconjar/IJSVG)-->
                                    <g fill="none">
                                       <path stroke="#323232" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5,7h14"></path>
                                       <path stroke="#323232" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18,7v11c0,1.105 -0.895,2 -2,2h-8c-1.105,0 -2,-0.895 -2,-2v-11"></path>
                                       <path stroke="#323232" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15,3.75h-6"></path>
                                       <path stroke="#323232" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10,11v5"></path>
                                       <path stroke="#323232" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14,11v5"></path>
                                    </g>
                                 </svg>
                                 <span>Remove</span>
                              </a>
                           </h2>
                           @endif
                           <div class="row">
                              <div class="col6 col-sm-12">
                                 <label>First name <span>*</span></label>
                                 <div class="input-safe-wrapper">
                                    <input class="required" type="text" id="name<?php echo $i; ?>" name="name[]" placeholder="Name*" value="@if(isset($pay_seats_data) && isset($pay_seats_data['names'][$i])){{$pay_seats_data['names'][$i]}}@elseif(isset($cur_user) && $i == 0){{$cur_user->firstname}}@endif" required />
                                 </div>
                              </div>
                              <div class="col6 col-sm-12">
                                 <label>Last name <span>*</span></label>
                                 <div class="input-safe-wrapper">
                                          <input class="required" type="text" id="surname<?php echo $i; ?>" name="surname[]" placeholder="Surname*" value="@if(isset($pay_seats_data) && isset($pay_seats_data['surnames'][$i])){{$pay_seats_data['surnames'][$i]}}@elseif(isset($cur_user) && $i == 0){{$cur_user->lastname}}@endif" required />
                                 </div>
                              </div>
                              <div class="col6 col-sm-12">
                                 <label>Email <span>*</span></label>
                                 <div class="input-safe-wrapper">
                                          <input class="required" type="email" id="email<?php echo $i; ?>" name="email[]" placeholder="Email*" value="@if(isset($pay_seats_data) && isset($pay_seats_data['emails'][$i])){{$pay_seats_data['emails'][$i]}}@elseif(isset($cur_user) && $i == 0){{ $cur_user->email}}@endif" required />
                                 </div>
                              </div>
                              <div class="col6 col-sm-12">
                                 <label>Mobile phone <span>*</span></label>
                                 <div class="input-safe-wrapper is-flex full-width">
                                    <select name="countryCodes[]" onchange="selectCountryCode(this,<?php echo $i; ?>)" id="selectCountry<?php echo $i; ?>" class="select2 form-control mb-3 custom-select country-select-reg">
                                       <option data-countryCode="DZ" value="213">DZ (+213)</option>
                                       <option data-countryCode="AD" value="376">AD (+376)</option>
                                       <option data-countryCode="AO" value="244">AO (+244)</option>
                                       <option data-countryCode="AI" value="1264">AI (+1264)</option>
                                       <option data-countryCode="AG" value="1268">AG (+1268)</option>
                                       <option data-countryCode="AR" value="54">AR (+54)</option>
                                       <option data-countryCode="AM" value="374">AM (+374)</option>
                                       <option data-countryCode="AW" value="297">AW (+297)</option>
                                       <option data-countryCode="AU" value="61">AU (+61)</option>
                                       <option data-countryCode="AT" value="43">AT (+43)</option>
                                       <option data-countryCode="AZ" value="994">AZ (+994)</option>
                                       <option data-countryCode="BS" value="1242">BS (+1242)</option>
                                       <option data-countryCode="BH" value="973">BH (+973)</option>
                                       <option data-countryCode="BD" value="880">BD (+880)</option>
                                       <option data-countryCode="BB" value="1246">BB (+1246)</option>
                                       <option data-countryCode="BY" value="375">BY (+375)</option>
                                       <option data-countryCode="BE" value="32">BE (+32)</option>
                                       <option data-countryCode="BZ" value="501">BZ (+501)</option>
                                       <option data-countryCode="BJ" value="229">BJ (+229)</option>
                                       <option data-countryCode="BM" value="1441">BM (+1441)</option>
                                       <option data-countryCode="BT" value="975">BT (+975)</option>
                                       <option data-countryCode="BO" value="591">BO (+591)</option>
                                       <option data-countryCode="BA" value="387">BA (+387)</option>
                                       <option data-countryCode="BW" value="267">BW (+267)</option>
                                       <option data-countryCode="BR" value="55">BR (+55)</option>
                                       <option data-countryCode="BN" value="673">BN (+673)</option>
                                       <option data-countryCode="BG" value="359">BG (+359)</option>
                                       <option data-countryCode="BF" value="226">BF (+226)</option>
                                       <option data-countryCode="BI" value="257">BI (+257)</option>
                                       <option data-countryCode="KH" value="855">KH (+855)</option>
                                       <option data-countryCode="CM" value="237">CM (+237)</option>
                                       <option data-countryCode="CA" value="1">CA (+1)</option>
                                       <option data-countryCode="CV" value="238">CV (+238)</option>
                                       <option data-countryCode="KY" value="1345">KY (+1345)</option>
                                       <option data-countryCode="CF" value="236">CF (+236)</option>
                                       <option data-countryCode="CL" value="56">CL (+56)</option>
                                       <option data-countryCode="CN" value="86">CN (+86)</option>
                                       <option data-countryCode="CO" value="57">CO (+57)</option>
                                       <option data-countryCode="KM" value="269">KM (+269)</option>
                                       <option data-countryCode="CG" value="242">CG (+242)</option>
                                       <option data-countryCode="CK" value="682">CK (+682)</option>
                                       <option data-countryCode="CR" value="506">CR (+506)</option>
                                       <option data-countryCode="HR" value="385">HR (+385)</option>
                                       <option data-countryCode="CU" value="53">CU (+53)</option>
                                       <option data-countryCode="CY" value="90392">CY (+90392)</option>
                                       <option data-countryCode="CY" value="357">CY (+357)</option>
                                       <option data-countryCode="CZ" value="42">CZ (+42)</option>
                                       <option data-countryCode="DK" value="45">DK (+45)</option>
                                       <option data-countryCode="DJ" value="253">DJ (+253)</option>
                                       <option data-countryCode="DM" value="1809">DM (+1809)</option>
                                       <option data-countryCode="DO" value="1809">DO (+1809)</option>
                                       <option data-countryCode="EC" value="593">EC (+593)</option>
                                       <option data-countryCode="EG" value="20">EG (+20)</option>
                                       <option data-countryCode="SV" value="503">SV (+503)</option>
                                       <option data-countryCode="GQ" value="240">GQ (+240)</option>
                                       <option data-countryCode="ER" value="291">ER (+291)</option>
                                       <option data-countryCode="EE" value="372">EE (+372)</option>
                                       <option data-countryCode="ET" value="251">ET (+251)</option>
                                       <option data-countryCode="FK" value="500">FK (+500)</option>
                                       <option data-countryCode="FO" value="298">FO (+298)</option>
                                       <option data-countryCode="FJ" value="679">FJ (+679)</option>
                                       <option data-countryCode="FI" value="358">FI (+358)</option>
                                       <option data-countryCode="FR" value="33">FR (+33)</option>
                                       <option data-countryCode="GF" value="594">GF (+594)</option>
                                       <option data-countryCode="PF" value="689">PF (+689)</option>
                                       <option data-countryCode="GA" value="241">GA (+241)</option>
                                       <option data-countryCode="GM" value="220">GM (+220)</option>
                                       <option data-countryCode="GE" value="7880">GE (+7880)</option>
                                       <option data-countryCode="DE" value="49">DE (+49)</option>
                                       <option data-countryCode="GH" value="233">GH (+233)</option>
                                       <option data-countryCode="GI" value="350">GI (+350)</option>
                                       <option data-countryCode="GR" value="30" selected>GR (+30)</option>
                                       <option data-countryCode="GL" value="299">GL (+299)</option>
                                       <option data-countryCode="GD" value="1473">GD (+1473)</option>
                                       <option data-countryCode="GP" value="590">GP (+590)</option>
                                       <option data-countryCode="GU" value="671">GU (+671)</option>
                                       <option data-countryCode="GT" value="502">GT (+502)</option>
                                       <option data-countryCode="GN" value="224">GN (+224)</option>
                                       <option data-countryCode="GW" value="245">GW (+245)</option>
                                       <option data-countryCode="GY" value="592">GY (+592)</option>
                                       <option data-countryCode="HT" value="509">HT (+509)</option>
                                       <option data-countryCode="HN" value="504">HN (+504)</option>
                                       <option data-countryCode="HK" value="852">HK (+852)</option>
                                       <option data-countryCode="HU" value="36">HU (+36)</option>
                                       <option data-countryCode="IS" value="354">IS (+354)</option>
                                       <option data-countryCode="IN" value="91">IN (+91)</option>
                                       <option data-countryCode="ID" value="62">ID (+62)</option>
                                       <option data-countryCode="IR" value="98">IR (+98)</option>
                                       <option data-countryCode="IQ" value="964">IQ (+964)</option>
                                       <option data-countryCode="IE" value="353">IE (+353)</option>
                                       <option data-countryCode="IL" value="972">IL (+972)</option>
                                       <option data-countryCode="IT" value="39">IT (+39)</option>
                                       <option data-countryCode="JM" value="1876">JM (+1876)</option>
                                       <option data-countryCode="JP" value="81">JP (+81)</option>
                                       <option data-countryCode="JO" value="962">JO (+962)</option>
                                       <option data-countryCode="KZ" value="7">KZ (+7)</option>
                                       <option data-countryCode="KE" value="254">KE (+254)</option>
                                       <option data-countryCode="KI" value="686">KI (+686)</option>
                                       <option data-countryCode="KP" value="850">KP (+850)</option>
                                       <option data-countryCode="KR" value="82">KR (+82)</option>
                                       <option data-countryCode="KW" value="965">KW (+965)</option>
                                       <option data-countryCode="KG" value="996">KG (+996)</option>
                                       <option data-countryCode="LA" value="856">LA (+856)</option>
                                       <option data-countryCode="LV" value="371">LV (+371)</option>
                                       <option data-countryCode="LB" value="961">LB (+961)</option>
                                       <option data-countryCode="LS" value="266">LS (+266)</option>
                                       <option data-countryCode="LR" value="231">LR (+231)</option>
                                       <option data-countryCode="LY" value="218">LY (+218)</option>
                                       <option data-countryCode="LI" value="417">LI (+417)</option>
                                       <option data-countryCode="LT" value="370">LT (+370)</option>
                                       <option data-countryCode="LU" value="352">LU (+352)</option>
                                       <option data-countryCode="MO" value="853">MO (+853)</option>
                                       <option data-countryCode="SK" value="389">SK (+389)</option>
                                       <option data-countryCode="MG" value="261">MG (+261)</option>
                                       <option data-countryCode="MW" value="265">MW (+265)</option>
                                       <option data-countryCode="MY" value="60">MY (+60)</option>
                                       <option data-countryCode="MV" value="960">MV (+960)</option>
                                       <option data-countryCode="ML" value="223">ML (+223)</option>
                                       <option data-countryCode="MT" value="356">MT (+356)</option>
                                       <option data-countryCode="MH" value="692">MH (+692)</option>
                                       <option data-countryCode="MQ" value="596">MQ (+596)</option>
                                       <option data-countryCode="MR" value="222">MR (+222)</option>
                                       <option data-countryCode="YT" value="269">YT (+269)</option>
                                       <option data-countryCode="MX" value="52">MX (+52)</option>
                                       <option data-countryCode="FM" value="691">FM (+691)</option>
                                       <option data-countryCode="MD" value="373">MD (+373)</option>
                                       <option data-countryCode="MC" value="377">MC (+377)</option>
                                       <option data-countryCode="MN" value="976">MN (+976)</option>
                                       <option data-countryCode="MS" value="1664">MS (+1664)</option>
                                       <option data-countryCode="MA" value="212">MA (+212)</option>
                                       <option data-countryCode="MZ" value="258">MZ (+258)</option>
                                       <option data-countryCode="MN" value="95">MN (+95)</option>
                                       <option data-countryCode="NA" value="264">NA (+264)</option>
                                       <option data-countryCode="NR" value="674">NR (+674)</option>
                                       <option data-countryCode="NP" value="977">NP (+977)</option>
                                       <option data-countryCode="NL" value="31">NL (+31)</option>
                                       <option data-countryCode="NC" value="687">NC (+687)</option>
                                       <option data-countryCode="NZ" value="64">NZ (+64)</option>
                                       <option data-countryCode="NI" value="505">NI (+505)</option>
                                       <option data-countryCode="NE" value="227">NE (+227)</option>
                                       <option data-countryCode="NG" value="234">NG (+234)</option>
                                       <option data-countryCode="NU" value="683">NU (+683)</option>
                                       <option data-countryCode="NF" value="672">NF (+672)</option>
                                       <option data-countryCode="NP" value="670">NP (+670)</option>
                                       <option data-countryCode="NO" value="47">NO (+47)</option>
                                       <option data-countryCode="OM" value="968">OM (+968)</option>
                                       <option data-countryCode="PW" value="680">PW (+680)</option>
                                       <option data-countryCode="PA" value="507">PA (+507)</option>
                                       <option data-countryCode="PG" value="675">PG (+675)</option>
                                       <option data-countryCode="PY" value="595">PY (+595)</option>
                                       <option data-countryCode="PE" value="51">PE (+51)</option>
                                       <option data-countryCode="PH" value="63">PH (+63)</option>
                                       <option data-countryCode="PL" value="48">PL (+48)</option>
                                       <option data-countryCode="PT" value="351">PT (+351)</option>
                                       <option data-countryCode="PR" value="1787">PR (+1787)</option>
                                       <option data-countryCode="QA" value="974">QA (+974)</option>
                                       <option data-countryCode="RE" value="262">RE (+262)</option>
                                       <option data-countryCode="RO" value="40">RO (+40)</option>
                                       <option data-countryCode="RU" value="7">RU (+7)</option>
                                       <option data-countryCode="RW" value="250">RW (+250)</option>
                                       <option data-countryCode="SM" value="378">SM (+378)</option>
                                       <option data-countryCode="ST" value="239">ST (+239)</option>
                                       <option data-countryCode="SA" value="966">SA (+966)</option>
                                       <option data-countryCode="SN" value="221">SN (+221)</option>
                                       <option data-countryCode="CS" value="381">CS (+381)</option>
                                       <option data-countryCode="SC" value="248">SC (+248)</option>
                                       <option data-countryCode="SL" value="232">SL (+232)</option>
                                       <option data-countryCode="SG" value="65">SG (+65)</option>
                                       <option data-countryCode="SK" value="421">SK (+421)</option>
                                       <option data-countryCode="SI" value="386">SI (+386)</option>
                                       <option data-countryCode="SB" value="677">SB (+677)</option>
                                       <option data-countryCode="SO" value="252">SO (+252)</option>
                                       <option data-countryCode="ZA" value="27">ZA (+27)</option>
                                       <option data-countryCode="ES" value="34">ES (+34)</option>
                                       <option data-countryCode="LK" value="94">LK (+94)</option>
                                       <option data-countryCode="SH" value="290">SH (+290)</option>
                                       <option data-countryCode="KN" value="1869">KN (+1869)</option>
                                       <option data-countryCode="SC" value="1758">SC (+1758)</option>
                                       <option data-countryCode="SD" value="249">SD (+249)</option>
                                       <option data-countryCode="SR" value="597">SR (+597)</option>
                                       <option data-countryCode="SZ" value="268">SZ (+268)</option>
                                       <option data-countryCode="SE" value="46">SE (+46)</option>
                                       <option data-countryCode="CH" value="41">CH (+41)</option>
                                       <option data-countryCode="SI" value="963">SI (+963)</option>
                                       <option data-countryCode="TW" value="886">TW (+886)</option>
                                       <option data-countryCode="TJ" value="7">TJ (+7)</option>
                                       <option data-countryCode="TH" value="66">Thailand (+66)</option>
                                       <option data-countryCode="TG" value="228">TH (+228)</option>
                                       <option data-countryCode="TO" value="676">TO (+676)</option>
                                       <option data-countryCode="TT" value="1868">TT (+1868)</option>
                                       <option data-countryCode="TN" value="216">TN (+216)</option>
                                       <option data-countryCode="TR" value="90">TR (+90)</option>
                                       <option data-countryCode="TM" value="7">TM (+7)</option>
                                       <option data-countryCode="TM" value="993">TM (+993)</option>
                                       <option data-countryCode="TC" value="1649">TC</option>
                                       <option data-countryCode="TV" value="688">TV (+688)</option>
                                       <option data-countryCode="UG" value="256">UG (+256)</option>
                                       <option data-countryCode="GB" value="44">UK (+44)</option>
                                       <option data-countryCode="UA" value="380">UA (+380)</option>
                                       <option data-countryCode="AE" value="971">"AE (+971)</option>
                                       <option data-countryCode="UY" value="598">UY (+598)</option>
                                       <option data-countryCode="US" value="1">USA (+1)</option>
                                       <option data-countryCode="UZ" value="7">UZ (+7)</option>
                                       <option data-countryCode="VU" value="678">VU (+678)</option>
                                       <option data-countryCode="VA" value="379">VA (+379)</option>
                                       <option data-countryCode="VE" value="58">VE (+58)</option>
                                       <option data-countryCode="VN" value="84">VN (+84)</option>
                                       <option data-countryCode="VG" value="84">VG (+1284)</option>
                                       <option data-countryCode="VI" value="84">VI (+1340)</option>
                                       <option data-countryCode="WF" value="681">WF (+681)</option>
                                       <option data-countryCode="YE" value="969">YE (North)(+969)</option>
                                       <option data-countryCode="YE" value="967">YE (South)(+967)</option>
                                       <option data-countryCode="ZM" value="260">ZM (+260)</option>
                                       <option data-countryCode="ZW" value="263">ZW (+263)</option>

                                    </select>
                                    <input class="required"type="text"  onkeyup="checkPhoneNumberParticipant(this,<?php echo $i; ?>)" id="mobile<?php echo $i; ?>" name="mobile[]" placeholder="Mobile*" value="@if(isset($pay_seats_data) && isset($pay_seats_data['mobiles'][$i])){{$pay_seats_data['mobiles'][$i]}}@elseif(isset($cur_user) && $i == 0){{$cur_user->mobile}}@endif" required />
                                    <input class="required"type="hidden" id="mobileCheck<?php echo $i; ?>" name="mobileCheck[]" placeholder="Mobile*" value="@if(isset($pay_seats_data) && isset($pay_seats_data['mobileCheck'][$i])){{$pay_seats_data['mobileCheck'][$i]}}@elseif(isset($cur_user) && $i == 0)+{{$cur_user->country_code}}{{$cur_user->mobile}}@endif" required />
                                 </div>
                              </div>
                              <div class="col6 col-sm-12">
                                 <label>Company </label>
                                    <input type="text" id="company<?php echo $i; ?>" name="company[]" placeholder="Company (if any)" value="@if(isset($pay_seats_data) && isset($pay_seats_data['companies'][$i])){{$pay_seats_data['companies'][$i]}}@elseif(isset($cur_user) && $i == 0){{$cur_user->company}}@endif" />

                              </div>
                              <div class="col6 col-sm-12">
                                 <label>Position title </label>
                                 <div class="input-safe-wrapper">
                                          <input class="" type="text" id="jobtitle<?php echo $i; ?>" name="jobtitle[]" placeholder="Title or Job Position" value="@if(isset($pay_seats_data) && isset($pay_seats_data['jobtitles'][$i])){{$pay_seats_data['jobtitles'][$i]}}@elseif(isset($cur_user) && $i == 0){{$cur_user->job_title}}@endif" required />
                                 </div>
                              </div>
                              @if($item->options->has('type'))
                              @switch ( $item->options->type)
                              @case(0)
                              <div class="form-group endrow" id="student" hidden>
                              <div class="input-safe-wrapper">
                                          <input class="required" type="text" class="form-control" id="studentId"  value="-">
                              </div>
                              </div>
                              @break
                              @case(7)
                              <div class="form-group endrow" id="student" hidden>
                              <div class="input-safe-wrapper">
                                          <input class="required" type="text" class="form-control" id="studentId"  value="-">
                              </div>
                              </div>
                              @break
                              @case(1)
                              <div class="col6 col-sm-12">
                              <label>Unemployed Id* </label>
                              <div class="input-safe-wrapper">
                                          <input class="required" type="text"  id="studentId" name="studentId[]" placeholder="Unemployed Id*" value="@if(isset($pay_seats_data) && isset($pay_seats_data['studentId'][$i])){{$pay_seats_data['studentId'][$i]}}@elseif(isset($cur_user) && $i == 0){{$cur_user->student_type_id}}@endif" required>
                              </div>
                              </div>
                              @break
                              @case(2)
                              <div class="col6 col-sm-12">
                              <label>Student Id*</label>
                              <div class="input-safe-wrapper">
                                          <input class="required" type="text" id="studentId" name="studentId[]" placeholder="Student Id*" value="@if(isset($pay_seats_data) && isset($pay_seats_data['studentId'][$i])){{$pay_seats_data['studentId'][$i]}}@elseif(isset($cur_user) && $i == 0){{$cur_user->student_type_id}}@endif" required>
                              </div>
                              </div>
                              @break
                              @case(3)
                              <div class="col6 col-sm-12">
                              <label>Knowcrunch Id* / Deree Id* </label>
                              <div class="input-safe-wrapper">
                                 <input class="required" type="text"  id="studentId" name="studentId[]" placeholder="Knowcrunch Id* / Deree Id*" value="@if(Auth::check()){{Auth::user()->id}}@endif" required>
                              </div>
                              </div>
                              @break
                              @case(4)
                              <div class="col6 col-sm-12">
                              <div class="input-safe-wrapper">
                                          <input class="required" type="text" id="dereeId" name="studentId[]" placeholder="Deree Id"  value="" required>
                              </div>
                              </div>
                              @break
                              @case (5)
                              <div class="col6 col-sm-12" hidden>
                                 <input type="text" id="studentId" name="groupOf2[]" placeholder="Group Of 2+" value="-">
                              </div>
                              @break
                              @default
                              @endswitch
                              @endif
                           </div>
                           @if($i>0)
                        </div>
                        @endif
                        <?php endfor ?>
                     </form>
                     @if($thetype==5)
                     <a href="#" class="btn btn--lg btn--secondary btn-add-participant" data-ticket-max = "{{$themax}}" data-participant-number="{{ $item->qty }}"><img src="{{cdn('/theme/assets/images/icons/icon-add-user.svg')}}" alt="Add another participant">Add another participant</a>
                     @endif
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="section-checkout checkout-billing section--gray">
      <div class="container">
         <div class="checkout-wrapper">
            <div class="row">
               <div class="col8 col-sm-12">
                  <h2>Billing</h2>
                  <div class="form-wrapper">
                     <form method="post" id="billing-setting" action="/pay-sbt" class="small-form">
                        {{csrf_field()}}
                        <div class="row">
                           @if(!isset($paywithstripe) || $paywithstripe != 1)
                           <div class="col12 col-sm-12">
                              <div class="custom-radio-wrapper">
                                 <div class="custom-radio-box active">
                                    <div class="crb-wrapper">
                                       <input id="radio-receipt-control" name="needbilling" value="1" @if(isset($pay_bill_data) && isset($pay_bill_data['billing']) && $pay_bill_data['billing'] == 1) checked @elseif(!isset($pay_bill_data['billing'])) checked @endif type="radio" data-fieldset-target="receipt-fields" name="billing-receipt-invoice" checked="checked">
                                       <span></span>
                                    </div>
                                    <div class="label-wrapper">
                                       <label for="radio-receipt-control">Receipt</label>
                                    </div>
                                 </div>
                                 <div class="custom-radio-box">
                                    <div class="crb-wrapper">
                                       <input id="radio-invoice-control" name="needbilling" value="2" @if(isset($pay_bill_data) && isset($pay_bill_data['billing']) && $pay_bill_data['billing'] == 2) checked @endif type="radio" data-fieldset-target="invoice-fields" name="billing-receipt-invoice">
                                       <span></span>
                                    </div>
                                    <div class="label-wrapper">
                                       <label for="radio-invoice-control">Invoice</label>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           @else
                           <input hidden id="radio-receipt-control" name="needbilling" value="1" @if(isset($pay_bill_data) && isset($pay_bill_data['billing']) && $pay_bill_data['billing'] == 1) checked @elseif(!isset($pay_bill_data['billing'])) checked @endif type="radio" data-fieldset-target="receipt-fields" name="billing-receipt-invoice" checked="checked">
                           @endif
                           <div>
                           <div class="col12 receipt-fields hidden-fields-actions" style="display:block">
                              <div class ="row">
                              <div class="col6 col-sm-12">
                              @if(!isset($paywithstripe) || $paywithstripe != 1)
                              <label>First name <span>*</span></label>
                                 <div class="input-safe-wrapper">
                                    <input class="required" type="text" id="billname" name="billname" placeholder="Name" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billname'])){{$pay_bill_data['billname']}}@endif" required />
                                 </div>
                              </div>

                              @else
                              <label>Name or Company <span>*</span></label>
                                 <div class="input-safe-wrapper">
                                    <input class="required" type="text" id="billname" name="billname" placeholder="Name" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billname'])){{$pay_bill_data['billname']}}@endif" oninput="billsurname.value = billname.value; return true;" required />
                                 </div>
                              </div>

                              @endif
                              @if(!isset($paywithstripe) || $paywithstripe != 1)
                              <div class="col6 col-sm-12">
                                 <label>Last name <span>*</span></label>
                                 <div class="input-safe-wrapper">
                                          <input  class="required" type="text" id="billsurname" name="billsurname" placeholder="Surname" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billsurname'])){{$pay_bill_data['billsurname']}}@endif" required />
                                 </div>
                              </div>

                              @else

                              <input style="display:none"  class="hidden" type="text" id="billsurname" name="billsurname" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billsurname'])){{$pay_bill_data['billsurname']}}@endif" oninput="billname.value = billsurname.value; return true;" />

                              @endif
                              <div class="col6 col-sm-12" >
                                 <label>Street <span>*</span></label>
                                 <div class="input-safe-wrapper">
                                          <input class="required" type="text" id="billaddress" name="billaddress" placeholder="Street Address" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billaddress'])){{$pay_bill_data['billaddress']}}@endif" required />
                                 </div>
                              </div>
                              <div class="col6 col-sm-12">
                                 <label>Street number <span>*</span></label>
                                 <div class="input-safe-wrapper">
                                          <input class="required" type="number" id="billaddressnum" name="billaddressnum" placeholder="Street Number" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billaddressnum'])){{$pay_bill_data['billaddressnum']}}@endif" required />
                                 </div>
                              </div>
                              <div class="col6 col-sm-12">
                                 <label>City <span>*</span></label>
                                 <div class="input-safe-wrapper">
                                          <input class="required" type="text" id="billcity" name="billcity" placeholder="City" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billcity'])){{$pay_bill_data['billcity']}}@endif" required />
                                 </div>
                              </div>
                              <div class="col6 col-sm-12">
                                 <label>Postcode <span>*</span></label>
                                 <div class="input-safe-wrapper">
                                          <input class="required" type="text" id="billpostcode" name="billpostcode" placeholder="Post Code" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billpostcode'])){{$pay_bill_data['billpostcode']}}@endif" required />
                                 </div>
                              </div>
                              {{--
                              <div class="col6 col-sm-12">
                                 <label>Country <span>*</span></label>
                                 <input type="text" name="" required />
                              </div>
                              --}}
                              <div class="col6 col-sm-12">
                                 <label>VAT/ID number <span>*</span></label>
                                 <div class="input-safe-wrapper">
                                          <input class="required" type="text" id="billafm" name="billafm" placeholder="Α.Φ.Μ." value="@if(isset($pay_bill_data) && isset($pay_bill_data['billafm'])){{$pay_bill_data['billafm']}}@endif" required />
                                 </div>
                              </div>
                              </div>
                              </div>
                           <div class="col12 hidden-fields-actions invoice-fields">
                              <div class="row">
                                 <div class="col6 col-sm-12">
                                    <label> Επωνυμία εταιρίας <span>*</span></label>
                                    <div class="input-safe-wrapper">
                                          <input class="required"type="text" id="companyname" name="companyname" placeholder="Επωνυμία εταιρίας" value="@if(isset($pay_bill_data) && isset($pay_bill_data['companyname'])){{$pay_bill_data['companyname']}}@endif" required />
                                    </div>
                                 </div>
                                 <div class="col6 col-sm-12">
                                    <label>Δραστηριότητα εταιρίας <span>*</span></label>
                                    <div class="input-safe-wrapper">
                                          <input class="required" type="text"id="companyprofession" name="companyprofession" placeholder="Δραστηριότητα εταιρίας" value="@if(isset($pay_bill_data) && isset($pay_bill_data['companyprofession'])){{$pay_bill_data['companyprofession']}}@endif" required />
                                    </div>
                                 </div>
                                 <div class="col6 col-sm-12">
                                    <label>Α.Φ.Μ <span>*</span></label>
                                    <div class="input-safe-wrapper">
                                          <input class="required" type="text" id="companyafm" name="companyafm" placeholder="Α.Φ.Μ." value="@if(isset($pay_bill_data) && isset($pay_bill_data['companyafm'])){{$pay_bill_data['companyafm']}}@endif" required />
                                    </div>
                                 </div>
                                 <div class="col6 col-sm-12">
                                    <label>ΔΟΥ <span>*</span></label>
                                    <div class="input-safe-wrapper">
                                          <input class="required" type="text" id="companydoy" name="companydoy" placeholder="ΔΟΥ" value="@if(isset($pay_bill_data) && isset($pay_bill_data['companydoy'])){{$pay_bill_data['companydoy']}}@endif" required />
                                    </div>
                                 </div>
                                 <div class="col6 col-sm-12">
                                    <label>Διεύθυνση <span>*</span></label>
                                    <div class="input-safe-wrapper">
                                          <input class="required" type="text" id="companyaddress" name="companyaddress" placeholder="Διεύθυνση" value="@if(isset($pay_bill_data) && isset($pay_bill_data['companyaddress'])){{$pay_bill_data['companyaddress']}}@endif">
                                    </div>
                                 </div>
                                 <div class="col6 col-sm-12">
                                    <label>Αριθμός <span>*</span></label>
                                    <div class="input-safe-wrapper">
                                          <input class="required" type="number"  id="companyaddressnum" name="companyaddressnum" placeholder="Αριθμός" value="@if(isset($pay_bill_data) && isset($pay_bill_data['companyaddressnum'])){{$pay_bill_data['companyaddressnum']}}@endif">
                                    </div>
                                 </div>
                                 <div class="col6 col-sm-12">
                                    <label>T.K. <span>*</span></label>
                                    <div class="input-safe-wrapper">
                                          <input class="required" type="text"  id="companypostcode" name="companypostcode" placeholder="Τ.Κ." value="@if(isset($pay_bill_data) && isset($pay_bill_data['companypostcode'])){{$pay_bill_data['companypostcode']}}@endif">
                                    </div>
                                 </div>
                                 <div class="col6 col-sm-12">
                                    <label>Πόλη<span>*</span></label>
                                    <div class="input-safe-wrapper">
                                          <input class="required" type="text"id="companycity" name="companycity" placeholder="Πόλη" value="@if(isset($pay_bill_data) && isset($pay_bill_data['companycity'])){{$pay_bill_data['companycity']}}@endif"required />
                                    </div>
                                 </div>
                                 <div class="col6 col-sm-12">
                                    <label>Email αποστολής τιμολογίου <span>*</span></label>
                                    <div class="input-safe-wrapper">
                                          <input class="required" type="text"id="companyemail" name="companyemail" placeholder="Email αποστολής τιμολογίου" value="@if(isset($pay_bill_data) && isset($pay_bill_data['companyemail'])){{$pay_bill_data['companyemail']}}@endif"required />
                                    </div>
                                 </div>

                              </div>
                           </div>

                        </div>



                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- /.section-checkout.checkout-billing.section--gray -->
   </section>
   <section class="section-checkout checkout-payment">
      <div class="container">
         <div class="checkout-wrapper">
            <div class="row">
               <div class="col12 col-sm-12">
                  <h2>Payment</h2>
                  <div class="form-wrapper">
                     <form method="post" id='sbt-pay' action="/pay-sbt">
                        {{csrf_field()}}

                        @if(isset($paywithstripe) && $paywithstripe == 1 && count($coupons) > 0)
                           @if($item->options->has('type'))
                              {{-- if(item->options->type == 0) --}}
                              <div class="col6 col-sm-12 coupon-pad-bottom">
                                 <h3>Coupon</h3>
                                 <div class="col-sm-12" >
                                    <label>Do you have a discount code?</label>
                                    <div class="lg-flex">
                                       <input class="mar-right" onkeyup="convertToUppercase(this)" onblur="this.placeholder = 'Type here'" onfocus="this.placeholder = ''"  type="text" id="coupon" name="coupon" placeholder="Type here" value />
                                       <input class="btn1 btn--sm btn--secondary coupon-submit" type="button" value="Apply">
                                    </div>

                                 </div>

                              </div>
                              {{-- endif --}}
                           @endif
                        @endif

                        <div class="row">
                           @if(isset($paywithstripe) && $paywithstripe == 1)
                              @include('theme.cart.stripe-form')
                              <input type="hidden" id="payment_method_id" name="payment_method_id" value="100">

                           @else
                           <div class="col12 col-sm-12">
                              <div class="custom-radio-wrapper">
                                 <div class="custom-radio-box active">
                                    <div class="crb-wrapper">
                                       <input id="cardtype" type="radio" name="payment-card" checked="checked">
                                       <input type="hidden"  name="cardtype" value="1">
                                       <span></span>
                                    </div>
                                    <div class="label-wrapper">
                                       <label for="radio-debit-card-control">Debit card</label>
                                       <span>No installments.</span>
                                    </div>
                                 </div>
                                 <div class="custom-radio-box">
                                    <div class="crb-wrapper">
                                       <input id="cardtype" type="radio" data-fieldset-target="installments-fields" name="payment-card">
                                       <input type="hidden"  name="cardtype" value="2">
                                       <span></span>
                                    </div>
                                    <div class="label-wrapper">
                                       <label for="radio-credit-card-control">Credit card</label>
                                       <span>Up to 3 installments (some international credit cards and AMEX cards do not accept installments).</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col12 hidden-fields-actions installments-fields">
                              <?php
                                 $instOne = Cart::instance('default')->subtotal();
                                 $instTwo = round($instOne / 2, 2);
                                 $instThree = round($instOne / 3, 2);
                                 ?>
                              <div class="custom-radio-wrapper small-radio">
                                 <div class="custom-radio-box children-radio">
                                    <div class="crb-wrapper">
                                       <input id="radio-installment-full-control" type="radio" name='installments' value='1' data-fieldset-target="installments-fields" >
                                       <span></span>
                                    </div>
                                    <div class="label-wrapper">
                                       <label for="radio-installment-full-control">Payment in full <span>€{{$instOne}}</span>.</label>
                                    </div>
                                 </div>
                                 <div class="custom-radio-box children-radio">
                                    <div class="crb-wrapper">
                                       <input id="radio-installment-2-control" name='installments' value='2' type="radio" data-fieldset-target="installments-fields" >

                                       <span></span>
                                    </div>
                                    <div class="label-wrapper">
                                       <label for="radio-installment-2-control">2 installments: <span>2x €{{$instTwo}}</span>.</label>

                                    </div>
                                 </div>
                                 <div class="custom-radio-box children-radio">
                                    <div class="crb-wrapper">
                                       <input id="radio-installment-3-control" name='installments' value='3' type="radio" data-fieldset-target="installments-fields" >
                                       <span></span>
                                    </div>
                                    <div class="label-wrapper">
                                       <label for="radio-installment-3-control">3 installments: <span>3x €{{$instThree}}</span>.</label>


                                    </div>
                                 </div>
                              </div>


                              <input type="hidden" id="payment_method_id" name="payment_method_id" value="{{$pay_methods['id']}}">

                              <!-- /.col12.hidden-fields-actions.installments-fields -->
                           </div>
                           @endif



                           <div class="col12">
                              <div class="custom-checkbox-wrapper text-align-left extra-top-margin clearfix">
                                 <div class="custom-checkbox">
                                    <input type="checkbox" id="read-accept-tcp" name="read-accept-tcp" value="true"/>
                                    <span></span>
                                 </div>
                                 <label for="read-accept-tcp" class="big">I have read, agree upon & accept the <a href="/terms">terms & conditions </a> and <a href="/data-privacy-policy">data privacy policy</a>. </label>
                              </div>
                           </div>
                           <div class="col12">

                              <div class="checkout-proceed-action">
                                 <button id="checkout-button" type="submit" class="btn btn--xl btn--primary do-checkout">Proceed to payment</button>
                              </div>

                           </div>
                        </div>

                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- /.section-checkout.checkout-billing.section--gray -->
   </section>
</main>

@endif
@if(Cart::instance('default')->subtotal() > 0)
{{--include('theme.cart.alpha-logos')--}}
<!-- STRIPE CHECK LOGOS -->
@endif
<div class="loadmodal"></div>
@stop
@section('scripts')

@if(isset($paywithstripe) && $paywithstripe == 1 && Auth::check())
<script>
    $(document).on('click', '#addCard', function(e){

      /*$('<script>')
       .attr('src', 'https://js.stripe.com/v3/')
       .attr('id', 'stripe-js')
       .appendTo('head');*/



      $('#addCard').prop('disabled', true);
      $('.msg_save_card').remove();
      $('#container').append(`
         <input id="card-holder-name" type="text">
         <!-- Stripe Elements Placeholder -->
         <div id="card-element"></div>
         <button id="card-button" type="button" class="btn btn--secondary btn--sm" data-secret="{{ Auth::user()->createSetupIntent()->client_secret }}">
             Update Payment Method
         </button>`)


         $('<script>')
       .text(`var stripe = Stripe('{{$stripe_key}}',{locale: 'en'});
               var elements = stripe.elements();
               var cardElement = elements.create('card',{
                  style: {
                     base: {

                        fontSize: '18px',

                     },
                  },
                  hidePostalCode: true,
                  });
               cardElement.mount('#card-element');`)

       .attr('id', 'stripe-form')
       .appendTo('head');




   })



</script>

@endif




<script>


   $(document).on('click',"#card-button",async (e) => {
      var cardHolderName = document.getElementById('card-holder-name');
      var cardButton = document.getElementById('card-button');
      var clientSecret = cardButton.dataset.secret;
      let { setupIntent, error } = await stripe.confirmCardSetup(
           clientSecret, {
               payment_method: {
                   card: cardElement,
                   billing_details: { name: cardHolderName.value }
               }
           }
       ).then(function (result) {
            if (result.error) {
               console.log('error = ', result.error)
                //$('#card-errors').text(result.error.message)
                //$('button.pay').removeAttr('disabled')
            } else {
               paymentMethod = result.setupIntent.payment_method
               console.log(paymentMethod)
               $('button').prop('disabled', true);
               $.ajax({
                  type:'POST',
                  url:'card/store_from_payment',
                  headers: {
                   'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                  },
                  data:{ 'payment_method' : paymentMethod},
                  success:function(data) {

                     if(data['success']){
                        data = JSON.stringify(data['card'])
                        data = JSON.parse(data)


                        $('#brand').text(data['brand'])
                        $('#last4').text(data['last4'])
                        $('#exp_month').text(data['exp_month'])
                        $('#exp_year').text(data['exp_year'])

                        $("#stripe-form").remove();
                     $("#stripe-js").remove();

                        $('#container').children().remove();

                        $('#container').append(`<p class="normal msg_save_card"> Successfully added card!!</p>`)
                        $('#addCard').prop('disabled', false);
                        $('button').prop('disabled', false);
                     }else{
                        let message = `<img src="{{cdn('theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert">` + data['message'];
                        $("#card-message").html( message)

                        var favDialogCard = document.getElementById('favDialogCardNumberFailed');
                        favDialogCard.style.display = "block";

                        $('#addCard').prop('disabled', false);
                        $('button').prop('disabled', false);
                        $("#stripe-form").remove();
                     $("#stripe-js").remove();
                     }



                  },

               });
            }
         });

   })




</script>

<script>
   $(document).ready(function() {
       $(document).on('submit', '#billing-setting', function() {
           $('#checkout-button').attr('disabled', 'disabled');
           $('button').prop('disabled', true);
       });
   });
</script>

<script>
   $(document).on('click', '.newCustomer', function(e){
      var thec = $('input#regaccept');
      if (thec.prop("checked") === false) {
         // e.preventDefault();
        //  alert('Please accept the terms, conditions & data privacy in order to complete your registration.');
        var favDialog = document.getElementById('favDialog');
         // favDialog.showModal();
         favDialog.style.display = "block";
          $("body").css("overflow-y", "hidden")

       // favDialog.show();
      }else{
        var form = document.getElementById('new-customer-form');
        //console.log($('#new-customer-form').serialize);
        form.submit();
      }
   })
</script>

<script>
   $(document).on('click', '.close-alert', function(e){
      var favDialog = document.getElementById('favDialog');
      var favDialogCard = document.getElementById('favDialogCard');
      var favDialogCardNumberFailed = document.getElementById('favDialogCardNumberFailed');
     // favDialog.close();
     favDialog.style.display = "none";
     favDialogCard.style.display = "none";
     favDialogCardNumberFailed.style.display = "none";
      $("body").css("overflow-y", "auto")
   })

</script>
<script>
   $(document).on('click', '#forgot-pass2', function(e){
       $('#loginf').hide()
       $('#forgotf').show()
   })

   $(document).on('click', '.close-btn', function(e){
      $('#loginf').show()
       $('#forgotf').hide()
   })

</script>
@if(Cart::instance('default')->subtotal() >  0)
<script type="text/javascript">var type = "<?= $item->options->type ?>";</script>
@endif
<script src="theme/assets/js/cart.js"></script>
<script type="text/javascript">
   $(document).ready(function(){
       var next = 1;
       $(".add-more").click(function(e){
           e.preventDefault();
           var addto = "#field" + next;
           var addRemove = "#field" + (next);
           next = next + 1;
           var newIn = '<div id="field' + next + '" class="acfield"><div class="row"><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group startrow"><input type="text" class="form-control" id="name' + next + '" name="name[]" placeholder="Name"></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group startrow"><input type="text" class="form-control" id="surname' + next + '" name="surname[]" placeholder="Surname"></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group startrow"><input type="text" class="form-control" id="email' + next + '" name="email[]" placeholder="Email"></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group endrow"><input type="text" class="form-control" id="mobile' + next + '" name="mobile[]" placeholder="Email"></div></div></div><div class="row"><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group startrow"><input type="text" class="form-control" id="address' + next + '" name="address[]" placeholder="Street Name"></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group startrow"><input type="text" class="form-control" id="addressnum' + next + '" name="addressnum[]" placeholder="Street Address"></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group startrow"><input type="text" class="form-control" id="postcode' + next + '" name="postcode[]" placeholder="Post Code"></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group endrow"><input type="text" class="form-control" id="city' + next + '" name="city[]" placeholder="City"></div></div></div></div>';

           var newInput = $(newIn);
           var removeBtn = '<button id="remove' + (next - 1) + '" class="btn btn-booknow andnormal remove-me" >-</button>';
           var removeButton = $(removeBtn);
           $(addto).after(newInput);
           $(addRemove).after(removeButton);
           $("#field" + next).attr('data-source',$(addto).attr('data-source'));
           $("#count").val(next);

               $('.remove-me').click(function(e){
                   e.preventDefault();
                   var fieldNum = this.id.charAt(this.id.length-1);
                   var fieldID = "#field" + fieldNum;
                   $(this).remove();
                   $(fieldID).remove();
               });
       });
   });
</script>
<script>
   $(".btn-add-participant").on("click", function() {

   var number = $(this).attr('data-participant-number');
         var maxticket = $(this).attr('data-ticket-max');
         maxticket = parseInt(maxticket);
         var newNumber = parseInt(number)+1;
   document.getElementById('item-quantity').setAttribute('value',newNumber)

   	  $.ajax({ url: '/cart', type: "post",
             data: $(".small-form ").serialize(),
             success: function(data) {

             	ajaxCount('default');
   		window.location.replace('cart/');
             }
         });
   });
</script>
<script>
   $(".remove-participant").on("click", function() {

   var number = $(this).parent().parent().parent('.participant-form-group').next('.btn-add-participant').attr('data-participant-number');
         var maxticket = $(this).attr('data-ticket-max');
         maxticket = parseInt(maxticket);
         var newNumber = parseInt(number)-1;
   document.getElementById('item-quantity').setAttribute('value',newNumber)

   	  $.ajax({ url: '/cart', type: "post",
             data: $(".small-form ").serialize(),
             success: function(data) {
             	ajaxCount('default');
   		window.location.replace('cart/');
             }
         });
   });
</script>

@if(request()->has('reg') &&request()->get('reg') == 10 )
<script type="text/javascript">
   $( document ).ready(function() {

      fbq('track', 'CompleteRegistration');

       $('#billing input[type=radio]').on('change', function() {

       	if (this.value == 1) {
       		$('#billing-form').slideDown();
       		$('#invoice-form').slideUp();
       	}
       	else if (this.value == 2) {
       		$('#billing-form').slideUp();
       		$('#invoice-form').slideDown();
       	}
       	else {
       		$('#billing-form').slideUp();
       		$('#invoice-form').slideUp();
       	}
   	});


   	$('#cardtype input[type=radio]').on('change', function() {
       	//console.log(this.value);
       	if (this.value == 2) {
       		$('#installments-form').slideDown();
       	} else {
       		$('#installments-form').slideUp();

       	}
   	});


   });

</script>
@endif
<script>
$( document ).ready(function() {

   @if((isset($info) && isset($info['success'])) && $info['success'] !== true && isset($item))

   if(<?php echo Cart::instance('default')->subtotal() ?> > 0){

      fbq('track', 'AddToCart', {
         content_name: '<?php echo  $item->name ?>',
         content_category: '<?php echo $categoryScript; ?>',
         content_ids: ['{{$eventId}}'],
         content_type: 'product',
         value: '{{ $item->subtotal }}',
         currency: 'EUR',
      });


   }
@endif

});

</script>

<script type="text/javascript">
   function acceptance() {
   	var accept_btn = document.getElementById('accbtn').checked;
   	var form_post = document.getElementById('demo');
   	if (accept_btn)
   	{
   		form_post.submit();
   	} else {

   		alert("Please accept the Terms of use (I Agree)");
   	}
   }
</script>
@if(isset($info) && isset($info['message']) && $info['message'] != '')
@if($info['statusClass'] == 'success')
@if(isset($tigran) && isset($tigran['evid']) && isset($tigran['price']))
<script type="text/javascript">

   setTimeout(function(){
   	fbq('track', 'Purchase', {
   value: '{{$tigran['price']}}',
   currency: 'EUR',
   content_ids: '{{$tigran['evid']}}',
   });
   gtag('event', 'conversion', {
   'send_to': 'AW-859787100/L83oCJ_NhKMBENye_ZkD',
   'value': '{{$tigran['price']}}',
   'currency': 'EUR',
   'transaction_id': '{{$tigran['transid']}}'
   });


   gtag('event', 'purchase', {
  'transaction_id': '{{$tigran['transid']}}',
  'affiliation': 'Knowcrunch',
  'value': '{{$tigran['price']}}',
  'currency': 'EUR',
  'items': [
    {
      'id': '{{$tigran['evid']}}',
      'name': '{{$tigran['evid']}}'
    }
  ]
});
   }, 1000);
</script>
@else
<script type="text/javascript">
   setTimeout(function(){
   fbq('track', 'Purchase');
   gtag('event', 'conversion', {
   'send_to': 'AW-859787100/L83oCJ_NhKMBENye_ZkD',
   'value': 1.0,
   'currency': 'EUR'
   });
   }, 1000);

</script>
@endif
@else
<script type="text/javascript">
   // setTimeout(function(){
   //   		fbq('track', 'FailedPayment');
   //   	}, 1000);

</script>
@endif
@endif
<style>
 .select2-container .select2-selection--single{
    width: 110px!important;
}
   </style>

<script>

   $(document).ready(function() {

      $(".select2").select2()

      @if("{{ old('countryCode') }}")

         $("#selectCountry").val("{{ old('countryCode') }}").change();

      @endif

      @if(isset($totalitems))

         @for($i =0; $i < $totalitems; $i++)

            @if(isset($pay_seats_data) && !isset($pay_seats_data['countryCodes'][$i]) && $i == 0)

               $("#selectCountry"+"{{$i}}").val("{{$cur_user->country_code}}").change();

            @elseif( isset($pay_seats_data) && isset($pay_seats_data['countryCodes'][$i]) )

               $("#selectCountry"+"{{$i}}").val("{{ old('countryCodes[$i]',$pay_seats_data['countryCodes'][$i])}}").change();

            @endif

         @endfor
      @endif

   });

</script>


<script>

   function selectCountryCode(code,index){

      let mobile = $("#mobile"+index).val()
      $("#mobileCheck"+index).val("+" + code.value + mobile)

   }


   function checkPhoneNumberParticipant(phone,index){

      phone = phone.value.replace(/\s/g,'')
      let validatePhone = false;

      if(phone.length > 3){

         if(phone.substring(0, 3) == '+30' || phone.substring(0, 2) == '30'){

            $("#selectCountry"+index).val("30").change();
            $("#selectCountry-reg"+index).val("30").change();
            validatePhone = true;
         }else if(phone.substring(0, 2) == '69'){
            //phone = '+30'+phone
            validatePhone = true;
            $("#selectCountry-reg"+index).val("30").change();
            $("#selectCountry"+index).val("30").change();
         }
         else if(phone.substring(0, 4) == '+357' || phone.substring(0, 3) == '357'){//cyprus
            validatePhone = true;
            $("#selectCountry"+index).val("357").change();
            $("#selectCountry-reg"+index).val("357").change();
         }else if(phone.substring(0, 1) == '9'){
            //phone = '+357'+phone
            validatePhone = true;
            $("#selectCountry"+index).val("357").change();
            $("#selectCountry-reg"+index).val("357").change();
         }

         /*else if(phone.substring(0, 2) == '+1' || phone.substring(0, 1) == '1'){//usa
            validatePhone = true;

         }else if(phone.substring(0, 2) == '69'){
            phone = '+30'+phone
            validatePhone = true;

         }*/

         else if(phone.substring(0, 3) == '+44' || phone.substring(0, 2) == '44'){//england
            validatePhone = true;
            $("#selectCountry"+index).val("44").change();
            $("#selectCountry-reg"+index).val("44").change();
         }else if(phone.substring(0, 2) == '07' /*|| phone.substring(0, 3) == '073' || phone.substring(0, 3) == '074' || phone.substring(0, 3) == '075' || phone.substring(0, 3) == '076'
            || phone.substring(0, 3) == '077' || phone.substring(0, 3) == '078' || phone.substring(0, 3) == '079'*/){

               //phone = '+44'+phone
               validatePhone = true;
               $("#selectCountry"+index).val("44").change();
               $("#selectCountry-reg"+index).val("44").change();

         }

         //$("#mobile").val(phone)

         let mobile = '+' + $( "#selectCountry"+index ).val() + $("#mobile"+index).val()
         $("#mobileCheck"+index).val( mobile)

      }



   }
</script>


<script>

      $("#selectCountry").change(function() {
         let mobile = $("#mobile").val()

         $("#mobileCheck").val("+" + this.value + mobile)
      });

      $("#selectCountry-reg").change(function() {
         let mobile = $("#mobile").val()

         $("#mobileCheck").val("+" + this.value + mobile)
      });


   function checkPhoneNumber(phone){

      phone = phone.value.replace(/\s/g,'')
      let validatePhone = false;

      if(phone.length > 3){

         if(phone.substring(0, 3) == '+30' || phone.substring(0, 2) == '30'){

            $("#selectCountry").val("30").change();
            $("#selectCountry-reg").val("30").change();
            validatePhone = true;
         }else if(phone.substring(0, 2) == '69'){
            //phone = '+30'+phone
            validatePhone = true;
            $("#selectCountry").val("30").change();
            $("#selectCountry-reg").val("30").change();
         }
         else if(phone.substring(0, 4) == '+357' || phone.substring(0, 3) == '357'){//cyprus
            validatePhone = true;
            $("#selectCountry").val("357").change();
            $("#selectCountry-reg").val("357").change();
         }else if(phone.substring(0, 1) == '9'){
            //phone = '+357'+phone
            validatePhone = true;
            $("#selectCountry").val("357").change();
            $("#selectCountry-reg").val("357").change();
         }

         /*else if(phone.substring(0, 2) == '+1' || phone.substring(0, 1) == '1'){//usa
            validatePhone = true;

         }else if(phone.substring(0, 2) == '69'){
            phone = '+30'+phone
            validatePhone = true;

         }*/

         else if(phone.substring(0, 3) == '+44' || phone.substring(0, 2) == '44'){//england
            validatePhone = true;
            $("#selectCountry").val("44").change();
            $("#selectCountry-reg").val("44").change();
         }else if(phone.substring(0, 2) == '07' /*|| phone.substring(0, 3) == '073' || phone.substring(0, 3) == '074' || phone.substring(0, 3) == '075' || phone.substring(0, 3) == '076'
            || phone.substring(0, 3) == '077' || phone.substring(0, 3) == '078' || phone.substring(0, 3) == '079'*/){

               //phone = '+44'+phone
               validatePhone = true;
               $("#selectCountry").val("44").change();
               $("#selectCountry-reg").val("44").change();
         }

         //$("#mobile").val(phone)

         let mobile = '+' + $( "#selectCountry-reg" ).val() + $("#mobile").val()
         $("#mobileCheck").val( mobile)

      }



   }
</script>

@if(isset($paywithstripe) && $paywithstripe == 1)
   @if($item->options->has('type'))
      {{-- if(item->options->type == 0) --}}
      <script>

         function convertToUppercase(value){

            value = (value.value).toUpperCase()
            $("#coupon").val(value)
         }

         $('.coupon-submit').click(function(){

            //success-alert
            //error-alert

            $.ajax({ url: '/cart/checkCoupon/{{$eventId}}', type: "post",
            data:{'coupon': $("#coupon").val()} ,
            success: function(data) {
               if(data['success']){
                  $('.ticket-coupon').text('€' + "{{ $item->qty }}" * data['new_price']);
                  $('.ticket-coupon-inst2').text('€' + "{{ $item->qty }}" * data['newPriceInt2']);
                  $('.ticket-coupon-inst3').text('€' + "{{ $item->qty }}" * data['newPriceInt3']);

                  let p = `<p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-success-alert.svg')}}" alt="Info Alert">` + data['message']+ `</p>`

                  $("#couponDialog .alert-inner").empty();
                  $("#couponDialog .alert-inner").append(p);

                  $("#couponDialog .alert-wrapper").addClass('success-alert')
                  $("#couponDialog").css("display", "block")
                  $("body").css("overflow-y", "hidden")

                  $('.coupon-submit').css("display", "none")
                  $("#coupon").prop("readonly", true);
               }else{

                  let p = `<p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert">` + data['message']+ `</p>`

                  $("#couponDialog .alert-inner").empty();
                  $("#couponDialog .alert-inner").append(p);

                  $("#couponDialog .alert-wrapper").addClass('error-alert')
                  $("#couponDialog").css("display", "block")
                  $("body").css("overflow-y", "hidden")

               }

               setTimeout(function() {
                  $("#couponDialog").css("display", "none")
                  $("#couponDialog .alert-wrapper").removeClass('error-alert')
                  $("#couponDialog .alert-wrapper").removeClass('success-alert')
                  $("body").css("overflow-y", "auto")
                }, 2000);

            }
         });

         })
      </script>
      {{-- endif --}}
   @endif
@endif

@stop
