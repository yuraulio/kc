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
						<form action="{{route('registration.code_event')}}" method="post" id="participant-form" name="participant-form">
                        @csrf
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
										{{--<option value="0" label="Select a country">Select a country</option>--}}
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
											<option value="385" label="Croatia (+386)">Croatia </option>
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
										<input name="company[]" type="text" class="form-control" value="{{old('company',$company[0])}}"  aria-describedby="inputGroupPrepend3">			
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
								<button id="btn" type="submit" class="btn registration checkout-button-secondary">Next: Billing <img src="{{cdn('new_cart/images/arrow-next-red.svg')}}" width="20px" height="12px" class="without-hover" alt=""> <img src="{{cdn('new_cart/images/arrow-next-red2.svg')}}" width="20px" height="12px" class="with-hover" alt=""> </button>
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

	alphabetizeList('#country');
})

</script>
@endpush