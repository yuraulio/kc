@php
    $form = [];
    foreach ($column->template->inputs as $input){
        $form[$input->key] = $input->value ?? "";
    }

    $overlap = $form['contact_form_top_overlap'] ?? true;
    $overlap_class = $overlap ? 'form-overlap' : 'form-default';
@endphp

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
            <form id="doall" novalidate class="contactUsForm">
                <h3 class="form-h3">{{ $form["giveaway_form_title"] ?? "" }}</h3>
                <label>{{ $form["giveaway_form_first_name"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="cname" name="cname"/>
                </div>
                <label>{{ $form["giveaway_form_last_name"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text"  id="csurname" name="csurname">
                </div>
                <label>{{ $form["giveaway_form_position"] ?? "" }}</label>
                <div class="input-safe-wrapper">
                    <input  type="text" id="cjob" name="cjob">
                </div>
                <label>{{ $form["giveaway_form_company"] ?? "" }}</label>
                <div class="input-safe-wrapper">
                    <input type="text" id="ccompany" name="ccompany">
                </div>
                <label>{{ $form["giveaway_form_email"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="email" id="cemail" name="cemail">
                </div>
                <label>{{ $form["giveaway_form_phone"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required"type="text" id="ctel" name="ctel" >
                </div>

                @if($form["giveaway_form_consent"])
                    <div class="checkbox-row custom-checkbox-wrapper">
                        <div class="custom-checkbox">
                            <input type="checkbox" id="accept" name="accept" value="accept">
                            <span></span>
                        </div>
                        <label for="receive-messages">
                            {!! $form["giveaway_form_consent_text"] ?? "" !!}
                        </label>
                    </div>
                @endif

                <input type="hidden" name="recipient" id="recipient" value="{{ $form["giveaway_form_recipient"] ?? "" }}">

                <div class="submit-area-custom">
                    <button onClick="giveawaySubmit()" id="sendme1" type="button" class="btn btn--md btn--secondary contactUsSubmit1">
                        {{ $form["giveaway_form_button"] ?? "" }} 
                    </button>
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
            var firstname = $("#cname").val();
            var surname = $("#csurname").val();
            var company = $("#ccompany").val();
            var job = $("#cjob").val();
            var email = $("#cemail").val();
            var tel = $("#ctel").val();
            var recipient = $("#recipient").val();
            $("#returnmessage").empty(); // To empty previous error/success message.
            // Checking for blank fields.
            var thec = $('input#accept');
            if (thec.prop("checked") === false) {
                alert('Please accept our data privacy policy');
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
                        'ctel': tel,
                        'recipient': recipient,
                    },
                    success: function(data){
                        $('.contactUsForm').find("input[type=text], input[type=email], textarea").removeClass('validate-error');
                        if (Number(data.status) === 0) {
                            $.each(data.errors, function (key, row) {
                                var message = "This field is required.";
                                $('.contactUsForm').find('input[name="'+key+'"], textarea[name="'+key+'"]').addClass('validate-error');
                                $('.contactUsForm').find('input[name="'+key+'"]').attr('placeholder', message);
                                $('.contactUsForm').find('textarea[name="'+key+'"]').attr('placeholder', message);
                            });
                        } else {
                            var p = document.getElementById('coorporate-success').textContent = "{{ $form['giveaway_form_success'] ?? "" }}";
                            var img = document.createElement('img');
                            img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-success-alert.svg" )
                            img.setAttribute('alt',"Info Alert" )
                            $('#coorporate-success').append(img);
                            $('.success-giveAway').show()
                            $("#doall").slideUp();
                            $("#doall")[0].reset();
                        }

                        /**
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
                        */
                    }
                });
            }
        }
    </script>
@endpush