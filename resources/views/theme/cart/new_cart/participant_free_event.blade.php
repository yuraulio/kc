@extends('theme.cart.new_cart.master')

@section('content')

<!---------------- checkout progress-bar start --------------->
<div class="checkout-step">
		<div class="container">		
			<ul>
				<li class="active"><span class="counter">1.</span><i>Participant(s)</i></li>
				<li><span class="counter">2.</span><i>Billing</i></li>
				<li><span class="counter">3.</span><i>Checkout</i></li>
			</ul>
		</div>
	</div>

<!---------------- checkout progress-bar end --------------->	
<div class="form-wrap">
		<div class="container">			
			<h1>Participant(s)</h1>			
			<div class="row">
				<!---------------- Participant form start--------------->
				<div class="col-md-6 col-xl-6">
					<div class="participant-full-wrap">
					@if(!Auth::check())<p class="login-link">Already have an account? <a href="#" class="link-color">Log in</a></p>@endif			
						<form action="{{route('enrollForFree',$eventId)}}" method="get" id="participant-form" name="participant-form">
                        
							<div class="form-wrp box" id="clone-box">												
								
								<p class="validation-info">Fields marked with an asterisk <span class="checkout-required-data">(*)</span> are required.</p>	
								
								<div class="form-row">
									<div class="col-md-6 mb-4 pr-md-3">
										<label class="input-label">My first name is  <span class="checkout-required-data">(*)</span></label>
										<input type="text" name="firstname[]" class="form-control with-focus-visible" value="{{old('firstname',$firstname[0])}}"  required>
										<div class="valid-feedback">
											
										</div>
									</div>
									<div class="col-md-6 mb-4 pl-md-3">
										<label class="input-label">My last name is <span class="checkout-required-data">(*)</span></label>
										<input type="text" name="lastname[]" class="form-control" value="{{old('lastname',$lastname[0])}}"  required>
										<div class="valid-feedback">
											
										</div>
									</div>					
								</div>
								<div class="form-row">
									<div class="col-md-12 mb-4">
										<label class="input-label">My e-mail is  <span class="checkout-required-data">(*)</span></label>							<div class="email-wrap">
												<input type="text" name="email[]" id="email" value="{{old('email',$email[0])}}" class="form-control" required>
										  	</div>
									</div>
								</div>
								<div class="form-row">
									<div class="col-md-12 mb-4 position-relative country-dropdown">
										<label class="input-label">My mobile phone number is <span class="checkout-required-data">(*)</span></label>
										<select name="country_code[]"  class="form-control" id="country">
											    <option value="0" label="Select a country">Select a country</option>
												<option value="213" label="Algeria">Algeria</option>
												<option value="244" label="Angola">Angola</option>
												<option value="229" label="Benin">Benin</option>
												<option value="267" label="Botswana">Botswana</option>
												<option value="226" label="Burkina Faso">Burkina Faso</option>
												<option value="257" label="Burundi">Burundi</option>
												<option value="237" label="Cameroon">Cameroon</option>
												<option value="238" label="Cape Verde">Cape Verde</option>
												<option value="236" label="Central African Republic">Central African Republic</option>
												<option value="TD" label="Chad">Chad</option>
												<option value="269" label="Comoros">Comoros</option>
												<option value="242" label="Congo - Brazzaville">Congo - Brazzaville</option>
												<option value="CD" label="Congo - Kinshasa">Congo - Kinshasa</option>
												<option value="CI" label="Côte d’Ivoire">Côte d’Ivoire</option>
												<option value="253" label="Djibouti">Djibouti</option>
												<option value="20" label="Egypt">Egypt</option>
												<option value="240" label="Equatorial Guinea">Equatorial Guinea</option>
												<option value="291" label="Eritrea">Eritrea</option>
												<option value="251" label="Ethiopia">Ethiopia</option>
												<option value="241" label="Gabon">Gabon</option>
												<option value="220" label="Gambia">Gambia</option>
												<option value="233" label="Ghana">Ghana</option>
												<option value="224" label="Guinea">Guinea</option>
												<option value="245" label="Guinea-Bissau">Guinea-Bissau</option>
												<option value="254" label="Kenya">Kenya</option>
												<option value="266" label="Lesotho">Lesotho</option>
												<option value="231" label="Liberia">Liberia</option>
												<option value="218" label="Libya">Libya</option>
												<option value="261" label="Madagascar">Madagascar</option>
												<option value="265" label="Malawi">Malawi</option>
												<option value="223" label="Mali">Mali</option>
												<option value="222" label="Mauritania">Mauritania</option>
												<option value="MU" label="Mauritius">Mauritius</option>
												<option value="269" label="Mayotte">Mayotte</option>
												<option value="212" label="Morocco">Morocco</option>
												<option value="258" label="Mozambique">Mozambique</option>
												<option value="264" label="Namibia">Namibia</option>
												<option value="227" label="Niger">Niger</option>
												<option value="234" label="Nigeria">Nigeria</option>
												<option value="250" label="Rwanda">Rwanda</option>
												<option value="262" label="Réunion">Réunion</option>
												<option value="290" label="Saint Helena">Saint Helena</option>
												<option value="221" label="Senegal">Senegal</option>
												<option value="248" label="Seychelles">Seychelles</option>
												<option value="232" label="Sierra Leone">Sierra Leone</option>
												<option value="252" label="Somalia">Somalia</option>
												<option value="27" label="South Africa">South Africa</option>
												<option value="249" label="Sudan">Sudan</option>
												<option value="268" label="Swaziland">Swaziland</option>
												<option value="239" label="São Tomé and Príncipe">São Tomé and Príncipe</option>
												<option value="TZ" label="Tanzania">Tanzania</option>
												<option value="228" label="Togo">Togo</option>
												<option value="216" label="Tunisia">Tunisia</option>
												<option value="256" label="Uganda">Uganda</option>
												<option value="EH" label="Western Sahara">Western Sahara</option>
												<option value="260" label="Zambia">Zambia</option>
												<option value="263" label="Zimbabwe">Zimbabwe</option>										
												<option value="1264" label="Anguilla">Anguilla</option>
												<option value="1268" label="Antigua and Barbuda">Antigua and Barbuda</option>
												<option value="54" label="Argentina">Argentina</option>
												<option value="297" label="Aruba">Aruba</option>
												<option value="1242" label="Bahamas">Bahamas</option>
												<option value="1246" label="Barbados">Barbados</option>
												<option value="501" label="Belize">Belize</option>
												<option value="1441" label="Bermuda">Bermuda</option>
												<option value="591" label="Bolivia">Bolivia</option>
												<option value="55" label="Brazil">Brazil</option>
												<option value="84" label="British Virgin Islands">British Virgin Islands</option>
												<option value="1" label="Canada">Canada</option>
												<option value="1345" label="Cayman Islands">Cayman Islands</option>
												<option value="56" label="Chile">Chile</option>
												<option value="57" label="Colombia">Colombia</option>
												<option value="506" label="Costa Rica">Costa Rica</option>
												<option value="53" label="Cuba">Cuba</option>
												<option value="1809" label="Dominica">Dominica</option>
												<option value="1809" label="Dominican Republic">Dominican Republic</option>
												<option value="593" label="Ecuador">Ecuador</option>
												<option value="503" label="El Salvador">El Salvador</option>
												<option value="500" label="Falkland Islands">Falkland Islands</option>
												<option value="594" label="French Guiana">French Guiana</option>
												<option value="299" label="Greenland">Greenland</option>
												<option value="1473" label="Grenada">Grenada</option>
												<option value="590" label="Guadeloupe">Guadeloupe</option>
												<option value="502" label="Guatemala">Guatemala</option>
												<option value="592" label="Guyana">Guyana</option>
												<option value="509" label="Haiti">Haiti</option>
												<option value="504" label="Honduras">Honduras</option>
												<option value="1876" label="Jamaica">Jamaica</option>
												<option value="596" label="Martinique">Martinique</option>
												<option value="52" label="Mexico">Mexico</option>
												<option value="1664" label="Montserrat">Montserrat</option>
												<option value="AN" label="Netherlands Antilles">Netherlands Antilles</option>
												<option value="505" label="Nicaragua">Nicaragua</option>
												<option value="507" label="Panama">Panama</option>
												<option value="595" label="Paraguay">Paraguay</option>
												<option value="51" label="Peru">Peru</option>
												<option value="1787" label="Puerto Rico">Puerto Rico</option>
												<option value="BL" label="Saint Barthélemy">Saint Barthélemy</option>
												<option value="1869" label="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
												<option value="LC" label="Saint Lucia">Saint Lucia</option>
												<option value="MF" label="Saint Martin">Saint Martin</option>
												<option value="PM" label="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
												<option value="VC" label="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
												<option value="597" label="Suriname">Suriname</option>
												<option value="1868" label="Trinidad and Tobago">Trinidad and Tobago</option>
												<option value="1649" label="Turks and Caicos Islands">Turks and Caicos Islands</option>
												<option value="84" label="U.S. Virgin Islands">U.S. Virgin Islands</option>
												<option value="1" label="United States">United States</option>
												<option value="598" label="Uruguay">Uruguay</option>
												<option value="58" label="Venezuela">Venezuela</option>										
												<option value="AF" label="Afghanistan">Afghanistan</option>
												<option value="374" label="Armenia">Armenia</option>
												<option value="994" label="Azerbaijan">Azerbaijan</option>
												<option value="973" label="Bahrain">Bahrain</option>
												<option value="880" label="Bangladesh">Bangladesh</option>
												<option value="975" label="Bhutan">Bhutan</option>
												<option value="673" label="Brunei">Brunei</option>
												<option value="855" label="Cambodia">Cambodia</option>
												<option value="86" label="China">China</option>
												<option value="7880" label="Georgia">Georgia</option>
												<option value="852" label="Hong Kong SAR China">Hong Kong SAR China</option>
												<option value="91" label="India">India</option>
												<option value="62" label="Indonesia">Indonesia</option>
												<option value="98" label="Iran">Iran</option>
												<option value="964" label="Iraq">Iraq</option>
												<option value="972" label="Israel">Israel</option>
												<option value="81" label="Japan">Japan</option>
												<option value="962" label="Jordan">Jordan</option>
												<option value="7" label="Kazakhstan">Kazakhstan</option>
												<option value="965" label="Kuwait">Kuwait</option>
												<option value="996" label="Kyrgyzstan">Kyrgyzstan</option>
												<option value="856" label="Laos">Laos</option>
												<option value="961" label="Lebanon">Lebanon</option>
												<option value="853" label="Macau SAR China">Macau SAR China</option>
												<option value="60" label="Malaysia">Malaysia</option>
												<option value="960" label="Maldives">Maldives</option>
												<option value="976" label="Mongolia">Mongolia</option>
												<option value="MM" label="Myanmar [Burma]">Myanmar [Burma]</option>
												<option value="977" label="Nepal">Nepal</option>
												<option value="NT" label="Neutral Zone">Neutral Zone</option>
												<option value="850" label="North Korea">North Korea</option>
												<option value="968" label="Oman">Oman</option>
												<option value="PK" label="Pakistan">Pakistan</option>
												<option value="PS" label="Palestinian Territories">Palestinian Territories</option>
												<option value="YD" label="People's Democratic Republic of Yemen">People's Democratic Republic of Yemen</option>
												<option value="63" label="Philippines">Philippines</option>
												<option value="974" label="Qatar">Qatar</option>
												<option value="966" label="Saudi Arabia">Saudi Arabia</option>
												<option value="65" label="Singapore">Singapore</option>
												<option value="82" label="South Korea">South Korea</option>
												<option value="94" label="Sri Lanka">Sri Lanka</option>
												<option value="SY" label="Syria">Syria</option>
												<option value="886" label="Taiwan">Taiwan</option>
												<option value="7" label="Tajikistan">Tajikistan</option>
												<option value="66" label="Thailand">Thailand</option>
												<option value="TL" label="Timor-Leste">Timor-Leste</option>
												<option value="90" label="Turkey">Turkey</option>
												<option value="993" label="Turkmenistan">Turkmenistan</option>
												<option value="971" label="United Arab Emirates">United Arab Emirates</option>
												<option value="7" label="Uzbekistan">Uzbekistan</option>
												<option value="84" label="Vietnam">Vietnam</option>
												<option value="967" label="Yemen">Yemen</option>									
												<option value="AL" label="Albania">Albania</option>
												<option value="376" label="Andorra">Andorra</option>
												<option value="43" label="Austria">Austria</option>
												<option value="375" label="Belarus">Belarus</option>
												<option value="32" label="Belgium">Belgium</option>
												<option value="387" label="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
												<option value="359" label="Bulgaria">Bulgaria</option>
												<option value="385" label="Croatia">Croatia</option>
												<option value="357" label="Cyprus">Cyprus</option>
												<option value="420" label="Czech Republic">Czech Republic</option>
												<option value="45" label="Denmark">Denmark</option>
												<option value="DD" label="East Germany">East Germany</option>
												<option value="372" label="Estonia">Estonia</option>
												<option value="298" label="Faroe Islands">Faroe Islands</option>
												<option value="358" label="Finland">Finland</option>
												<option value="33" label="France">France</option>
												<option value="49" label="Germany">Germany</option>
												<option value="350" label="Gibraltar">Gibraltar</option>
												<option value="30" label="Greece">Greece</option>
												<option value="GG" label="Guernsey">Guernsey</option>
												<option value="36" label="Hungary">Hungary</option>
												<option value="354" label="Iceland">Iceland</option>
												<option value="353" label="Ireland">Ireland</option>
												<option value="IM" label="Isle of Man">Isle of Man</option>
												<option value="39" label="Italy">Italy</option>
												<option value="JE" label="Jersey">Jersey</option>
												<option value="371" label="Latvia">Latvia</option>
												<option value="417" label="Liechtenstein">Liechtenstein</option>
												<option value="370" label="Lithuania">Lithuania</option>
												<option value="352" label="Luxembourg">Luxembourg</option>
												<option value="MK" label="Macedonia">Macedonia</option>
												<option value="356" label="Malta">Malta</option>
												<option value="FX" label="Metropolitan France">Metropolitan France</option>
												<option value="373" label="Moldova">Moldova</option>
												<option value="377" label="Monaco">Monaco</option>
												<option value="ME" label="Montenegro">Montenegro</option>
												<option value="31" label="Netherlands">Netherlands</option>
												<option value="47" label="Norway">Norway</option>
												<option value="48" label="Poland">Poland</option>
												<option value="351" label="Portugal">Portugal</option>
												<option value="40" label="Romania">Romania</option>
												<option value="7" label="Russia">Russia</option>
												<option value="378" label="San Marino">San Marino</option>
												<option value="RS" label="Serbia">Serbia</option>
												<option value="381" label="Serbia and Montenegro">Serbia and Montenegro</option>
												<option value="421" label="Slovakia">Slovakia</option>
												<option value="386" label="Slovenia">Slovenia</option>
												<option value="34" label="Spain">Spain</option>
												<option value="SJ" label="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
												<option value="46" label="Sweden">Sweden</option>
												<option value="41" label="Switzerland">Switzerland</option>
												<option value="380" label="Ukraine">Ukraine</option>
												<option value="SU" label="Union of Soviet Socialist Republics">Union of Soviet Socialist Republics</option>
												<option value="44" label="United Kingdom">United Kingdom</option>
												<option value="379" label="Vatican City">Vatican City</option>
												<option value="AX" label="Åland Islands">Åland Islands</option>										
												<option value="AS" label="American Samoa">American Samoa</option>
												<option value="AQ" label="Antarctica">Antarctica</option>
												<option value="61" label="Australia">Australia</option>
												<option value="BV" label="Bouvet Island">Bouvet Island</option>
												<option value="IO" label="British Indian Ocean Territory">British Indian Ocean Territory</option>
												<option value="CX" label="Christmas Island">Christmas Island</option>
												<option value="CC" label="Cocos [Keeling] Islands">Cocos [Keeling] Islands</option>
												<option value="682" label="Cook Islands">Cook Islands</option>
												<option value="679" label="Fiji">Fiji</option>
												<option value="689" label="French Polynesia">French Polynesia</option>
												<option value="TF" label="French Southern Territories">French Southern Territories</option>
												<option value="671" label="Guam">Guam</option>
												<option value="HM" label="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
												<option value="686" label="Kiribati">Kiribati</option>
												<option value="692" label="Marshall Islands">Marshall Islands</option>
												<option value="691" label="Micronesia">Micronesia</option>
												<option value="674" label="Nauru">Nauru</option>
												<option value="687" label="New Caledonia">New Caledonia</option>
												<option value="64" label="New Zealand">New Zealand</option>
												<option value="683" label="Niue">Niue</option>
												<option value="672" label="Norfolk Island">Norfolk Island</option>
												<option value="MP" label="Northern Mariana Islands">Northern Mariana Islands</option>
												<option value="680" label="Palau">Palau</option>
												<option value="675" label="Papua New Guinea">Papua New Guinea</option>
												<option value="PN" label="Pitcairn Islands">Pitcairn Islands</option>
												<option value="WS" label="Samoa">Samoa</option>
												<option value="677" label="Solomon Islands">Solomon Islands</option>
												<option value="GS" label="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
												<option value="TK" label="Tokelau">Tokelau</option>
												<option value="676" label="Tonga">Tonga</option>
												<option value="688" label="Tuvalu">Tuvalu</option>
												<option value="UM" label="U.S. Minor Outlying Islands">U.S. Minor Outlying Islands</option>
												<option value="678" label="Vanuatu">Vanuatu</option>
												<option value="681" label="Wallis and Futuna">Wallis and Futuna</option>
											</optgroup>
										</select>

                                        <input class="required" id="mobile" onkeyup="checkPhoneNumber(this)" type="number"  name="mobile[]" value="{{old('mobile',$mobile[0])}}"/>
                                        <label id="mobile-error1" style="display:none" class="error error-mobile" for="mobile"></label>
                                        <input type="hidden" name="mobileCheck[]" id="mobileCheck" value="{{old('mobile',$mobile[0])}}">
									</div>
								</div>	
								<div class="form-row">
									<div class="col-md-12 mb-4">
										<label class="input-label">Town or city I live in is</label>								
										<input type="text" name="city[]" class="form-control" value="{{old('city',$city[0])}}"  aria-describedby="inputGroupPrepend3">
										<div class="invalid-feedback">										
										</div>
									</div>
								</div>	
								<div class="form-row">
									<div class="col-md-12 mb-4">
										<label class="input-label">My company or employer is</label>													
										<input name="company[]" value="{{old('company',$company[0])}}" type="text" class="form-control"  aria-describedby="inputGroupPrepend3">			
									</div>
								</div>
								<div class="form-row">
									<div class="col-md-12 mb-4">
										<label class="input-label">My occupation or title is</label>													
										<input type="text" name="jobtitle[]" value="{{old('jobtitle',$job_title[0])}}" class="form-control" placeholder="E.g. Marketing specialist" aria-describedby="inputGroupPrepend3">			
									</div>
								</div>	
								<div class="form-row">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="terms_condition" class="custom-control-input" id="customCheck1">
										<label class="custom-control-label" for="customCheck1"></label><p>I have read, agree upon & accept the <a href="/terms" class="link-color">terms & conditions</a> and <a href="/data-privacy-policy" class="link-color">data privacy policy.</a></p>
										<label id="terms_condition-error" class="error" for="terms_condition" style="display:none;"> </label>
									</div>
								</div>
							</div>
							
							<div class="checkout-btn-wrap form-row mb-4 my-md-5 align-items-center">
								<button id="btn" type="submit" class="btn registration checkout-button-secondary do-checkout-free">Next: Billing <img src="{{cdn('new_cart/images/arrow-next-red.svg')}}" width="20px" height="12px" class="without-hover" alt=""> <img src="{{cdn('new_cart/images/arrow-next-red2.svg')}}" width="20px" height="12px" class="with-hover" alt=""> </button>
							</div>
						</form>	
					</div>
				</div>
				<!---------------- Participant form end--------------->

				<!---------------- My Selection start--------------->
                @include('theme.cart.new_cart.selection')
				<!---------------- My Selection end--------------->
			</div>
		</div>						
	</div>
