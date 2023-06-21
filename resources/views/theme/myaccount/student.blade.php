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
                     //dd($user['image']);
                     ?>
                  <img loading="lazy" id="user-img-up" width="20" height="20" src="{{cdn($img_src)}}" onerror="this.src='{{cdn('/theme/assets/images/icons/user-circle-placeholder.svg')}}'" title="{{ $currentuser['firstname'] }} {{ $currentuser['lastname'] }}" alt="{{ $currentuser['firstname'] }} {{ $currentuser['lastname'] }}"/>
                  @else
                  <img loading="lazy" id="user-img-up" width="20" height="20" src="{{cdn('/theme/assets/images/icons/user-circle-placeholder.svg')}}" alt="user-circle" title="user-circle"/>
                  @endif
               </div>
               <div class="account-hero-info">
                  <h2>{{ $currentuser['firstname'] }} {{ $currentuser['lastname'] }}</h2>
                  <ul>
                     @if($currentuser['kc_id'] != '')
                     <li>{{ $currentuser['kc_id'] }}</li>
                     @endif
                     @if($currentuser['partner_id'])
                     <li>, DR-{{ $currentuser['partner_id'] }}</li>
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
               <p><img loading="lazy" src="{{cdn('theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert" width="24" height="24" title="Info Alert">Do you really want to remove your profile picture?</p>
               <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert" width="22" height="22" title="Closed Alert"/></a>
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
      <div id="share-twitter-modal" hidden>


            <div class="alert-wrapper">
                <div class="alert-inner">
                    <p class="message"></p>
                </div>
                <button class="btn btn--sm close-btn" style="margin-top:1.5rem">ok </button>
            </div>

            <!-- /.alert-outer -->

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
         <div class="tab-controls subscription-account">
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
                              <img loading="lazy" id="user-img" src="{{cdn($img_src)}}" onerror="this.src='{{cdn('/theme/assets/images/icons/user-circle-placeholder.svg')}}'" alt="{{ $currentuser['firstname'] }} {{ $currentuser['lastname'] }}" title="{{ $currentuser['firstname'] }} {{ $currentuser['lastname'] }}" width="230" height="230"/>
                              @else
                              <img loading="lazy" id="user-img" src="{{cdn('/theme/assets/images/icons/user-circle-placeholder.svg')}}" alt="user-circle" title="user-circle" width="233" height="233"/>
                              @endif
                           </div>
                           <div class="actions">
                              <ul id='user-media'>
                                 <li class="change-photo"><a id="logoDropzone" class="custFieldMediaDrop dz-message" href="javascript:void(0)"><img loading="lazy" src="{{cdn('/theme/assets/images/icons/icon-edit.svg')}}" alt="Change photo" title="Change photo" width="16" height="16"/><span>Change photo</span>
                                    </a>
                                 </li>
                                 @if(isset($user['image']))
                                 <li class="remove-photo delete_media"><a data-dp-media-id="{{ $user['image']['id'] }}" href="javascript:void(0)"><img loading="lazy" src="{{cdn('/theme/assets/images/icons/icon-remove.svg')}}" alt="Remove photo" title="Remove photo" width="10" height="10"/><span>Remove photo</span></a></li>
                                 @endif
                              </ul>
                           </div>
                        </div>
                        <div class="download-area">
                           <div class="download-area-inner">
                              <a id="gdpr-download" href="javascript:void(0)"><span><img loading="lazy" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" alt="Remove photo" title="Remove photo" width="16" height="16"/>Download all my data.</span></a>
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
                                 {{--<li><a  href="#subscriptions" >Payment</a></li>--}}
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
                                          <label>My first name is <span>(*)</span></label>
                                          <div class="input-safe-wrapper">
                                             <input class="required" type="text" name="firstname" id="firstname"  value="{{ old('firstname', $currentuser['firstname']) }}" >
                                          </div>
                                       </div>
                                       <div class="col12">
                                          <label>My last name is: <span>(*)</span></label>
                                          <div class="input-safe-wrapper">
                                             <input class="required"  type="text" name="lastname" id="lastname" value="{{ old('lastname', $currentuser['lastname']) }}" >
                                          </div>
                                       </div>
                                       <div class="col12">
                                          <?php $birthday = $currentuser['birthday'] ? date('j F Y',strtotime($currentuser['birthday'])) : ""?>
                                          <label>Date of birth:</label>


                                          <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                            </div>
                                            <input id="birthday" type="text" class="form-control datepicker" name="birthday" value="{{$birthday}}">
                                        </div>
                                       </div>
                                       <div class="col12">
                                          <label>Company/Employer:</label>
                                          <div class="input-safe-wrapper">
                                             <input  type="text" name="company" id="company" value="{{ old('company', $currentuser['company']) }}" >
                                          </div>
                                       </div>
                                       <div class="col12">
                                          <label>Occupation:</label>
                                          <div class="input-safe-wrapper">
                                             <input name="job_title" id="job_title" value="{{ old('job_title', $currentuser['job_title']) }}" >
                                          </div>
                                       </div>
                                       <div class="col12">
                                          <label>Town / City:</label>
                                          <div class="input-safe-wrapper">
                                             <input name="city" id="city" value="{{ old('city', $currentuser['city']) }}" >
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

                                          <label>Mobile phone: <span>*</span></label>
                                          <div class="input-safe-wrapper is-flex full-width">
                                             <div class="col4">
                                                <select name="country_code" id="selectCountry" class="form-control valid" aria-required="true" aria-invalid="false">
                                                <option value="213" label="Algeria (+213)">Algeria </option>
											               <option value="244" label="Angola (+244)">Angola </option>
											               <option value="229" label="Benin (+229)">Benin </option>
											               <option value="267" label="Botswana (+267)">Botswana </option>
											               <option value="226" label="Burkina Faso (+226)">Burkina Faso </option>
											               <option value="257" label="Burundi (+257)">Burundi </option>
											               <option value="237" label="Cameroon (+237)">Cameroon </option>
											               <option value="238" label="Cape Verde (+238)">Cape Verde </option>
											               <option value="236" label="Central African Republic (+236)">Central African Republic </option>
											               <option value="269" label="Comoros (+269)">Comoros </option>
											               <option value="242" label="Congo - Brazzaville (+242)">Congo - Brazzaville </option>
											               <option value="253" label="Djibouti (+253)">Djibouti </option>
											               <option value="20" label="Egypt (+20)">Egypt </option>
											               <option value="240" label="Equatorial Guinea (+240)">Equatorial Guinea</option>
											               <option value="291" label="Eritrea (+291)">Eritrea </option>
											               <option value="251" label="Ethiopia (+251)">Ethiopia </option>
											               <option value="241" label="Gabon">Gabon</option>
											               <option value="220" label="Gambia (+220)">Gambia </option>
											               <option value="233" label="Ghana (+233)">Ghana </option>
											               <option value="224" label="Guinea (+224)">Guinea </option>
											               <option value="245" label="Guinea-Bissau (+245)">Guinea-Bissau </option>
											               <option value="254" label="Kenya (+254)">Kenya</option>
											               <option value="266" label="Lesotho (+266)">Lesotho</option>
											               <option value="231" label="Liberia (+231)">Liberia</option>
											               <option value="218" label="Libya (+218)">Libya</option>
											               <option value="261" label="Madagascar (+261)">Madagascar</option>
											               <option value="265" label="Malawi (+265)">Malawi</option>
											               <option value="223" label="Mali (+223)">Mali</option>
											               <option value="222" label="Mauritania (+222)">Mauritania</option>
											               <option value="262" label="Mayotte (+262)">Mayotte </option>
											               <option value="212" label="Morocco (+212)">Morocco</option>
											               <option value="258" label="Mozambique (+258)">Mozambique</option>
											               <option value="264" label="Namibia (+264)">Namibia</option>
											               <option value="227" label="Niger (+227)">Niger</option>
											               <option value="234" label="Nigeria (+234)">Nigeria</option>
											               <option value="250" label="Rwanda (+250)">Rwanda</option>
											               <option value="262" label="Réunion (+262)">Réunion</option>
											               <option value="290" label="Saint Helena (+290)">Saint Helena</option>
											               <option value="221" label="Senegal (+221)">Senegal</option>
											               <option value="248" label="Seychelles (+248)">Seychelles</option>
											               <option value="232" label="Sierra Leone (+232)">Sierra Leone</option>
											               <option value="252" label="Somalia (+252)">Somalia</option>
											               <option value="27" label="South Africa (+27)">South Africa</option>
											               <option value="249" label="Sudan (+249)">Sudan</option>
											               <option value="268" label="Swaziland (+268)">Swaziland</option>
											               <option value="239" label="São Tomé and Príncipe (+239)">São Tomé and Príncipe</option>
											               <option value="228" label="Togo (+228)">Togo</option>
											               <option value="216" label="Tunisia (+216)">Tunisia</option>
											               <option value="256" label="Uganda (+256)">Uganda</option>
											               <option value="260" label="Zambia (+260)">Zambia</option>
											               <option value="263" label="Zimbabwe (+263)">Zimbabwe</option>
											               <option value="1264" label="Anguilla (+1264)">Anguilla </option>
											               <option value="595" label="Paraguay (+595)">Paraguay </option>
											               <option value="51" label="Peru (+51)">Peru </option>
											               <option value="1268" label="Antigua and Barbuda (+1268)">Antigua and Barbuda </option>
											               <option value="54" label="Argentina (+54)">Argentina </option>
											               <option value="297" label="Aruba (+297)">Aruba </option>
											               <option value="1242" label="Bahamas (+1242)">Bahamas </option>
											               <option value="1246" label="Barbados (+1246)">Barbados </option>
											               <option value="501" label="Belize (+501)">Belize </option>
											               <option value="1441" label="Bermuda (+1441)">Bermuda </option>
											               <option value="591" label="Bolivia (+591)">Bolivia </option>
											               <option value="55" label="Brazil (+55)">Brazil</option>
											               <option value="84" label="British Virgin Islands (+84)">British Virgin Islands</option>
											               <option value="1" label="Canada (+1)">Canada </option>
											               <option value="1345" label="Cayman Islands (+1345)">Cayman Islands </option>
											               <option value="56" label="Chile (+56)">Chile </option>
											               <option value="57" label="Colombia (+57)">Colombia </option>
											               <option value="506" label="Costa Rica (+506)">Costa Rica </option>
											               <option value="53" label="Cuba (+53)">Cuba </option>
											               <option value="90392" label="Northen Cyprus (+90392)">Northen Cyprus </option>
											               <option value="1809" label="Dominica (+1809)">Dominica </option>
											               <option value="1809" label="Dominican Republic (+1809)">Dominican Republic </option>
											               <option value="593" label="Ecuador (+593)">Ecuador </option>
											               <option value="503" label="El Salvador (+503)">El Salvador </option>
											               <option value="500" label="Falkland Islands (+500)">Falkland Islands</option>
											               <option value="594" label="French Guiana (+594)">French Guiana </option>
											               <option value="299" label="Greenland (+299)">Greenland </option>
											               <option value="1473" label="Grenada (+1473)">Grenada </option>
											               <option value="590" label="Guadeloupe (+590)">Guadeloupe </option>
											               <option value="502" label="Guatemala (+502)">Guatemala </option>
											               <option value="592" label="Guyana (+592)">Guyana </option>
											               <option value="509" label="Haiti (+509)">Haiti </option>
											               <option value="504" label="Honduras (+504)">Honduras </option>
											               <option value="1876" label="Jamaica (+1876)">Jamaica</option>
											               <option value="596" label="Martinique (+596)">Martinique</option>
											               <option value="52" label="Mexico (+52)">Mexico</option>
											               <option value="1664" label="Montserrat (+1664)">Montserrat</option>
											               <option value="AN" label="Netherlands Antilles">Netherlands Antilles</option>
											               <option value="505" label="Nicaragua (+505)">Nicaragua</option>
											               <option value="507" label="Panama (+507)">Panama</option>
											               <option value="95" label="Myanmar (+95)">Myanmar</option>
											               <option value="1787" label="Puerto Rico (+1787)">Puerto Rico</option>
											               <option value="1869" label="Saint Kitts and Nevis (+1869)">Saint Kitts and Nevis</option>
											               <option value="597" label="Suriname (+597)">Suriname</option>
											               <option value="1868" label="Trinidad and Tobago (+1868)">Trinidad and Tobago</option>
											               <option value="1649" label="Turks and Caicos Islands (+1649)">Turks and Caicos Islands</option>
											               <option value="84" label="U.S. Virgin Islands (+84)">U.S. Virgin Islands</option>
											               <option value="1" label="United States (+1)">United States</option>
											               <option value="598" label="Uruguay (+598)">Uruguay</option>
											               <option value="58" label="Venezuela (+58)">Venezuela</option>
											               <option value="374" label="Armenia (+374)">Armenia </option>
											               <option value="994" label="Azerbaijan (+994)">Azerbaijan</option>
											               <option value="973" label="Bahrain (+973)">Bahrain </option>
											               <option value="880" label="Bangladesh  (+880)">Bangladesh</option>
											               <option value="975" label="Bhutan (+975)">Bhutan </option>
											               <option value="673" label="Brunei (+673)">Brunei </option>
											               <option value="63" label="Philippines (+63)">Philippines  </option>
											               <option value="855" label="Cambodia (+855)">Cambodia </option>
											               <option value="86" label="China (+86)">China </option>
											               <option value="7880" label="Georgia (+7880)">Georgia </option>
											               <option value="852" label="Hong Kong SAR China (+852)">Hong Kong SAR China</option>
											               <option value="91" label="India (+91)">India</option>
											               <option value="62" label="Indonesia (+62)">Indonesia</option>
											               <option value="98" label="Iran (+98)">Iran</option>
											               <option value="964" label="Iraq (+964)">Iraq</option>
											               <option value="972" label="Israel (+972)">Israel</option>
											               <option value="81" label="Japan (+81)">Japan</option>
											               <option value="962" label="Jordan (+962)">Jordan</option>
											               <option value="7" label="Kazakhstan (+7)">Kazakhstan</option>
											               <option value="965" label="Kuwait (+965)">Kuwait</option>
											               <option value="856" label="Laos (+856)">Laos</option>
											               <option value="961" label="Lebanon (+961)">Lebanon</option>
											               <option value="853" label="Macau SAR China (+853)">Macau SAR China</option>
											               <option value="60" label="Malaysia (+60)">Malaysia</option>
											               <option value="960" label="Maldives (+960)">Maldives</option>
											               <option value="976" label="Mongolia (+976)">Mongolia</option>
											               <option value="977" label="Nepal (+977)">Nepal</option>
											               <option value="850" label="North Korea (+850)">North Korea</option>
											               <option value="968" label="Oman (+968)">Oman</option>
											               <option value="PK" label="Pakistan">Pakistan</option>
											               <option value="996" label="Kyrgyzstan (+996)"> Kyrgyzstan </option>
											               <option value="974" label="Qatar (+974)">Qatar</option>
											               <option value="966" label="Saudi Arabia (+966)">Saudi Arabia</option>
											               <option value="65" label="Singapore (+65)">Singapore</option>
											               <option value="82" label="South Korea (+82)">South Korea</option>
											               <option value="94" label="Sri Lanka (+94)">Sri Lanka</option>
											               <option value="963" label="Syria (+963)">Syria</option>
											               <option value="886" label="Taiwan (+886)">Taiwan</option>
											               <option value="389" label="Fyrom (+389)">Fyrom</option>
											               <option value="7" label="Tajikistan (+7)">Tajikistan</option>
											               <option value="66" label="Thailand (+6)">Thailand</option>
											               <option value="90" label="Turkey (+90)">Turkey</option>
											               <option value="993" label="Turkmenistan (+993)">Turkmenistan</option>
											               <option value="971" label="United Arab Emirates (+971)">United Arab Emirates</option>
											               <option value="7" label="Uzbekistan (+7)">Uzbekistan</option>
											               <option value="84" label="Vietnam (+84)">Vietnam</option>
											               <option value="967" label="Yemen (+967)">Yemen</option>
											               <option value="376" label="Andorra (+376)">Andorra </option>
											               <option value="43" label="Austria (+43)">Austria </option>
											               <option value="375" label="Belarus (+375)">Belarus </option>
											               <option value="32" label="Belgium (+32)">Belgium </option>
											               <option value="387" label="Bosnia and Herzegovina (+387)">Bosnia and Herzegovina </option>
											               <option value="359" label="Bulgaria (+359)">Bulgaria </option>
											               <option value="385" label="Croatia (+385)">Croatia </option>
											               <option value="357" label="Cyprus (+357)">Cyprus </option>
											               <option value="420" label="Czech Republic (+420)">Czech Republic </option>
											               <option value="45" label="Denmark (+45)">Denmark</option>
											               <option value="372" label="Estonia (+372)">Estonia </option>
											               <option value="298" label="Faroe Islands  (+298)">Faroe Islands</option>
											               <option value="358" label="Finland (+358)">Finland (+358)</option>
											               <option value="33" label="France (+33)">France </option>
											               <option value="49" label="Germany (+49)">Germany </option>
											               <option value="350" label="Gibraltar (+350)">Gibraltar</option>
											               <option selected value="30" label="Greece (+30)">Greece</option>
											               <option value="36" label="Hungary (+36)">Hungary</option>
											               <option value="354" label="Iceland (+354)">Iceland</option>
											               <option value="353" label="Ireland (+353)">Ireland</option>
											               <option value="39" label="Italy (+39)">Italy</option>
											               <option value="371" label="Latvia (+371)">Latvia</option>
											               <option value="417" label="Liechtenstein (+417)">Liechtenstein</option>
											               <option value="1785" label="Saint Lucia (+1758)">Saint Lucia</option>
											               <option value="370" label="Lithuania (+370)">Lithuania</option>
											               <option value="352" label="Luxembourg (+352)">Luxembourg</option>
											               <option value="356" label="Malta (+356)">Malta</option>
											               <option value="373" label="Moldova (+373)">Moldova</option>
											               <option value="377" label="Monaco (+377)">Monaco</option>
											               <option value="31" label="Netherlands (+31)">Netherlands</option>
											               <option value="47" label="Norway (+47)">Norway</option>
											               <option value="48" label="Poland (+48)">Poland</option>
											               <option value="351" label="Portugal (+351)">Portugal</option>
											               <option value="40" label="Romania (+40)">Romania</option>
											               <option value="7" label="Russia (+7)">Russia</option>
											               <option value="378" label="San Marino (+378)">San Marino</option>
											               <option value="381" label="Serbia and Montenegro (381)">Serbia and Montenegro</option>
											               <option value="421" label="Slovakia (+421)">Slovakia</option>
											               <option value="386" label="Slovenia (+386)">Slovenia</option>
											               <option value="34" label="Spain (+34)">Spain</option>
											               <option value="46" label="Sweden (+46)">Sweden</option>
											               <option value="41" label="Switzerland (+41)">Switzerland</option>
											               <option value="380" label="Ukraine (+380)">Ukraine</option>
											               <option value="44" label="United Kingdom (+44)">United Kingdom</option>
											               <option value="379" label="Vatican City(+379)">Vatican City</option>
											               <option value="61" label="Australia (+61)">Australia </option>
											               <option value="682" label="Cook Islands (+682)">Cook Islands</option>
											               <option value="679" label="Fiji (+679)">Fiji</option>
											               <option value="670" label="East Timor (+670)">East Timor</option>
											               <option value="689" label="French Polynesia (+689)">French Polynesia</option>
											               <option value="241" label="French Southern Territories (+241)">French Southern Territories</option>
											               <option value="671" label="Guam (+671)">Guam</option>
											               <option value="686" label="Kiribati (+686)">Kiribati</option>
											               <option value="692" label="Marshall Islands (+692)">Marshall Islands</option>
											               <option value="691" label="Micronesia (+691)">Micronesia</option>
											               <option value="674" label="Nauru (+674)">Nauru</option>
											               <option value="687" label="New Caledonia (+687)">New Caledonia</option>
											               <option value="64" label="New Zealand (+64)">New Zealand</option>
											               <option value="683" label="Niue (+683)">Niue</option>
											               <option value="672" label="Norfolk Island (+672)">Norfolk Island</option>
											               <option value="680" label="Palau (+680)">Palau</option>
											               <option value="675" label="Papua New Guinea (+675)">Papua New Guinea</option>
											               <option value="677" label="Solomon Islands (+677)">Solomon Islands</option>
											               <option value="676" label="Tonga (+676)">Tonga</option>
											               <option value="688" label="Tuvalu (+688)">Tuvalu</option>
											               <option value="678" label="Vanuatu (+678)">Vanuatu</option>
											               <option value="681" label="Wallis and Futuna (+681)">Wallis and Futuna</option>
                                                </select>
                                             </div>
                                             <div class="col8">
                                                <input class="required" onkeyup="checkPhoneNumber(this)" type="number" name="mobile" id="mobile" value="{{{ old('mobile', $currentuser['mobile']) }}}" >
                                                <input type="hidden" name="mobileCheck" id="mobileCheck" value="{{{ old('mobile', '+'.$currentuser['country_code'].$currentuser['mobile']) }}}">
                                                <label id="mobile-error1" style="display:none" class="error error-mobile" for="mobile"></label>

                                             </div>
                                          </div>


                                       </div>
                                       {{--
                                       <div class="checkbox-row">
                                          <div class="custom-checkbox">
                                             <input type="checkbox" id="receive-emails" name="receive-emails" value="accept" checked="checked">
                                             <span></span>
                                          </div>
                                          <label for="receive-emails">I want to receive emails from Knowcrunch.</label>
                                       </div>
                                       <div class="checkbox-row">
                                          <div class="custom-checkbox">
                                             <input type="checkbox" id="receive-messages" name="receive-messages" value="accept" checked="checked">
                                             <span></span>
                                          </div>
                                          <label for="receive-messages">I want to receive important mobile messages from Knowcrunch.</label>
                                       </div>
                                       --}}
                                       <div class="form-submit-area">
                                          <button id="update-personal-info" type="button" class="btn btn--md btn--secondary">Update</button>
                                       </div>
                                    </form>
                                 </div>
                              </div>

                              <div id="billing-data" class="in-tab-wrapper">

                                 <div class="form-wrapper profile-form-wrapper">

                                    <form action="myaccount/updrecbill" method="post" id="billing-data-form">
                                    @csrf
                                       <div >
                                          <?php $billingDetails = json_decode($user['receipt_details'],true);?>




                                          <div class="col12">
                                             <label>Company or full name:</label>
                                             <div class="input-safe-wrapper">
                                                <input  type="text" id="billname" name="billname" value="{{ isset($billingDetails['billname']) ? $billingDetails['billname'] : '' }}" >
                                             </div>
                                          </div>

                                          <div class="col12">
                                             <label>VAT or tax ID:</label>
                                             <div class="input-safe-wrapper">
                                                <input  type="text" id="billafm" name="billafm" value="{{ isset($billingDetails['billafm']) ? $billingDetails['billafm'] : '' }}" >
                                             </div>
                                          </div>

                                          <div class="col12">
                                             <label>Street:</label>
                                             <div class="input-safe-wrapper">
                                                <input  type="text" id="billaddress" name="billaddress" value="{{ isset($billingDetails['billaddress']) ? $billingDetails['billaddress'] : '' }}" >
                                             </div>
                                          </div>

                                          <div class="col12">
                                             <label>Street number:</label>
                                             <div class="input-safe-wrapper">
                                                <input  type="number" id="billaddressnum" name="billaddressnum" value="{{ isset($billingDetails['billaddressnum']) ? $billingDetails['billaddressnum'] : '' }}" >
                                             </div>
                                          </div>


                                          <div class="col12">
                                             <label>Town/city:</label>
                                             <div class="input-safe-wrapper">
                                                <input  type="text" id="billcity" name="billcity" value="{{ isset($billingDetails['billcity']) ? $billingDetails['billcity'] : '' }}" >
                                             </div>
                                          </div>

                                          <div class="col12">
                                             <label>Postcode:</label>
                                             <div class="input-safe-wrapper">
                                                <input  type="number" id="billpostcode" name="billpostcode" value="{{ isset($billingDetails['billpostcode']) ? $billingDetails['billpostcode'] : '' }}" >
                                             </div>
                                          </div>

                                          <div class="col12">
                                             <label>State:</label>
                                             <div class="input-safe-wrapper">
                                                <input  type="text" id="billstate" name="billstate" value="{{ isset($billingDetails['billstate']) ? $billingDetails['billstate'] : '' }}" >
                                             </div>
                                          </div>

                                          <div class="col12">
                                             <label>Country:</label>
                                             <div class="input-safe-wrapper">
                                                <input  type="text" id="billcountry" name="billcountry" value="{{ isset($billingDetails['billcountry']) ? $billingDetails['billcountry'] : '' }}" >
                                             </div>
                                          </div>

                                          <div class="col12">
                                             <label>Bill email:</label>
                                             <div class="input-safe-wrapper">
                                                <input  type="text" id="billemail" name="billemail" value="{{ isset($billingDetails['billemail']) ? $billingDetails['billemail'] : '' }}" >
                                             </div>
                                          </div>

                                          <div class="form-submit-area">
                                             <button id="save-receipt-data" type="button" class="btn btn--md btn--secondary">Update</button>
                                          </div>

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
            <div id="courses" class="tab-content-wrapper active-tab new-tab-content-wrapper">

                @php
                $has_plan = false;
                $availablePlans = [];
                $showPlan = null;


                foreach($plans as $key => $plan){
                    foreach($plan->events as $ev){
                        $availablePlans[$ev->id] = $ev;
                    }

                    if($plan->events->first()){
                        $has_plan = true;
                    }
                }

                if(isset($availablePlans[2304])){
                    $showPlan = $availablePlans[2304];
                }else if(isset($availablePlans[1350])){
                    $showPlan = $availablePlans[1350];
                }


                @endphp


               @if($showPlan != null && $has_plan && !$masterClassAccess && $subscriptionAccess)
                    <div class="subscription-div">
                        <div class="col12 dynamic-courses-wrapper subscription-card">
                                <div class="item">
                                    <h2>E-Learning Masterclass Digital & Social Media Marketing Annual Access</h2>


                                    <div class="bottom">
                                    <div class="left">
                                        <div class="location">

                                        You are now eligible for one year access to our award winning course. Subscribe for €199/year and get access to all updated videos & files. Renew every year if you want.

                                        </div>
                                    </div>
                                    <div class="right subscription-button">

                                    @foreach($plans as $key => $plan)
                                        <a href="/myaccount/subscription/{{$showPlan['title']}}/{{ $plan->name }}" class="btn btn--primary btn--lg">GET ANNUAL ACCESS NOW</a>
                                    @endforeach
                                    </div>
                                    </div>

                                </div>

                            </div>
                        </div>

               @endif





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
                  @endif
               @endif


               <div class="container container-new">
                  <div class="row">






                     <?php $tab = 0; ?>
                     <?php //dd($events); ?>

                     @if(isset($events) && count($events) > 0)

                        @foreach($events as $keyType => $event)
                        {{--@if($event['view_tpl'] != 'elearning_free' && $event['view_tpl'] != 'elearning_event')--}}
                        @if($event['delivery'] != 143)
                        <div class="col12 dynamic-courses-wrapper dynamic-courses-wrapper--style2 @if(isset($event['paid']) && $event['paid'] == 0){{'unpaid'}}@endif">
                            <div class="item">
                                <h2>{{ $event['title'] }}</h2>
                                <div class="inside-tabs">
                                    <div class="tabs-ctrl">
                                        <ul>
                                            {{--<li class="active"><a href="#c-info-inner{{$tab}}">Info</a></li>--}}
                                            @if(isset($event['topics']) && count($event['topics']) > 0)<li class="active"><a href="#c-shedule-inner{{$tab}}">Schedule </a></li>@endif
                                            <?php  $fa = strtotime(date('Y-m-d',strtotime($event['release_date_files']))) >= strtotime(date('Y-m-d'))?>
                                            {{--@if(!$instructor && isset($event['category'][0]['dropbox']) && count($event['category'][0]['dropbox']) != 0 &&--}}
                                            @if(isset($event['dropbox']) && count($event['dropbox']) != 0 &&
                                            $event['status'] == 3 &&  $fa)
                                            <li><a href="#c-files-inner{{$tab}}">Files</a></li>
                                            @endif
                                            @if(isset($event['exams']) && count($event['exams']) >0 )
                                            <li><a href="#c-exams-inner{{$tab}}">Exams</a></li>
                                            @endif
                                            @if(count($event['certs']) > 0)
                                            <li><a href="#c-cert-inner{{$tab}}">Certificate</a></li>
                                            @endif
                                            {{--
                                            <li><a href="#c-subs-inner{{$tab}}">Subscription</a></li>
                                            --}}
                                        </ul>
                                    </div>
                                    <div class="inside-tabs-wrapper">
                                        {{--<div id="c-info-inner{{$tab}}" class="in-tab-wrapper" style="display: block;">
                                            <div class="bottom">
                                            <?php

                                                /*$summaryDate = '';
                                                foreach($event['summary1'] as $summary){
                                                    if($summary['section'] == 'date'){
                                                        $summaryDate = $summary['title'];
                                                    }
                                                }*/
                                                ?>
                                            @if(isset($event['summaryDate']))<div class="duration"><img loading="lazy" class="replace-with-svg resp-img" onerror="this.src='{{cdn('/theme/assets/images/icons/Duration_Hours.svg')}}'" src="{{cdn($event['summaryDate_icon'])}}" title="summaryDate_icon" alt="summaryDate_icon">{{$event['summaryDate']}}</div>@endif
                                            @if($event['hours'])
                                            <div class="expire-date"><img loading="lazy" class="replace-with-svg" onerror="this.src='{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}'"  src="{{cdn($event['hours_icon'])}}" title="hours" alt="hours_icon">{{$event['hours']}}h</div>
                                            @endif

                                            </div>
                                            @if($event['status'] == 5)
                                            <div>
                                                You are on the waiting list.
                                            </div>
                                            @endif
                                        </div>--}}

                                        <div id="c-shedule-inner{{$tab}}" class="in-tab-wrapper" style="display: block;">

                                            <div class="bottom">
                                            @if(isset($event['summaryDate']))<div class="duration"><img loading="lazy" class="replace-with-svg resp-img" onerror="this.src='{{cdn('/theme/assets/images/icons/Duration_Hours.svg')}}'" width="20" height="20" src="{{cdn($event['summaryDate_icon'])}}" title="summary_icon" alt="summary_icon">{{$event['summaryDate']}}</div>@endif
                                            @if($event['hours'])
                                            <div class="expire-date"><img loading="lazy" class="replace-with-svg resp-img" onerror="this.src='{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}'"  src="{{cdn($event['hours_icon'])}}" width="20" height="20" title="summary_icon" alt="summary_icon">{{$event['hours']}}h</div>
                                            @endif
                                            @if($event['status'] == 5)
                                            <div>
                                                You are on the waiting list.
                                            </div>
                                            @endif
                                            </div>

                                            @if(isset($event['topics']) && count($event['topics']) > 0)
                                            <div class="bottom  @if((isset($event['paid']) && $event['paid']) || !isset($event['paid'])) {{ 'tabs-bottom'}} @endif">

                                            <div class="expire-date exp-date"><img loading="lazy" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" width="20" height="20" title="Access-Files-icon" alt="Access-Files-icon">Schedule available in PDF</div>
                                            <div class="right">
                                                <a target="_blank" href="/print/syllabus/{{$event['slugable']['slug']}}" class="btn btn--secondary btn--md"> DOWNLOAD SCHEDULE </a>
                                            </div>
                                            </div>
                                            <div class="acc-topic-accordion">
                                            @if((isset($event['paid']) && $event['paid']) || !isset($event['paid']))
                                            <div class="accordion-wrapper accordion-big">
                                                <?php $catId = -1?>
                                                <?php //dd($event['topics']); ?>
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
                                                                    <span class="meta-item duration"><img loading="lazy" class="resp-img" src="{{cdn('/theme/assets/images/icons/Duration_Hours.svg')}}" alt="Duration_Hours_icon" title="Duration_Hours_icon" /><?= date( "l d M Y", strtotime($lesso['pivot']['time_starts']) ) ?></span> <!-- Feedback 18-11 changed -->
                                                                    <span class="meta-item duration"><img loading="lazy" class="resp-img" src="{{cdn('/theme/assets/images/icons/Times.svg')}}" alt="Times_icon" title="Times_icon" /><?= date( "H:i", strtotime($lesso['pivot']['time_starts']) ) ?> ({{$lesso['pivot']['duration']}})</span> <!-- Feedback 18-11 changed -->
                                                                    <span class="meta-item duration"><img loading="lazy" class="resp-img" src="{{cdn('/theme/assets/images/icons/icon-marker.svg')}}" alt="icon-marker" title="icon-marker"/>@if(isset($lesso['pivot']['location_url']) && $lesso['pivot']['location_url']) <a href="{{$lesso['pivot']['location_url']}}" target="_blank"> {{$lesso['pivot']['room']}} </a> @else {{$lesso['pivot']['room']}} @endif</span> <!-- Feedback 18-11 changed -->
                                                                    </div>
                                                                <!-- /.topic-title-meta -->
                                                            </div>
                                                            <div class="author-img">
                                                                <!-- Feedback 18-11 changed -->
                                                                <a href="{{$instructors[$lesso['instructor_id']][0]['slugable']['slug']}}">
                                                                <span class="custom-tooltip"><?= $instructors[$lesso['instructor_id']][0]['title'].' '.$instructors[$lesso['instructor_id']][0]['subtitle']; ?></span>
                                                                <?php
                                                                    $imageDetails = get_image_version_details('instructors-small');
                                                                ?>
                                                                <img loading="lazy" class="resp-img" src="{{cdn( get_image($instructors[$lesso['instructor_id']][0]['mediable'],'instructors-small') )}}" width="{{ $imageDetails['w'] }}" height="{{ $imageDetails['h'] }}" title="<?= $instructors[$lesso['instructor_id']][0]['title']; $instructors[$lesso['instructor_id']][0]['subtitle']; ?> alt="<?= $instructors[$lesso['instructor_id']][0]['title']; $instructors[$lesso['instructor_id']][0]['subtitle']; ?>"/>
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

                                                <!-- /.accordion-wrapper -->
                                            </div>
                                            @endif
                                            <!-- /.acc-topic-accordion -->
                                            </div>
                                            @endif

                                        </div>

                                        <?php

                                            $now1 = strtotime(date("Y-m-d"));
                                            $display = false;
                                            if(!isset($event['release_date_files'])){
                                             $display = false;
                                            }
                                            else if(!$event['release_date_files'] && $event['status'] == 3){
                                                $display = true;

                                            }else if(strtotime(date('Y-m-d',strtotime($event['release_date_files']))) >= $now1 && $event['status'] == 3){

                                                $display = true;
                                            }

                                            ?>


                                        @if(isset($event['dropbox']))
                                        <div id="c-files-inner{{$tab}}" class="in-tab-wrapper">
                                                <?php

                                                    foreach($event['dropbox'] as $dropbox){
                                                        $folders = isset($dropbox['folders'][0]) ? $dropbox['folders'][0] : [];
                                                        //dd($folders);

                                                        //dd($dropbox);
                                                        $selectedFiles = [];
                                                        if(isset($dropbox['pivot']['selectedFolders'])){
                                                        $selectedFiles = $dropbox['pivot']['selectedFolders'];
                                                        $selectedFiles = json_decode($selectedFiles, true);
                                                        }


                                                        $folders_bonus = isset($dropbox['folders'][1]) ? $dropbox['folders'][1] : [];
                                                        //dd($folders_bonus);
                                                        $files = isset($dropbox['files'][1]) ? $dropbox['files'][1] : [];
                                                        $files_bonus = isset($dropbox['files'][2]) ? $dropbox['files'][2] : [];





                                                ?>
                                            @if($display)
                                            <div class="acc-topic-accordion">
                                            <div class="accordion-wrapper accordion-big">
                                                @if(isset($folders) && count($folders) > 0)
                                                <?php
                                                    if($event['slugable']['slugable_id'] == 4612){
                                                        //dd($dropbox['folder_name']);
                                                    }
                                                ?>
                                                <div class="accordion-item">
                                                    {{--<h3 class="accordion-title title-blue-gradient scroll-to-top"> {{ $dropbox['folder_name'] }}</h3>--}}

                                                    {{--<div class="accordion-content accordion-content-root">--}}
                                                        @foreach($folders as $folder)
                                                            <?php
                                                                $folderIsSelected = false;
                                                                if(isset($selectedFiles['selectedAllFolders']))
                                                                {
                                                                  if($selectedFiles['selectedAllFolders']){
                                                                     $folderIsSelected = true;
                                                                  }else{
                                                                     foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile){
                                                                         if($folder['dirname'] == $selectedFile){
                                                                             $folderIsSelected = true;
                                                                         }
                                                                     }
                                                                  }
                                                                }


                                                                $checkedF = [];
                                                                $fs = [];
                                                                $fk = 1;
                                                                $bonus = [];
                                                                $subfolder = [];
                                                                $subfiles = [];
                                                                ?>
                                                            <div class="accordion-item d-none">
                                                                <h3 class="accordion-title title-blue-gradient scroll-to-top"> {{ $folder['foldername'] }}</h3>
                                                                <div class="accordion-content no-padding">
                                                                    @if(isset($files) && count($files) > 0)
                                                                    @foreach($folders_bonus as $folder_bonus)
                                                                        @if(isset($folder_bonus['parent']) && $folder_bonus['parent'] == $folder['id']  && !in_array($folder_bonus['foldername'],$bonusFiles))
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
                                                                                @if(isset($folder_bonus['parent']) && $folder_bonus['parent'] == $folder['id'])

                                                                                <?php $folderIsSelected = false; ?>
                                                                                @if(isset($selectedFiles['selectedAllFolders']))
                                                                                @if($selectedFiles['selectedAllFolders'])
                                                                                    <?php $folderIsSelected = true; ?>
                                                                                @else
                                                                                    @foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile)
                                                                                        @if($folder_bonus['dirname'] == $selectedFile)
                                                                                            <?php $folderIsSelected = true; ?>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif
                                                                                @endif

                                                                                <?php $subfolder[] =  $subf['foldername']; ?>
                                                                                <div class="files-wrapper bonus-files d-none">
                                                                                <h4 class="bonus-title">{{ $subf['foldername'] }}</h4>
                                                                                <span><i class="icon-folder-open"></i>   </span>
                                                                                    @foreach($files_bonus as $file_bonus)
                                                                                        @if(isset($file_bonus['parent']) && $file_bonus['fid'] == $subf['id'] && $file_bonus['parent'] == $subf['parent'] )

                                                                                        @if($folderIsSelected)
                                                                                            <?php $subfiles[]= $file_bonus['filename'] ?>
                                                                                            <div class="file-wrapper">
                                                                                                <h4 class="file-title">{{ $file_bonus['filename'] }}</h4>
                                                                                                <span class="last-modified">Last modified:  {{$file_bonus['last_mod']}}</span>
                                                                                                <a  class="download-file getdropboxlink"  data-dirname="{{ $file_bonus['dirname'] }}" data-filename="{{ $file_bonus['filename'] }}" href="javascript:void(0)" >
                                                                                                <img loading="lazy" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" class="resp-img" width="34" height="34" alt="Download File" title="Download File"/></a>
                                                                                            </div>
                                                                                        @else
                                                                                          @if(isset($selectedFiles['selectedFolders']))
                                                                                            @foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile)

                                                                                                @if($file_bonus['dirname'] == $selectedFile)
                                                                                                <?php $subfiles[]= $file_bonus['filename'] ?>
                                                                                                    <div class="file-wrapper">
                                                                                                        <h4 class="file-title">{{ $file_bonus['filename'] }}</h4>
                                                                                                        <span class="last-modified">Last modified:  {{$file_bonus['last_mod']}}</span>
                                                                                                        <a  class="download-file getdropboxlink"  data-dirname="{{ $file_bonus['dirname'] }}" data-filename="{{ $file_bonus['filename'] }}" href="javascript:void(0)" >
                                                                                                        <img loading="lazy" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" class="resp-img" width="34" height="34" alt="Download File" title="Download File"/></a>
                                                                                                    </div>
                                                                                                @endif
                                                                                            @endforeach
                                                                                          @endif
                                                                                        @endif


                                                                                        @endif
                                                                                    @endforeach
                                                                                </div>
                                                                                @endif
                                                                            @endforeach
                                                                        @endforeach
                                                                    @endif
                                                                    @foreach($files as $file)
                                                                        @if($folder['id'] == $file['fid'])

                                                                            @if($folderIsSelected)
                                                                                <div class="files-wrapper no-bonus">
                                                                                    <div class="file-wrapper">
                                                                                        <h4 class="file-title">{{ $file['filename'] }}</h4>
                                                                                        <span class="last-modified">Last modified:  {{ $file['last_mod'] }}</span>
                                                                                        <a  class="download-file getdropboxlink"  data-dirname="{{ $file['dirname'] }}" data-filename="{{ $file['filename'] }}" href="javascript:void(0)" >
                                                                                        <img loading="lazy" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" class="resp-img" width="34" height="34" alt="Download File" title="Download File"/></a>
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                            @if(isset($selectedFiles['selectedFolders']))
                                                                                @foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile)
                                                                                    @if($file['dirname'] == $selectedFile)
                                                                                        <div class="files-wrapper no-bonus">
                                                                                            <div class="file-wrapper">
                                                                                                <h4 class="file-title">{{ $file['filename'] }}</h4>
                                                                                                <span class="last-modified">Last modified:  {{ $file['last_mod'] }}</span>
                                                                                                <a  class="download-file getdropboxlink"  data-dirname="{{ $file['dirname'] }}" data-filename="{{ $file['filename'] }}" href="javascript:void(0)" >
                                                                                                <img loading="lazy" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" class="resp-img" width="34" height="34" alt="Download File" title="Download File"/></a>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                @endforeach
                                                                              @endif
                                                                            @endif
                                                                            <!-- <div class="files-wrapper">
                                                                                <div class="file-wrapper">
                                                                                    <h4 class="file-title">{{ $file['filename'] }}</h4>
                                                                                    <span class="last-modified">Last modified:  {{ $file['last_mod'] }}</span>
                                                                                    <a  class="download-file getdropboxlink"  data-dirname="{{ $file['dirname'] }}" data-filename="{{ $file['filename'] }}" href="javascript:void(0)" >
                                                                                    <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                                                                </div>
                                                                            </div> -->
                                                                            @endif
                                                                    @endforeach
                                                                    @endif
                                                                    @if(isset($folders_bonus) && count($folders_bonus) > 0)
                                                                    <div class="files-wrapper bonus-files d-none">
                                                                    @foreach($folders_bonus as $folder_bonus)
                                                                    <?php
                                                                        if(in_array($folder_bonus['foldername'],$subfolder)){
                                                                            continue;
                                                                        }
                                                                        ?>
                                                                    @if(isset($folder_bonus['parent']) && $folder_bonus['parent'] == $folder['id'])

                                                                            <?php $folderIsSelected = false; ?>
                                                                            @if(isset($selectedFiles['selectedFolders']))
                                                                            @if($selectedFiles['selectedAllFolders'])
                                                                                <?php $folderIsSelected = true; ?>
                                                                            @else
                                                                                @foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile)
                                                                                    @if($folder_bonus['dirname'] == $selectedFile)
                                                                                        <?php $folderIsSelected = true; ?>
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                            @endif



                                                                    <h4 class="bonus-title">{{ $folder_bonus['foldername'] }}</h4>
                                                                    <span><i class="icon-folder-open"></i>   </span>
                                                                    @if(isset($files_bonus) && count($files_bonus) > 0)
                                                                    @foreach($files_bonus as $file_bonus)
                                                                    @if(isset($file_bonus['parent']) && $file_bonus['parent'] == $folder_bonus['parent'] && !in_array($file_bonus['filename'],$subfiles))

                                                                        @if($folderIsSelected)
                                                                            <div class="file-wrapper">
                                                                                <h4 class="file-title">{{ $file_bonus['filename'] }}</h4>
                                                                                <span class="last-modified">Last modified:  {{$file_bonus['last_mod']}}</span>
                                                                                <a  class="download-file getdropboxlink"  data-dirname="{{ $file_bonus['dirname'] }}" data-filename="{{ $file_bonus['filename'] }}" href="javascript:void(0)" >
                                                                                <img loading="lazy" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" class="resp-img" width="34" height="34" alt="Download File" title="Download File"/></a>
                                                                            </div>
                                                                        @else
                                                                        @if(isset($selectedFiles['selectedFolders']))
                                                                            @foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile)
                                                                                @if($file_bonus['dirname'] == $selectedFile)
                                                                                    <div class="file-wrapper">
                                                                                        <h4 class="file-title">{{ $file_bonus['filename'] }}</h4>
                                                                                        <span class="last-modified">Last modified:  {{$file_bonus['last_mod']}}</span>
                                                                                        <a  class="download-file getdropboxlink"  data-dirname="{{ $file_bonus['dirname'] }}" data-filename="{{ $file_bonus['filename'] }}" href="javascript:void(0)" >
                                                                                        <img loading="lazy" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" class="resp-img" width="34" height="34" alt="Download File" title="Download File"/></a>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                           @endif
                                                                        @endif

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
                                                    {{--</div>--}}
                                                </div>
                                                @endif
                                            </div>
                                            </div>
                                            @endif
                                            <?php } ?>
                                        </div>
                                        @endif
                                        @if(isset($event['exams']) && count($event['exams']) >0 )
                                        <?php $nowTime = \Carbon\Carbon::now(); ?>
                                        <div id="c-exams-inner{{$tab}}" class="in-tab-wrapper">
                                            <div class="dynamic-courses-wrapper dynamic-courses-wrapper--style2">
                                            <div class="bottom">
                                                <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Customer_Access.svg')}}" title="customer_Access_icon" alt="customer_Access_icon">Exams will activate in the end of your course.</div>
                                                @foreach($event['exams'] as $p)
                                                <div class="right">
                                                    <!-- Feedback 8-12 changed -->

                                                    <?php $userExam = isset($user['hasExamResults'][$p->id][0]) ? $user['hasExamResults'][$p->id][0] : null ?>

                                                    @if( $userExam  && $nowTime->diffInHours($userExam->end_time) < 48)
                                                    <a target="_blank" href="{{ route('exam-results', [$p->id,'s'=>1]) }}" title="{{$p['title']}}" class="btn btn--secondary btn--md">VIEW RESULT</a>
                                                    @elseif($userExam )
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

                                        @if(count($event['certs']) > 0)
                                        <div id="c-cert-inner{{$tab}}" class="in-tab-wrapper">
                                            <div class="bottom">
                                            <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" title="customer_Access_icon" alt="Access-Files-icon">@if(isset($newlayoutExamsEvent[$keyType]) && count($newlayoutExamsEvent[$keyType])>0)Certificate download after completing your exams. @else Your certification is ready @endif</div>
                                            @foreach($event['certs'] as $certificate)
                                            <?php
                                                    $expirationMonth = '';
                                                    $expirationYear = '';
                                                    $certUrl = trim(url('/') . '/mycertificate/' . base64_encode(Auth::user()->email."--".$certificate->id));
                                                    if($certificate->expiration_date){
                                                        $expirationMonth = date('m',$certificate->expiration_date);
                                                        $expirationYear = date('Y',$certificate->expiration_date);
                                                    }

                                                    $certiTitle = preg_replace( "/\r|\n/", " ", $certificate->certificate_title );

                                                    if(strpos($certificate->certificate_title, '</p><p>')){
                                                        $certiTitle = substr_replace($certificate->certificate_title, ' ', strpos($certificate->certificate_title, '</p>'), 0);
                                                    }else{
                                                        $certiTitle = $certificate->certificate_title;
                                                    }
                                                    $certiTitle = str_replace('&nbsp;',' ',$certiTitle);
                                                    $certiTitle = urlencode(htmlspecialchars_decode(strip_tags($certiTitle),ENT_QUOTES));
                                                ?>
                                            <div class="right">
                                                <a  class="btn btn--secondary btn--md" target="_blank" href="/mycertificate/{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}" >DOWNLOAD </a>
                                                <a class="linkedin-post cert-post 11"  target="_blank" href="https://www.linkedin.com/profile/add?startTask={{$certiTitle}}&name={{$certiTitle}}&organizationId=3152129&issueYear={{date('Y',$certificate->create_date)}}
                                                        &issueMonth={{date('m',$certificate->create_date)}}&expirationYear={{$expirationYear}}&expirationMonth={{$expirationMonth}}&certUrl={{$certUrl}}&certId={{$certificate->credential}}">
                                                        <img loading="lazy" class="linkdein-image-add resp-img" width="36" height="36" src="{{cdn('theme/assets/images/icons/social/events/Linkedin.svg')}}"  title="LinkedIn Add to Profile button" alt="LinkedIn Add to Profile button">
                                                </a>
                                                {{--@if($user->id == 1359)--}}
                                                <a class="facebook-post-cert" data-certTitle="{{$certiTitle}}" data-certid="{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}" title="Add this certification to your Facebook profile" href="javascript:void(0)">
                                                        <img loading="lazy" class="linkdein-image-add resp-img" width="36" height="36" src="{{cdn('theme/assets/images/icons/social/events/Facebook.svg')}}" title="Facebook Add to Profile button" alt="Facebook Add to Profile button">
                                                </a>
                                                {{--<a class="twitter-post-cert" data-certTitle="{{$certiTitle}}" data-certid="{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}" title="Add this certification to your Twitter profile" href="javascript:void(0)">
                                                        <img loading="lazy" class="linkdein-image-add resp-img" width="36" height="36" src="{{cdn('theme/assets/images/icons/social/events/Twitter.svg')}}" title="Twitter Add to Profile button" alt="Twitter Add to Profile button">
                                                </a>--}}
                                                {{--@endif--}}

                                                </div>


                                            @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @if(isset($event['paid']) && $event['paid'] == 0)
                                 <div class="unpaidMessage d-none">
                                     <h3>You have an unpaid amount for this course. Please contact us to arrange payment and retrieve your access.</h3>
                                 </div>
                                 @endif
                            </div>
                        </div>
                     @else


                        <div class="col12 dynamic-courses-wrapper @if(isset($event['paid']) && $event['paid'] == 0){{'unpaid'}}@endif">
                            <div class="item">
                            <h2>{{ $event['title'] }}</h2>
                            <div class="inside-tabs">
                                <div class="tabs-ctrl">
                                    <ul>
                                        {{--<li ><a href="#c-info-inner{{$tab}}">Info</a></li>--}}
                                        <li class="active"><a href="#c-watch-inner{{$tab}}">Watch</a></li>


                                        @if(isset($event['exams']) && count($event['exams']) >0 )
                                        <li><a href="#c-exams-inner{{$tab}}">Exams</a></li>
                                        @endif
                                        @if(count($event['certs']) > 0)
                                        <li><a href="#c-cert-inner{{$tab}}">Certificate</a></li>
                                        @endif

                                        @if($event['mySubscription'])
                                        <li><a href="#c-subs-inner{{$tab}}">Subscription</a></li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="inside-tabs-wrapper">
                                    {{--<div id="c-info-inner{{$tab}}" class="in-tab-wrapper" style="display: block;">
                                        <div class="bottom">
                                        <?php //dd($event['videos_progress']); ?>
                                        @if($event['expiration'])
                                        <div class="expire-date exp-date"><img src="{{cdn('/theme/assets/images/icons/Days-Week.svg')}}" alt="">Expiration date: {{$event['expiration']}}</div>
                                        @endif
                                        @if (isset($event['hours']))
                                        <div  class="duration"><img class="replace-with-svg" width="20" onerror="this.src='{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}'"  src="{{cdn($event['hours_icon'])}}" alt=""> {{$event['hours']}}h </div>
                                        @endif
                                        @if (isset($event['videos_progress']))
                                        <?php //dd($event); ?>
                                        <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/E-Learning.svg')}}" alt=""> {{$event['videos_progress']}}% </div>
                                        @endif
                                        </div>
                                    </div>--}}
                                    @if($event['mySubscription'])
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
                                                <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/clock-coins.svg')}}" alt="clock-coins-icon" title="clock-coins-icon"><?php echo 'Your trial expiration: '.$date; ?></div>
                                                @if($event['mySubscription']['status'])
                                                <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt="credit-icon" title="credit-icon"><?php echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
                                                @endif
                                                @else
                                                @if($event['mySubscription'])
                                                <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt="credit-icon" title="credit-icon"><?php echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
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
                                                <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt="credit-icon" title="credit-icon"><?php echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
                                                @endif
                                                @else
                                                @if($event['mySubscription'])
                                                <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt="credit-icon" title="credit-icon"><?php echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
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
                                                        $showToggle = isset($event['mySubscription']['stripe_status']) && $event['mySubscription']['stripe_status'] == 'active' ? true : false;
                                                        if($event['mySubscription']['status']){
                                                            $a = 'checked';
                                                            $status = 'Active';
                                                            //row_status = ` style="color:green;" `;

                                                        }else{
                                                            $status = 'Cancel';
                                                            //row_status = ` style="color:red;" `;
                                                        }
                                                    ?>
                                                    @if($showToggle)
                                                    <div class="status_switch">
                                                    <div class="onoffswitch" data-status="{{$status}}" data-id="{{$event['mySubscription']['id']}}" id="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" tabindex="0" <?php echo $a; ?>>
                                                        <label class="onoffswitch-label" for="myonoffswitch">
                                                        <span class="onoffswitch-inner"></span>
                                                        <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                    </div>
                                                    @endif
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
                                    <div id="c-watch-inner{{$tab}}" class="in-tab-wrapper" style="display: block;">
                                        <div class="bottom">
                                        @if (isset($event['hours']))
                                        <div  class="duration"><img loading="lazy" class="replace-with-svg resp-img" height="20" width="20" onerror="this.src='{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}'"  src="{{cdn($event['hours_icon'])}}" title="hours" alt="hours"> {{$event['hours']}}h </div>
                                        @endif
                                        @if (isset($event['videos_progress']))
                                        <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/E-Learning.svg')}}" alt="elearning" title="elearning"> {{$event['videos_progress']}}% </div>
                                        @endif
                                        @if (isset($event['videos_seen']))
                                        <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/Recap-Events.svg')}}" alt="recap-events" title="recap-events"> {{str_replace('/','of',$event['videos_seen'])}} </div>
                                        @endif
                                        @if(isset($event['expiration']) && $event['expiration'])
                                        <div class="expire-date exp-date"><img src="{{cdn('/theme/assets/images/icons/Days-Week.svg')}}" alt="days-week">Expiration date: {{$event['expiration']}}</div>
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
                                            <a href="/myaccount/elearning/{{ $event['title'] }}" class="btn btn--secondary btn--md">@if( (isset($event['videos_progress']) && $event['videos_progress'] == 100) ) WATCH AGAIN @else WATCH NOW @endif</a>
                                            @endif
                                        </div>
                                        </div>
                                    </div>

                                    @if(isset($event['exams']))
                                    <?php $nowTime = \Carbon\Carbon::now(); ?>
                                    <div id="c-exams-inner{{$tab}}" class="in-tab-wrapper">
                                        <div class="bottom">
                                        @foreach($event['exams'] as $p)

                                        <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Customer_Access.svg')}}" alt="Customer_Access_icon" title="Customer_Access_icon">
                                        @if(isset($event['exam_activate_months']) && $event['exam_activate_months'] != null)
                                                {{ 'Exams activate automatically after' }} {{ $event['exam_activate_months'] }} {{'months' }}
                                        @else
                                                {{ 'Exams activate automatically after 80% progress' }}
                                        @endif

                                        </div>
                                        <div class="right">
                                            <!-- Feedback 8-12 changed -->

                                            <?php $userExam = isset($user['hasExamResults'][$p->id][0]) ? $user['hasExamResults'][$p->id][0] : null ?>
                                            @if($event['exam_access'] && !$userExam)
                                                @if($p->islive == 1)
                                                <a target="_blank" onclick="window.open('{{ route('attempt-exam', [$p->id]) }}', 'newwindow', 'width=1400,height=650'); return false;" title="{{$p['exam_name']}}" class="btn btn--secondary btn--md">TAKE EXAM</a>
                                                @endif
                                            @elseif($userExam)
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
                                        <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" alt="Access-Files-icon" title="Access-Files-icon">@if(isset($newlayoutExamsEvent[$keyType]) && count($newlayoutExamsEvent[$keyType])>0)Certificate download after completing your exams. @else Your certification is ready @endif</div>
                                        @foreach($event['certs'] as $certificate)
                                            <?php
                                                $expirationMonth = '';
                                                $expirationYear = '';
                                                $certUrl = trim(url('/') . '/mycertificate/' . base64_encode(Auth::user()->email."--".$certificate->id));
                                                if($certificate->expiration_date){
                                                    $expirationMonth = date('m',$certificate->expiration_date);
                                                    $expirationYear = date('Y',$certificate->expiration_date);
                                                }

                                                //dd(strpos($certificate->certificate_title, '</p>'));

                                                if(strpos($certificate->certificate_title, '</p><p>')){
                                                    $certiTitle = substr_replace($certificate->certificate_title, ' ', strpos($certificate->certificate_title, '</p>'), 0);
                                                }else{
                                                    $certiTitle = $certificate->certificate_title;
                                                }
                                                $certiTitle = str_replace('&nbsp;',' ',$certiTitle);
                                                $certiTitle = urlencode(htmlspecialchars_decode(strip_tags($certiTitle),ENT_QUOTES));

                                                ?>
                                        <div class="right">

                                                <a  class="btn btn--secondary btn--md asd" target="_blank" href="/mycertificate/{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}" >DOWNLOAD </a>
                                                <a class="linkedin-post cert-post 22"  target="_blank" href="https://www.linkedin.com/profile/add?startTask={{$certiTitle}}&name={{$certiTitle}}&organizationId=3152129&issueYear={{date('Y',$certificate->create_date)}}
                                                    &issueMonth={{date('m',$certificate->create_date)}}&expirationYear={{$expirationYear}}&expirationMonth={{$expirationMonth}}&certUrl={{$certUrl}}&certId={{$certificate->credential}}">
                                                    <img loading="lazy" class="linkdein-image-add resp-img" width="36" height="36" src="{{cdn('theme/assets/images/icons/social/events/Linkedin.svg')}}" alt="LinkedIn Add to Profile button" title="LinkedIn Add to Profile button">
                                                </a>
                                            {{--@if($user->id == 1359)--}}
                                            <a class="facebook-post-cert cert-post" data-certTitle="{{$certiTitle}}" data-certid="{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}" title="Add this certification to your Facebook profile" href="javascript:void(0)">
                                                    <img loading="lazy" class="linkdein-image-add resp-img" width="36" height="36" src="{{cdn('theme/assets/images/icons/social/events/Facebook.svg')}}" alt="Facebook Add to Profile button" title="Facebook Add to Profile button">
                                            </a>
                                            {{--<a class="twitter-post-cert" data-certTitle="{{$certiTitle}}" data-certid="{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}" title="Add this certification to your Facebook profile" href="javascript:void(0)">
                                                    <img loading="lazy" class="linkdein-image-add resp-img" width="36" height="36" src="{{cdn('theme/assets/images/icons/social/events/Twitter.svg')}}" alt="Twitter Add to Profile button" title="Twitter Add to Profile button">
                                            </a>--}}
                                            {{--@endif--}}
                                        </div>

                                        @endforeach
                                        </div>
                                    </div>
                                    @endif

                                </div>
                            </div>
                            @if(isset($event['paid']) && $event['paid'] == 0)
                                <div class="unpaidMessage d-none">
                                    <h3>You have an unpaid amount for this course. Please contact us to arrange payment and retrieve your access.</h3>
                                </div>
                            @endif
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
                     {{--@if($event['view_tpl'] != 'elearning_event' && $event['view_tpl'] != 'elearning_free')--}}
                     @if($event['delivery'] != 143)
                     @else
                     <div class="col12 dynamic-courses-wrapper">
                        <div class="item">
                           <h2>{{ $event['title'] }}</h2>
                           <div class="inside-tabs">
                              <div class="tabs-ctrl">
                                 <ul>
                                    {{--<li class="active"><a href="#c-info-inner{{$tab}}">Info</a></li>--}}
                                    <li class="active"><a href="#c-watch-inner{{$tab}}">Watch</a></li>
                                    @if(isset($event['exams']) && count($event['exams']) >0 )
                                       <li><a href="#c-exams-inner{{$tab}}">Exams</a></li>
                                    @endif

                                    @if(count($event['certs']) > 0)
                                       <li><a href="#c-cert-inner{{$tab}}">Certificate</a></li>
                                    @endif

                                    <li><a href="#c-subs-inner{{$tab}}">Subscription</a></li>
                                 </ul>
                              </div>
                              <div class="inside-tabs-wrapper">
                                {{-- <div id="c-info-inner{{$tab}}" class="in-tab-wrapper" style="display: block;">
                                    <div class="bottom">
                                       @if (isset($event['hours']) && $event['hours'])
                                       <div  class="duration"><img class="replace-with-svg" width="20" onerror="this.src='{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}'"  src="{{cdn($event['hours_icon'])}}" alt=""> {{$event['hours']}}h </div>
                                       @endif
                                       @if (isset($event['videos_progress']))
                                       <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/E-Learning.svg')}}" alt=""> {{$event['videos_progress']}}% </div>
                                       @endif

                                    </div>
                                 </div>--}}
                                 <div id="c-watch-inner{{$tab}}" class="in-tab-wrapper" style="display: block;">
                                    <div class="bottom">
                                       @if (isset($event['hours']) && $event['hours'])
                                       <div  class="duration"><img loading="lazy" class="replace-with-svg resp-img" width="20" height="20" onerror="this.src='{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}'"  src="{{cdn($event['hours_icon'])}}" alt="hours" title="hours"> {{$event['hours']}}h </div>
                                       @endif
                                       @if (isset($event['videos_progress']))
                                       <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/E-Learning.svg')}}" alt=""> {{$event['videos_progress']}}% </div>
                                       @endif
                                       @if (isset($event['videos_seen']))
                                       <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/Recap-Events.svg')}}" alt=""> {{str_replace('/','of',$event['videos_seen'])}} </div>
                                       @endif
                                       @if(isset($event['expiration']) && $event['expiration'])
                                       <div class="expire-date exp-date"><img src="{{cdn('/theme/assets/images/icons/Days-Week.svg')}}" alt="">Expiration date: {{$event['expiration']}}</div>
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
                                          {{--<a style="cursor:not-allowed; opacity: 0.5; pointer-events: none;" href="/myaccount/elearning/{{ $event['title'] }}" class="btn btn--secondary btn--md">@if( (isset($event['videos_progress']) && $event['videos_progress'] == 100) || count($event['cert'])>0) WATCH AGAIN @else WATCH NOW @endif</a>--}}
                                          @else
                                          <a href="/myaccount/elearning/{{ $event['title'] }}" class="btn btn--secondary btn--md">@if( isset($event['videos_progress']) && $event['videos_progress'] == 100 >0) WATCH AGAIN @else WATCH NOW @endif</a>
                                          @endif
                                       </div>
                                    </div>
                                 </div>

                                 @if(isset($event['exams']))
                                 <?php $nowTime = \Carbon\Carbon::now(); ?>
                                 <div id="c-exams-inner{{$tab}}" class="in-tab-wrapper">
                                    <div class="bottom">
                                       @foreach($event['exams'] as $p)
                                       <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Customer_Access.svg')}}" alt="Customer_Access_icon" title="Customer_Access_icon">
                                        @if(isset($event['exam_activate_months']) && $event['exam_activate_months'] != null)
                                                {{ 'Exams activate automatically after' }} {{ $event['exam_activate_months'] }} {{ 'months' }}
                                        @else
                                                {{ 'Exams activate automatically after 80% progress' }}
                                        @endif
                                        </div>
                                       <div class="right">
                                          <!-- Feedback 8-12 changed -->
                                          <?php $userExam = isset($user['hasExamResults'][$p->id][0]) ? $user['hasExamResults'][$p->id][0] : null ?>
                                          @if($event['exam_access'] && !$userExam)
                                          @if($p->islive == 1)
                                          <a target="_blank" onclick="window.open('{{ route('attempt-exam', [$p->id]) }}', 'newwindow', 'width=1400,height=650'); return false;" title="{{$p['exam_name']}}" class="btn btn--secondary btn--md">TAKE EXAM</a>
                                          @endif
                                          @elseif($userExam)
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
                                       <?php
                                             $expirationMonth = '';
                                             $expirationYear = '';
                                             $certUrl = trim(url('/') . '/mycertificate/' . base64_encode(Auth::user()->email."--".$certificate->id));
                                             if($certificate->expiration_date){
                                                $expirationMonth = date('m',$certificate->expiration_date);
                                                $expirationYear = date('Y',$certificate->expiration_date);
                                             }

                                             if(strpos($certificate->certificate_title, '</p><p>')){
                                                $certiTitle = substr_replace($certificate->certificate_title, ' ', strpos($certificate->certificate_title, '</p>'), 0);
                                             }else{
                                                $certiTitle = $certificate->certificate_title;
                                             }
                                             $certiTitle = str_replace('&nbsp;',' ',$certiTitle);
                                             $certiTitle = urlencode(htmlspecialchars_decode(strip_tags($certiTitle),ENT_QUOTES));

                                             ?>
                                       <div class="right">
                                             <a  class="btn btn--secondary btn--md" target="_blank" href="/mycertificate/{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}" >DOWNLOAD </a>
                                             <a class="linkedin-post cert-post 33"  target="_blank" href="https://www.linkedin.com/profile/add?startTask={{$certiTitle}}&name={{$certiTitle}}&organizationId=3152129&issueYear={{date('Y',$certificate->create_date)}}
                                                   &issueMonth={{date('m',$certificate->create_date)}}&expirationYear={{$expirationYear}}&expirationMonth={{$expirationMonth}}&certUrl={{$certUrl}}&certId={{$certificate->credential}}">
                                                   <img loading="lazy" class="linkdein-image-add resp-img" width="36" height="36" src="{{cdn('theme/assets/images/icons/social/events/Linkedin.svg')}}" alt="LinkedIn Add to Profile button" title="LinkedIn Add to Profile button">
                                             </a>
                                          {{--@if($user->id == 1359)--}}
                                          <a class="facebook-post-cert" data-certTitle="{{$certiTitle}}" data-certid="{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}" title="Add this certification to your Facebook profile" href="javascript:void(0)">
                                                <img loading="lazy" class="linkdein-image-add resp-img" width="36" height="36" src="{{cdn('theme/assets/images/icons/social/events/Facebook.svg')}}" alt="Facebook Add to Profile button" title="Facebook Add to Profile button">
                                          </a>
                                          {{--<a class="twitter-post-cert" data-certTitle="{{$certiTitle}}" data-certid="{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}" title="Add this certification to your Facebook profile" href="javascript:void(0)">
                                                <img loading="lazy" class="linkdein-image-add resp-img" width="36" height="36" src="{{cdn('theme/assets/images/icons/social/events/Twitter.svg')}}" alt="Twitter Add to Profile button" title="Twitter Add to Profile button">
                                          </a>--}}

                                          {{--@endif--}}
                                        </div>


                                       @endforeach
                                    </div>
                                 </div>
                                 @endif

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
                                             <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/clock-coins.svg')}}" alt="clock-coins" title="clock-coins"><?php echo 'Your trial expiration: '.$date; ?></div>
                                             @if($event['mySubscription']['status'])
                                             <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt="credit" title="credit"><?php  echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
                                             @endif
                                             @else
                                             @if($event['mySubscription'])
                                             <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt="credit" title="credit"><?php echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
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
                                             <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt="credit" title="credit"><?php echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
                                             @endif
                                             @else
                                             @if($event['mySubscription'])
                                             <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt="credit" title="credit"><?php echo 'You will be charged: '.date('d-m-Y',$event['mySubscription']['must_be_updated']); ?></div>
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
                                                      $showToggle = isset($event['mySubscription']['stripe_status']) && $event['mySubscription']['stripe_status'] == 'active' ? true : false;
                                                      if($event['mySubscription']['status']){
                                                         $a = 'checked';
                                                         $status = 'Active';
                                                         //row_status = ` style="color:green;" `;

                                                      }else{
                                                         $status = 'Cancel';
                                                         //row_status = ` style="color:red;" `;
                                                      }
                                                   ?>
                                                @if($showToggle)
                                                <div class="status_switch">
                                                   <div class="onoffswitch" data-status="{{$status}}" data-id="{{$event['mySubscription']['id']}}" id="onoffswitch">
                                                      <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" tabindex="0" <?php echo $a; ?>>
                                                      <label class="onoffswitch-label" for="myonoffswitch">
                                                      <span class="onoffswitch-inner"></span>
                                                      <span class="onoffswitch-switch"></span>
                                                      </label>
                                                   </div>
                                                </div>
                                                @endif
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

