@extends('theme.layouts.master')
@section('metas')
<title>{{ 'My Account' }}</title>
@endsection

@section('content')

<?php $currentuser = $user ?>
<?php $eventService = app()->make(\App\Services\EventService::class); ?>
<?php $allInstallmentsPayed = false; ?>


<main  id="" role="main">

    <section class="section-hero section-hero-small section-hero-blue-bg">
        <div class="container">
            <div class="hero-message">
                <div class="account-infos">
                    <div class="account-thumb">
                        {!! \App\Helpers\UserHelper::getUserProfileImage($user, ['width' => 43, 'height' => 43, 'id' => 'user-img-up', 'class' => 'profile_images_panel' ]) !!}
                    </div>
                    <div class="account-hero-info">
                        <h2>{{ $currentuser['firstname'] }} {{ $currentuser['lastname'] }}</h2>
                        <ul>
                          @if(isset($currentuser['kc_id']) && $currentuser['kc_id'] != '')
                            <li>{{ $currentuser['kc_id'] }}</li>
                          @endif
                          @if(isset($currentuser['partner_id']) && $currentuser['partner_id'] != '')
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
                                            {!! \App\Helpers\UserHelper::getUserProfileImage($user, ['width' => 230, 'height' => 230, 'id' => 'user-img', 'class' => 'profile_images_panel' ]) !!}
                                        </div>
                                        <div class="actions">
                                            <ul id='user-media'>
                                                <li class="change-photo"><a id="logoDropzone" class="custFieldMediaDrop dz-message" href="javascript:void(0)"><img loading="lazy" src="{{cdn('/theme/assets/images/icons/icon-edit.svg')}}" alt="Change photo" title="Change photo" width="16" height="16"/><span>Change photo</span>
                                                    </a>
                                                </li>

                                                <li class="remove-photo delete_media" @if(!isset($user->profile_image_id)) style="display: none" @endif><a data-dp-media-id="{{ $user->profile_image_id }}" href="javascript:void(0)"><img loading="lazy" src="{{cdn('/theme/assets/images/icons/icon-remove.svg')}}" alt="Remove photo" title="Remove photo" width="10" height="10"/><span>Remove photo</span></a></li>

                                                <li class="crop-photo crop_media" @if(!isset($user->profile_image_id)) style="display: none" @endif>
                                                  <a data-dp-media-id="{{ $user->profile_image_id }}" class="crop_image" style="text-decoration: underline; cursor: pointer;">
                                                    <img loading="lazy" src="{{cdn('/theme/assets/images/icons/icon-edit.svg')}}" alt="Crop photo" title="Crop photo" width="20" height="20"/>
                                                    <span>Crop photo</span>
                                                  </a>
                                                </li>
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
                                                @if(config('feature-flags.user_profile_enabled'))
                                                  <li><a  href="#user-profile" >Public Profile</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="inside-tabs-wrapper">
                                            <div id="personal-data" class="in-tab-wrapper" style="display: block;">
                                                @if($errors->any())
                                                  <div class="alert-outer">
                                                    <div class="">
                                                      <div class="alert-wrapper error-alert">
                                                        <div class="alert-inner">
                                                          <p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-success-alert.svg')}}"
                                                                  alt="Info Alert">{{ $errors->first() }}</p>
                                                          <a href="javascript:void(0)" class="close-alert"><img
                                                              src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}"
                                                              alt="Close Alert" /></a>
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
                                                            <p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-success-alert.svg')}}"
                                                                    alt="Info Alert">{{ Session::get('opmessage') }}</p>
                                                            <a href="javascript:void(0)" class="close-alert"><img
                                                                src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}"
                                                                alt="Close Alert" /></a>
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
                                                            <p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-success-alert.svg')}}"
                                                                    alt="Info Alert">{{ Session::get('opmessage') }}</p>
                                                            <a href="javascript:void(0)" class="close-alert"><img
                                                                src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}"
                                                                alt="Close Alert" /></a>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <!-- /.alert-outer -->
                                                    </div>
                                                  @endif
                                                @endif
                                                <div class="form-wrapper profile-form-wrapper" id="student-view-mode">
                                                  <form id="update-form" method="post" action="{{ route('update.personalInfo') }}" autocomplete="off">
                                                    {!! csrf_field() !!}
                                                    <div class="col12">
                                                      <label>My first name is <span>(*)</span></label>
                                                      <div class="input-safe-wrapper">
                                                        <input class="required" type="text" name="firstname" id="firstname"
                                                               value="{{ old('firstname', $currentuser['firstname']) }}">
                                                      </div>
                                                    </div>
                                                    <div class="col12">
                                                      <label>My last name is: <span>(*)</span></label>
                                                      <div class="input-safe-wrapper">
                                                        <input class="required" type="text" name="lastname" id="lastname"
                                                               value="{{ old('lastname', $currentuser['lastname']) }}">
                                                      </div>
                                                    </div>
                                                    <div class="col12">
                                                      <?php $birthday = $currentuser['birthday'] ? date('j F Y', strtotime($currentuser['birthday'])) : "" ?>
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
                                                        <input type="text" name="company" id="company" value="{{ old('company', $currentuser['company']) }}">
                                                      </div>
                                                    </div>
                                                    <div class="col12">
                                                      <label>Occupation:</label>
                                                      <div class="input-safe-wrapper">
                                                        <input name="job_title" id="job_title" value="{{ old('job_title', $currentuser['job_title']) }}">
                                                      </div>
                                                    </div>
                                                    <div class="col12">
                                                      <label>Town / City:</label>
                                                      <div class="input-safe-wrapper">
                                                        <input name="city" id="city" value="{{ old('city', $currentuser['city']) }}">
                                                      </div>
                                                    </div>
                                                    <div class="col12">
                                                      <label>Email: <span>*</span></label>
                                                      <div class="input-safe-wrapper">
                                                        <span id="email-error"></span>
                                                        <input class="required" id="email" name="email" type="email"
                                                               value="{{ old('email', $currentuser['email']) }}">
                                                      </div>
                                                    </div>
                                                    <div class="col12">
                                                      <label>Mobile phone: <span>*</span></label>
                                                      <div class="input-safe-wrapper is-flex full-width">
                                                        <div class="col4">
                                                          <select name="country_code" id="selectCountry" class="form-control valid" aria-required="true"
                                                                  aria-invalid="false">
                                                            <option value="213" label="Algeria (+213)">Algeria</option>
                                                            <option value="244" label="Angola (+244)">Angola</option>
                                                            <option value="229" label="Benin (+229)">Benin</option>
                                                            <option value="267" label="Botswana (+267)">Botswana</option>
                                                            <option value="226" label="Burkina Faso (+226)">Burkina Faso</option>
                                                            <option value="257" label="Burundi (+257)">Burundi</option>
                                                            <option value="237" label="Cameroon (+237)">Cameroon</option>
                                                            <option value="238" label="Cape Verde (+238)">Cape Verde</option>
                                                            <option value="236" label="Central African Republic (+236)">Central African Republic</option>
                                                            <option value="269" label="Comoros (+269)">Comoros</option>
                                                            <option value="242" label="Congo - Brazzaville (+242)">Congo - Brazzaville</option>
                                                            <option value="253" label="Djibouti (+253)">Djibouti</option>
                                                            <option value="20" label="Egypt (+20)">Egypt</option>
                                                            <option value="240" label="Equatorial Guinea (+240)">Equatorial Guinea</option>
                                                            <option value="291" label="Eritrea (+291)">Eritrea</option>
                                                            <option value="251" label="Ethiopia (+251)">Ethiopia</option>
                                                            <option value="241" label="Gabon">Gabon</option>
                                                            <option value="220" label="Gambia (+220)">Gambia</option>
                                                            <option value="233" label="Ghana (+233)">Ghana</option>
                                                            <option value="224" label="Guinea (+224)">Guinea</option>
                                                            <option value="245" label="Guinea-Bissau (+245)">Guinea-Bissau</option>
                                                            <option value="254" label="Kenya (+254)">Kenya</option>
                                                            <option value="266" label="Lesotho (+266)">Lesotho</option>
                                                            <option value="231" label="Liberia (+231)">Liberia</option>
                                                            <option value="218" label="Libya (+218)">Libya</option>
                                                            <option value="261" label="Madagascar (+261)">Madagascar</option>
                                                            <option value="265" label="Malawi (+265)">Malawi</option>
                                                            <option value="223" label="Mali (+223)">Mali</option>
                                                            <option value="222" label="Mauritania (+222)">Mauritania</option>
                                                            <option value="262" label="Mayotte (+262)">Mayotte</option>
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
                                                            <option value="1264" label="Anguilla (+1264)">Anguilla</option>
                                                            <option value="595" label="Paraguay (+595)">Paraguay</option>
                                                            <option value="51" label="Peru (+51)">Peru</option>
                                                            <option value="1268" label="Antigua and Barbuda (+1268)">Antigua and Barbuda</option>
                                                            <option value="54" label="Argentina (+54)">Argentina</option>
                                                            <option value="297" label="Aruba (+297)">Aruba</option>
                                                            <option value="1242" label="Bahamas (+1242)">Bahamas</option>
                                                            <option value="1246" label="Barbados (+1246)">Barbados</option>
                                                            <option value="501" label="Belize (+501)">Belize</option>
                                                            <option value="1441" label="Bermuda (+1441)">Bermuda</option>
                                                            <option value="591" label="Bolivia (+591)">Bolivia</option>
                                                            <option value="55" label="Brazil (+55)">Brazil</option>
                                                            <option value="84" label="British Virgin Islands (+84)">British Virgin Islands</option>
                                                            <option value="1" label="Canada (+1)">Canada</option>
                                                            <option value="1345" label="Cayman Islands (+1345)">Cayman Islands</option>
                                                            <option value="56" label="Chile (+56)">Chile</option>
                                                            <option value="57" label="Colombia (+57)">Colombia</option>
                                                            <option value="506" label="Costa Rica (+506)">Costa Rica</option>
                                                            <option value="53" label="Cuba (+53)">Cuba</option>
                                                            <option value="90392" label="Northen Cyprus (+90392)">Northen Cyprus</option>
                                                            <option value="1809" label="Dominica (+1809)">Dominica</option>
                                                            <option value="1809" label="Dominican Republic (+1809)">Dominican Republic</option>
                                                            <option value="593" label="Ecuador (+593)">Ecuador</option>
                                                            <option value="503" label="El Salvador (+503)">El Salvador</option>
                                                            <option value="500" label="Falkland Islands (+500)">Falkland Islands</option>
                                                            <option value="594" label="French Guiana (+594)">French Guiana</option>
                                                            <option value="299" label="Greenland (+299)">Greenland</option>
                                                            <option value="1473" label="Grenada (+1473)">Grenada</option>
                                                            <option value="590" label="Guadeloupe (+590)">Guadeloupe</option>
                                                            <option value="502" label="Guatemala (+502)">Guatemala</option>
                                                            <option value="592" label="Guyana (+592)">Guyana</option>
                                                            <option value="509" label="Haiti (+509)">Haiti</option>
                                                            <option value="504" label="Honduras (+504)">Honduras</option>
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
                                                            <option value="374" label="Armenia (+374)">Armenia</option>
                                                            <option value="994" label="Azerbaijan (+994)">Azerbaijan</option>
                                                            <option value="973" label="Bahrain (+973)">Bahrain</option>
                                                            <option value="880" label="Bangladesh  (+880)">Bangladesh</option>
                                                            <option value="975" label="Bhutan (+975)">Bhutan</option>
                                                            <option value="673" label="Brunei (+673)">Brunei</option>
                                                            <option value="63" label="Philippines (+63)">Philippines</option>
                                                            <option value="855" label="Cambodia (+855)">Cambodia</option>
                                                            <option value="86" label="China (+86)">China</option>
                                                            <option value="7880" label="Georgia (+7880)">Georgia</option>
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
                                                            <option value="996" label="Kyrgyzstan (+996)"> Kyrgyzstan</option>
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
                                                            <option value="376" label="Andorra (+376)">Andorra</option>
                                                            <option value="43" label="Austria (+43)">Austria</option>
                                                            <option value="375" label="Belarus (+375)">Belarus</option>
                                                            <option value="32" label="Belgium (+32)">Belgium</option>
                                                            <option value="387" label="Bosnia and Herzegovina (+387)">Bosnia and Herzegovina</option>
                                                            <option value="359" label="Bulgaria (+359)">Bulgaria</option>
                                                            <option value="385" label="Croatia (+385)">Croatia</option>
                                                            <option value="357" label="Cyprus (+357)">Cyprus</option>
                                                            <option value="420" label="Czech Republic (+420)">Czech Republic</option>
                                                            <option value="45" label="Denmark (+45)">Denmark</option>
                                                            <option value="372" label="Estonia (+372)">Estonia</option>
                                                            <option value="298" label="Faroe Islands  (+298)">Faroe Islands</option>
                                                            <option value="358" label="Finland (+358)">Finland (+358)</option>
                                                            <option value="33" label="France (+33)">France</option>
                                                            <option value="49" label="Germany (+49)">Germany</option>
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
                                                            <option value="61" label="Australia (+61)">Australia</option>
                                                            <option value="682" label="Cook Islands (+682)">Cook Islands</option>
                                                            <option value="679" label="Fiji (+679)">Fiji</option>
                                                            <option value="670" label="East Timor (+670)">East Timor</option>
                                                            <option value="689" label="French Polynesia (+689)">French Polynesia</option>
                                                            <option value="241" label="French Southern Territories (+241)">French Southern Territories
                                                            </option>
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
                                                          <input class="required" onkeyup="checkPhoneNumber(this)" type="number" name="mobile" id="mobile"
                                                                 value="{{{ old('mobile', $currentuser['mobile']) }}}">
                                                          <input type="hidden" name="mobileCheck" id="mobileCheck"
                                                                 value="{{{ old('mobile', '+'.$currentuser['country_code'].$currentuser['mobile']) }}}">
                                                          <label id="mobile-error1" style="display:none" class="error error-mobile" for="mobile"></label>

                                                        </div>
                                                      </div>
                                                    </div>
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

                                          <div id="user-profile" class="in-tab-wrapper">
                                            <div class="form-wrapper profile-form-wrapper" id="student-view-mode">
                                              <form id="update-public-profile" method="post" action="{{ route('update.publicProfile') }}" autocomplete="off">
                                                {!! csrf_field() !!}
                                                <!-- Social accounts -->
                                                <div class="col12">
                                                  <label>My LinkedIn is <span>(*)</span></label>
                                                  <div class="input-safe-wrapper">
                                                    <input class="required" type="text" name="social_links[linkedin]" id="linkedin"  value="{{ $currentuser['social_links']['linkedin'] ?? '' }}" placeholder="http://xx.linkedin.com/in/xxxxxxxxxxx">
                                                    <span class="validation"></span>
                                                  </div>
                                                </div>
                                                <!-- About me (biography) -->
                                                <div class="col12">
                                                  <label>About me<span>(*)</span></label>
                                                  <div class="input-safe-wrapper">
                                                    <textarea class="required" type="text" name="biography" id="biography">{{ $currentuser->biography }}</textarea>
                                                    <span class="validation"></span>
                                                  </div>
                                                </div>
                                                <!-- My Work availability -->
                                                <div class="col12">
                                                  <label>My Work Availability<span>(*)</span></label>
                                                  <div class="checkbox-row input-safe-wrapper" style="text-align: left">
                                                    <div class="custom-checkbox">
                                                      <input type="checkbox" id="is_employee" name="is_employee" value="1" {{ $currentuser->is_employee ? 'checked' : '' }}>
                                                      <span></span>
                                                    </div>
                                                    <label for="is_employee">Employee</label>
                                                    <div style="margin: 10px 0"><span class="validation text-sm"></span></div>
                                                  </div>
                                                  <div class="checkbox-row input-safe-wrapper" style="text-align: left">
                                                    <div class="custom-checkbox">
                                                      <input type="checkbox" id="is_freelancer" name="is_freelancer" value="1" {{ $currentuser->is_freelance ? 'checked' : '' }}>
                                                      <span></span>
                                                    </div>
                                                    <label for="is_freelancer">Freelance</label>
                                                    <div style="margin: 10px 0"><span class="validation text-sm"></span></div>
                                                  </div>
                                                </div>
                                                <!-- My working location preference -->
                                                <div class="col12">
                                                  <label>My Working Location Preference<span>(*)</span></label>
                                                  <div class="checkbox-row input-safe-wrapper" style="text-align: left">
                                                    <div class="custom-checkbox">
                                                      <input type="hidden" name="will_work_remote" value="0" />
                                                      <input type="checkbox" id="will_work_remote" name="will_work_remote" value="1" {{ $currentuser->will_work_remote ? 'checked' : '' }}>
                                                      <span></span>
                                                    </div>
                                                    <label for="will_work_remote">Remote</label>
                                                    <div style="margin: 10px 0"><span class="validation text-sm"></span></div>
                                                  </div>
                                                  <div class="checkbox-row input-safe-wrapper" style="text-align: left">
                                                    <div class="custom-checkbox">
                                                      <input type="hidden" name="will_work_in_person" value="0" />
                                                      <input type="checkbox" id="will_work_in_person" name="will_work_in_person" value="1" onchange="
                                                        const checkbox = this;
                                                        const citiesElement = document.querySelector('.cities');

                                                        if (checkbox.checked) {
                                                            citiesElement.classList.remove('hidden');
                                                        } else {
                                                            citiesElement.classList.add('hidden');
                                                        }
                                                      " {{ $currentuser->will_work_in_person ? 'checked' : '' }}>
                                                      <span></span>
                                                    </div>
                                                    <label for="will_work_in_person">In specific cities</label>
                                                    <div style="margin: 10px 0"><span class="validation text-sm"></span></div>
                                                  </div>
                                                  <div class="cities {{ $currentuser->will_work_in_person == false ? 'hidden' : '' }}" style="margin-top: 15px" >
                                                    <label>Cities<span>(*)</span></label>
                                                    <div class="input-safe-wrapper">
                                                      <select class="cities-select" name="city_ids[]" multiple="multiple" style="width: 100%">
                                                        @foreach($currentuser->cities as $city)
                                                          <option value="{{ $city->id }}" selected="selected">{{ $city->name }}, {{ $city->country->name }}</option>
                                                        @endforeach
                                                      </select>
                                                      <span class="validation"></span>
                                                    </div>
                                                  </div>
                                                </div>
                                                <!-- My skills -->
                                                <div class="col12">
                                                  <label>My Skills<span>(*)</span></label>
                                                  <div class="input-safe-wrapper">
                                                    <select class="skills-select" name="skill_ids[]" multiple="multiple" style="width: 100%">

                                                      @foreach($skills as $id => $skill)
                                                        <option value="{{ $id }}" {{ $currentuser->skills->pluck('id')->contains($id) ? 'selected' : '' }}>{{ $skill }}</option>
                                                      @endforeach
                                                    </select>
                                                    <div style="margin: 10px 0"><span class="validation"></span></div>
                                                  </div>
                                                </div>
                                                <!-- My career paths -->
                                                <div class="col12">
                                                  <label>My Career Paths<span>(*)</span></label>
                                                  <div class="input-safe-wrapper">
                                                    <select class="skills-select" name="career_path_ids[]" multiple="multiple" style="width: 100%">
                                                      @foreach($careerPaths as $id => $careerPath)
                                                        <option value="{{ $id }}" {{ $currentuser->careerPaths->pluck('id')->contains($id) ? 'selected' : '' }}>{{ $careerPath }}</option>
                                                      @endforeach
                                                    </select>
                                                    <div style="margin: 10px 0"><span class="validation"></span></div>
                                                  </div>
                                                </div>
                                                <!-- My experience -->
                                                <div class="col12">
                                                  <label>My experience<span>(*)</span></label>
                                                  <div class="checkbox-row input-safe-wrapper" style="text-align: left">
                                                    <div class="custom-checkbox">
                                                      <input type="radio" id="entry_level" name="work_experience" value="{{ \App\Enums\WorkExperience::ENTRY_LEVEL }}"  {{ $currentuser->work_experience == \App\Enums\WorkExperience::ENTRY_LEVEL ? 'checked' : '' }}>
                                                      <span></span>
                                                    </div>
                                                    <label for="entry_level">{{ __('work_experience.entry-level') }}</label>
                                                    <div style="margin: 10px 0"><span class="validation text-sm"></span></div>
                                                  </div>
                                                  <div class="checkbox-row input-safe-wrapper" style="text-align: left">
                                                    <div class="custom-checkbox">
                                                      <input type="radio" id="mid_level" name="work_experience" value="{{ \App\Enums\WorkExperience::MID_LEVEL }}" {{ $currentuser->work_experience == \App\Enums\WorkExperience::MID_LEVEL ? 'checked' : '' }}>
                                                      <span></span>
                                                    </div>
                                                    <label for="mid_level">{{ __('work_experience.mid-level') }}</label>
                                                    <div style="margin: 10px 0"><span class="validation text-sm"></span></div>
                                                  </div>
                                                  <div class="checkbox-row input-safe-wrapper" style="text-align: left">
                                                    <div class="custom-checkbox">
                                                      <input type="radio" id="senior_level" name="work_experience" value="{{ \App\Enums\WorkExperience::SENIOR_LEVEL }}" {{ $currentuser->work_experience == \App\Enums\WorkExperience::SENIOR_LEVEL ? 'checked' : '' }}>
                                                      <span></span>
                                                    </div>
                                                    <label for="senior_level">{{ __('work_experience.senior-level') }}</label>
                                                    <div style="margin: 10px 0"><span class="validation text-sm"></span></div>
                                                  </div>
                                                </div>

                                                <!-- Toggle public profile -->
                                                <div class="col12 mt-4">
                                                  <label>Enable Public Profile</label>
                                                  <div class="text-sm">
                                                    Enabling your profile will make your profile page URL visible.<br/>
                                                    <a target="_blank" href="{{ route('public-profile', $currentuser) }}">{{ route('public-profile', $currentuser) }}</a>
                                                  </div>
                                                  <div class="checkbox-row" style="text-align: left">
                                                    <div class="custom-checkbox">
                                                      <input type="hidden" name="is_public_profile_enabled" value="0" />
                                                      <input type="checkbox" id="public_profile_enabled" name="is_public_profile_enabled" value="1" {{ $currentuser->is_public_profile_enabled ? 'checked' : '' }}>
                                                      <span></span>
                                                    </div>
                                                    <label for="public_profile_enabled">Enable public profile</label>
                                                  </div>
                                                </div>

                                                <div class="form-submit-area">
                                                  <button id="" type="submit" class="btn btn--md btn--secondary">Save</button>
                                                </div>

                                              </form>
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
                        <x-annual-subscription-component
                          :events="$events ?? null"
                          :subscriptionEvents="$mySubscriptionEvents"
                          :plans="isset($plans) ? $plans : []"
                        />

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

                                @if(!empty($events) && count($events) > 0)

                                    @foreach($events as $keyType => $event)
                                        <x-students-course-component
                                          :keyType="$keyType"
                                          :event="$event"
                                          :tab="$tab"
                                          :mySubscriptions="$mySubscriptions"
                                          :instructors="$instructors"
                                        />
                                        <?php $tab += 1; ?>
                                    @endforeach

                                @endif

                                @if(isset($mySubscriptionEvents) && count($mySubscriptionEvents) > 0)
                                <!-- subs -->
                                    @foreach($mySubscriptionEvents as $keyType => $event)
                                        <x-students-course-component
                                          :keyType="$keyType"
                                          :event="$event"
                                          :tab="$tab"
                                          :mySubscriptions="$mySubscriptions"
                                          :instructors="$instructors"
                                          :isSubscription="true"
                                        />
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


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" integrity="sha512-hvNR0F/e2J7zPPfLC9auFe3/SE0yG4aJCOd/qxew74NN7eyiSKjr7xJJMu1Jy2wf7FXITpWS1E/RY8yzuXN7VA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js" integrity="sha512-9KkIqdfN7ipEW6B6k+Aq20PV31bjODg4AA52W+tYtAE0jE0kMx49bjJ3FgvS56wzmyfMUHbQ4Km2b7l9+Y/+Eg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('argon') }}/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="{{ asset('argon') }}/vendor/sweetalert2/dist/sweetalert2.min.css">


