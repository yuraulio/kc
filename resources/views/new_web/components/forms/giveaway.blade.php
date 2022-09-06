<div class="form-section cms-rich-text-editor">

    <div class="alert-outer error-giveAway" hidden>
        <div class="container">
            <div class="alert-wrapper error-alert">
                <div class="alert-inner">
                    <p id="error-give-away"></p>
                    <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
                </div>
            </div>
        </div>
    </div>

    <div class="form-area-wrapper m-0">
        <div class="form-wrapper blue-form w-m-bottom {{ $overlap_class }}">
            <form id="doall" novalidate>
                <h3 class="form-h3">{{ $form["title"] ?? "" }}</h3>
                <label>First name <span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="cfirstname" name="cfirstname"/>
                </div>
                <label>Last name <span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text"  id="csurname" name="csurname">
                </div>
                <label>Position title</label>
                <div class="input-safe-wrapper">
                    <input  type="text" id="cjob" name="cjob">
                </div>
                <label>Company name</label>
                <div class="input-safe-wrapper">
                    <input type="text" id="ccompany" name="ccompany">
                </div>
                <label>Email <span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="email" id="cemail" name="cemail">
                </div>
                <label>Mobile phone <span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required"type="text" id="ctel" name="ctel" >
                </div>
                <div class="checkbox-row custom-checkbox-wrapper">
                    <div class="custom-checkbox">
                        <input type="checkbox" id="accept" name="accept" value="accept">
                        <span></span>
                    </div>{{-- /give_away_terms/Knowcrunch - Όροι Συμμετοχής Boussias Communications.pdf --}}
                    <label for="receive-messages">
                        {!! $form["consent_text"] ?? "" !!}
                    </label>
                </div>
                <div class="submit-area-custom">
                    <button onClick="giveawaySubmit()" id="sendme1" type="button" class="btn btn--md btn--secondary contactUsSubmit1">Submit</button>
                </div>
            </form>
            <div class="alert-outer success-giveAway" hidden>
                <div class="container">
                    <div class="alert-wrapper success-alert">
                        <div class="alert-inner">
                            <p id="coorporate-success">
                            </p>
                            <a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('components-scripts')
    <script type="text/javascript">
        function giveawaySubmit() {
            var firstname = $("#cfirstname").val();
            var surname = $("#csurname").val();
            var company = $("#ccompany").val();
            var job = $("#cjob").val();
            var email = $("#cemail").val();
            //var comment = $("#comment").val();
            var tel = $("#ctel").val();
            $("#returnmessage").empty(); // To empty previous error/success message.
            // Checking for blank fields.
            var thec = $('input#accept');
            if (thec.prop("checked") === false) {
                alert('Please accept our data privacy policy');
            } else {
                if (email == '' || surname == '' || firstname == '' || tel == '') {
                    alert("Please Fill Required Fields");
                } else {
                    // Returns successful data submission message when the entered information is stored in database.
                    $.ajax({
                        type: "POST",
                        url: "/give-away",
                        data: {
                            'cname':firstname,
                            'csurname': surname,
                            'ccompany': company,
                            'cjob': job,
                            'cemail': email,
                            'ctel': tel
                        },
                        success: function(data){
                            if(data['success']){
                                var p = document.getElementById('coorporate-success').textContent = data['message'];
                                var img = document.createElement('img');
                                img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-success-alert.svg" )
                                img.setAttribute('alt',"Info Alert" )
                                $('#coorporate-success').append(img);
                                $('.success-giveAway').show()
                                $("#doall").slideUp();
                                $("#doall")[0].reset(); // To reset form fields on success.
                                //fbq('track', 'Lead');
                            }else{
                                var p = document.getElementById('error-give-away').textContent = data['errors'];
                                var img = document.createElement('img');
                                img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-success-alert.svg" )
                                img.setAttribute('alt',"Info Alert" )
                                $('#error-give-away').append(img);
                                $('.error-giveAway').show()
                            }
                        }
	                });
                }
            }
        }
    </script>
@endpush