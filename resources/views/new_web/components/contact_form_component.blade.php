@php
    $form = [];
    foreach ($column->template->inputs as $input){
        $form[$input->key] = $input->value ?? "";
    }

    $type = $form["form_type"]->id ?? 1;
@endphp

@if ($type == 1)
    <div class="form-section">
        <div class="form-area-wrapper m-0">
            <div class="form-wrapper blue-form w-m-bottom" style="margin-top: -315px; margin-bottom: -20px">
                <form id="doall" method="POST" class="contactUsForm" novalidate="">
                    <h3 class="form-h3">{{ $form["title"] ?? "" }}</h3>
                    <label>First name <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="text" id="first_name" name="first_name">
                    </div>
                    <label>Last name <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="text" id="last_name" name="last_name">
                    </div>
                    <label>Email <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="email" id="email" name="email">
                    </div>
                    <label>Mobile phone <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="text" id="mobile_phone" name="mobile_phone">
                    </div>
                    <label>Your message <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <textarea class="required" id="your_message" name="your_message"></textarea>
                    </div>

                    @if($form["enable_consent"])
                        <div class="checkbox-row custom-checkbox-wrapper">
                            <div class="custom-checkbox">
                                <input type="checkbox" id="accept" name="receive-messages" value="accept">
                                <span></span>
                            </div>
                            
                            <label class="contact-form" for="receive-messages">
                                {!! $form["consent_text"] ?? "" !!}
                            </label>

                        </div>
                    @endif

                    <input type="hidden" name="success" value="{{ $form["success_text"] ?? "" }}">

                    <div class="submit-area-custom">
                        <button onClick="contactUsSubmit()" type="button" id="sendme" class="btn btn--md btn--secondary contactUsSubmit">{{ $form["button_text"] ?? "" }} </button>
                    </div>
                </form>
                <div class="alert-outer" hidden="">
                    <div class="container">
                        <div class="alert-wrapper success-alert">
                            <div class="alert-inner">
                                <p id="contact-success"></p>
                                <a href="javascript:void(0)" class="close-alert"><img src="http://admin.knowcrunch.local/theme/assets/images/icons/alert-icons/icon-close-alert.svg" alt="Close Alert"></a>
                            </div>
                        </div>
                    </div>
                <!-- /.alert-outer -->
                </div>
            </div>
        </div>
    </div>

    @push('components-scripts')
        <script type="text/javascript">
            function contactUsSubmit() {
                var thec = $('input#accept');
                if (thec.prop("checked") === false) {
                    alert('Please accept the terms and condition and our data privacy policy.');
                }
                else {
                    var contactUrl = '/contact-us';
                    $.ajax({ url: contactUrl, type: "post",
                        data: $(".contactUsForm").serialize(),
                        success: function(data) {
                            $('.contactUsForm').find("input[type=text], input[type=email], textarea").removeClass('validate-error');
                            if (Number(data.status) === 0) {
                                $.each(data.errors, function (key, row) {
                                    $('.contactUsForm').find('input[name="'+key+'"], textarea[name="'+key+'"]').addClass('validate-error');
                                    $('.contactUsForm').find('input[name="'+key+'"]').attr('placeholder', row);
                                    $('.contactUsForm').find('textarea[name="'+key+'"]').attr('placeholder', row);
                                });
                            } else {
                                var p = document.getElementById('contact-success').textContent = data['message'];
                                var img = document.createElement('img');
                                img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-success-alert.svg" )
                                img.setAttribute('alt',"Info Alert" )
                                $('#contact-success').append(img);
                                $('.alert-outer').show()
                                $('.contactUsForm').find('input[type=text], input[type=email], textarea').val('');
                                $(".contactUsForm").slideUp();
                                $(".contact-wrap").slideUp();
                            }
                        }
                    });
                }
            }
        </script>
    @endpush
