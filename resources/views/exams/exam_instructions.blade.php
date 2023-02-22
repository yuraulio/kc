@extends('exams.exams-layout')



@section('content')

@if($exam->examCheckbox !== "" && $exam->examCheckbox)
<div class="container">

        <div class="row justify-content-center">
            <div id="generalDialog"></div>

            <div class="col-md-12">

                <div class="ib-card">


                    <div class="ib-card-body">

                        @if (session('status'))

                            <div class="alert alert-success" role="alert">

                                {{ session('status') }}

                            </div>

                        @endif



                        <div class="row">

                            <div class="col-md-12">

                                <?php echo htmlspecialchars_decode(htmlspecialchars_decode($exam->intro_text)); ?>

                            </div>

                        </div>

                    </div>

                    <div class="">

                            <div class="checkbox">

								  <label>
                                        PASSWORD<br/>
									<input type="password" required name="examPassword" id="examPassword">

                                    <i style="cursor: pointer;" id="togglePassword" class="fa fa-eye"></i>
                                    <!-- <button id="showPassword" class="btn">Show Password</button> -->

								  </label>

								</div>

                            <button id="submitPass" class="btn btn-lg button-secondary-next">START YOUR EXAM NOW</button>

					</div>

                </div>

            </div>

        </div>

    </div>


    @else

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-12">

                <div class="ib-card">


                    <div class="ib-card-body">

                        @if (session('status'))

                            <div class="alert alert-success" role="alert">

                                {{ session('status') }}

                            </div>

                        @endif



                        <div class="row">

                            <div class="col-md-12">

                                <?php echo htmlspecialchars_decode(htmlspecialchars_decode($exam->intro_text)); ?>

                            </div>

                        </div>

                    </div>

                    <div class="">

                        <button id="submitPassΝο" class="btn btn-lg button-secondary-next">START YOUR EXAM NOW</button>

					</div>

                </div>

            </div>

        </div>

    </div>


    @endif

    <script>

        function closeGeneralDialog(){

            $('#generalDialog').empty()
        }

        jQuery(document).ready(function(){

            if(jQuery('#togglePassword').length){
                const togglePassword = document.querySelector("#togglePassword");
                const password = document.querySelector("#examPassword");

                togglePassword.addEventListener("click", function () {
                    // toggle the type attribute
                    const type = password.getAttribute("type") === "password" ? "text" : "password";
                    password.setAttribute("type", type);

                    if(password.getAttribute("type") == 'password'){
                        $('#togglePassword').addClass('fa-eye')
                        $('#togglePassword').removeClass('fa-eye-slash')
                    }else{
                        $('#togglePassword').removeClass('fa-eye')
                        $('#togglePassword').addClass('fa-eye-slash')
                    }

                });
            }




            jQuery('#submitPassΝο').click(function(){
                if('{{$exam->examCheckbox}}' === ""){
                    window.location = '{{ route('exam-start', [$exam->id]) }}';
                }
                else {
                    showAlert('Sorry, wrong password!', 'error')
                    //alert('Sorry, wrong password!');
                }
            });

            jQuery('#submitPass').click(function(){
                var enter_pass = jQuery('#examPassword').val();
                if(enter_pass=='{{$exam->examCheckbox}}'){
                    window.location = '{{ route('exam-start', [$exam->id]) }}';
                }
                else {
                    showAlert('Sorry, wrong password!', 'error')
                    //alert('Sorry, wrong password!');
                }
            });



            function showAlert(msg, type){

                let dialog = `
                        <div>
                            <div class="alert-wrapper ${type}-alert">
                                <div class="alert-inner">
                                    <p>${msg}</p>
                                </div>

                                <div class="close-dialog-general-buttons">
                                    <button onclick="closeGeneralDialog()" class="btn btn-not-exit-exam btn-sm"> OK </button>
                                </div>

                                <!-- /.alert-outer -->
                            </div>
                        </div>
                    `
                $('#generalDialog').append(dialog)

            }



        });




    </script>

@endsection
