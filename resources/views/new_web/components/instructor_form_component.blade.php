@php
    $form = [];
    foreach ($column->template->inputs as $input){
        $form[$input->key] = $input->value ?? "";
    }

    $overlap = $form['instructor_form_top_overlap'] ?? true;
    $overlap_class = $overlap ? 'form-overlap' : 'form-default';
@endphp

<div class="form-section cms-rich-text-editor">
    <div class="form-area-wrapper m-0">
        <div class="form-wrapper blue-form w-m-bottom {{ $overlap_class }}">
            <form id="beForm" method="POST" class="contactUsForm" novalidate="">
                <h3 class="form-h3">{{ $form["instructor_form_title"] ?? "" }}</h3>
                <label>{{ $form["instructor_form_first_name"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="first_name" name="first_name"/>
                </div>
                <label>{{ $form["instructor_form_last_name"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text"id="last_name" name="last_name">
                </div>
                <label>{{ $form["instructor_form_email"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="email"  id="email" name="email">
                </div>
                <label>{{ $form["instructor_form_phone"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="mobile_phone" name="mobile_phone">
                </div>
                <label>{{ $form["instructor_form_linkedin"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="linked_in_profile" name="linked_in_profile">
                </div>
                <label>{{ $form["instructor_form_languages"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="fluent_in_languages" name="fluent_in_languages">
                </div>
                <label>{{ $form["instructor_form_experience"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="experiance_in_training" name='experiance_in_training'>
                </div>
                <label>{{ $form["instructor_form_topics"] ?? "" }}<span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="training_topics_experiance" name='training_topics_experiance'>
                </div>

                @if($form["instructor_form_consent"])
                    <div class="checkbox-row custom-checkbox-wrapper">
                        <div class="custom-checkbox">
                            <input type="checkbox" id="accept" name="receive-messages" value="accept">
                            <span></span>
                        </div>
                        
                        <label class="contact-form" for="receive-messages">
                            {!! $form["instructor_form_consent_text"] ?? "" !!}
                        </label>

                    </div>
                @endif

                <input type="hidden" name="success" value="{{ $form["success_text"] ?? "" }}">
                <input type="hidden" name="qed_form" value="1">
                <input type="hidden" name="recipient" id="recipient" value="{{ $form["instructor_form_recipient"] ?? "" }}">

                <div class="submit-area-custom">
                    <button onClick="becomeInstructorSubmit()" type="button" id="sendme1" class="btn btn--md btn--secondary contactUsSubmit">
                        {{ $form["instructor_form_button"] ?? "" }} 
                    </button>
                </div>
            </form>
            <div class="alert-outer" hidden="">
                <div class="container">
                    <div class="alert-wrapper success-alert">
                        <div class="alert-inner">
                            <p id="beIns-success"></p>
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
                                var message = "This field is required.";
                                $('.contactUsForm').find('input[name="'+key+'"], textarea[name="'+key+'"]').addClass('validate-error');
                                $('.contactUsForm').find('input[name="'+key+'"]').attr('placeholder', message);
                                $('.contactUsForm').find('textarea[name="'+key+'"]').attr('placeholder', message);
                            });
                        } else {
                            var p = document.getElementById('beIns-success').textContent = "{{ $form['instructor_form_success'] ?? "" }}";
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