@stop

@push('scripts')

@if(isset($tigran) && !env('APP_DEBUG'))
<script>
	dataLayer.push({'Event_ID':"{{$tigran['Event_ID'].'p'}}", 'event': 'Add To Cart', 'Product_id' : "{{$tigran['Product_id']}}", 'Price': "{{$tigran['price']}}",'ProductCategory':"{{$tigran['ProductCategory']}}"});
</script>
@endif

<script>

    $("#country").change(function() {
        let mobile = $("#mobile").val()
        $("#mobileCheck").val("+" + this.value + mobile)
    });



    function checkPhoneNumber(phone){

      phone = phone.value.replace(/\s/g,'')
      let validatePhone = false;

      if(phone.length > 3){

         if(phone.substring(0, 3) == '+30' || phone.substring(0, 2) == '30'){

            $("#country").val("30").change();
            //$("#selectCountry-reg").val("30").change();
            validatePhone = true;
         }else if(phone.substring(0, 2) == '69'){
            //phone = '+30'+phone
            validatePhone = true;
            $("#country").val("30").change();
            //$("#selectCountry-reg").val("30").change();
         }
         else if(phone.substring(0, 4) == '+357' || phone.substring(0, 3) == '357'){//cyprus
            validatePhone = true;
            $("#country").val("357").change();
            //$("#selectCountry-reg").val("357").change();
         }else if(phone.substring(0, 1) == '9'){
            //phone = '+357'+phone
            validatePhone = true;
            $("#country").val("357").change();
            //$("#selectCountry-reg").val("357").change();
         }

         /*else if(phone.substring(0, 2) == '+1' || phone.substring(0, 1) == '1'){//usa
            validatePhone = true;

         }else if(phone.substring(0, 2) == '69'){
            phone = '+30'+phone
            validatePhone = true;

         }*/

         else if(phone.substring(0, 3) == '+44' || phone.substring(0, 2) == '44'){//england
            validatePhone = true;
            $("#country").val("44").change();
            //$("#selectCountry-reg").val("44").change();
         }else if(phone.substring(0, 2) == '07' /*|| phone.substring(0, 3) == '073' || phone.substring(0, 3) == '074' || phone.substring(0, 3) == '075' || phone.substring(0, 3) == '076'
            || phone.substring(0, 3) == '077' || phone.substring(0, 3) == '078' || phone.substring(0, 3) == '079'*/){

               //phone = '+44'+phone
               validatePhone = true;
               $("#country").val("44").change();
               //$("#selectCountry-reg").val("44").change();
         }

         //$("#mobile").val(phone)

         //let mobile = '+' + $( "#selectCountry-reg" ).val() + $("#mobile").val()
         //$("#mobileCheck").val( mobile)

      }
    }

$(document).ready(function(){
	@if(old('country_code',$country_code[0]))
    	$("#country").val("{{old('country_code',$country_code[0])}}")
    	$("#country").change();

	@endif
})

</script>
@endpush