<script src="{{cdn('theme/assets/js/validation_myaccount/jquery.validate.min.js')}}" type="text/javascript" charset="utf-8" async defer></script>
<script src="{{cdn('theme/assets/js/validation_myaccount/additional-methods.min.js')}}" type="text/javascript" charset="utf-8" async defer></script>
<script src="{{cdn('theme/assets/js/validation_myaccount/validation.js')}}" type="text/javascript" charset="utf-8" async defer></script>
<script src="{{ asset('argon') }}/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script>


{{--@if($user->id == 1359)--}}
   $(document).on('click', '.facebook-post-cert', function() {
      var getUrl = window.location;
      var baseUrl = getUrl .protocol + "//" + getUrl.host;
      var certificateId = $(this).attr('data-certid');
      var certificateTitle = $(this).attr('data-certTitle');

      certificateTitle = certificateTitle.split('+').join('_')


      $.ajax({
          type: 'GET',
          url: "/mycertificate/convert-pdf-to-image/"+certificateId,
          success: function(data) {
            let url = data.path

              data = url.replace('\\','/')
              if(data){
                  var fbpopup = window.open(`http://www.facebook.com/sharer.php?u=${decodeURI(baseUrl)}/${decodeURI(data)}/${decodeURI(certificateTitle)}`, "pop", "width=600, height=400, scrollbars=no");
                  return false;
              }

          }
      });
   })

   $(document).on('click', '.twitter-post-cert', function() {
      var getUrl = window.location;
      var baseUrl = getUrl .protocol + "//" + getUrl.host;
      var certificateId = $(this).attr('data-certid');
      var certificateTitle = $(this).attr('data-certTitle');

      certificateTitle = certificateTitle.split('+').join(' ')

      $.ajax({
          type: 'GET',
          url: "/myaccount/twitter/"+certificateId+'/'+certificateTitle,
          success: function(data) {

            if(data){
                window.open(data.url, '_blank');
            }



            // let url = data.path

            //   data = url.replace('\\','/')
            //   if(data){
            //       var fbpopup = window.open(`http://twitter.com/share?url=${decodeURI(baseUrl)}/${decodeURI(data)}/${decodeURI(certificateTitle)}`, "pop", "width=600, height=400, scrollbars=no");
            //       return false;
            //   }

          }
      });
   })
{{--@endif--}}

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


                        document.getElementById('user-img').setAttribute('src','/theme/assets/images/icons/user-circle-placeholder.svg')
                        document.getElementById('user-img-up').setAttribute('src','/theme/assets/images/icons/user-circle-placeholder.svg')

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
           //var receiptdata = $("#billing-data input").serialize();
           //console.log(receiptdata);
           /*$.ajax({ url: "myaccount/updrecbill", type: "post",
               data: receiptdata,
               success: function(data) {
                   if (Number(data.status) === 1) {

                       window.location = 'myaccount'; //'myaccount/billing';
                   }
                   else {
                       alert('Not saved. Please try again');
                   }
               }
           });*/

           $("#billing-data-form").submit();
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

    let accordions = $('.accordion-item')
    $.each(accordions, function(index, value) {
        let files_in_accordion = $(value).find('.files-wrapper.no-bonus')

        let files_in_accordion_bonus = $(value).find('.files-wrapper.bonus-files')

        if(files_in_accordion_bonus.length == 0){
            if(files_in_accordion.length != 0){
                $(accordions[index]).removeClass('d-none');
            }
        }else{
            let files_in_accordion_bonus = $(value).find('.files-wrapper.bonus-files')[0]
            $.each(files_in_accordion_bonus, function(index1, value1) {

                let files_in_accordion = $(value).find('.file-wrapper')
                if(files_in_accordion.length != 0 || files_in_accordion.length != 0){
                    $(accordions[index]).removeClass('d-none');
                }
            })
        }


    })

    let bonusFiles = $('.files-wrapper.bonus-files')
    $.each(bonusFiles, function(index, value) {
        let files_in_bonus = $(value).find('.file-wrapper');

        if(files_in_bonus.length != 0){
            $(bonusFiles[index]).removeClass('d-none');
        }
    })


      $("#selectCountry").select()
      $("#selectCountry").change()

      @if("{{ old('country_code') }}")

         $("#selectCountry").val("{{ old('country_code',$currentuser->country_code) }}").change();

      @endif

      alphabetizeList('#selectCountry');

   });

