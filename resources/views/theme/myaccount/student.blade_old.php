@extends('theme.layouts.master')
@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<?php $currentuser = Sentinel::getUser(); ?>
<main id="" role="main">
   <section class="section-hero section-hero-small section-hero-blue-bg">
      <div class="container">
         <div class="hero-message">
            <div class="account-infos">
               <div class="account-thumb">
                  @if(isset($media))   
                  <?php $img_src = 'portal-img/users/'.$media['path'].'/'.$media['name'].$media['ext'];?> 
                  <img id="user-img-up" src="{{cdn($img_src)}}" alt="{{ $currentuser->first_name }} {{ $currentuser->last_name }}"/> 
                  @else
                  <img id="user-img-up" src="{{cdn('/theme/assets/images/icons/user-profile-placeholder-image.png')}}" alt="user-profile-placeholder-image"/> 
                  @endif
               </div>
               <div class="account-hero-info">
                  <h2>{{ $currentuser->first_name }} {{ $currentuser->last_name }}</h2>
                  <ul>
                     @if($currentuser->kc_id != '')   
                     <li>KnowCrunch alumni number: {{ $currentuser->kc_id }}</li>
                     @endif
                     @if($currentuser->partner_id)    
                     <li>Deree number: {{ $currentuser->partner_id }}</li>
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
                     <li><a href="#my-account" class="active">My Account</a></li>
                     <li>
                        @if( (isset($myEvents) && count($myEvents) == 0) || !$elearningAccess)
                        <a id='courses-tab' href="#nocourses" >My courses</a>
                        @else
                        <a id='courses-tab' >My courses</a>
                        @endif
                        <ul class="child-menu">
                           @if(isset($myEvents) && count($myEvents) > 0)
                           <?php $tab = 0; ?>
                           @foreach($myEvents as $key => $event)
                           @if($event['view_tpl'] != 'elearning_greek' && $event['view_tpl'] != 'elearning_event' && $event['view_tpl'] != 'elearning_free')
                           <li><a href="#topics-tab{{$tab}}">{{$key}}</a></li>
                           <?php $tab += 1; ?>
                           @endif
                           @endforeach
                           @foreach($myEvents as $key => $event)
                           @if($event['view_tpl'] == 'elearning_greek' || $event['view_tpl'] == 'elearning_event' || $event['view_tpl'] == 'elearning_free')
                           {{--if(event['video_access'])--}}
                           <?php 
                              $frame = $key;
                              $frame = str_replace(' ','_',$frame);
                              $frame = str_replace('-','',$frame);
                              $frame = str_replace('&','',$frame);
                              $frame = str_replace('_','',$frame);  
                                 
                              $frame = '{'.$frame.'}';
                                 
                              ?>
                           <li><a href="#elearning-tab-child{{$tab}}" onclick="tabclick({{$event['videos']}},{{$event['id']}},{{$event['lastVideoSeen']}},{{$event['event_statistic_id']}},'{{$frame}}')">{{$event['title']}}</a></li>
                           <?php $tab += 1; ?>
                           {{--endif--}}
                           @endif
                           @endforeach
                           @endif
                        </ul>
                     </li>
                  </ul>
                  <!-- /.container -->
               </div>
               <!-- /.tab-controls -->
            </div>
            <div class="tabs-content">
               <div id="my-account" class="tab-content-wrapper active-tab">
                  <div class="container">
                     <div class="row">
                        <div class="col4 col-sm-12">
                           <div class="account-image-actions"  id="logo_dropzone">
                              <div class="acc-img">
                                 @if(isset($media))   
                                 <img id="user-img" src="{{cdn($img_src)}}" alt="{{ $currentuser->first_name }} {{ $currentuser->last_name }}"/> 
                                 @else
                                 <img id="user-img" src="{{cdn('/theme/assets/images/icons/user-profile-placeholder-image.png')}}" alt="user-profile-placeholder-image"/> 
                                 @endif
                              </div>
                              <div class="actions">
                                 <ul id='user-media'>
                                    <li class="change-photo"><a id="logoDropzone" class="custFieldMediaDrop dz-message" href="javascript:void(0)"><img src="{{cdn('/theme/assets/images/icons/icon-edit.svg')}}" alt="Change photo"/><span>Change photo</span>
                                       </a>
                                    </li>
                                    @if(isset($media)) 
                                    <li class="remove-photo delete_media"><a data-dp-media-id="{{ $media['id'] }}" href="javascript:void(0)"><img src="{{cdn('/theme/assets/images/icons/icon-remove.svg')}}" alt="Remove photo"/><span>Remove photo</span></a></li>
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
                                    <li class="active"><a href="#personal-data">Personal data</a></li>
                                    <li><a href="#billing-data">Billing data</a></li>
                                    <li><a href="#password-edit">Password</a></li>
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
                                       <form method="post" action="{{ route('account.updateme') }}" autocomplete="off">
                                          {!! csrf_field() !!}
                                          <div class="col12">
                                             <label>First name: <span>*</span></label>
                                             <div class="input-safe-wrapper">
                                                <input class="required" type="text" name="first_name" id="first_name"  value="{{ old('first_name', $currentuser->first_name) }}" >
                                             </div>
                                          </div>
                                          <div class="col12">
                                             <label>Last name: <span>*</span></label>
                                             <div class="input-safe-wrapper">
                                                <input class="required"  type="text" name="last_name" id="last_name" value="{{{ Input::old('last_name', $currentuser->last_name) }}}" >
                                             </div>
                                          </div>
                                          <div class="col12">
                                             <?php $birthday = date('j F Y',strtotime($currentuser->birthday))?>
                                             <label>Date of birth:</label>
                                             <div class="input-wrapper">
                                                <input type="text" class="datepicker-jqui with-arrow" name="birthday" value="{{$birthday}}">
                                             </div>
                                          </div>
                                          <div class="col12">
                                             <label>Company:</label>
                                             <div class="input-safe-wrapper">
                                                <input  type="text" name="company" id="company" value="{{{ Input::old('company', $currentuser->company) }}}" >
                                             </div>
                                          </div>
                                          <div class="col12">
                                             <label>Position/Title:</label>
                                             <div class="input-safe-wrapper">
                                                <input name="job_title" id="job_title" value="{{{ Input::old('job_title', $currentuser->job_title) }}}" >
                                             </div>
                                          </div>
                                          <div class="col12">
                                             <label>Email: <span>*</span></label>
                                             <div class="input-safe-wrapper">
                                                <input class="required" name="email" type="email" value="{{{ Input::old('email', $currentuser->email) }}}" >
                                             </div>
                                          </div>
                                          <div class="col12">
                                             <label>Mobile phone: <span>*</span></label>
                                             <div class="input-safe-wrapper is-flex full-width">
                                       
                                          <select name="countryCode" id="selectCountry" class="select2 form-control mb-3 custom-select country-select">
         
                                   
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
                                                <input class="required" onkeyup="checkPhoneNumber(this)" type="text" name="mobile" id="mobile" value="{{{ Input::old('mobile', $currentuser->mobile) }}}" >
                                                <input type="hidden" name="mobileCheck" id="mobileCheck" value="{{{ Input::old('mobile', '+'.$currentuser->country_code.$currentuser->mobile) }}}">
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
                                             <button type="submit" class="btn btn--md btn--secondary">Update</button>
                                          </div>
                                       </form>
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
                                          <div id="receipt_add_edit_mode" class="hidden-fields-actions receipt-fields" style="display: block;">
                                             @if($receipt_info != '')
                                             <?php $receipt_info = json_decode($receipt_info);  ?>
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
                                             @if($invoice_info != '')
                                             <?php $invoice_info = json_decode($invoice_info);  ?>
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
                                          <form method="post" action="{{ route('account.updateme') }}" autocomplete="off" class="password-form">
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
                                                <input type="hidden" name='email' value="{{$currentuser->email}}">
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
               @if((isset($myEvents) && count($myEvents) == 0) || !$elearningAccess)
               <div id="nocourses" class="tab-content-wrapper">
                  <div class="container">
                     You have no courses
                  </div>
               </div>
               @endif
               @if((isset($myEvents) && count($myEvents) > 0) && $elearningAccess)
               <?php $firstCourse = true; ?>
               <?php $tab=0; ?>
               @foreach($myEvents as $keyType => $event)
               @if($event['view_tpl'] != 'elearning_event' && $event['view_tpl'] != 'elearning_greek' && $event['view_tpl'] != 'elearning_free')
               <?php 
                  if($firstCourse){
                     $hrf = '#topics-tab'.$tab;
                     echo "<script> document.getElementById('courses-tab').setAttribute('href','$hrf') </script>";
                     $firstCourse = false;
                  }
                  ?>
               <div id="topics-tab{{$tab}}" class="tab-content-wrapper">
                  <div class="container">
                     <div class="topic-infos">
                        <h2>{{$keyType}}</h2>
                        <div class="more-infos">
                           <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/marker.svg')}}" alt=""><a href="{{$event['city']['slug']}}">{{$event['city']['name']}}</a></div>
                           <div class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/icon-calendar.svg')}}" alt="">{{$event['date']}}</div>
                           @if($event['hour'])
                           <div class="expire-date"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt="">{{$event['hour']}}</div>
                           @endif
                        </div>
                     </div>
                     <div class="inside-tabs">
                        <div class="tabs-ctrl">
                           <ul>
                              <li class="active"><a href="#c-topics-inner{{$tab}}">Topics</a></li>
                              @if(!$instructor_topics && $showFiles[$keyType])
                              <li ><a href="#c-files-inner{{$tab}}">Files</a></li>
                              @endif
                              @if($event['exam'])
                              <li><a href="#c-exams{{$tab}}">Exams</a></li>
                              @endif
                              {{--
                              <li><a href="#c-certificates{{$tab}}">Certificates</a></li>
                              --}}
                           </ul>
                        </div>
                        <div class="inside-tabs-wrapper">
                           <div id="c-topics-inner{{$tab}}" class="in-tab-wrapper" style="display: block;">
                              <div class="download-area">
                                 <a  href="/print/syllabus/{{$event['slug']}}">Download full schedule as PDF</a>
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
                                                <img src="{{cdn($lesson['inst_photo'])}}" alt="{{$lesson['inst']}}"/>
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
                              <!-- /.in-tab-wrapper -->
                           </div>
                           @if(!$instructor_topics && $showFiles[$keyType])
                           <div id="c-files-inner{{$tab}}" class="in-tab-wrapper">
                              @if(isset($folders) && count($folders) > 0)
                              <div class="acc-topic-accordion">
                                 <div class="accordion-wrapper accordion-big">
                                    @foreach($folders as $catid => $dbfolder)
                                    <?php 
                                       $folder = false;
                                       
                                       if(trim($catid) == trim($catId)){
                                          $folder = true; 
                                       }
                                       
                                       
                                       ?>
                                    @if($folder)
                                    @if (isset($dbfolder[0]) && !empty($dbfolder[0]))
                                    @foreach($dbfolder[0] as $key => $folder)
                                    <?php
                                       $rf = strtolower($folder['dirname']);
                                       $rf1 = $folder['dirname']; //newdropbox
                                       ?>
                                    <?php  
                                       $topic=1; 
                                       if($instructor_topics){ 
                                          $topic=0;
                                          
                                          if((trim($folder['foldername']) === '1 - Prelearning - Digital & Social Media Fundamentals')
                                                   && in_array(trim('Pre-learning: Digital & Social Media Fundamentals'), $instructor_topics)){
                                             
                                             $topic = 1;
                                          }else{
                                             $topic_name = explode( '-', $folder['foldername'] );  
                                             $topic=in_array(trim($topic_name[1]), $instructor_topics); 
                                       }   }
                                       ?>
                                    @if($topic)
                                    <div class="accordion-item">
                                       <h3 class="accordion-title title-blue-gradient scroll-to-top"> {{ $folder['foldername'] }}</h3>
                                       <!-- Feedback 01-12 changed -->
                                       <div class="accordion-content no-padding">
                                          @if (isset($files[$catid][1]) && !empty($files[$catid][1]))
                                          @foreach($files[$catid][1] as $fkey => $frow)
                                          @if($frow['fid'] == $folder['id'])
                                          <div class="files-wrapper">
                                             <div class="file-wrapper">
                                                <h4 class="file-title">{{ $frow['filename'] }}</h4>
                                                <span class="last-modified">Last modified:  {{$frow['last_mod']}}</span>
                                                <a  class="download-file getdropboxlink"  data-dirname="{{ $frow['dirname'] }}" data-filename="{{ $frow['filename'] }}" href="javascript:void(0)" >
                                                <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                             </div>
                                          </div>
                                          @endif
                                          @endforeach
                                          <!-- bonus of each lesson -->
                                          @if (isset($dbfolder[1]) && !empty($dbfolder[1]))
                                          @foreach($dbfolder[1] as $nkey => $nfolder)
                                          @if($nfolder['parent'] == $folder['id']) <!--//lioncode-->
                                          <div class="files-wrapper bonus-files">
                                             <h4 class="bonus-title">{{ $nfolder['foldername'] }}</h4>
                                             <span><i class="icon-folder-open"></i>   </span>
                                             @if (isset($files[$catid][2]) && !empty($files[$catid][2]))
                                             @foreach($files[$catid][2] as $fkey => $frow)
                                             @if (strpos($frow['dirname'], $rf) !== false || strpos($frow['dirname'], $rf1) !== false)
                                             <div class="file-wrapper">
                                                <h4 class="file-title">{{ $frow['filename'] }}</h4>
                                                <span class="last-modified">Last modified:  {{$frow['last_mod']}}</span>
                                                <a  class="download-file getdropboxlink"  data-dirname="{{ $frow['dirname'] }}" data-filename="{{ $frow['filename'] }}" href="javascript:void(0)" >
                                                <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                             </div>
                                             @endif
                                             @endforeach
                                             </ul>
                                             @endif
                                          </div>
                                          @endif
                                          @endforeach
                                          @endif
                                          <!-- bonus of each lesson -->
                                          @endif
                                          <!-- /.accordion-content -->
                                       </div>
                                       <!-- /.accordion-item -->
                                    </div>
                                    @endif
                                    @endforeach
                                    @endif<!-- edw -->
                                    <!-- last files-->
                                    @if(!$instructor_topics)
                                    @if (isset($files[$catid][0]) && !empty($files[$catid][0]))
                                    @foreach($files[$catid][0] as $key => $row)
                                    <div class="files-wrapper bonus-files">
                                       <div class="file-wrapper">
                                          <h4 class="file-title">{{ $row['filename'] }}</h4>
                                          <span class="last-modified">Last modified:  {{$row['last_mod']}}</span>
                                          <a  class="download-file getdropboxlink"  data-dirname="{{ $row['dirname'] }}" data-filename="{{ $row['filename'] }}" href="javascript:void(0)" >
                                          <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                       </div>
                                    </div>
                                    @endforeach
                                    @endif
                                    @endif
                                    <!--last files -->
                                    @endif
                                    @endforeach
                                    <!-- /.accordion-wrapper -->
                                 </div>
                                 <!-- /.acc-topic-accordion -->
                              </div>
                              @endif
                           </div>
                           @endif
                           <!--- EXAMSSS -->
                           <div id="c-exams{{$tab}}" class="in-tab-wrapper">
                              <div class="account-text">
                                 {{-- 
                                 <h2>My exams</h2>
                                 --}}
                              </div>
                              @if(isset($newlayoutExamsEvent[$keyType])) 
                              <div class="dynamic-courses-wrapper dynamic-courses-wrapper--style2">
                                 @foreach($newlayoutExamsEvent[$keyType] as $p)
                                 @foreach($p['exams'] as $pe)
                                 <div class="item">
                                    <div class="left">
                                       <h2 class="item-title"><a href="{{$p['slug']}}">{{$p['title']}}</a></h2>
                                       <div class="bottom">
                                          <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/marker.svg')}}" alt=""><a href="{{$p['location']['slug']}}">{{$p['location']['city']}}</a></div>
                                          <div class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/icon-calendar.svg')}}" alt="">{{$p['date']}}</div>
                                          @if($p['hours'])
                                          <div class="expire-date"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt="">{{$p['hours']}}h</div>
                                          @endif
                                       </div>
                                    </div>
                                    <div class="right right--no-price">
                                       <!-- Feedback 8-12 changed -->
                                       @if($pe->exstatus == 1)
                                       <a target="_blank" href="{{ url('student-summary/' . $pe->id . '/' . $currentuser->id) }}?s=1" title="{{$p['title']}}" class="btn btn--secondary btn--md">VIEW RESULT</a>
                                       @elseif($pe->islive == 1)
                                       <a target="_blank" onclick="window.open('{{ route('attempt-exam', [$pe->id]) }}', 'newwindow', 'width=1400,height=650'); return false;" title="{{$p['title']}}" class="btn btn--secondary btn--md">TAKE EXAM</a>
                                       @elseif($pe->isupcom == 1)	
                                       <a  title="{{$p['title'] }}" class="btn btn--secondary btn--md">{{ date('F j, Y', strtotime($pe->publish_time)) }}</a>
                                       @endif
                                    </div>
                                 </div>
                                 <!-- ./item -->
                                 @endforeach
                                 @endforeach
                              </div>
                              <!-- ./dynamic-courses-wrapper -->
                              @endif
                           </div>
                           <!---EXAMSSS--->
                           <div id="c-certificates{{$tab}}" class="in-tab-wrapper">
                              <div class="account-text">
                                 {{-- 
                                 <h2>My certificates</h2>
                                 --}}
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- /#topics-tab.tab-content-wrapper -->
               </div>
               <?php $tab += 1; ?>
               @endif
               @endforeach
              
               @foreach($myEvents as $keyType => $event)
               @if($event['view_tpl'] == 'elearning_event' || $event['view_tpl'] == 'elearning_greek' || $event['view_tpl'] == 'elearning_free')
               
                  <?php 
                     $frame = $keyType;
                     $frame = str_replace(' ','_',$frame);
                     $frame = str_replace('-','',$frame);
                     $frame = str_replace('&','',$frame);
                     $frame = str_replace('_','',$frame);  
                     
                     $frame = '{'.$frame.'}';
                     
                     if($firstCourse){
                        $hrf = '#elearning-tab-child'.$tab;
                        $onclick = "tabclick(" . $event['videos'] . "," . $event['id'] . "," .$event['lastVideoSeen'] . "," . 
                                                   $event['event_statistic_id'] . "," . '"'. $frame.'"' .")";
                     
                        echo "<script> document.getElementById('courses-tab').setAttribute('onclick','$onclick') </script>";
                        echo "<script> document.getElementById('courses-tab').setAttribute('href','$hrf') </script>";
                        $firstCourse = false;
                     }
                     
                     
                     ?>
                  <div id="elearning-tab-child{{$tab}}" class="tab-content-wrapper">
                     <div class="container">
                        <div class="topic-infos">
                           <h2>{{$event['title']}}</h2>
                           <div class="more-infos">
                              @if($event['hour'])
                              <div class="expire-date"><img src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt="">{{$event['hour']}}</div>
                              @endif
                              @if($event['expiration_date'])
                              <div class="expire-date exp-date"><img src="{{cdn('/theme/assets/images/icons/Days-Week.svg')}}" alt="">Your access expires on: {{$event['expiration_date']}}</div>
                              @endif
                              
                           </div>
                        </div>
                        <div class="inside-tabs">
                           <div class="tabs-ctrl">
                              <ul>
                                 @if($event['video_access'])
                                    <li class="active"><a href="#elearning-tab-child-videos{{$tab}}">Videos</a></li>
                                 @endif
                                 @if(!$instructor_topics) 
                                 <li @if(!$event['video_access']) class="active" @endif><a href="#elearning-tab-child-files{{$tab}}">Files</a></li>
                                 @endif
                                 @if(!$instructor_topics)  @if($event['exam']) 
                                 <li><a href="#elearning-tab-child-exams{{$tab}}">Exams</a></li>
                                 @endif
                                 @if(count($event['cert'])>0)
                                 <li><a href="#elearning-tab-child-certificates{{$tab}}">Certificates</a></li>
                                 @endif @endif
                              </ul>
                           </div>
                           <?php 
                              $catId = -1;

                              foreach($event['topics'] as $keyTopic => $topic){
                                 foreach($topic as $keyLesso => $lesso){
                                    foreach($lesso as $keyLesson => $lesson){
                                       $catId = $lesson['cat_id'];
                                    }
                                 }
                                    
                              }

                           ?>
                           
                           <div class="inside-tabs-wrapper">
                           @if($event['video_access'])
                              <div id="elearning-tab-child-videos{{$tab}}" class="in-tab-wrapper" style="display: block;">
                                 <div class="profile-elearning-video-wrapper">
                                    <div class="video-area video-point-scroll">
                                       <div id="{{$event['id']}}" class="responsive-video">
                                          <?php $fv = 'https://player.vimeo.com/video/' . $event['lastVideoSeen'] . '?title=false' ?>
                                          <div data-vimeo-url="{{$fv}}" data-vimeo-width="740" id="{{$frame}}"></div>
                                       </div>
                                       <script>
                                          var width = $('#'+"{{$event['id']}}").width();
                                          var html = `<div data-vimeo-url="{{$fv}}" data-vimeo-width="` + width +`"id="`+ "{{$frame}}" +`"></div>`                  
                                          $('#'+"{{$event['id']}}").empty();
                                          $('#'+"{{$event['id']}}").append(html);
                                       </script>
                                    </div>
                                    <div class="sidebar-area">
                                       <div class="sidebar-wrapper">
                                          <div class="accordion-wrapper custom-scroll-area">
                                             <?php 
                                                $catId = -1;
                                                $firstTab =true;
                                                $firstLesson =true;
                                                ?>
                                             @foreach($event['topics'] as $keyTopic => $topic)
                                             <div class="accordion-item @if($firstTab && $event['lastVideoSeen'] === -1) active-tab  @endif">
                                                <h2 class="accordion-title scroll-to-top--inside">{{$keyTopic}}</h2>
                                                <div class="accordion-content" @if($firstTab && $event['lastVideoSeen'] === -1) style="display: block;" @endif>
                                                   @foreach($topic as $keyLesso => $lesso)
                                                   @foreach($lesso as $keyLesson => $lesson)
                                                      <?php $catId = $lesson['cat_id'];
                                                         $path = $lesson['vimeo'];
                                                         if($firstLesson){
                                                            $firstLesson = false;
                                                         }
                                                      ?>
                                                      <?php 
                                                         $tabb = $keyType.'_'.$lesson['tab'];
                                                         $tabb = str_replace(' ','_',$tabb);
                                                         $tabb = str_replace('-','',$tabb);
                                                         $tabb = str_replace('&','',$tabb);
                                                         $tabb = str_replace('_','',$tabb);
                                                         
                                                         //$tabb = $keyLesso;
                                                         
                                                         $videoLesId  =$tabb.$lesson['id'];
                                                         
                                                         ?>
                                                      <div class="topic-wrapper @if($lesson['seen']) watched @endif" id="{{$tabb}}">
                                                         <a onclick="play_video('{{$path}}','{{$tabb}}', '{video{{$lesson['id']}}}', '{{$lesson['id']}}')" href="javascript:void(0)" class="play-link">
                                                         @if($lesson['seen'])
                                                         <img src="{{cdn('/theme/assets/images/icons/check_lesson.svg')}}" width="12" class="icon" alt=""/></a>
                                                         @else
                                                         <img id="play-image-account{{$lesson['id']}}" src="{{cdn('/theme/assets/images/icons/Play.svg')}}" width="12" class="icon" alt=""/></a>
                                                         @endif                                                            
                                                         </a> <!-- Feedback 18-11 changed -->
                                                         <div class="topic-title-meta">
                                                            <h3><a class="go-to-video-area" onclick="play_video('{{$path}}','{{$tabb}}', '{video{{$lesson['id']}}}', '{{$lesson['id']}}')" href="javascript:void(0)">{{$keyLesson}}</a></h3>
                                                            <div class="topic-meta">
                                                               <span class="duration"><img src="{{cdn('/theme/assets/images/icons/Times.svg')}}" alt="" />{{$lesson['duration']}}</span> <!-- Feedback 18-11 changed -->
                                                            </div>
                                                            <!-- /.topic-title-meta -->
                                                         </div>
                                                         <div class="author-img">
                                                            <a href="{{$lesson['slug']}}">
                                                            <span class="custom-tooltip">{{$lesson['inst']}}</span>
                                                            <img src="{{cdn($lesson['inst_photo'])}}" alt="{{$lesson['inst']}}"/>
                                                            </a>
                                                         </div>
                                                         <!-- /.topic-wrapper -->
                                                      </div>
                                                   @endforeach
                                                   @endforeach
                                                </div>
                                                <!-- /.accordion-content -->
                                             </div>
                                             <?php $firstTab = false; ?>
                                             @endforeach
                                             <!-- /.accordion-item -->
                                          </div>
                                       
                                          <!-- /.accordion-wrapper -->
                                       </div>
                                    </div>
                                    
                                 </div>
                              </div>
                              @endif
                              @if(!$instructor_topics)
                           <div id="elearning-tab-child-files{{$tab}}" class="in-tab-wrapper" @if(!$event['video_access']) style="display: block;" @endif>
                              @if(isset($folders) && count($folders) > 0)
                         
                              <div class="acc-topic-accordion">
                                 <div class="accordion-wrapper accordion-big">
                                    @foreach($folders as $catid => $dbfolder)
                                    <?php //dd($folders) ?>
                                    {{--if(isset($blockcat) && isset($blockcat[$catid]))--}}                                        
                                    <?php 
                                       $folder = false;
                                       if(trim($catid) == trim($catId)){
                                          
                                          $folder = true; 
                                       }
                                          
                                    ?>


                                    @if($folder)
                                    @if (isset($dbfolder[0]) && !empty($dbfolder[0]))
                               
                                    @foreach($dbfolder[0] as $key => $folder)
                                   
                                    <?php
                                       $rf = strtolower($folder['dirname']);
                                       $rf1 = $folder['dirname']; //newdropbox
                                       ?>
                                    <?php   
                                       $topic=1; 
                                       if($instructor_topics){ 
                                          $topic=0;
                                          
                                          if((trim($folder['foldername']) === '1 - Prelearning - Digital & Social Media Fundamentals')
                                                   && in_array(trim('Pre-learning: Digital & Social Media Fundamentals'), $instructor_topics)){
                                             $topic = 1;
                                          }else{
                                             $topic_name = explode( '-', $folder['foldername'] );  
                                             $topic=in_array(trim($topic_name[1]), $instructor_topics); 
                                       }   }
                                       ?>
                                    @if($topic)
                                    <div class="accordion-item">
                                       <h3 class="accordion-title title-blue-gradient scroll-to-top"> {{ $folder['foldername'] }}</h3>
                                       <!-- Feedback 01-12 changed -->
                                       <div class="accordion-content no-padding">
                                          
                                       <?php    
                                          $checkedF = []; 
                                          $fs = [];
                                          $fk = 1;
                                          $bonus = [];
                                          $subfolder = false;
                                       ?>
                                          
                                          @if (isset($files[$catid][1]) && !empty($files[$catid][1]))
                                    
                                          @foreach($files[$catid][1] as $fkey => $frow2)

                                          @if($frow2['fid'] == $folder['id'])
                                  

                                          <?php 
                                            
                                             $fn = $folder['foldername'];
                                             
                                             if(isset($dbfolder[1]) && !empty($dbfolder[1])){
                                                foreach($dbfolder[1] as $nkey => $nfolder){
                                                   $dirname = explode('/',$nfolder['dirname']);
                                                   if($nfolder['parent'] == $folder['id'] && in_array($fn,$dirname) && !$subfolder  && $nfolder['foldername'] != '_Bonus' && $nfolder['foldername'] != 'Bonus'){
                                                      
                                                      $checkedF[] = $nfolder['id'] + 1 ;
                                                      $fs[$nfolder['id']+1]=[];
                                                      $fs[$nfolder['id']+1][] = $nfolder;
                                                      
                                                   }
                                                }
                                             }
                                             
                                             if(count($fs) > 0 ){
                                                $subfolder = true;
                                             }

                                          ?>
                                         
                                             @if($subfolder && in_array($fk,$checkedF))

                                                @while(in_array($fk,$checkedF))
                                                      <?php 

                                                         $sfv = reset($checkedF);
                                                         $sfk = array_search($sfv, $checkedF);
                                                         unset($checkedF[$sfk]);
                                                      ?>


                                                      @if (isset($dbfolder[1]) && !empty($dbfolder[1]))
                                                         @foreach($dbfolder[1] as $nkey => $nfolder)
                                                             @if($nfolder['id'] == $fs[$sfv][0]['id'] && $nfolder['parent'] ==  $fs[$sfv][0]['parent'] && $nfolder['foldername'] !== '_Bonus' && $nfolder['foldername'] !== 'Bonus') <!--//lioncode-->

                                                               <div class="files-wrapper bonus-files">
                                                                  <h4 class="bonus-title">{{ $nfolder['foldername'] }}</h4>
                                                                  <span><i class="icon-folder-open"></i>   </span>
                                                                  @if (isset($files[$catid][2]) && !empty($files[$catid][2]))
                                                                     @foreach($files[$catid][2] as $fkey => $frow)
                                                                        @if (strpos($frow['dirname'], $rf) !== false || strpos($frow['dirname'], $rf1) !== false && ( $frow['fid'] == ($sfv-1)  ))
                                                                        <?php $bonus[]= $frow['filename'] ?>    
                                                                           <div class="file-wrapper">
                                                                              <h4 class="file-title">{{ $frow['filename'] }}</h4>
                                                                              <span class="last-modified">Last modified:  {{$frow['last_mod']}}</span>
                                                                              <a  class="download-file getdropboxlink"  data-dirname="{{ $frow['dirname'] }}" data-filename="{{ $frow['filename'] }}" href="javascript:void(0)" >
                                                                              <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                                                           </div>
                                                                           @endif
                                                                     @endforeach

                                                                  @endif
                                                               </div>
                                                            @endif   
                                                         @endforeach
                                                      @endif
                                                      <!-- bonus of each lesson -->
                                      
                                                   <?php $fk += 1;?>

                                                @endwhile
                                                <div class="files-wrapper">
                                                   <div class="file-wrapper">
                                                      <h4 class="file-title">{{ $frow2['filename'] }}</h4>
                                                      <span class="last-modified">Last modified:  {{$frow2['last_mod']}}</span>
                                                      <a  class="download-file getdropboxlink"  data-dirname="{{ $frow2['dirname'] }}" data-filename="{{ $frow2['filename'] }}" href="javascript:void(0)" >
                                                      <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                                   </div>
                                                </div>
                                             @else
                                                <div class="files-wrapper">
                                                   <div class="file-wrapper">
                                                      <h4 class="file-title">{{ $frow2['filename'] }}</h4>
                                                      <span class="last-modified">Last modified:  {{$frow2['last_mod']}}</span>
                                                      <a  class="download-file getdropboxlink"  data-dirname="{{ $frow2['dirname'] }}" data-filename="{{ $frow2['filename'] }}" href="javascript:void(0)" >
                                                      <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                                   </div>
                                                </div>
                                             @endif
                                             
                                             
                                             <?php
                                        
                                             $fk += 1;

                                          ?>
                                          @endif
                                        
                                          @endforeach
                                          
                                          <!-- bonus of each lesson -->
                                          @if (isset($dbfolder[1]) && !empty($dbfolder[1]))
                                          @foreach($dbfolder[1] as $nkey => $nfolder)
                                          @if($nfolder['parent'] == $folder['id'] && ($nfolder['foldername'] == '_Bonus' || $nfolder['foldername'] == 'Bonus')) <!--//lioncode-->
                                         
                                          <div class="files-wrapper bonus-files">
                                             <h4 class="bonus-title">{{ $nfolder['foldername'] }}</h4>
                                             <span><i class="icon-folder-open"></i>   </span>
                                             @if (isset($files[$catid][2]) && !empty($files[$catid][2]))
                                             @foreach($files[$catid][2] as $fkey => $frow)
                                             @if (strpos($frow['dirname'], $rf) !== false || strpos($frow['dirname'], $rf1) !== false && !in_array($frow['filename'],$bonus))
                                             <div class="file-wrapper">
                                                <h4 class="file-title">{{ $frow['filename'] }}</h4>
                                                <span class="last-modified">Last modified:  {{$frow['last_mod']}}</span>
                                                <a  class="download-file getdropboxlink"  data-dirname="{{ $frow['dirname'] }}" data-filename="{{ $frow['filename'] }}" href="javascript:void(0)" >
                                                <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                             </div>
                                             @endif
                                             @endforeach
                                             </ul>
                                             @endif
                                          </div>
                                          @endif
                                          @endforeach
                                          @endif
                                          <!-- bonus of each lesson -->
                                          @endif
                                          <!-- /.accordion-content -->
                                       </div>
                                       <!-- /.accordion-item -->
                                    </div>
                                    @endif
                                    @endforeach
                                    @endif
                                    <!-- last files-->
                                    @if(!$instructor_topics)
                                    @if (isset($files[$catid][0]) && !empty($files[$catid][0]))
                                    @foreach($files[$catid][0] as $key => $row)
                                    <div class="files-wrapper bonus-files">
                                       <div class="file-wrapper">
                                          <h4 class="file-title">{{ $row['filename'] }}</h4>
                                          <span class="last-modified">Last modified:  {{$row['last_mod']}}</span>
                                          <a  class="download-file getdropboxlink"  data-dirname="{{ $row['dirname'] }}" data-filename="{{ $row['filename'] }}" href="javascript:void(0)" >
                                          <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                       </div>
                                    </div>
                                    @endforeach
                                    @endif
                                    @endif
                                    <!--last files -->
                                    @endif
                                    {{--endif--}}
                                    @endforeach
                                    <!-- /.accordion-wrapper -->
                                 </div>
                                 <!-- /.acc-topic-accordion -->
                              </div>
                              @endif
                           </div>
                           @endif
                           
                           @if(!$instructor_topics)
                           <div id="elearning-tab-child-exams{{$tab}}" class="in-tab-wrapper">
                              <div class="account-text">
                                 {{-- 
                                 <h2>My exams</h2>
                                 --}}
                              </div>
                              @if(isset($newlayoutExamsEvent[$keyType])) 
                              <div class="dynamic-courses-wrapper">
                                 @foreach($newlayoutExamsEvent[$keyType] as $ek => $p)
                                 @foreach($p['exams'] as $pe)
                                 @if($p['access'])
                                 <div class="item">
                                    <div class="left">
                                       <h2 class="item-title"><a href="{{$p['slug']}}">{{$p['title']}}</a></h2>
                                       <div class="bottom">
                                          <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/marker.svg')}}" alt=""><a href="{{$p['location']['slug']}}">{{$p['location']['city']}}</a></div>
                                          <div class="duration"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/icon-calendar.svg')}}" alt="">{{$p['date']}}</div>
                                          @if($p['hours'])
                                          <div class="expire-date"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" alt="">{{$p['hours']}}h</div>
                                          @endif
                                       </div>
                                    </div>
                                    <div class="right right--no-price">
                                       <!-- Feedback 8-12 changed -->
                                       @if($pe->exstatus == 1)
                                       <a target="_blank" href="{{ url('student-summary/' . $pe->id . '/' . $currentuser->id) }}?s=1" title="{{$p['title']}}" class="btn btn--secondary btn--md">VIEW RESULT</a>
                                       @elseif($pe->islive == 1)
                                       <a target="_blank" onclick="window.open('{{ route('attempt-exam', [$pe->id]) }}', 'newwindow', 'width=1400,height=650'); return false;" title="{{$p['title']}}" class="btn btn--secondary btn--md">TAKE EXAM</a>
                                       @elseif($pe->isupcom == 1)	
                                       <a  title="{{$p['title'] }}" class="btn btn--secondary btn--md">{{ date('F j, Y', strtotime($pe->publish_time)) }}</a>
                                       @endif
                                    </div>
                                 </div>
                                 <!-- ./item -->
                                 @endif
                                 @endforeach
                                 @endforeach
                              </div>
                              <!-- ./dynamic-courses-wrapper -->
                              @endif
                           </div>
                           <div id="elearning-tab-child-certificates{{$tab}}" class="in-tab-wrapper">
                              <div class="account-text">
                                 {{--
                                 <h2>My certificates</h2>
                                 --}}
                                 @foreach($event['cert'] as $certificate)
                                 <div class="files-wrapper">
                                    <div class="file-wrapper">
                                       <h4 class="file-title"> {{$certificate->content->title}}</h4>
                                       <a  class="download-file getcertificate" target="_blank" href="/myaccount/mycertificate/{{$certificate->id}}" >
                                       <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                    </div>
                                 </div>
                                 @endforeach
                              </div>
                           </div>
                           @endif
                           </div>
                          
                          
                        </div>
                        <!-- /.inside-tabs -->
                     </div>
                     <!-- /.container -->
                  </div>
                  <?php $tab += 1; ?>
            
               @endif
               @endforeach
            </div>
           
            <!-- /#elearning-tab-child.tab-content-wrapper -->
            @endif
            <!-- /.tabs-content -->
           
         </div>
         <!-- /.tabs-wrapper -->
      </div>
      <!-- /.content-wrapper -->
      </div>
      <!-- /.section-account-tabs -->
   </section>
