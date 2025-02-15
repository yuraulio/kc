@extends('theme.layouts.master')
@section('content')


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
<section class="selection-section">
      <div class="container">
         <h1 class="page-title">Checkout</h1>
         <h2>Your selection</h2>



         <div class="your-selection">
            <div class="item">
               <div class="remove-area">
                  <a class="btn-remove1" href="myaccount/"><img src="{{cdn('/theme/assets/images/icons/icon-delete.svg')}}" class="replace-with-svg" alt=""/><span>Remove</span></a>
               </div>
               <div class="left">
                  <h3> {{$event->title}} </h3>
                  <div class="bottom">
                     @if(isset($city))<div class="location"><img src="{{cdn('/theme/assets/images/icons/marker.svg')}}" alt="">{{$city}}</div>@endif
                     {{--<div class="duration"><img src="{{cdn('/theme/assets/images/icons/icon-calendar.svg')}}" alt="">@if(isset($ev_date_help)) {{$ev_date_help}}@endif</div>--}}
                     @if(isset($duration))<div class="expire-date"><img src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt="">{{$duration}}</div>@endif

                  </div>
               </div>
               <div class="right">
                  <ul class="checkout-infos">
                     <li>
                        <span class="label">Subscription:</span>
                        <span class="value">{{$plan->name}}</span>
                     </li>
                     <li>
                        <span class="label">Billing period:</span>

                        <span class="value ticket-coupon">
                        {{$plan->period()}}
                        </span>

                     </li>
                     <li class="total-amount">
                        <span class="label">Total amount:</span>
                        <span class="value ticket-coupon">€{{$plan->cost}}</span>
                     </li>
                  </ul>
               </div>
            </div>
            <!-- ./item -->
         </div>

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
                     <form method="post" id='user-info' action="/myaccount/subscription/store/{{$event->title}}/{{$plan->name}}" class="small-form">
                        {{csrf_field()}}

                        <?php $i=0 ?>

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
                                       <option data-countryCode="CZ" value="420">CZ (+420)</option>
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

                           </div>
                           @if($i>0)
                        </div>
                        @endif

                     </form>

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
                     <form method="post" id="billing-setting" action="/myaccount/subscription/store/{{$event->title}}/{{$plan->name}}" class="small-form">
                        {{csrf_field()}}
                        <div class="row">


                           <div class="col12 receipt-fields hidden-fields-actions" style="display:block">
                              <div class ="row">
                              <div class="col6 col-sm-12">

                              <label>Name or Company <span>*</span></label>
                                 <div class="input-safe-wrapper">
                                    <input class="required" type="text" id="billname" name="billname" placeholder="Name" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billname'])){{$pay_bill_data['billname']}}@endif" oninput="billsurname.value = billname.value; return true;" required />
                                 </div>
                              </div>



                              <input hidden id="radio-receipt-control" name="needbilling" value="1" @if(isset($pay_bill_data) && isset($pay_bill_data['billing']) && $pay_bill_data['billing'] == 1) checked @elseif(!isset($pay_bill_data['billing'])) checked @endif type="radio" data-fieldset-target="receipt-fields" name="billing-receipt-invoice" checked="checked">
                              <input style="display:none" name="event" value="{{$event->id}}">

                              <input style="display:none"  class="hidden" type="text" id="billsurname" name="billsurname" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billsurname'])){{$pay_bill_data['billsurname']}}@endif" oninput="billname.value = billsurname.value; return true;" />


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
                     <form method="post" id='sbt-pay' action="/myaccount/subscription/store/{{$event->title}}/{{$plan->name}}">
                        {{csrf_field()}}


                        <input type="hidden" name="plan_id" value="{{$plan->id}}">
                        <div class="row">

                              @include('theme.cart.stripe-suscription-form')
                              <input type="hidden" id="payment_method_id" name="payment_method_id" value="100">




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
                                 <button id="checkout-button" type="submit" class="btn btn--xl btn--primary do-checkout-subscription">Proceed to payment</button>
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

@stop

@section('scripts')

<script>
$(document).ready(function() {
    $(document).on('submit', '#billing-setting', function() {
        $('#checkout-button').attr('disabled', 'disabled');
        $('button').prop('disabled', true);
    });
});
</script>

   <script src="theme/assets/js/cart.js"></script>
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
    $(document).on('click', '#saveCard', function(e){
      let card_no = $('#card_no').val()
      let cvv = $('#cvvNumber').val()
      let exp_month = $('#ccExpiryMonth').val()
      let exp_year = $('#ccExpiryYear').val()
      $('button').prop('disabled', true);
      $.ajax({
               type:'POST',
               url:'/myaccount/card/store_from_payment',
               headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               data:{ 'card_no' : card_no, 'cvv' : cvv, 'exp_month' : exp_month, 'exp_year' : exp_year},
               success:function(data) {

                  if(data['success']){
                     data = JSON.stringify(data['card'])
                     data = JSON.parse(data)


                     $('#brand').text(data['brand'])
                     $('#last4').text(data['last4'])
                     $('#exp_month').text(data['exp_month'])
                     $('#exp_year').text(data['exp_year'])


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
                  }


               },
               error:function(data){

                  let message = `<img src="{{cdn('theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert">` + data['message'];
                  $("#card-message").html( message)

                  var favDialogCard = document.getElementById('favDialogCardNumberFailed');
                  favDialogCard.style.display = "block";

                  $('#addCard').prop('disabled', false);
                 $('button').prop('disabled', false);
               }
            });
    });
   </script>
   <script>


   function cvv(input) {


      if(isNaN(input.value)){
         input.value = '';
      }

   }


   function cardNo(input) {


      if(isNaN(input.value)){
         input.value = '';
      }

   }

   function month(input) {


      if(isNaN(input.value)){
         input.value = '';
      }

      if(input.value.length == 1 && (input.value >= 2 || input.value < 0)){
         input.value = ''
      }else if(input.value.length == 2 && (input.value <= 0 || input.value > 12)){
         input.value = ''
      }else if(input.value.length >= 3){
         input.value = ''
      }else if(input.value == ''){
         input.value = ''
      }

   }

   function year(input) {


      if(isNaN(input.value)){
         input.value = '';
      }

      if(input.value.length == 1 && input.value != 2){
         input.value = ''
      }else if(input.value.length == 2 && (input.value < 20 || input.value >=30)){
         input.value = ''
      }else if(input.value.length >= 4 && (input.value < 2020 || input.value >=3000)){
         input.value = ''
      }else if(input.value.length < 4){
         document.getElementById('checkout-button').disabled = true;
      }else{
         document.getElementById('checkout-button').disabled = false;
      }

   }

   </script>

@stop
