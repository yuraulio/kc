@php
    $form = [];
    foreach ($column->template->inputs as $input){
        $form[$input->key] = $input->value ?? "";
    }

    $overlap = $form['corporate_training_top_overlap'] ?? true;
    $overlap_class = $overlap ? 'form-overlap' : 'form-default';
@endphp

<div class="form-section cms-rich-text-editor">
    <div class="form-area-wrapper m-0">
        <div class="form-wrapper blue-form w-m-bottom {{ $overlap_class }}">
            <form id="doall" method="POST" class="contactUsForm" novalidate="">
                <h3 class="form-h3">{{ $form["corporate_training_title"] ?? "" }}</h3>
                <label>{{ $form["corporate_training_first_name"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="first_name" name="first_name">
                </div>
                <label>{{ $form["corporate_training_last_name"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="last_name" name="last_name">
                </div>
                <label>{{ $form["corporate_training_position"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="position_title" name="position_title">
                </div>
                <label>{{ $form["corporate_training_company"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="company_name" name="company_name">
                </div>
                <label>{{ $form["corporate_training_email"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="email" id="corporate_email" name="email">
                </div>
                <label>{{ $form["corporate_training_phone"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="mobile_phone" name="mobile_phone">
                </div>

                @if($form["corporate_training_consent"])
                    <div class="checkbox-row custom-checkbox-wrapper">
                        <div class="custom-checkbox">
                            <input type="checkbox" id="accept" name="receive-messages" value="accept">
                            <span></span>
                        </div>

                        <label class="contact-form" for="receive-messages">
                            {!! $form["corporate_training_consent_text"] ?? "" !!}
                        </label>

                    </div>
                @endif

                <input type="hidden" name="success" value="{{ $form["corporate_training_success"] ?? "" }}">
                <input type="hidden" name="qed_form" value="1">
                <input type="hidden" name="recipient" id="recipient" value="{{ $form["corporate_training_recipient"] ?? "" }}">

                <div class="submit-area-custom">
                    <button onClick="corporateTrainingSubmit()" type="button" id="sendme1" class="btn btn--md btn--secondary contactUsSubmit">
                        {{ $form["corporate_training_button"] ?? "" }}
                    </button>
                </div>
            </form>
            <div class="alert-outer" hidden="">
                <div class="container">
                    <div class="alert-wrapper success-alert">
                        <div class="alert-inner">
                            <p id="coorporate-success"></p>
                            <a href="javascript:void(0)" class="close-alert"><img src="/theme/assets/images/icons/alert-icons/icon-close-alert.svg" alt="Close Alert"></a>
                        </div>
                    </div>
                </div>
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
            var email = $("#corporate_email").val();
            var tel = $("#mobile_phone").val();
            var recipient = $("#recipient").val();
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
                        'mobile_phone': tel,
                        'qed_form': 1,
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
                            var p = document.getElementById('coorporate-success').textContent = "{{ $form['corporate_training_success'] ?? "" }}";
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
