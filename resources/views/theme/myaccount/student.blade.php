@extends('theme.layouts.master')
@section('metas')
<title>{{ 'My Account' }}</title>
@endsection
@section('content')
<?php $bonusFiles = ['_Bonus', 'Bonus', 'Bonus Files', 'Βonus', '_Βonus', 'Βonus', 'Βonus Files'] ?>
<?php $currentuser = $user ?>
<main  id="" role="main">
   <section class="section-hero section-hero-small section-hero-blue-bg">
      <div class="container">
         <div class="hero-message">
            <div class="account-infos">
               <div class="account-thumb">
                  @if(isset($user['image']))
                  <?php
                     $img_src = get_profile_image($user['image']);
                     ?>
                  <img id="user-img-up" src="{{cdn($img_src)}}" onerror="this.src='{{cdn('/theme/assets/images/icons/user-profile-placeholder-image.png')}}'" alt="{{ $currentuser['firstname'] }} {{ $currentuser['lastname'] }}"/>
                  @else
                  <img id="user-img-up" src="{{cdn('/theme/assets/images/icons/user-profile-placeholder-image.png')}}" alt="user-profile-placeholder-image"/>
                  @endif
               </div>
               <div class="account-hero-info">
                  <h2>{{ $currentuser['firstname'] }} {{ $currentuser['lastname'] }}</h2>
                  <ul>
                     @if($currentuser['kc_id'] != '')
                     <li>KnowCrunch alumni number: {{ $currentuser['kc_id'] }}</li>
                     @endif
                     @if($currentuser['partner_id'])
                     <li>Deree number: {{ $currentuser['partner_id'] }}</li>
                     @endif
                  </ul>
               </div>
            </div>
         </div>
      </div>
      <!-- /.section-hero -->
   </section>
   <section class="section-account-tabs">
      <div id="favDialog" hidden>
         <div class="alert-wrapper error-alert">
            <div class="alert-inner">
               <p><img src="{{cdn('theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert">Do you really want to remove your profile picture?</p>
               <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
            </div>
            <button class="btn btn-del btn--sm deleteImg"> ok </button>
            <button class="btn btn-del btn--sm cancelImg"> cancel </button>
            <!-- /.alert-outer -->
         </div>
      </div>
      <div id="favDialogCard" hidden>
         <div class="alert-wrapper error-alert">
            <div class="alert-inner">
               <p><img src="{{cdn('theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert">Do you really want to remove your profile picture?</p>
               <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
            </div>
            <button class="btn btn-del btn--sm deleteImg"> ok </button>
            <button class="btn btn-del btn--sm cancelImg"> cancel </button>
            <!-- /.alert-outer -->
         </div>
      </div>
      <div id="favDialog1" hidden>
         <div class="alert-outer" >
            <div class="container">
               <div class="alert-wrapper success-alert">
                  <div class="alert-inner">
                     <p id ="message"></p>
                  </div>
               </div>
            </div>
            <!-- /.alert-outer -->
         </div>
      </div>
      <div id="examDialog" hidden>
         <div class="alert-wrapper success-alert">
            <div class="alert-inner">
               <p>Your examination has now been activated.</p>
               <p>Access from: My Account → My courses → E-learning Masterclass → Exams.</p>
               <p>Good luck!</p>
               <a id="close-exam-dialog" href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
            </div>
            <button class="btn btn-del btn--sm go-to-account">Go to my account </button>
            <!-- /.alert-outer -->
         </div>
      </div>
      <div class="content-wrapper">
      <div class="tabs-wrapper fixed-tab-controls">
         <div class="tab-controls">
            <div class="container">
               <a href="#" class="mobile-tabs-menu">Account</a>
               <ul class="clearfix tab-controls-list">
                  <li><a href="#my-account">My Account</a></li>
                  <li>
                     @if( (isset($events) && count($events) == 0))
                     <a  href="#nocourses" >My courses</a>
                     @else
                     <a id="myCourses"  class="active" href="#courses">My courses</a>
                     @endif
                  </li>
               </ul>
               <!-- /.container -->
            </div>
            <!-- /.tab-controls -->
         </div>
         <div class="tabs-content">
            <div id="my-account" class="tab-content-wrapper">
               <div class="container">
                  <div class="row">
                     <div class="col4 col-sm-12">
                        <div class="account-image-actions"  id="logo_dropzone">
                           <div class="acc-img">
                              @if(isset($user['image']) && $user['image']['name'] != '')
                              <img id="user-img" src="{{cdn($img_src)}}" onerror="this.src='https://via.placeholder.com/150'" alt="{{ $currentuser['firstname'] }} {{ $currentuser['lastname'] }}"/>
                              @else
                              <img id="user-img" src="{{cdn('/theme/assets/images/icons/user-profile-placeholder-image.png')}}" alt="user-profile-placeholder-image"/>
                              @endif
                           </div>
                           <div class="actions">
                              <ul id='user-media'>
                                 <li class="change-photo"><a id="logoDropzone" class="custFieldMediaDrop dz-message" href="javascript:void(0)"><img src="{{cdn('/theme/assets/images/icons/icon-edit.svg')}}" alt="Change photo"/><span>Change photo</span>
                                    </a>
                                 </li>
                                 @if(isset($user['image']))
                                 <li class="remove-photo delete_media"><a data-dp-media-id="{{ $user['image']['id'] }}" href="javascript:void(0)"><img src="{{cdn('/theme/assets/images/icons/icon-remove.svg')}}" alt="Remove photo"/><span>Remove photo</span></a></li>
                                 @endif
                              </ul>
                           </div>
                        </div>
                        <div class="download-area">
                           <div class="download-area-inner">
                              <a id="gdpr-download" href="javascript:void(0)"><span><img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" alt="Remove photo"/>Download all my data.</span></a>
                           </div>
                        </div>
                     </div>
                     <div class="col8 col-sm-12">
                        <div class="account-text">
                           <h2>My account</h2>
                        </div>
                        <div class="inside-tabs">
                           <div class="tabs-ctrl">
                              <ul>
                                 <li class="active"><a href="#personal-data">Personal</a></li>
                                 <li><a href="#billing-data">Billing</a></li>
                                 <li><a href="#password-edit">Password</a></li>
                                 <li><a  href="#subscriptions" >Payment</a></li>
                              </ul>
                           </div>
                           <div class="inside-tabs-wrapper">
                              <div id="personal-data" class="in-tab-wrapper" style="display: block;">
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
                                 @if(Session::has('opmessage'))
                                 @if(Session::get('opstatus'))
                                 <div class="alert-outer">
                                    <div class="container">
                                       <div class="alert-wrapper success-alert">
                                          <div class="alert-inner">
                                             <p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-success-alert.svg')}}" alt="Info Alert">{{ Session::get('opmessage') }}</p>
                                             <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
                                          </div>
                                       </div>
                                    </div>
                                    <!-- /.alert-outer -->
                                 </div>
                                 @else
                                 <div class="alert-outer">
                                    <div class="container">
                                       <div class="alert-wrapper error-alert">
                                          <div class="alert-inner">
                                             <p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-success-alert.svg')}}" alt="Info Alert">{{ Session::get('opmessage') }}</p>
                                             <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
                                          </div>
                                       </div>
                                    </div>
                                    <!-- /.alert-outer -->
                                 </div>
                                 @endif
                                 @endif
                                 <div class="form-wrapper profile-form-wrapper" id="student-view-mode">
                                    {{--
                                    <div class="form-action-upper">
                                       <a href="#" class="edit-fields" href="javascript:void(0)"><img src="{{cdn('/theme/assets/images/icons/icon-edit.svg')}}" alt="Edit Fields"/><span>Edit</span></a>
                                    </div>
                                    --}}
                                    <form id="update-form" method="post" action="{{ route('update.personalInfo') }}" autocomplete="off">
                                       {!! csrf_field() !!}
                                       <div class="col12">
                                          <label>First name: <span>*</span></label>
                                          <div class="input-safe-wrapper">
                                             <input class="required" type="text" name="firstname" id="firstname"  value="{{ old('firstname', $currentuser['firstname']) }}" >
                                          </div>
                                       </div>
                                       <div class="col12">
                                          <label>Last name: <span>*</span></label>
                                          <div class="input-safe-wrapper">
                                             <input class="required"  type="text" name="lastname" id="lastname" value="{{ old('lastname', $currentuser['lastname']) }}" >
                                          </div>
                                       </div>
                                       <div class="col12">
                                          <?php $birthday = date('j F Y',strtotime($currentuser['birthday']))?>
                                          <label>Date of birth:</label>
                                          <div class="input-wrapper">
                                             <input type="text" class="datepicker-jqui with-arrow" name="birthday" value="{{$birthday}}">
                                          </div>
                                       </div>
                                       <div class="col12">
                                          <label>Company:</label>
                                          <div class="input-safe-wrapper">
                                             <input  type="text" name="company" id="company" value="{{ old('company', $currentuser['company']) }}" >
                                          </div>
                                       </div>
                                       <div class="col12">
                                          <label>Position/Title:</label>
                                          <div class="input-safe-wrapper">
                                             <input name="job_title" id="job_title" value="{{ old('job_title', $currentuser['job_title']) }}" >
                                          </div>
                                       </div>
                                       <div class="col12">
                                          <label>Email: <span>*</span></label>
                                          <div class="input-safe-wrapper">
                                             <span id="email-error"></span>
                                             <input class="required" id="email" name="email" type="email" value="{{ old('email', $currentuser['email']) }}" >
                                          </div>
                                       </div>
                                       <div class="col12">
                                          <span id="mobileCheck-error"></span>
                                          <label>Mobile phone: <span>*</span></label>
                                          <div class="input-safe-wrapper is-flex full-width">
                                             <select name="country_code" id="selectCountry" class="select2 form-control mb-3 custom-select country-select">
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
                                             <input class="required" onkeyup="checkPhoneNumber(this)" type="text" name="mobile" id="mobile" value="{{{ old('mobile', $currentuser['mobile']) }}}" >
                                             <input type="hidden" name="mobileCheck" id="mobileCheck" value="{{{ old('mobile', '+'.$currentuser['country_code'].$currentuser['mobile']) }}}">
                                          </div>
                                       </div>
                                       {{--
                                       <div class="checkbox-row">
                                          <div class="custom-checkbox">
                                             <input type="checkbox" id="receive-emails" name="receive-emails" value="accept" checked="checked">
                                             <span></span>
                                          </div>
                                          <label for="receive-emails">I want to receive emails from KnowCrunch.</label>
                                       </div>
                                       <div class="checkbox-row">
                                          <div class="custom-checkbox">
                                             <input type="checkbox" id="receive-messages" name="receive-messages" value="accept" checked="checked">
                                             <span></span>
                                          </div>
                                          <label for="receive-messages">I want to receive important mobile messages from KnowCrunch.</label>
                                       </div>
                                       --}}
                                       <div class="form-submit-area">
                                          <button id="update-personal-info" type="button" class="btn btn--md btn--secondary">Update</button>
                                       </div>
                                    </form>
                                 </div>
                              </div>
                              <div id="subscriptions" class="in-tab-wrapper">
                                 <div id="container" class="container">
                                    <div class="row" id="cardList">
                                       @if(count($defaultPaymetnt) > 0)
                                       <table  style="width:100%">
                                          <tr>
                                             <th>Brand</th>
                                             <th>Default</th>
                                             <th>Last four</th>
                                             <th>Expire Month</th>
                                             <th>Expire Year</th>
                                             <th>Actions</th>
                                          </tr>
                                          @foreach($defaultPaymetnt as $card)
                                          <tr id="defalt-card">
                                             <td>{{$card['brand']}}</td>
                                             <td><i class="far fa-check-circle"></i>Yes</td>
                                             <td>{{$card['last4']}}</td>
                                             <td>{{$card['exp_month']}}</td>
                                             <td>{{$card['exp_year']}}</td>
                                          </tr>
                                          @endforeach
                                          @if(count($cards) > 1)
                                          @foreach($cards as $card)
                                          @if($card['id'] != $defaultPaymetntId)
                                          <tr id="all-cards">
                                             <td>{{$card['card']['brand']}}</td>
                                             <td><i class="far fa-check-circle"></i>No</td>
                                             <td>{{$card['card']['last4']}}</td>
                                             <td>{{$card['card']['exp_month']}}</td>
                                             <td>{{$card['card']['exp_year']}}</td>
                                             <td>
                                                <form action="{{route('payment_method.update')}}" method="post" id="payment-form">
                                                   {{ csrf_field() }}
                                                   <input type="hidden" name="card_id" value="{{$card['id']}}">
                                                   <button class="btn btn--secondary btn--sm">Set default</button>
                                                </form>
                                                <form action="{{route('payment_method.remove')}}" method="post" id="payment-form">
                                                   {{ csrf_field() }}
                                                   <input type="hidden" name="card_id" value="{{$card['id']}}">
                                                   <button id="removebtn" class="btn btn--secondary btn--sm">Remove</button>
                                                </form>
                                             </td>
                                          </tr>
                                          @endif
                                          @endforeach
                                          @endif
                                       </table>
                                       @else
                                       <p>You don't have any credit/debit cards available. </p>
                                       @endif
                                       <div id="addCardBtn" class="col12 text-right">
                                          <button type="button" id="addCard" class="btn btn--secondary btn--sm">Add New Card</button>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div id="billing-data" class="in-tab-wrapper">
                                 <?php
                                    /*'billemail' => 'Email',
                                                'billmobile' => 'Mobile',*/
                                        $hone = [
                                                'billname' => 'First name',
                                                'billsurname' => 'Last name',
                                                'billaddress' => 'Street',
                                                'billaddressnum' => 'Street number',
                                                'billcity' => 'City',
                                                'billpostcode' => 'PostCode',
                                                'billafm' => 'VAT Number'
                                            ];
                                        $inv_data = [];
                                        $htwo = [
                                                'companyname' => 'Company name',
                                                'companyprofession' => 'Profession',
                                                'companyafm' => 'VAT Number',
                                                'companydoy' => 'Tax Area',
                                                'companyaddress' => 'Address',
                                                'companyaddressnum' => 'Street Number',
                                                'companycity' => 'City',
                                                'companypostcode' => 'PostCode',
                                                'companyemail' => 'Company email'
                                            ];
                                        $rec_data = [];


                                    ?>
                                 <div class="form-wrapper profile-form-wrapper">
                                    {{--
                                    <div class="form-action-upper">
                                       <a href="#" class="edit-fields"><img src="/theme/assets/images/icons/icon-edit.svg" alt="Edit Fields"/><span>Edit</span></a>
                                    </div>
                                    --}}
                                    <form >
                                       <div class="custom-radio-wrapper">
                                          <div class="custom-radio-box active">
                                             <div class="crb-wrapper">
                                                <input id="radio-receipt-control" type="radio" data-fieldset-target="receipt-fields" name="billing-receipt-invoice" checked="checked">
                                                <span></span>
                                             </div>
                                             <div class="label-wrapper">
                                                <label for="radio-receipt-control">Receipt</label>
                                             </div>
                                          </div>
                                          <div class="custom-radio-box">
                                             <div class="crb-wrapper">
                                                <input id="radio-invoice-control" type="radio" data-fieldset-target="invoice-fields" name="billing-receipt-invoice">
                                                <span></span>
                                             </div>
                                             <div class="label-wrapper">
                                                <label for="radio-invoice-control">Invoice</label>
                                             </div>
                                          </div>
                                       </div>
                                       <?php //dd($receipt_info); ?>
                                       <div id="receipt_add_edit_mode" class="hidden-fields-actions receipt-fields" style="display: block;">
                                          <?php //dd($user['receipt_details']);?>
                                          @if($user['receipt_details'] != '')
                                          <?php $receipt_info = json_decode($user['receipt_details']);  ?>
                                          @foreach($receipt_info as $k => $v)
                                          @if($k != 'billing' && isset($hone[$k]))
                                          <div class="col12">
                                             <label>{{$hone[$k]}}:</label>
                                             <div class="input-safe-wrapper">
                                                <input  type="text" id="{{$k}}" name="{{$k}}" value="{{ $v }}" >
                                             </div>
                                          </div>
                                          @endif
                                          @endforeach
                                          <div class="form-submit-area">
                                             <button id="save-receipt-data" type="button" class="btn btn--md btn--secondary">Update</button>
                                          </div>
                                          @endif
                                       </div>
                                       <div id="invoice_add_edit_mode" class="hidden-fields-actions invoice-fields">
                                          @if($user['invoice_details'] != '')
                                          <?php $invoice_info = json_decode($user['invoice_details']);  ?>
                                          @foreach($invoice_info as $k => $v)
                                          @if($k != 'billing' && isset($htwo[$k]))
                                          <div class="col12">
                                             <label>{{$htwo[$k]}}:</label>
                                             <div class="input-safe-wrapper">
                                                <input  type="text" id="{{$k}}" name="{{$k}}" value="{{ $v }}" >
                                             </div>
                                          </div>
                                          @endif
                                          @endforeach
                                          <div class="form-submit-area">
                                             <button id="save-invoice-data" type="button" class="btn btn--md btn--secondary">Update</button>
                                          </div>
                                          @endif
                                       </div>
                                    </form>
                                    <?php //dd(); ?>
                                    <!-- /.form-wrapper.profile-form-wrapper -->
                                 </div>
                                 <!-- /#billing-data.in-tab-wrapper -->
                              </div>
                              <div id="password-edit" class="in-tab-wrapper">
                                 <div class="account-text">
                                    {{--
                                    <h2>Change password</h2>
                                    --}}
                                    <div class="form-wrapper profile-form-wrapper">
                                       <form method="post" action="{{ route('update.personalInfo') }}" autocomplete="off" class="password-form">
                                          {!! csrf_field() !!}
                                          <div class="row">
                                             <div class="col12 col-sm-12">
                                                <label>New password:{{--<br/><small>(Min. 8 letters, min. 1 capital letter & 1 number)</small>--}}</label>
                                                <input type="password" autocomplete="new-password" name="password" id="password" required />
                                             </div>
                                             <div class="col12 col-sm-12">
                                                <label>Confirm new password:</label>
                                                <input type="password" autocomplete="new-password" name="password_confirm" type="password"
                                                   data-bv-identical="true"
                                                   data-bv-identical-field="password"
                                                   data-bv-identical-message="The password and its confirmation are not the same!" required />
                                             </div>
                                             <input type="hidden" name='email' value="{{$currentuser['email']}}">
                                          </div>
                                          <div class="form-submit-area">
                                             <button type="submit" class="btn btn--md btn--secondary">Change password</button>
                                          </div>
                                       </form>
                                       <!-- /.form-wrapper -->
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- /.container -->
               </div>
               <!-- /#my-account.tab-content-wrapper -->
            </div>
            @if((isset($events) && count($events) == 0) )
            <div id="nocourses" class="tab-content-wrapper active-tab">
               <div class="container">
                  You have no courses
               </div>
            </div>
            @else
            <div id="courses" class="tab-content-wrapper active-tab">
               @if(\Session('stripe-error'))
               <div class="alert-outer">
                  <div class="container">
                     <div class="alert-wrapper error-alert">
                        <div class="alert-inner">
                           <p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert">{{\Session('stripe-error')}}.</p>
                           <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
                        </div>
                     </div>
                  </div>
                  <!-- /.alert-outer -->
               </div>
               @endif
               <div class="container">
                  <div class="row">
                     <?php $tab = 0; ?>
                     <?php //dd($events[0]); ?>
                     @if(isset($events) && count($events) > 0)
                     @foreach($events as $keyType => $event)
                     @if($event['view_tpl'] != 'elearning_free' && $event['view_tpl'] != 'elearning_event')
                     <div class="col12 dynamic-courses-wrapper dynamic-courses-wrapper--style2">
                        <div class="item">
                           <h2>{{ $event['title'] }}</h2>
                           <div class="inside-tabs">
                              <div class="tabs-ctrl">
                                 <ul>
                                    <li class="active"><a href="#c-info-inner{{$tab}}">Info</a></li>
                                    <li><a href="#c-shedule-inner{{$tab}}">Schedule </a></li>
                                    <?php  $fa = strtotime(date('Y-m-d',strtotime($event['release_date_files']))) >= strtotime(date('Y-m-d'))?>
                                    @if(!$instructor && isset($event['category'][0]['dropbox']) && count($event['category'][0]['dropbox']) != 0 &&
                                    $event['status'] == 3 &&  $fa)
                                    <li><a href="#c-files-inner{{$tab}}">Files</a></li>
                                    @endif
                                    @if(isset($event['exams']) && count($event['exams']) >0 )
                                    <li><a href="#c-exams-inner{{$tab}}">Exams</a></li>
                                    @endif
                                    {{--
                                    <li><a href="#c-subs-inner{{$tab}}">Subscription</a></li>
                                    --}}
                                 </ul>
                              </div>
                              <div class="inside-tabs-wrapper">
                                 <div id="c-info-inner{{$tab}}" class="in-tab-wrapper" style="display: block;">
                                    <div class="bottom">
                                       <?php
                                          $summaryDate = '';
                                          foreach($event['summary1'] as $summary){
                                             if($summary['section'] == 'date'){
                                                $summaryDate = $summary['title'];
                                             }
                                          }
                                          ?>
                                       <div class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/icon-calendar.svg')}}" alt="">{{$summaryDate}}</div>
                                       @if($event['hours'])
                                       <div class="expire-date"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt="">{{$event['hours']}}</div>
                                       @endif
                                    </div>
                                 </div>
                                 <div id="c-shedule-inner{{$tab}}" class="in-tab-wrapper">
                                    <div class="bottom tabs-bottom">
                                       <div class="expire-date exp-date"><img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" alt="">Schedule available in PDF</div>
                                       <div class="right">
                                          <a target="_blank" href="/print/syllabus/{{$event['slugable']['slug']}}" class="btn btn--secondary btn--md"> DOWNLOAD SCHEDULE </a>
                                       </div>
                                    </div>
                                    <div class="acc-topic-accordion">
                                       <div class="accordion-wrapper accordion-big">
                                          <?php $catId = -1?>
                                          <?php //dd($event['topics']); ?>
                                          @if(isset($event['topics']) && count($event['topics']) > 0)
                                          @foreach($event['topics'] as $keyTopic => $topic)
                                          <?php //dd($keyTopic); ?>
                                          @if(isset($topic) && count($topic) != 0 )
                                          <div class="accordion-item">
                                             <h3 class="accordion-title title-blue-gradient scroll-to-top">{{$keyTopic}}</h3>
                                             <div class="accordion-content no-padding">
                                                <?php //dd($topic[0]['lessons']); ?>
                                                @foreach($topic['lessons'] as $keyLesso => $lesso)
                                                <div class="topic-wrapper-big">
                                                   <div class="topic-title-meta">
                                                      <h4>{{$lesso['title']}}</h4>
                                                      <!-- Feedback 18-11 changed -->
                                                      <div class="topic-meta">
                                                         @if(count($lesso['type']) >0)
                                                         <div class="category">{{$lesso['type'][0]['name']}}</div>
                                                         @endif
                                                         <!-- Feedback 18-11 changed -->
                                                         <span class="meta-item duration"><img src="{{cdn('/theme/assets/images/icons/icon-calendar.svg')}}" alt="" /><?= date( "l d M Y", strtotime($lesso['pivot']['time_starts']) ) ?></span> <!-- Feedback 18-11 changed -->
                                                         <span class="meta-item duration"><img src="{{cdn('/theme/assets/images/icons/Times.svg')}}" alt="" /><?= date( "H:i", strtotime($lesso['pivot']['time_starts']) ) ?> ({{$lesso['pivot']['duration']}})</span> <!-- Feedback 18-11 changed -->
                                                         <span class="meta-item duration"><img src="{{cdn('/theme/assets/images/icons/icon-marker.svg')}}" alt="" />{{$lesso['pivot']['room']}}</span> <!-- Feedback 18-11 changed -->
                                                      </div>
                                                      <!-- /.topic-title-meta -->
                                                   </div>
                                                   <div class="author-img">
                                                      <!-- Feedback 18-11 changed -->
                                                      <a href="{{$instructors[$lesso['instructor_id']][0]['slugable']['slug']}}">
                                                      <span class="custom-tooltip"><?= $instructors[$lesso['instructor_id']][0]['title'].' '.$instructors[$lesso['instructor_id']][0]['subtitle']; ?></span>
                                                      <img src="{{cdn( get_image($instructors[$lesso['instructor_id']][0]['mediable'],'instructors-small') )}}" alt="<?= $instructors[$lesso['instructor_id']][0]['title']; $instructors[$lesso['instructor_id']][0]['subtitle']; ?>"/>
                                                      </a>
                                                   </div>
                                                   <!-- /.topic-wrapper-big -->
                                                </div>
                                                {{--@if($lesso['type'])
                                                @endif--}}
                                                @endforeach
                                                <!-- /.accordion-content -->
                                             </div>
                                             <!-- /.accordion-item -->
                                          </div>
                                          @endif
                                          @endforeach
                                          @endif
                                          <!-- /.accordion-wrapper -->
                                       </div>
                                       <!-- /.acc-topic-accordion -->
                                    </div>
                                 </div>
                                 <?php
                                    $dropbox = isset($event['category'][0]['dropbox'][0]) ? $event['category'][0]['dropbox'][0] : [];
                                    //dd($dropbox);
                                    $folders = isset($dropbox['folders'][0]) ? $dropbox['folders'][0] : [];
                                    //dd($folders);

                                    $folders_bonus = isset($dropbox['folders'][1]) ? $dropbox['folders'][1] : [];
                                    //dd($folders_bonus);
                                    $files = isset($dropbox['files'][1]) ? $dropbox['files'][1] : [];
                                    $files_bonus = isset($dropbox['files'][2]) ? $dropbox['files'][2] : [];

                                    //dd($files);

                                    ?>
                                 <?php
                                    $now1 = strtotime(date("Y-m-d"));
                                    $display = false;
                                    if(!$event['release_date_files'] && $event['status'] == 3){
                                        $display = true;

                                    }else if(strtotime(date('Y-m-d',strtotime($event['release_date_files']))) >= $now1 && $event['status'] == 3){

                                        $display = true;
                                    }

                                    ?>
                                 @if(isset($dropbox) && $folders != null)
                                 <div id="c-files-inner{{$tab}}" class="in-tab-wrapper">
                                    @if($display)
                                    <div class="acc-topic-accordion">
                                       <div class="accordion-wrapper accordion-big">
                                          @if(isset($folders) && count($folders) > 0)
                                          @foreach($folders as $folder)
                                          <?php
                                             $checkedF = [];
                                             $fs = [];
                                             $fk = 1;
                                             $bonus = [];
                                             $subfolder = [];
                                             $subfiles = [];
                                             ?>
                                          <div class="accordion-item">
                                             <h3 class="accordion-title title-blue-gradient scroll-to-top"> {{ $folder['foldername'] }}</h3>
                                             <div class="accordion-content no-padding">
                                                @if(isset($files) && count($files) > 0)
                                                   @foreach($folders_bonus as $folder_bonus)
                                                      @if($folder_bonus['parent'] == $folder['id']  && !in_array($folder_bonus['foldername'],$bonusFiles))
                                                      <?php
                                                         $checkedF[] = $folder_bonus['id'] + 1 ;
                                                         $fs[$folder_bonus['id']+1]=[];
                                                         $fs[$folder_bonus['id']+1] = $folder_bonus;

                                                         ?>
                                                      @endif
                                                   @endforeach
                                                   @if(count($fs) > 0)
                                                   
                                                      @foreach($fs as $subf)
                                                         @foreach($files_bonus as $folder_bonus)
                                                         <?php
                                                            if(in_array($subf['foldername'],$subfolder)){
                                                               continue;
                                                            }
                                                            ?>
                                                            @if($folder_bonus['parent'] == $folder['id'])
                                                            <?php $subfolder[] =  $subf['foldername']; ?>
                                                            <div class="files-wrapper bonus-files">
                                                               <h4 class="bonus-title">{{ $subf['foldername'] }}</h4>
                                                               <span><i class="icon-folder-open"></i>   </span>
                                                                  @foreach($files_bonus as $file_bonus)
                                                                     @if($file_bonus['fid'] == $subf['id'] && $file_bonus['parent'] == $subf['parent'] )
                                                                     <?php $subfiles[]= $file_bonus['filename'] ?>
                                                                     <div class="file-wrapper">
                                                                        <h4 class="file-title">{{ $file_bonus['filename'] }}</h4>
                                                                        <span class="last-modified">Last modified:  {{$file_bonus['last_mod']}}</span>
                                                                        <a  class="download-file getdropboxlink"  data-dirname="{{ $file_bonus['dirname'] }}" data-filename="{{ $file_bonus['filename'] }}" href="javascript:void(0)" >
                                                                        <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                                                     </div>
                                                                     @endif
                                                                  @endforeach
                                                            </div>
                                                            @endif
                                                         @endforeach
                                                      @endforeach
                                                   @endif
                                                   @foreach($files as $file)
                                                      @if($folder['id'] == $file['fid'])
                                                      <div class="files-wrapper">
                                                         <div class="file-wrapper">
                                                            <h4 class="file-title">{{ $file['filename'] }}</h4>
                                                            <span class="last-modified">Last modified:  {{ $file['last_mod'] }}</span>
                                                            <a  class="download-file getdropboxlink"  data-dirname="{{ $file['dirname'] }}" data-filename="{{ $file['filename'] }}" href="javascript:void(0)" >
                                                            <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                                         </div>
                                                      </div>
                                                      @endif
                                                   @endforeach
                                                @endif
                                                @if(isset($folders_bonus) && count($folders_bonus) > 0)
                                                <div class="files-wrapper bonus-files">
                                                   @foreach($folders_bonus as $folder_bonus)
                                                   <?php
                                                      if(in_array($folder_bonus['foldername'],$subfolder)){
                                                         continue;
                                                      }
                                                      ?>
                                                   @if($folder_bonus['parent'] == $folder['id'])
                                                   <h4 class="bonus-title">{{ $folder_bonus['foldername'] }}</h4>
                                                   <span><i class="icon-folder-open"></i>   </span>
                                                   @if(isset($files_bonus) && count($files_bonus) > 0)
                                                   @foreach($files_bonus as $file_bonus)
                                                   @if($file_bonus['parent'] == $folder_bonus['parent'] && !in_array($file_bonus['filename'],$subfiles))
                                                   <div class="file-wrapper">
                                                      <h4 class="file-title">{{ $file_bonus['filename'] }}</h4>
                                                      <span class="last-modified">Last modified:  {{$file_bonus['last_mod']}}</span>
                                                      <a  class="download-file getdropboxlink"  data-dirname="{{ $file_bonus['dirname'] }}" data-filename="{{ $file_bonus['filename'] }}" href="javascript:void(0)" >
                                                      <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                                   </div>
                                                   @endif
                                                   @endforeach
                                                   @endif
                                                   @endif
                                                   @endforeach
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          @endforeach
                                          @endif
                                       </div>
                                    </div>
                                    @endif
                                 </div>
                                 @endif
                                 @if(isset($event['exams']) && count($event['exams']) >0 )
                                 <?php $nowTime = \Carbon\Carbon::now(); ?>
                                 <div id="c-exams-inner{{$tab}}" class="in-tab-wrapper">
                                    <div class="dynamic-courses-wrapper dynamic-courses-wrapper--style2">
                                       <div class="bottom">
                                          <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Customer_Access.svg')}}" alt="">Exams will activate in the end of your course.</div>
                                          @foreach($event['exams'] as $p)
                                          <div class="right">
                                             <!-- Feedback 8-12 changed -->
                                             @if(($userExam = $user->hasExamResults($p->id)) && $nowTime->diffInHours($userExam->end_time) < 48)
                                             <a target="_blank" href="{{ route('exam-results', [$p->id,'s'=>1]) }}" title="{{$p['title']}}" class="btn btn--secondary btn--md">VIEW RESULT</a>
                                             @elseif($user->hasExamResults($p->id) )
                                             <a target="_blank" href="{{ url('exam-results/' . $p->id) }}?s=1" title="{{$p['title']}}" class="btn btn--secondary btn--md btn--completed">VIEW RESULT</a>
                                             @elseif($p->islive == 1)
                                             <a target="_blank" onclick="window.open('{{ route('attempt-exam', [$p->id]) }}', 'newwindow', 'width=1400,height=650'); return false;" title="{{$p['title']}}" class="btn btn--secondary btn--md">TAKE EXAM</a>
                                             @elseif($p->isupcom == 1)
                                             <a  title="{{$p['title'] }}" class="btn btn--secondary btn--md">{{ date('F j, Y', strtotime($p->publish_time)) }}</a>
                                             @endif
                                          </div>
                                          <!-- ./item -->
                                          @endforeach
                                       </div>
                                    </div>
                                    <!-- ./dynamic-courses-wrapper -->
                                 </div>
                                 @endif
                              </div>
                           </div>
                        </div>
                     </div>
                     @else
                     <div class="col12 dynamic-courses-wrapper">
                        <div class="item">
                           <?php //dd($event['title']); ?>
                           <h2>{{ $event['title'] }}</h2>
                           <div class="inside-tabs">
                              <div class="tabs-ctrl">
                                 <ul>
                                    <li class="active"><a href="#c-info-inner{{$tab}}">Info</a></li>
                                    <li><a href="#c-watch-inner{{$tab}}">Watch</a></li>
                                    @if($event['view_tpl'] != 'elearning_free')
                                    <?php //']); ?>
                                    @if(isset($event['exams']) && count($event['exams']) >0 )
                                    <li><a href="#c-exams-inner{{$tab}}">Exams</a></li>
                                    @endif
                                    @if(count($event['certs']) > 0)
                                    <li><a href="#c-cert-inner{{$tab}}">Certificate</a></li>
                                    @endif
                                    @endif
                                    @if($subscriptionAccess && count($event['plans']) > 0)
                                    <li><a href="#c-subs-inner{{$tab}}">Subscription</a></li>
                                    @endif
                                 </ul>
                              </div>
                              <div class="inside-tabs-wrapper">
                                 <div id="c-info-inner{{$tab}}" class="in-tab-wrapper" style="display: block;">
                                    <div class="bottom">
                                       <?php //dd($event['videos_progress']); ?>
                                       @if($event['expiration'])
                                       <div class="expire-date exp-date"><img src="{{cdn('/theme/assets/images/icons/Days-Week.svg')}}" alt="">Expiration date: {{$event['expiration']}}</div>
                                       @endif
                                       @if (isset($event['hours']))
                                       <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt=""> {{$event['hours']}}h </div>
                                       @endif
                                       @if (isset($event['videos_progress']))
                                       <?php //dd($event); ?>
                                       <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/E-Learning.svg')}}" alt=""> {{$event['videos_progress']}}% </div>
                                       @endif
                                    </div>
                                 </div>
                                 @if($subscriptionAccess)
                                 <div id="c-subs-inner{{$tab}}" class="in-tab-wrapper">
                                    <div class="bottom">
                                       @if($event['mySubscription'])
                                       <div class="left">
                                          <div class="bottom">
                                             @if($event['mySubscription']['trial_ends_at'])
                                             <?php
                                                $date_timestamp = strtotime($event['mySubscription']['trial_ends_at']);
                                                $now_date = strtotime(date('d/m/Y'));
                                                $date = date('d-m-Y',$date_timestamp);
                                                ?>
                                             @if($date_timestamp > $now_date )
                                             <?php //dd('not expired'); ?>
                                             <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/clock-coins.svg')}}" alt=""><?php echo 'Your trial expiration: '.$date; ?></div>
                                             @if($event['mySubscription']['status'])
                                             <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt=""><?php echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
                                             @endif
                                             @else
                                             @if($event['mySubscription'])
                                             <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt=""><?php echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
                                             @endif
                                             @endif
                                             @else
                                             <?php
                                                $date_timestamp = strtotime($event['mySubscription']['trial_ends_at']);
                                                $now_date = strtotime(date('d/m/Y'));
                                                $date = date('d-m-Y',$date_timestamp);
                                                ?>
                                             @if($date_timestamp > $now_date )
                                             @if($event['mySubscription']['status'])
                                             <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt=""><?php echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
                                             @endif
                                             @else
                                             @if($event['mySubscription'])
                                             <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt=""><?php echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
                                             @endif
                                             @endif
                                             @endif
                                             @if(isset($event['mySubscription']))
                                             <div class="status_wrapper">
                                                <div class="status_label"><label> Status:  </label></div>
                                                <?php
                                                   //dd($event['mySubscription']['active']);
                                                      $a = '';
                                                      $status = '';
                                                      $row_status = '';
                                                      if($event['mySubscription']['status']){
                                                         $a = 'checked';
                                                         $status = 'Active';
                                                         //row_status = ` style="color:green;" `;

                                                      }else{
                                                         $status = 'Cancel';
                                                         //row_status = ` style="color:red;" `;
                                                      }
                                                   ?>
                                                <div class="status_switch">
                                                   <div class="onoffswitch" data-status="{{$status}}" data-id="{{$event['mySubscription']['id']}}" id="onoffswitch">
                                                      <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" tabindex="0" <?php echo $a; ?>>
                                                      <label class="onoffswitch-label" for="myonoffswitch">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                   </div>
                                                </div>
                                             </div>
                                             @endif
                                          </div>
                                       </div>
                                       @endif
                                       <?php //dd($event['mySubscription']); ?>
                                       @if(!$event['mySubscription'])
                                       <div class="left">
                                          <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/checklist-graduate-hat.svg')}}" alt="">Get annual access to updated videos & files. 15 days free trial.</div>
                                       </div>
                                       @endif
                                       <div class="right">
                                          <?php //dd($event) ?>
                                          @if(!$event['mySubscription'])
                                          @foreach($event['plans'] as $key => $plan)
                                          <a href="/myaccount/subscription/{{$event['title']}}/{{ $plan->name }}" class="btn btn--secondary btn--md">SUBSCRIBE NOW</a>
                                          @endforeach
                                          @endif
                                       </div>
                                    </div>
                                 </div>
                                 @endif
                                 <?php //dd($event); ?>
                                 <div id="c-watch-inner{{$tab}}" class="in-tab-wrapper">
                                    <div class="bottom">
                                       @if (isset($event['videos_progress']))
                                       <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/E-Learning.svg')}}" alt=""> {{$event['videos_progress']}}% </div>
                                       @endif
                                       @if (isset($event['videos_seen']))
                                       <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/Recap-Events.svg')}}" alt=""> {{str_replace('/','of',$event['videos_seen'])}} </div>
                                       @endif
                                       <div class="right">
                                          <?php $expire = false; ?>
                                          @foreach($mySubscriptions as $key => $sub)
                                          @foreach($plans as $key1 => $plan)
                                          <?php //dd($plan['stipe_plan']); ?>
                                          @if($sub['stripe_plan'] == $plan['stripe_plan'])
                                          @if($event['id'] == $plan['event_id'])
                                          <?php
                                             if(date("Y-m-d h:i:s") < $sub['must_be_updated']){
                                                $expire = false;
                                             }else{
                                                $expire = true;
                                             }
                                             ?>
                                          @endif
                                          @endif
                                          @endforeach
                                          @endforeach
                                          @if(!$event['video_access'])
                                          {{--<a style="cursor:not-allowed; opacity: 0.5; pointer-events: none;" href="/myaccount/elearning/{{ $event['title'] }}" class="btn btn--secondary btn--md">@if((isset($event['videos_progress']) && $event['videos_progress'] == 100) || count($event['cert'])>0) WATCH AGAIN @else WATCH NOW @endif</a>--}}
                                          @else
                                          <a href="/myaccount/elearning/{{ $event['title'] }}" class="btn btn--secondary btn--md">@if((isset($event['videos_progress']) && $event['videos_progress'] == 100) ) WATCH AGAIN @else WATCH NOW @endif</a>
                                          @endif
                                       </div>
                                    </div>
                                 </div>
                                 @if($event['view_tpl'] != 'elearning_free' )
                                 @if(isset($event['exams']))
                                 <?php $nowTime = \Carbon\Carbon::now(); ?>
                                 <div id="c-exams-inner{{$tab}}" class="in-tab-wrapper">
                                    <div class="bottom">
                                       @foreach($event['exams'] as $p)
                                       <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Customer_Access.svg')}}" alt="">Exams activate automatically when you watch 80% </div>
                                       <div class="right">
                                          <!-- Feedback 8-12 changed -->
                                          @if($event['exam_access'] && !$user->hasExamResults($p->id))
                                          @if($p->islive == 1)
                                          <a target="_blank" onclick="window.open('{{ route('attempt-exam', [$p->id]) }}', 'newwindow', 'width=1400,height=650'); return false;" title="{{$p['exam_name']}}" class="btn btn--secondary btn--md">TAKE EXAM</a>
                                          @endif
                                          @elseif($userExam = $user->hasExamResults($p->id))
                                          @if($nowTime->diffInHours($userExam->end_time) < 48)
                                          <a target="_blank" href="{{ url('exam-results/' . $p->id) }}?s=1" title="{{$p['exam_name']}}" class="btn btn--secondary btn--md">VIEW RESULT</a>
                                          @else
                                          <a target="_blank" href="{{ url('exam-results/' . $p->id) }}?s=1" title="{{$p['exam_name']}}" class="btn btn--secondary btn--md btn--completed">VIEW RESULT</a>
                                          @endif
                                          @else
                                          <div class="right">
                                             <a href="javascript:void(0)" title="{{$p['exam_name']}}" class="btn btn--secondary btn--md btn--completed">TAKE EXAM</a>
                                          </div>
                                          @endif
                                       </div>
                                       @endforeach
                                    </div>
                                 </div>
                                 @endif
                                 @if(count($event['certs']) > 0)
                                 <div id="c-cert-inner{{$tab}}" class="in-tab-wrapper">
                                    <div class="bottom">
                                       <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" alt="">@if(isset($newlayoutExamsEvent[$keyType]) && count($newlayoutExamsEvent[$keyType])>0)Certificate download after completing your exams. @else Your certification is ready @endif</div>
                                       @foreach($event['certs'] as $certificate)
                                       <div class="right">
                                          <a  class="btn btn--secondary btn--md" target="_blank" href="/myaccount/mycertificate/{{$certificate->id}}" >DOWNLOAD </a>
                                       </div>
                                       @endforeach
                                    </div>
                                 </div>
                                 @endif
                                 @endif
                              </div>
                           </div>
                           <!-- ./item -->
                        </div>
                     </div>
                     @endif
                     <?php $tab += 1; ?>
                     @endforeach
                     @endif
                     @if(isset($mySubscriptionEvents) && count($mySubscriptionEvents) > 0)
                     <!-- subs -->
                     @foreach($mySubscriptionEvents as $keyType => $event)
                     @if($event['view_tpl'] != 'elearning_event' && $event['view_tpl'] != 'elearning_free')
                     @else
                     <div class="col12 dynamic-courses-wrapper">
                        <div class="item">
                           <h2>{{ $event['title'] }}</h2>
                           <div class="inside-tabs">
                              <div class="tabs-ctrl">
                                 <ul>
                                    <li class="active"><a href="#c-info-inner{{$tab}}">Info</a></li>
                                    <li><a href="#c-watch-inner{{$tab}}">Watch</a></li>
                                    <li><a href="#c-subs-inner{{$tab}}">Subscription</a></li>
                                 </ul>
                              </div>
                              <div class="inside-tabs-wrapper">
                                 <div id="c-info-inner{{$tab}}" class="in-tab-wrapper" style="display: block;">
                                    <div class="bottom">
                                       @if (isset($event['hours']) && $event['hours'])
                                       <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt=""> {{$event['hours']}}h </div>
                                       @endif
                                       @if (isset($event['videos_progress']))
                                       <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/E-Learning.svg')}}" alt=""> {{$event['videos_progress']}}% </div>
                                       @endif
                                    </div>
                                 </div>
                                 <div id="c-watch-inner{{$tab}}" class="in-tab-wrapper">
                                    <div class="bottom">
                                       @if (isset($event['videos_progress']))
                                       <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/E-Learning.svg')}}" alt=""> {{$event['videos_progress']}}% </div>
                                       @endif
                                       @if (isset($event['videos_seen']))
                                       <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/Recap-Events.svg')}}" alt=""> {{str_replace('/','of',$event['videos_seen'])}} </div>
                                       @endif
                                       <div class="right">
                                          <?php $expire = false; ?>
                                          @foreach($mySubscriptions as $key => $sub)
                                          @foreach($plans as $key1 => $plan)
                                          @if($sub['stripe_plan'] == $plan['stripe_plan'])
                                          @if($event['id'] == $plan['event_id'])
                                          <?php
                                             if(date("Y-m-d h:i:s") < $sub['must_be_updated']){
                                                $expire = false;
                                             }else{
                                                $expire = true;
                                             }
                                             ?>
                                          @endif
                                          @endif
                                          @endforeach
                                          @endforeach
                                          @if(!$event['video_access'])
                                          {{--<a style="cursor:not-allowed; opacity: 0.5; pointer-events: none;" href="/myaccount/elearning/{{ $event['title'] }}" class="btn btn--secondary btn--md">@if((isset($event['videos_progress']) && $event['videos_progress'] == 100) || count($event['cert'])>0) WATCH AGAIN @else WATCH NOW @endif</a>--}}
                                          @else
                                          <a href="/myaccount/elearning/{{ $event['title'] }}" class="btn btn--secondary btn--md">@if((isset($event['videos_progress']) && $event['videos_progress'] == 100))>0) WATCH AGAIN @else WATCH NOW @endif</a>
                                          @endif
                                       </div>
                                    </div>
                                 </div>
                                 @if($subscriptionAccess)
                                 <div id="c-subs-inner{{$tab}}" class="in-tab-wrapper">
                                    <div class="bottom">
                                       <div class="left">
                                          @if($event['mySubscription'])
                                          <div class="bottom">
                                             @if($event['mySubscription']['trial_ends_at'])
                                             <?php
                                                $date_timestamp = strtotime($event['mySubscription']['trial_ends_at']);
                                                $now_date = strtotime(date('d/m/Y'));
                                                $date = date('d-m-Y',$date_timestamp);

                                                ?>
                                             @if($date_timestamp > $now_date )
                                             <?php //dd('not expired'); ?>
                                             <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/clock-coins.svg')}}" alt=""><?php echo 'Your trial expiration: '.$date; ?></div>
                                             @if($event['mySubscription']['status'])
                                             <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt=""><?php  echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
                                             @endif
                                             @else
                                             @if($event['mySubscription'])
                                             <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt=""><?php echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
                                             @endif
                                             @endif
                                             @else
                                             <?php
                                                $date_timestamp = strtotime($event['mySubscription']['trial_ends_at']);
                                                $now_date = strtotime(date('d/m/Y'));
                                                $date = date('d-m-Y',$date_timestamp);
                                                ?>
                                             @if($date_timestamp > $now_date )
                                             @if($event['mySubscription']['status'])
                                             <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt=""><?php echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
                                             @endif
                                             @else
                                             @if($event['mySubscription'])
                                             <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt=""><?php echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
                                             @endif
                                             @endif
                                             @endif
                                             @if(isset($event['mySubscription']))
                                             <div class="status_wrapper">
                                                <div class="status_label"><label> Status:  </label></div>
                                                <?php
                                                   //dd($event['mySubscription']['active']);
                                                      $a = '';
                                                      $status = '';
                                                      $row_status = '';
                                                      if($event['mySubscription']['status']){
                                                         $a = 'checked';
                                                         $status = 'Active';
                                                         //row_status = ` style="color:green;" `;

                                                      }else{
                                                         $status = 'Cancel';
                                                         //row_status = ` style="color:red;" `;
                                                      }
                                                   ?>
                                                <div class="status_switch">
                                                   <div class="onoffswitch" data-status="{{$status}}" data-id="{{$event['mySubscription']['id']}}" id="onoffswitch">
                                                      <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" tabindex="0" <?php echo $a; ?>>
                                                      <label class="onoffswitch-label" for="myonoffswitch">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                   </div>
                                                </div>
                                             </div>
                                             @endif
                                          </div>
                                          @endif
                                       </div>
                                       @if(!$event['mySubscription'])
                                       <div class="left">
                                          <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/checklist-graduate-hat.svg')}}" alt="">Get annual access to updated videos & files. 15 days free trial.</div>
                                       </div>
                                       @endif
                                       <div class="right">
                                          @if(!$event['mySubscription'])
                                          @foreach($event['plans'] as $key => $plan)
                                          <a href="/myaccount/subscription/{{$event['title']}}/{{ $plan->name }}" class="btn btn--secondary btn--md">SUBSCRIBE NOW</a>
                                          @endforeach
                                          @endif
                                       </div>
                                    </div>
                                    @endif
                                 </div>
                              </div>
                              <!-- ./item -->
                           </div>
                        </div>
                        @endif
                        <?php $tab += 1; ?>
                        @endforeach
                        @endif
                        @if(isset($subscriptionEvents) && count($subscriptionEvents) > 0 )
                        <!-- subs -->
                        @foreach($subscriptionEvents as $keyType => $event)
                        @if($event['view_tpl'] != 'elearning_event' && $event['view_tpl'] != 'elearning_free')
                        <div class="col12 dynamic-courses-wrapper dynamic-courses-wrapper--style2">
                           <div class="item">
                              <h2>{{ $event['title'] }}</h2>
                              <div class="inside-tabs">
                                 <div class="tabs-ctrl">
                                    <ul>
                                       <li class="active"><a href="#c-info-inner{{$tab}}">Info</a></li>
                                       <li><a href="#c-shedule-inner{{$tab}}">Schedule </a></li>
                                       @if(isset($showFiles[$keyType]) && $showFiles[$keyType])
                                       <li><a href="#c-files-inner{{$tab}}">Files</a></li>
                                       @endif
                                       {{--
                                       <li><a href="#c-subs-inner{{$tab}}">Subscription</a></li>
                                       --}}
                                    </ul>
                                 </div>
                                 <div class="inside-tabs-wrapper">
                                    <div id="c-info-inner{{$tab}}" class="in-tab-wrapper" style="display: block;">
                                       <div class="bottom">
                                          <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/marker.svg')}}" alt=""><a href="{{$event['city']['slug']}}">{{$event['city']['name']}}</a></div>
                                          <div class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/icon-calendar.svg')}}" alt="">{{$event['date']}}</div>
                                          @if($event['hours'])
                                          <div class="expire-date"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt="">{{$event['hours']}}</div>
                                          @endif
                                       </div>
                                    </div>
                                    <div id="c-shedule-inner{{$tab}}" class="in-tab-wrapper">
                                       <div class="bottom tabs-bottom">
                                          <div class="expire-date exp-date"><img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" alt="">Schedule available in PDF</div>
                                          <div class="right">
                                             <a target="_blank" href="/print/syllabus/{{$event['slug']}}" class="btn btn--secondary btn--md"> DOWNLOAD SCHEDULE </a>
                                          </div>
                                       </div>
                                       <div class="acc-topic-accordion">
                                          <div class="accordion-wrapper accordion-big">
                                             <?php $catId = -1?>
                                             @foreach($event['topics'] as $keyTopic => $topic)
                                             <div class="accordion-item">
                                                <h3 class="accordion-title title-blue-gradient scroll-to-top">{{$keyTopic}}</h3>
                                                <div class="accordion-content no-padding">
                                                   @foreach($topic as $keyLesso => $lesso)
                                                   @foreach($lesso as $keyLesson => $lesson)
                                                   <?php $catId = $lesson['cat_id'] ?>
                                                   @if($lesson['type'])
                                                   <div class="topic-wrapper-big">
                                                      <div class="topic-title-meta">
                                                         <h4>{{$keyLesson}}</h4>
                                                         <!-- Feedback 18-11 changed -->
                                                         <div class="topic-meta">
                                                            @if($lesson['type'])
                                                            <div class="category">{{$lesson['type']}}</div>
                                                            @endif
                                                            <!-- Feedback 18-11 changed -->
                                                            <span class="meta-item duration"><img src="{{cdn('/theme/assets/images/icons/icon-calendar.svg')}}" alt="" />{{$lesson['eldate']}}</span> <!-- Feedback 18-11 changed -->
                                                            <span class="meta-item duration"><img src="{{cdn('/theme/assets/images/icons/Times.svg')}}" alt="" />{{$lesson['eltime']}} ({{$lesson['in_class_duration']}})</span> <!-- Feedback 18-11 changed -->
                                                            <span class="meta-item duration"><img src="{{cdn('/theme/assets/images/icons/icon-marker.svg')}}" alt="" />{{$lesson['room']}}</span> <!-- Feedback 18-11 changed -->
                                                         </div>
                                                         <!-- /.topic-title-meta -->
                                                      </div>
                                                      <div class="author-img">
                                                         <!-- Feedback 18-11 changed -->
                                                         <a href="{{$lesson['slug']}}">
                                                         <span class="custom-tooltip">{{$lesson['inst']}}</span>
                                                         <img src="{{cdn( get_image($instructors[$lesso['instructor_id']][0]['mediable'],'instructors-small') )}}" alt="{{$lesson['inst']}}"/>
                                                         </a>
                                                      </div>
                                                      <!-- /.topic-wrapper-big -->
                                                   </div>
                                                   @endif
                                                   @endforeach
                                                   @endforeach
                                                   <!-- /.accordion-content -->
                                                </div>
                                                <!-- /.accordion-item -->
                                             </div>
                                             @endforeach
                                             <!-- /.accordion-wrapper -->
                                          </div>
                                          <!-- /.acc-topic-accordion -->
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        @else
                        <div class="col12 dynamic-courses-wrapper">
                           <div class="item">
                              <h2>{{ $event['title'] }}</h2>
                              <div class="inside-tabs">
                                 <div class="tabs-ctrl">
                                    <ul>
                                       <li class="active"><a href="#c-info-inner{{$tab}}">Info</a></li>
                                       <li><a href="#c-subs-inner{{$tab}}">Subscription</a></li>
                                    </ul>
                                 </div>
                                 <div class="inside-tabs-wrapper">
                                    <div id="c-info-inner{{$tab}}" class="in-tab-wrapper" style="display: block;">
                                       <div class="bottom">
                                          @if (isset($event['hours']) && $event['hours'])
                                          <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt=""> {{$event['hours']}}h </div>
                                          @endif
                                          @if (isset($event['videos_progress']))
                                          <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/E-Learning.svg')}}" alt=""> {{$event['videos_progress']}}% </div>
                                          @endif
                                       </div>
                                    </div>
                                    @if($subscriptionAccess)
                                    <div id="c-subs-inner{{$tab}}" class="in-tab-wrapper">
                                       <div class="bottom">
                                          <div class="left">
                                             <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/checklist-graduate-hat.svg')}}" alt="">Get annual access to updated videos & files. 15 days free trial.</div>
                                          </div>
                                          <div class="right">
                                             @foreach($event['plans'] as $key => $plan)
                                             <a href="/myaccount/subscription/{{$event['title']}}/{{ $plan->name }}" class="btn btn--secondary btn--md">SUBSCRIBE NOW</a>
                                             @endforeach
                                          </div>
                                       </div>
                                    </div>
                                    @endif
                                 </div>
                              </div>
                              <!-- ./item -->
                           </div>
                        </div>
                        @endif
                        <?php $tab += 1; ?>
                        @endforeach
                        @endif
                     </div>
                  </div>
               </div>
               @endif
            </div>
            <!-- /#elearning-tab-child.tab-content-wrapper -->
            <!-- /.tabs-content -->
         </div>
         <!-- /.tabs-wrapper -->
      </div>
      <!-- /.content-wrapper -->
      <!-- /.section-account-tabs -->
   </section>