<script>
  let cropper = null;
  let modal = null;
  let profile_image = @json($user['profile_image']);
  let profile_image_original = @json($user->profile_image_original());


  if(profile_image){
    initCropper();
  }

  function getFullPath() {
    return profile_image_original.full_path;
  }

  function initCropper(){
    img = media = profile_image;
    full_path = profile_image_original.full_path;
    details = profile_image.crop_data;

    // Cropping images
    $('.crop_image').off('click').on('click', () => {
      if (cropper) {
        cropper.destroy();
        cropper = null;
        $('#modalProfileImageCrop').remove();
        modal = null;
      }

      modal = $(
        '<div class="modal fade" id="modalProfileImageCrop" tabindex="-1" role="dialog">' +
          '<div class="modal-dialog" role="document">' +
            '<div class="modal-content">' +
              '<div class="modal-header" style="position: relative">' +
                '<h5 class="modal-title">Crop profile image</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 0px; right: 0px; border: 0px; background: transparent">' +
                  '<svg style="width: 25px; height: 25px; fill: gray;" data-dismiss="modal" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>' +
                '</button>' +
              '</div>' +
              '<div class="modal-body" id="cropper-modal-body">' +
                '<img src="' + full_path + '" id="profile-image-to-crop">' +
              '</div>' +
              '<div class="modal-footer" style="padding-top: 20px">' +
                '<button type="button" class="btn btn--secondary btn--md" id="crop-image">Save</button>' +
                '<button type="button" class="btn btn--primary btn--md" style="margin-left: 10px" data-dismiss="modal">Cancel</button>' +
              '</div>' +
            '</div>' +
          '</div>' +
        '</div>'
      );

      $('body').append(modal);

      let width = 800
      let height = 800
      let x = 0;
      let y = 0;

      const cropDataIsString = profile_image.hasOwnProperty('crop_data') && typeof profile_image.crop_data === 'string';

      image_details = cropDataIsString ? JSON.parse(profile_image.crop_data.split(',')) : profile_image.crop_data;
      width = image_details.crop_width;
      height = image_details.crop_height;
      x = image_details.width_offset;
      y = image_details.height_offset;

      // add cropper creation to the end of event loop queue
      setTimeout(() => {
        cropper = new Cropper(document.getElementById(`profile-image-to-crop`), {
          aspectRatio: Number((width/height), 4),
          viewMode: 0,
          dragMode: "crop",
          responsive: true,
          autoCropArea: 0,
          restore: false,
          movable: false,
          rotatable: false,
          scalable: false,
          zoomable: false,
          cropBoxMovable: true,
          cropBoxResizable: true,
          minContainerWidth: 300,
          minContainerHeight: 300,
          data:{
            x:parseInt(x),
            y:parseInt(y),
            width: parseInt(width),
            height: parseInt(height)
          }
        });
      }, 0);

      $('#modalProfileImageCrop').modal('show');


      $("#crop-image").click(function(){
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: 'post',
          url: '/media/crop_profile_image',
          data: {'media_id': profile_image.id, 'x':cropper.getData({rounded: true}).x, 'y':cropper.getData({rounded: true}).y, 'width':cropper.getData({rounded: true}).width, 'height':cropper.getData({rounded: true}).height},
          success: function (data) {
            if(data){

              var imagenes = document.querySelectorAll('.profile_images_panel')
              for (var i = 0; i < imagenes.length; i++) {
                  imagenes[i].src = data.profile_image.full_path + "?v=" + new Date().getTime();
              }

              Swal.fire(
                'Good job!',
                'Successfully Cropped!',
                'success'
              )

              modal.modal('hide');
              if (cropper) {
                cropper.destroy();
                cropper = null; // Opcional: limpiar la referencia si no vas a reutilizarla
              }
              $('#modalProfileImageCrop').remove();

              profile_image = data.profile_image;

            }
          }
        });

      });

    });
  }

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
                  let link = `${decodeURI('{{ config('app.url') }}/mycertificateview/share/facebook/' + certificateId + '/' + data.split('/')[1])}`;

                  var fbpopup = window.open(`http://www.facebook.com/sharer.php?u=${link}`, "pop", "width=600, height=400, scrollbars=no");
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
                $('#user-img').attr('src', response.data);
                //    $('#logo_media').val(response.urls.large_thumb);
                //    $('#logo_media_id').val(response.media_id);
                renderLogoDropzone(response, true);

                var imagenes = document.querySelectorAll('.profile_images_panel')
                for (var i = 0; i < imagenes.length; i++) {
                    var newSrc = response.data + "?v=" + new Date().getTime();
                    imagenes[i].src = newSrc;
                }

                Swal.fire(
                  'Good job!',
                  'Image profile set correctly!',
                  'success'
                );

                details = response.profile_image.crop_data;
                profile_image = response.profile_image;
                profile_image_original = response.profile_image_original;

                console.log(profile_image);

                $('.remove-photo').show();
                $('.crop-photo').show();

                initCropper();
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
        e.preventDefault();
        $('.onoffswitch').removeAttr( "id" )

        let sub_id = $(this).parent().data('id')
        let status = $(this).parent().data('status')
        let renewLink = $(this).parent().data('renew-link')
        let set_status = '';

        if(status == 'Active') {
            set_status = 'Cancel'
            $(this).parent().data('status', 'Cancel');
            document.getElementsByClassName("onoffswitch-checkbox")[0].removeAttribute("checked");
        }else if(status == 'Cancel') {
            set_status = 'Active'
            $(this).parent().data('status', 'Active');
            $('.onoffswitch-checkbox').attr('checked', '')
        }

        const toggle = $(this);

        $.ajax({
            type: 'POST',
            url: 'myaccount/subscription/change_status',
            data:{'sub_id':sub_id, 'status':set_status},
            success: function(data) {
                if(data) {
                    if (data.hasOwnProperty('need_new_subscription') && data.need_new_subscription === true) {
                      if (data.hasOwnProperty('new_subscription_link') && data.new_subscription_link !== null) {
                        window.location.href = data.new_subscription_link;
                      } else {
                        showErrorMessage(toggle);
                      }
                    } else {
                        toggle[0].checked = (set_status === 'Active')
                        if(data.cancel_at) {
                          $('#status').text('Cancel')
                          $('#status').css('color', 'red')
                        } else {
                          $('#status').text('Active')
                          $('#status').css('color', 'green')
                        }
                        $('.onoffswitch').attr('id', 'onoffswitch');
                    }
                }
            }
        }).fail(() => {
          showErrorMessage($(this));
        })

        function showErrorMessage(container) {
            const wrapper = container.closest('.status_wrapper');
            wrapper.append('<div class="status_error" style="color: red;">An unexpected error occurred while updating the subscription status. Please try again later.</div>');
            setTimeout(() => {
              wrapper.children('.status_error').remove();
            }, 5000);
        }
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

              document.getElementById('user-img').setAttribute('src','/theme/assets/images/icons/user-circle-placeholder.svg')
              document.getElementById('user-img-up').setAttribute('src','/theme/assets/images/icons/user-circle-placeholder.svg')

              $('.delete_media').hide();
              $('.crop_media').hide();
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

  function getInputNameFromValidationKey(inputString) {
    // Check if the inputString contains a dot
    if (inputString.includes('.')) {
      // Split the string by the dot
      var parts = inputString.split('.');

      // Reconstruct the string with square brackets around the second part
      return parts[0] + '[' + parts[1] + ']';
    } else {
      // Return the original string if no dot is found
      return inputString;
    }
  }

  $("#update-public-profile").submit(function(e) {
    e.preventDefault(); // Prevent the default form submission

    $("#update-public-profile").find('span.validation').text('');

    // Serialize the form data
    var formData = $(this).serialize();
    // Make an AJAX post request to the action defined in the form
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'PUT',
      url: $(this).attr('action'), // Action defined in the form
      data: formData,
      success: function(response) {
        // refresh so that the flashed status message is shown
        window.location.reload()
      },
      error: function(xhr) {
        // alert('An error occurred while submitting the form');
        console.log(xhr.responseJSON.errors)
        // Check if responseJSON and errors exist
        let form = $("#update-public-profile");
        if (xhr.responseJSON && xhr.responseJSON.errors) {
          var errors = xhr.responseJSON.errors;
          for (var key in errors) {
            if (errors.hasOwnProperty(key)) {
              console.log(key)
              console.log(errors[key][0])
              console.log(form)
              console.log('[name="' + getInputNameFromValidationKey(key) + '"]')
              console.log(form.find('[name="' + getInputNameFromValidationKey(key) + '"]'))

              form.find('[name="' + getInputNameFromValidationKey(key) + '"], [name="' + getInputNameFromValidationKey(key) + '[]"]')
                .closest('.input-safe-wrapper')
                .find('.validation')
                .text(errors[key][0])
            }
          }
        }
      }
    });
  });

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

        let pendingLinks = $('.dynamic-courses-wrapper.pendingSepa').find('a')

        $.each(pendingLinks, function(index, value) {
            $(value).attr('href', 'javascript:void(0)')
        })

        $( '.dynamic-courses-wrapper.pendingSepa' ).mouseenter( handlerIn1 ).mouseleave( handlerOut1 );

        function handlerIn1(){
            let tabsElem = $(this).find('.inside-tabs')[0];
            let messageElem = $(this).find('.pendingSepaMessage')[0];

            $(tabsElem).addClass('d-none');
            $(messageElem).removeClass('d-none');
        }

        function handlerOut1(){
            let tabsElem = $(this).find('.inside-tabs')[0];
            let messageElem = $(this).find('.pendingSepaMessage')[0];

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

        $(".skills-select").select2();

        $(".cities-select").select2({
          ajax: {
            url: "/api/cities",
            dataType: 'json',
            delay: 150,
            processResults: function (data) {
              console.log(data);
              // Transforms the top-level key of the response object from 'items' to 'results'
              return {
                results: data.data.map(item => {
                  return {
                    id: item.id,
                    text: item.name + ', ' + item.country.name
                  }
                })
              };
            }
          }
        });
    })
</script>

@stop
