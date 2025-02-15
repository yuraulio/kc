@php
    $form = [];
    foreach ($column->template->inputs as $input){
        $form[$input->key] = $input->value ?? "";
    }

    $overlap = $form['contact_form_top_overlap'] ?? true;
    $overlap_class = $overlap ? 'form-overlap' : 'form-default';
@endphp

<div class="form-section cms-rich-text-editor">
    <div class="form-area-wrapper m-0">
        <div class="form-wrapper blue-form w-m-bottom {{ $overlap_class }}">
            <form id="doall" method="POST" class="contactUsForm" novalidate="">
                <h3 class="form-h3">{{ $form["contact_form_title"] ?? "" }}</h3>
                <label>{{ $form["contact_form_first_name"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="first_name" name="first_name">
                </div>
                <label>{{ $form["contact_form_last_name"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="last_name" name="last_name">
                </div>
                <label>{{ $form["contact_form_email"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="email" id="email" name="email">
                </div>
                <label>{{ $form["contact_form_phone"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="mobile_phone" name="mobile_phone">
                </div>
                <label>{{ $form["contact_form_message"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <textarea class="required" id="your_message" name="your_message"></textarea>
                </div>

                @if($form["contact_form_consent"])
                    <div class="checkbox-row custom-checkbox-wrapper">
                        <div class="custom-checkbox">
                            <input type="checkbox" id="accept" name="receive-messages" value="accept">
                            <span></span>
                        </div>

                        <label class="contact-form" for="receive-messages">
                            {!! $form["contact_form_consent_text"] ?? "" !!}
                        </label>

                    </div>
                @endif

                <input type="hidden" name="success" value="{{ $form["success_text"] ?? "" }}">
                <input type="hidden" name="qed_form" value="1">
                <input type="hidden" name="recipient" id="recipient" value="{{ $form["contact_form_recipient"] ?? "" }}">
                <div class="submit-area-custom">
                 
                    <button onClick="contactUsSubmit()" type="button" id="sendme" class="btn btn--md btn--secondary contactUsSubmit">
                        {{ $form["contact_form_button"] ?? "" }}
                    </button>
                </div>
            </form>
            <div class="alert-outer" hidden="">
                <div class="container">
                    <div class="alert-wrapper success-alert">
                        <div class="alert-inner">
                            <p id="contact-success"></p>
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
                                var message = "This field is required.";
                                $('.contactUsForm').find('input[name="'+key+'"]').attr('placeholder', message);
                                $('.contactUsForm').find('textarea[name="'+key+'"]').attr('placeholder', message);
                            });
                        } else {
                            var p = document.getElementById('contact-success').textContent = "{{ $form['contact_form_success'] ?? "" }}";
                            var img = document.createElement('img');
                            img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-success-alert.svg" )
                            img.setAttribute('alt',"Info Alert" )
                            $('#contact-success').append(img);
                            $('.alert-outer').show()
                            $('.contactUsForm').find('input[type=text], input[type=email], textarea').val('');
                            $(".contactUsForm").slideUp();
                            $(".contact-wrap").slideUp();
                            
                            dataLayer.push({'event': 'kc_contact_leads'});
                        }
                    }
                });
            }
        }
    </script>
@endpush