</script>

<script>
   $("#update-personal-info").click(function(){

      /*let val = $("#firstname").val();
      let newVal = val.charAt(0).toUpperCase() + val.slice(1).toLowerCase();
      $("#firstname").val(newVal)

      val = $("#lastname").val();
      newVal = val.charAt(0).toUpperCase() + val.slice(1).toLowerCase();
      $("#lastname").val(newVal)*/

      let val = ($("#firstname").val()).split(" ");
      let newVal = '';
      $.each(val, function( index, value ) {
          newVal += value.charAt(0).toUpperCase() + value.slice(1).toLowerCase() + ' ';
      });
      $("#firstname").val(newVal.trim());

      val = ($("#lastname").val()).split(" ");
      newVal = '';
      $.each(val, function( index, value ) {
          newVal += value.charAt(0).toUpperCase() + value.slice(1).toLowerCase() + ' ';
      });

      $("#lastname").val(newVal.trim());



    var checkoutUrl = '/myaccount/validate-personal-info';
    var fdata = $("#update-form").serialize();
    $(".error-mobile").hide();


    $.ajax({ url: checkoutUrl, type: "post",

         headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         data: fdata,
         success: function(data) {

             $('#update-form').find("input[type=text]").removeClass('verror');
             if (Number(data.status) === 0) {
                 //var html = '<ul>';
                 $("#update-form").valid();
                 $.each(data.errors, function (key, row) {

                     $("#mobile-error1").html(row);
                     $("#mobile-error1").show()

                 });

                 //$("#participant-form").valid();
             } else {

               $('#update-form').submit();

             }
         }

    });



   });