@elseif ($type == 2)
    <div class="form-section">
        <div class="form-area-wrapper m-0">
            <div class="form-wrapper blue-form w-m-bottom" style="margin-top: -315px; margin-bottom: -20px">
                <form id="doall" method="POST" class="contactUsForm" novalidate="">
                    <h3 class="form-h3">{{ $form["title"] ?? "" }}</h3>
                    <label>First name <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="text" id="first_name" name="first_name">
                    </div>
                    <label>Last name <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="text" id="last_name" name="last_name">
                    </div>
                    <label>Position title <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="text" id="position_title" name="position_title">
                    </div>
                    <label>Company name <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="text" id="company_name" name="company_name">
                    </div>
                    <label>Email <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="email" id="email" name="email">
                    </div>
                    <label>Mobile phone <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="text" id="mobile_phone" name="mobile_phone">
                    </div>

                    @if($form["enable_consent"])
                        <div class="checkbox-row custom-checkbox-wrapper">
                            <div class="custom-checkbox">
                                <input type="checkbox" id="accept" name="receive-messages" value="accept">
                                <span></span>
                            </div>
                            
                            <label class="contact-form" for="receive-messages">
                                {!! $form["consent_text"] ?? "" !!}
                            </label>

                        </div>
                    @endif

                    <input type="hidden" name="success" value="{{ $form["success_text"] ?? "" }}">

                    <div class="submit-area-custom">
                        <button onClick="corporateTrainingSubmit()" type="button" id="sendme1" class="btn btn--md btn--secondary contactUsSubmit">{{ $form["button_text"] ?? "" }} </button>
                    </div>
                </form>
                <div class="alert-outer" hidden="">
                    <div class="container">
                        <div class="alert-wrapper success-alert">
                            <div class="alert-inner">
                                <p id="coorporate-success"></p>
                                <a href="javascript:void(0)" class="close-alert"><img src="http://admin.knowcrunch.local/theme/assets/images/icons/alert-icons/icon-close-alert.svg" alt="Close Alert"></a>
                            </div>
                        </div>
                    </div>
                <!-- /.alert-outer -->
                </div>
            </div>
        </div>
    </div>

    @push('components-scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                $.fn.inputFilter = function(inputFilter) {
                    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
                        if (inputFilter(this.value)) {
                            this.oldValue = this.value;
                            this.oldSelectionStart = this.selectionStart;
                            this.oldSelectionEnd = this.selectionEnd;
                        } else if (this.hasOwnProperty("oldValue")) {
                            this.value = this.oldValue;
                            this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                        }
                    });
                };
                $("#ctel").inputFilter(function(value) {
                    return /^\d*$/.test(value);
                });
            });

            //email function
            function corporateTrainingSubmit() {
                var firstname = $("#first_name").val();
                var surname = $("#last_name").val();
                var company = $("#company_name").val();
                var job = $("#position_title").val();
                var email = $("#email").val();
                var tel = $("#mobile_phone").val();
                $("#returnmessage").empty(); // To empty previous error/success message.
                // Checking for blank fields.
                var thec = $('input#accept');
                if (thec.prop("checked") === false) {
                    alert('Please accept our data privacy policy');
                }else {
                    $.ajax({
                        type: "POST",
                        url: "{{route('corporate')}}",
                        data: {
                            'first_name': firstname,
                            'last_name': surname,
                            'company_name': company,
                            'position_title': job,
                            'email': email,
                            'mobile_phone': tel
                        },
                        success: function(data){
                            $('.contactUsForm').find("input[type=text], input[type=email], textarea").removeClass('validate-error');
                            if (Number(data.status) === 0) {
                                $.each(data.errors, function (key, row) {
                                    $('.contactUsForm').find('input[name="'+key+'"], textarea[name="'+key+'"]').addClass('validate-error');
                                    $('.contactUsForm').find('input[name="'+key+'"]').attr('placeholder', row);
                                    $('.contactUsForm').find('textarea[name="'+key+'"]').attr('placeholder', row);
                                });
                            } else {
                                var p = document.getElementById('coorporate-success').textContent = data['message'];
                                var img = document.createElement('img');
                                img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-success-alert.svg" )
                                img.setAttribute('alt',"Info Alert" )
                                $('#coorporate-success').append(img);
                                $('.alert-outer').show()
                                $('#doall').find('input[type=text], input[type=email], textarea').val('');
                                $('#doall').slideUp();
                            }
                        }
                    });
                }
            }

        </script>
    @endpush