</main>
@endsection
@section('scripts')
<script src="{{ cdn('theme/assets/addons/dropzone/dropzone.js') }}"></script>
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
       url: 'admin/media_uploader/upload',
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
       success: function (file, response, load) {
           $('#logo_media').val(response.urls.large_thumb);
           $('#logo_media_id').val(response.media_id);
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
              
               deleteMediaPromt($(this).attr("data-dp-media-id"));
                    
            });
   
   
            $("body").on("click", ".cancelImg", function (event) {
              
               var favDialog = document.getElementById('favDialog');
             //  favDialog.close(); 
               favDialog.style.display = "none";  
               $("body").css("overflow-y", "auto")
                   
           });
      
          function deleteMediaPromt(media_id) {
      
              $.ajax({ url: "myaccount/removeavatar", type: "post",
                  data: media_id,
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
           //console.log(data);
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
<script src="https://player.vimeo.com/api/player.js"></script>
<script>
   
   var event = false;
   var videos = false;
   var videosSeen = [];
   var lastVideoSeen = -1
   var previousVideo = false;
   var previousK=false;
   var videoId;
   var eventStatistic;
   var playVi = false;
   var frame = false;
   var frameVi = [];
   var videoPlayers = [];
   var tabWatching = false;
   var previousFrame = false;
   var videosPlayed = [];
   
   function tabclick(videos,event,seen,statisticId,frame){
   

      if(previousFrame){
        
         if(playVi){
            videoPlayers[previousFrame].pause().then(function() {

         
            }).catch(function(error) {
               switch (error.name) {
                  case 'PasswordError':
                        // the video is password-protected and the viewer needs to enter the
                        // password first
                        break;

                  case 'PrivacyError':
                        // the video is private
                        break;

                  default:
                        // some other error occurred
                        break;
               }
            });
         }

      }else{
         playVi = false;
      }

      this.event = event;
      this.lastVideoSeen = seen
      this.eventStatistic = statisticId
      this.frame = frame
      previousFrame = frame;
      
      //console.log('videos seen = ', videosSeen)  
      if(frame in this.videosSeen == false){
         this.videos = videos;   
      }else{           
        //console.log(videosSeen);
        //console.log('videos seen');
        videos = videosSeen[frame];
      }

      if(frame in this.frameVi == false){
         this.frameVi[frame] = frame;
         this.videoPlayers[frame] = '';
         this.videosPlayed[frame] = [];
      }else{
       //  this.videoPlayers[frame].on('play');
      }
   
      if(!this.previousK){
   
         this.previousK = frame
         this.previousK = this.previousK.replace('{',''); 
         this.previousK = this.previousK.replace('}','');
      
      }  
     
      if(lastVideoSeen!=-1){
         
         $(".active-tab").removeClass("active-tab");
         $this = $('#'+videos[lastVideoSeen]['tab']).parent().parent().children('h2')
         $this.click();
        
      }
     
      if(seen != -1){
         
         if(tabWatching != false){
            document.getElementById(tabWatching).classList.remove('isWatching')
         }

         previousVideo = videos[seen]['tab'];
         tabWatching = videos[seen]['tab'];

         document.getElementById(previousVideo).classList.add('isWatching')
   
         let vimeoID ='"{ video'+videos[seen]['lesson_id'] + frame + '}"';
         var cvl = document.getElementById(this.frameVi[this.frame]).cloneNode(true);;
         cvl = document.getElementById(this.frameVi[this.frame]).setAttribute('id',vimeoID);
   
         $('#courses-video').append(cvl)
         
         this.previousK = vimeoID;
         this.frameVi[this.frame] = this.previousK;
        
         //videoPlayers[frame] = new Vimeo.Player(vimeoID);
 
        // if(videosPlayed[frame].includes(seen) == false){ 

            //console.log('frame = ', frame)
           // console.log(videosPlayed)

            videoPlayers[frame] = new Vimeo.Player(vimeoID); 
          //  videosPlayed[frame].push(parseInt(seen)); 
         
       //  }

         videoPlayers[frame].loadVideo(seen).then(function(id) {
            
            videoId = id
            videoPlayers[frame].setCurrentTime(videos[id]['stop_time'])
            
         }).catch(function(error) {

            switch (error.name) {
               case 'TypeError':
                     // the id was not a number
                  //   console.log('edww');
                     break;
   
               case 'PasswordError':
                     // the video is password-protected and the viewer needs to enter the
                     // password first
                     break;
   
               case 'PrivacyError':
                     // the video is password-protected or private
                     break;
   
               default:
                     // some other error occurred
                     break;
            }
         });

      }

      this.videoPlayers[this.frame].on('play', function(e) {

         if(!playVi){
            videoPlayers[frame].setCurrentTime(videos[videoId]['stop_time'])

         }
         playVi = true;
  
      });
      
      this.videoPlayers[this.frame].on('pause', function(e) {
        
         @if(!$instructor_topics)
            
            if(playVi){
               playVi = false;
               videos[videoId]['stop_time'] = e['seconds'];
               videos[videoId]['percentMinutes'] = e['percent'];     
      
               if(e['percent'] >= 0.8){
                     videos[videoId]['seen'] = 1;
               }
              // console.log('edww');
            //   this.videoPlayers[this.frame].off('play');;
               $.ajax({
                  type: 'PUT',
                  url: '/elearning/save',
                  data:{'videos':videos,'event_statistic':eventStatistic,'lastVideoSeen':videoId,'event':event},
                  success: function(data) {    
                        if(!data['loged_in']){
                           notLogin(data)
                        }else{
                           videosSeen[frame] = data['videos'];      
                        }
                        //playVi = true;
                    
                  }
               });
            }

         @endif
   
      });
    
      this.videoPlayers[this.frame].on('progress', function(e) {
      
         @if(!$instructor_topics)
         
            if(e['percent'] >= 0.8){
               if(videos[videoId]['seen'] == 0){
                  
                  videos[videoId]['stop_time'] = e['seconds'];
                  videos[videoId]['percentMinutes'] = e['percent'];
                  videos[videoId]['seen'] = 1;
                  
                  document.getElementById(previousVideo).classList.add('watched')
                  document.getElementById('play-image-account'+videos[videoId]['lesson_id']).setAttribute('src',"{{cdn('/theme/assets/images/icons/check_lesson.svg')}}")
                          

                  $.ajax({
                     type: 'PUT',
                     url: '/elearning/save',
                     data:{'videos':videos,'event_statistic':eventStatistic,'lastVideoSeen':videoId,'event':event},
                     success: function(data) {  
                        if(!data['loged_in']){
                           notLogin(data)
                        }else{
                           videosSeen[frame] = data['videos'];    
                           checkForExam(data['exam_access'])
                        }  
                       
                     }
                  });
            
         
               }
            }
         
         @endif
      
      });
   
      this.videoPlayers[this.frame].on('fullscreenchange', function(e) {
         window.focus()
      });
   
   
   }
   
   function play_video(video,playingVideo,vk,lesson){
   
      if(previousVideo !==false){
         document.getElementById(previousVideo).classList.remove('isWatching')
      
      }
              
      document.getElementById(playingVideo).classList.add('isWatching')
      tabWatching = playingVideo;
      previousVideo = playingVideo;

      vk = vk.replace('{',''); 
      vk = vk.replace('}',''); 
      let vimeoID ='"{'+ vk + this.frame + '}"';
         
      var cvl = document.getElementById(previousK).cloneNode(true);;
      cvl = document.getElementById(previousK).setAttribute('id',vimeoID);

      $('#courses-video').append(cvl)
      this.previousK = vimeoID;
      this.frameVi[this.frame] = this.previousK;
      
      video = video.split('/')
      video = video[4].split('?')[0]

      this.videoPlayers[frame] = new Vimeo.Player(vimeoID);

      videoPlayers[frame].loadVideo(video).then(function(id) {
     
         this.videoId = id
         this.videoPlayers[this.frame].setCurrentTime(videos[id]['stop_time'])

      }).catch(function(error) {
         switch (error.name) {
            case 'TypeError':
                  // the id was not a number
                  break;

            case 'PasswordError':
                  // the video is password-protected and the viewer needs to enter the
                  // password first
                  break;

            case 'PrivacyError':
                  // the video is password-protected or private
                  break;

            default:
                  // some other error occurred
                  break;
         }
      });
   
   
       
   }
   
   @if(!$instructor_topics)
  // console.log('olay = ', this.playVi)
  // if(playVi){
      window.onbeforeunload = function (ev) {
   
         this.videoPlayers[this.frame].pause().then(function() {

         
         }).catch(function(error) {
            switch (error.name) {
               case 'PasswordError':
                     // the video is password-protected and the viewer needs to enter the
                     // password first
                     break;

               case 'PrivacyError':
                     // the video is private
                     break;

               default:
                     // some other error occurred
                     break;
            }
         });

      };

  // }
   @endif
   
   document.body.onkeydown= function(e){


      if(e.keyCode == 32){

         if(this.playVi){

            this.playVi = false;

            videoPlayers[frame].pause().then(function() {

            }).catch(function(error) {
               switch (error.name) {
                  case 'PasswordError':
                        // the video is password-protected and the viewer needs to enter the
                        // password first
                        break;

                  case 'PrivacyError':
                        // the video is private
                        break;

                  default:
                        // some other error occurred
                        break;
               }
            });

         }else{
            this.playVi = true;

            videoPlayers[frame].play().then(function() {
            // the video was paused
            }).catch(function(error) {
               switch (error.name) {
                  case 'PasswordError':
                        // the video is password-protected and the viewer needs to enter the
                        // password first
                        break;

                  case 'PrivacyError':
                        // the video is private
                        break;

                  default:
                        // some other error occurred
                        break;
               }
            });

         

         e.preventDefault();

         }

      }   
   }
   
</script>

<script>

   function notLogin(data){
      let p = ''
      p = `<img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-success-alert.svg')}}" alt="Info Alert">` + data['message'];         
      $('#message').append(p);
      var favDialog = document.getElementById('favDialog1');
      favDialog.style.display = "block";
      $("body").css("overflow-y", "hidden")

      setTimeout( function(){
            window.location.replace(data['redirect']);
      }, 3000 );

   }

</script>


<script>

   function checkForExam(examAccess){
     
      if(examAccess){

         var d = new Date();
         d.setTime(d.getTime() + (1*24*60*60*1000));
         var expires = "expires="+ d.toUTCString();
         document.cookie = 'examMessage-' + eventStatistic + "=" + 'showmessage' + ";" + expires + ";path=/";

         var favDialog = document.getElementById('examDialog');
         favDialog.style.display = "block";
         $("body").css("overflow-y", "hidden")

         videoPlayers[frame].pause().then(function() {

         }).catch(function(error) {
            switch (error.name) {
               case 'PasswordError':
                     // the video is password-protected and the viewer needs to enter the
                     // password first
                     break;

               case 'PrivacyError':
                     // the video is private
                     break;

               default:
                     // some other error occurred
                     break;
            }
         });

      }
      
   }

</script>


<script>

   $("#close-exam-dialog").click(function(){

      var favDialog = document.getElementById('examDialog');
         favDialog.style.display = "none";
         $("body").css("overflow-y", "auto")

      videoPlayers[frame].play().then(function() {
            // the video was paused
            }).catch(function(error) {
               switch (error.name) {
                  case 'PasswordError':
                        // the video is password-protected and the viewer needs to enter the
                        // password first
                        break;

                  case 'PrivacyError':
                        // the video is private
                        break;

                  default:
                        // some other error occurred
                        break;
               }
            });
   })


   $(".go-to-account").click(function(){
      window.location.replace('/myaccount');
   })




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

      @if("{{ old('countryCode') }}")
         
         $("#selectCountry").val("{{ old('countryCode',$currentuser->country_code) }}").change();
      
      @endif

   });

</script>

@stop