</script>

<script>

		function alphabetizeList(listField) {
    		var sel = $(listField);
    		var selected = sel.val(); // cache selected value, before reordering
    		var opts_list = sel.find('option');
    		opts_list.sort(function (a, b) {
    		    return $(a).text().trim() > $(b).text().trim() ? 1 : -1;
    		});
    		sel.html('').append(opts_list);
    		sel.val(selected); // set cached selected value
		}

</script>

<script>
   var datePickerOptions = {
        format: 'dd-mm-yyyy',
        changeMonth: true,
        changeYear: true,
    }
    $("#birthday").datepicker(datePickerOptions);


</script>

<script>
    $( document ).ready(function() {
        let unpaidLinks = $('.dynamic-courses-wrapper.unpaid').find('a')

        $.each(unpaidLinks, function(index, value) {
            $(value).attr('href', 'javascript:void(0)')
        })

        $( '.dynamic-courses-wrapper.unpaid' ).mouseenter( handlerIn ).mouseleave( handlerOut );

        function handlerIn(){
            let tabsElem = $(this).find('.inside-tabs')[0];
            let messageElem = $(this).find('.unpaidMessage')[0];

            $(tabsElem).addClass('d-none');
            $(messageElem).removeClass('d-none');
        }

        function handlerOut(){
            let tabsElem = $(this).find('.inside-tabs')[0];
            let messageElem = $(this).find('.unpaidMessage')[0];

            $(tabsElem).removeClass('d-none');
            $(messageElem).addClass('d-none');
        }

        // $(document).on('mouseover', '.dynamic-courses-wrapper.unpaid', function(){
        //     console.log('test')
        //     let tabsElem = $(this).find('.inside-tabs')[0];
        //     let messageElem = $(this).find('.unpaidMessage')[0];

        //     $(tabsElem).addClass('d-none');
        //     $(messageElem).removeClass('d-none');
        // })

        // $(document).on('mouseleave', '.dynamic-courses-wrapper.unpaid', function(){

        //     let tabsElem = $(this).find('.inside-tabs')[0];
        //     let messageElem = $(this).find('.unpaidMessage')[0];

        //     $(tabsElem).removeClass('d-none');
        //     $(messageElem).addClass('d-none');
        // })

        // Getting the page href
        const pageHref = window.location.search;
        if(pageHref != ''){

            let params = pageHref.substring(pageHref.indexOf('?'))
            params = params.split('=');


            if(params[1] !== undefined && params[1] == 'ok'){

                $('#share-twitter-modal .message').text('Share certificate on twitter successfuly!!');
                $('#share-twitter-modal .alert-wrapper').addClass('success-alert')
                $('#share-twitter-modal').show();

            }else if(params[1] !== undefined && params[1] == 'error'){

                $('#share-twitter-modal .message').text('Share certificate on twitter not successfuly!!');
                $('#share-twitter-modal .alert-wrapper').addClass('error-alert')
                $('#share-twitter-modal').show();
            }

            params = new URLSearchParams(pageHref);
            params.delete('twitter_share');
        }



        $( "#share-twitter-modal .close-btn" ).on( "click", function() {
            $('#share-twitter-modal').hide();
            $('#share-twitter-modal .alert-wrapper').removeClass('success-alert')
            $('#share-twitter-modal .alert-wrapper').removeClass('error-alert')
            $('#share-twitter-modal .message').text('');
        });




    })
</script>

@stop