</main>
@endsection
@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
   var stripeUserId = '{{ Auth::user()->createSetupIntent()->client_secret }}';
   $(document).on('click', '#addCard', function(e){

     /*$('<script>')
      .attr('src', 'https://js.stripe.com/v3/')
      .attr('id', 'stripe-js')
      .appendTo('head');*/



     $('#addCard').prop('disabled', true);
     $('.msg_save_card').remove();
     $('#container').append(`<div id="paymentMethodAdd">
        <input id="card-holder-name" type="text">
        <!-- Stripe Elements Placeholder -->
        <div id="card-element"></div>
        <button id="card-button" type="button" class="btn btn--secondary btn--sm" data-secret="${stripeUserId}">
            Update Payment Method
        </button></div>`)


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
               $('button').prop('disabled', true);
               $.ajax({
                  type:'POST',
                  url:'myaccount/card/store_from_payment_myaccount',
                  headers: {
                   'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                  },
                  data:{ 'payment_method' : paymentMethod},
                  success:function(data) {
                     stripeUserId = data.id;
                     if(data['success']){

                        let defaultPaymetntID = data['defaultPaymetntId'];
                        let defaultCard = data['default_card'];
                        let cards = data['cards'];

                        let html = ` <table  style="width:100%"><tr>
                              <th>Brand</th>
                              <th>Default</th>
                              <th>Last four</th>
                              <th>Expire Month</th>
                              <th>Expire Year</th>
                              <th>Actions</th>
                           </tr>`;

                        $.each( defaultCard, function( key, value ) {

                           html +=`<tr><td>` + value['brand'] + `</td>` +
                           `<td><i class="far fa-check-circle"></i>Yes</td>`+
                                 `<td>` + value['last4'] + `</td>` +
                                 `<td>` + value['exp_month'] + `</td>` +
                                 `<td>` + value['exp_year'] + `</td></tr>` ;
                        });

                        $.each( cards, function( key, value ) {
                           if(value['id'] != defaultPaymetntID){
                              html +=`<tr><td>` + value['card']['brand'] + `</td>` +
                              `<td><i class="far fa-check-circle"></i>no</td>`+
                                 `<td>` + value['card']['last4'] + `</td>` +
                                 `<td>` + value['card']['exp_month'] + `</td>` +
                                 `<td>` + value['card']['exp_year'] + `</td>` +
                                 `<td>

                                       <form action="{{route('payment_method.update')}}" method="post" id="payment-form">
                                          {{ csrf_field() }}
                                          <input type="hidden" name="card_id" value="`+ value['id'] +`">
                                          <button class="btn btn--secondary btn--sm">Set default</button>
                                       </form>

                                       <form action="{{route('payment_method.remove')}}" method="post" id="payment-form">
                                          {{ csrf_field() }}
                                          <input type="hidden" name="card_id" value="`+ value['id'] +`">
                                          <button id="removebtn" class="btn btn--secondary btn--sm">Remove</button>
                                       </form>
                                 </td></tr>`;
                           }

                        });
                        html += '</table>'
                        $("#cardList").empty();
                        $("#cardList").append(html);

                        $("#stripe-form").remove();
                        $("#stripe-js").remove();

                        $('#paymentMethodAdd').children().remove();

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
<script src="{{ cdn('theme/assets/addons/dropzone/dropzone.js') }}"></script>
<script>
   $( document ).ready(function() {
      if($('tr').length <= 2){
         let elem = $('tr').find('#removebtn').css('display', 'none')
      }
   });
</script>
<script type="text/javascript">
   $.ajaxSetup({headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

   $(document).ajaxError(function(event, jqxhr, settings, exception) {
       if (exception == 'Unauthorized') {
           window.location.href = baseUrl+'/';
       }
   });

   var settingsObj = {
       maxUploadSize: "3", // in MB
       dropzoneAcceptedFiles: "image/*",
       dropzoneAcceptImage: "image/*"
   };



   var logo_dropzone = {};
   var logo_dropzoneObj = {dragenter: 0, dragleave: 0};

   function logo_dropzoneToDropzone() {
       logo_dropzone = new Dropzone(document.getElementById("logo_dropzone"), {
       url: 'myaccount/upload-profile-image',
       method: "post",
       maxFilesize: settingsObj.maxUploadSize,
       paramName: "dp_fileupload",
       uploadMultiple: false,
       headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
       previewsContainer: null,
       previewTemplate: '<div id="preview-template" style="display: none;"></div>',
       createImageThumbnails: false,
       maxFiles: null,
       init: function () { },
       acceptedFiles: settingsObj.dropzoneAcceptedFiles,
       autoProcessQueue: true,
       uploadprogress: function(file, progress, bytesSent) {
           //console.log(progress);
       },
       sending: function (file, xhr, formData) {
          /* NProgress.configure({ parent: '#logo_dropzone' });
           NProgress.start();*/
          formData.append("filesize", file.size);
           formData.append("student_avatar", '1');
           formData.append("stid", $('#stid').html());
           formData.append("scope", "logo_dropzone");
       },
       complete: function (file) {
       },
       drop: function(){
       },
       success: function (file, response, load) {
           $('#user-img').attr('src', response.data)
        //    $('#logo_media').val(response.urls.large_thumb);
        //    $('#logo_media_id').val(response.media_id);
           renderLogoDropzone(response, true);
       }
   });

   logo_dropzone.on("dragenter", function(event) {
       logo_dropzoneObj.dragenter++;
       logo_dropzoneObj.dragleave = 0;
       $("#logoDropzone").addClass('acceptDrop');
   });

   logo_dropzone.on("dragleave", function(file) {
       logo_dropzoneObj.dragleave++;
       logo_dropzoneObj.dragenter = 0;
       if (logo_dropzoneObj.dragleave > 1) {
           $("#logoDropzone").removeClass('acceptDrop');
       }
   });
   }

   logo_dropzoneToDropzone();



   function renderLogoDropzone(mediaObj, overwrite) {
   //    console.log(mediaObj);

   if (mediaObj.media_id > 0) {

       var userMedia = document.getElementById('user-media');
        var li = document.createElement('li')
        li.className = "remove-photo delete_media";

        var a = document.createElement('a');

        a.setAttribute('data-dp-media-id',mediaObj.media_id)
        a.setAttribute('href',"javascript:void(0)");

     var img = document.createElement('img')

     img.setAttribute('src','/theme/assets/images/icons/icon-remove.svg')
     img.setAttribute('alt','Remove photo')

     var span = document.createElement('span')
     span.innerHTML = 'Remove photo';



     a.append(img)
     a.append(span);
     li.append(a)
     userMedia.append(li)


       document.getElementById('user-img').setAttribute('src','portal-img/users/'+mediaObj.media.path+'/'+mediaObj.media.name+mediaObj.media.ext)
       document.getElementById('user-img-up').setAttribute('src','portal-img/users/'+mediaObj.media.path+'/'+mediaObj.media.name+mediaObj.media.ext)


   }

   if ((typeof overwrite !== "undefined") && (overwrite === true)) {
       //console.log(html);
      // $('[data-dp-scope="logo_dropzone"] #cfMedia_logo_dropzone').html(html);
     ///  $('[data-dp-scope="logo_dropzone"] #cfMedia_logo_dropzone').find('.logo_dropzone_click').addClass('dz-clickable');
       var dropzoneFn = 'logo_dropzoneToDropzone';
       //window[dropzoneFn]();
       return true;
   } else {
       return html;
   }
   }



</script>
<script>
   $(document).on('click', '#myonoffswitch', function(e){
      //alert('asd')
   //e.preventDefault();
   $('.onoffswitch').removeAttr( "id" )

   let sub_id = $(this).parent().data('id')
   let status = $(this).parent().data('status')
   let set_status = '';

   if(status == 'Active'){
     set_status = 'Cancel'
     $(this).parent().data('status', 'Cancel');
     document.getElementsByClassName("onoffswitch-checkbox")[0].removeAttribute("checked");
   }else if(status == 'Cancel'){
     set_status = 'Active'
     $(this).parent().data('status', 'Active');
     $('.onoffswitch-checkbox').attr('checked', '')
   }

   $.ajax({
     type: 'POST',
     url: 'myaccount/subscription/change_status',
     data:{'sub_id':sub_id,'status':set_status},
     success: function(data) {
           data = JSON.parse(data)
           if(data){
              if(data.cancel_at){
                 $('#status').text('Cancel')
                 $('#status').css('color', 'red')
              }else{
                 $('#status').text('Active')
                 $('#status').css('color', 'green')
              }
              $('.onoffswitch').attr('id', 'onoffswitch');
           }
     }
   });

   });
</script>
<script>
   $("body").on("click", ".delete_media", function (event) {

      var favDialog = document.getElementById('favDialog');
        //favDialog.showModal();
        favDialog.style.display = "block";
        $("body").css("overflow-y", "hidden")

     // favDialog.show();
            /*if(confirm('Do you really want to remove your profile picture?')) {
                  //alert('You are very brave!');
                  deleteMediaPromt($(this).attr("data-dp-media-id"));
              }
              else {
                  alert('Tip! Drag and Drop or click to change your profile picture');

              }*/
          });
          $("body").on("click", ".deleteImg", function (event) {
               deleteMediaPromt($('.remove-photo.delete_media a').attr('data-dp-media-id'));

            });


            $("body").on("click", ".cancelImg", function (event) {

               var favDialog = document.getElementById('favDialog');
             //  favDialog.close();
               favDialog.style.display = "none";
               $("body").css("overflow-y", "auto")

           });

          function deleteMediaPromt(media_id) {
            //alert(media_id)
              $.ajax({ url: "myaccount/remove-avatar", type: "post",
                  data: {'media':media_id},
                  success: function(data) {

                      if (Number(data.status) === 1) {


                        document.getElementById('user-img').setAttribute('src','/theme/assets/images/icons/user-profile-placeholder-image.png')
                        document.getElementById('user-img-up').setAttribute('src','/theme/assets/images/icons/user-profile-placeholder-image.png')

                          $('.delete_media').hide();
                      }
                  }
              });

              var favDialog = document.getElementById('favDialog');
              favDialog.style.display = "none";
               $("body").css("overflow-y", "auto")
          }



          $(document).on('click', '.close-alert', function(e){
            var favDialog = document.getElementById('favDialog');
            favDialog.style.display = "none";
            //favDialog.close();
            $("body").css("overflow-y", "auto")
         })

   /*$('.getcertificate').click(function() {

      var dir = $(this).attr('data-dirname');
      var fname = $(this).attr('data-filename');



      $.ajax({ url: '/myaccount/mycertificate/' + dir, type: "get",

          success: function(data) {
           //console.log(data);
      //      window.location.href = data;
          }
      });

   });*/

   $('.getdropboxlink').click(function() {

      var dir = $(this).attr('data-dirname');
      var fname = $(this).attr('data-filename');


      $.ajax({ url: '/getdropbox', type: "post",
          data: {dir: dir, fname:fname},

          success: function(data) {

            window.location.href = data;
          }
      });

   });

   $('#gdpr-download').click(function() {

   window.location.href = 'myaccount/mydata';


   });
      $('.edit-mode').on('click', function() {
      //    $('#student-view-mode').addClass('hidden');
          $('#student-view-mode').hide();
         // $('#student-billing-view-mode').addClass('hidden');
          $('#student-edit-mode').show();
      });
      $('.cancel-edit-mode').on('click', function() {
         // $('#student-edit-mode').addClass('hidden');
         // $('#student-view-mode').removeClass('hidden');
         $('#student-view-mode').show();
          $('#student-edit-mode').hide();
      });

      $('.edit-invoice-mode').on('click', function() {
           $('#invoice_add_edit_mode').show();
           $('#edit-invoice-mode').hide('hidden');
       });
       $('.cancel-edit-invoice-mode').on('click', function() {
           $('#invoice_add_edit_mode').hide();
           $('#edit-invoice-mode').show();
       });

       $('.edit-receipt-mode').on('click', function() {
          // $('#static-receipt').hide();
           $('#receipt_add_edit_mode').show();
           $('#edit-receipt-mode').hide();
       });
       $('.cancel-edit-receipt-mode').on('click', function() {
           $('#receipt_add_edit_mode').hide();
           //$('#static-receipt').show();
           $('#edit-receipt-mode').show();
       });



       $('#save-receipt-data').on('click', function() {
           var receiptdata = $("#receipt_add_edit_mode :input").serialize();
           //console.log(receiptdata);
           $.ajax({ url: "myaccount/updrecbill", type: "post",
               data: receiptdata,
               success: function(data) {
                   if (Number(data.status) === 1) {

                       window.location = 'myaccount'; //'myaccount/billing';
                   }
                   else {
                       alert('Not saved. Please try again');
                   }
               }
           });
       });


       $('#save-invoice-data').on('click', function() {
           var invoicedata = $("#invoice_add_edit_mode :input").serialize();
           //console.log(invoicedata);
           $.ajax({ url: "myaccount/updinvbill", type: "post",
               data: invoicedata,
               success: function(data) {
                   if (Number(data.status) === 1) {
                       window.location = 'myaccount';//'myaccount/billing';
                   }
                   else {
                       alert('Not saved. Please try again');
                   }
               }
           });
       });
</script>
<script>
   $("#selectCountry").change(function() {

      let mobile = $("#mobile").val()

      $("#mobileCheck").val("+" + this.value + mobile)
   });

   function checkPhoneNumber(phone){

   phone = phone.value.replace(/\s/g,'')
   let validatePhone = false;

   if(phone.length > 3){

      if(phone.substring(0, 3) == '+30' || phone.substring(0, 2) == '30'){

         $("#selectCountry").val("30").change();
         validatePhone = true;
      }else if(phone.substring(0, 2) == '69'){
         //phone = '+30'+phone
         validatePhone = true;
         $("#selectCountry").val("30").change();
      }
      else if(phone.substring(0, 4) == '+357' || phone.substring(0, 3) == '357'){//cyprus
         validatePhone = true;
         $("#selectCountry").val("357").change();
      }else if(phone.substring(0, 1) == '9'){
         //phone = '+357'+phone
         validatePhone = true;
         $("#selectCountry").val("357").change();
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
      }else if(phone.substring(0, 2) == '07' /*|| phone.substring(0, 3) == '073' || phone.substring(0, 3) == '074' || phone.substring(0, 3) == '075' || phone.substring(0, 3) == '076'
         || phone.substring(0, 3) == '077' || phone.substring(0, 3) == '078' || phone.substring(0, 3) == '079'*/){

            //phone = '+44'+phone
            validatePhone = true;
            $("#selectCountry").val("44").change();
      }

      //$("#mobile").val(phone)
      let mobile = '+' + $( "#selectCountry" ).val() + $("#mobile").val()
      $("#mobileCheck").val( mobile)

      if(!validatePhone){
         //$("#mobile").val('')
      }

   }



   }
</script>
<script>
   $(document).ready(function() {



      $("#selectCountry").select2()

      @if("{{ old('country_code') }}")

         $("#selectCountry").val("{{ old('country_code',$currentuser->country_code) }}").change();

      @endif

   });

</script>
<script>
   $("#update-personal-info").click(function(){

      fdata =$("#update-form").serialize();
      var firstError = false;
      $.ajax({ url: "{{route('validate.personalInfo')}}", type: "post",
            data: fdata,
            success: function(data) {
                //console.log(data);
                //return;
                $('#update-form').find("input").removeClass('verror');
                if (Number(data.status) === 0) {
                    //var html = '<ul>';
                    $.each(data.errors, function (key, row) {
                        //console.log(data.errors);
                        var newkey = key.replace('.', '');

                        $('#update-form').find('input#'+newkey).addClass(['verror','validate-error']);

                        if(!firstError){
                            elementsHeight = Math.round($('#header').outerHeight()) - document.getElementById(newkey).getBoundingClientRect().top +30
                            firstError = true;

                            $('html, body').animate({
                                scrollTop: elementsHeight
                            }, 300);
                        }

                        $('#'+newkey+'-error').text(row[0]);
                        //var s = $('#update-form').find('input#'+newkey).attr('placeholder');
                        var pl =  row[0] ;

                        $('#update-form').find('input#'+newkey).attr('placeholder', pl);

                    });



                } else {
                  $('#update-form').submit();
                }
            }
        });

   });

</script>
@stop
