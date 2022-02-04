@php
    $form = [];
    foreach ($column->template->inputs as $input){
        $form[$input->key] = $input->value ;
    }
@endphp

<div class="form-section">
    <div class="form-area-wrapper m-0">
        <div class="form-wrapper blue-form w-m-bottom" style="margin-top: -315px;">
            <form id="doall" method="POST" class="contactUsForm" novalidate="">
                <h3 class="form-h3">{{ $form["title"] }}</h3>
                <label>First name <span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="cname" name="cname">
                </div>
                <label>Last name <span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="csurname" name="csurname">
                </div>
                <label>Email <span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="email" id="cemail" name="cemail">
                </div>
                <label>Mobile phone <span>*</span></label>
                <div class="input-safe-wrapper">
                    <input class="required" type="text" id="ctel" name="ctel">
                </div>
                <label>Your message <span>*</span></label>
                <div class="input-safe-wrapper">
                    <textarea class="required" id="comment" name="cmessage"></textarea>
                </div>

                @if($form["enable_consent"])
                    <div class="checkbox-row custom-checkbox-wrapper">
                        <div class="custom-checkbox">
                            <input type="checkbox" id="accept" name="receive-messages" value="accept">
                            <span></span>
                        </div>
                        
                        <label class="contact-form" for="receive-messages">
                            {!! $form["consent_text"] !!}
                        </label>

                    </div>
                @endif

                <input type="hidden" name="success" value="{{ $form["success_text"] }}">

                <div class="submit-area-custom">
                    <button onClick="contactUsSubmit()" type="button" id="sendme" class="btn btn--md btn--secondary contactUsSubmit">{{ $form["button_text"] }} </button>
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

        /* Contact Us Methods */
        function contactUsSubmit() {
            var thec = $('input#accept');
            if (thec.prop("checked") === false) {
                alert('Please accept the terms and condition and our data privacy policy');
            }
            else {
                var contactUrl = '/contact-us';
                $.ajax({ url: contactUrl, type: "post",
                    data: $(".contactUsForm").serialize(),
                    success: function(data) {
                        //alert('HO');
                        $('.contactUsForm').find("input[type=text], input[type=email], textarea").removeClass('verror');
                        if (Number(data.status) === 0) {
                            $.each(data.errors, function (key, row) {
                                $('.contactUsForm').find('input[name="'+key+'"], textarea[name="'+key+'"]').addClass('verror');
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