@elseif ($type == 3)
    <div class="form-section">
        <div class="form-area-wrapper m-0">
            <div class="form-wrapper blue-form w-m-bottom" style="margin-top: -315px; margin-bottom: -20px">
                <form id="beForm" method="POST" class="contactUsForm" novalidate="">
                    <h3 class="form-h3">{{ $form["title"] ?? "" }}</h3>
                    <label>First name <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="text" id="first_name" name="first_name"/>
                    </div>
                    <label>Last name <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="text"id="last_name" name="last_name">
                    </div>
                    <label>Email <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="email"  id="email" name="email">
                    </div>
                    <label>Mobile phone <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="text" id="mobile_phone" name="mobile_phone">
                    </div>
                    <label>Linkedin profile link <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="text" id="linked_in_profile" name="linked_in_profile">
                    </div>
                    <label>Fluent in languages <span>*</span><br/><small>(separated by a comma)</small> </label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="text" id="fluent_in_languages" name="fluent_in_languages">
                    </div>
                    <label>Experience in training (years) <span>*</span></label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="text" id="experiance_in_training" name='experiance_in_training'>
                    </div>
                    <label>Training topics expertise <span>*</span><br/><small>(separated by a comma)</small> </label>
                    <div class="input-safe-wrapper">
                        <input class="required" type="text" id="training_topics_experiance" name='training_topics_experiance'>
                    </div>

                    @if($form["enable_consent"])
                        <div class="checkbox-row custom-checkbox-wrapper">
                            <div class="custom-checkbox">
                                <input type="checkbox" id="accept" name="receive-messages" value="accept">
                                <span></span>
                            </div>
                            
                            <label class="contact-form" for="receive-messages">
                                {!! $form["consent_text"] ?? "" !!}
                            </label>

                        </div>
                    @endif

                    <input type="hidden" name="success" value="{{ $form["success_text"] ?? "" }}">

                    <div class="submit-area-custom">
                        <button onClick="becomeInstructorSubmit()" type="button" id="sendme1" class="btn btn--md btn--secondary contactUsSubmit">{{ $form["button_text"] ?? "" }} </button>
                    </div>
                </form>
                <div class="alert-outer" hidden="">
                    <div class="container">
                        <div class="alert-wrapper success-alert">
                            <div class="alert-inner">
                                <p id="beIns-success"></p>
                                <a href="javascript:void(0)" class="close-alert"><img src="http://admin.knowcrunch.local/theme/assets/images/icons/alert-icons/icon-close-alert.svg" alt="Close Alert"></a>
                            </div>
                        </div>
                    </div>
                <!-- /.alert-outer -->
                </div>
            </div>
        </div>
    </div>

    @push('components-scripts')
        <script type="text/javascript">

            function becomeInstructorSubmit() {
                var thec = $('input#accept');
                if (thec.prop("checked") === false) {
                    alert('Please accept the terms and condition');
                }
                else {
                    var checkoutUrl = 'applyforbe';
                    var fdata = $("#beForm").serialize();
                    $.ajax({ url: checkoutUrl, type: "post",
                        data: fdata,
                        success: function(data) {
                            $('.contactUsForm').find("input[type=text], input[type=email], textarea").removeClass('validate-error');
                            if (Number(data.status) === 0) {
                                $.each(data.errors, function (key, row) {
                                    $('.contactUsForm').find('input[name="'+key+'"], textarea[name="'+key+'"]').addClass('validate-error');
                                    $('.contactUsForm').find('input[name="'+key+'"]').attr('placeholder', row);
                                    $('.contactUsForm').find('textarea[name="'+key+'"]').attr('placeholder', row);
                                });
                            } else {
                                var p = document.getElementById('beIns-success').textContent = data['message'];
                                var img = document.createElement('img');
                                img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-success-alert.svg" )
                                img.setAttribute('alt',"Info Alert" )

                                $('#beIns-success').append(img);
                                $('.alert-outer').show()
                                $('#beForm').find('input[type=text], input[type=email], textarea').val('');
                                $('#beForm').slideUp();
                                $('.successHide').hide();
                            }
                        }
                    });
                }
            }

        </script>
    @endpush
@endif