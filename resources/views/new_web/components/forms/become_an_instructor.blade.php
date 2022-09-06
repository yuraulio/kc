<div class="form-section cms-rich-text-editor">
    <div class="form-area-wrapper m-0">
        <div class="form-wrapper blue-form w-m-bottom {{ $overlap_class }}">
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
                <input type="hidden" name="qed_form" value="1">

                <div class="submit-area-custom">
                    <button onClick="becomeInstructorSubmit()" type="button" id="sendme1" class="btn btn--md btn--secondary contactUsSubmit">{{ $form["button_text"] ?? "" }} </button>
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