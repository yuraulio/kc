@extends('exams.exams-layout')



@section('content')
@if($exam->examCheckbox !== "" && $exam->examCheckbox)
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

                    <div class="card-footer" style="text-align:center">

                            <div class="checkbox">

								  <label>
PASSWORD<br/>
									<input type="password" required name="examPassword" id="examPassword"> 

								  </label>

								</div>

                            <button id="submitPass" class="btn btn-primary">START THE EXAM NOW</button>

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

                    <div class="card-footer" style="text-align:center">

                        <button id="submitPassΝο" class="btn btn-primary">START THE EXAM NOW</button>

					</div>

                </div>                

            </div>            

        </div>

    </div>	


    @endif

    <script>

        jQuery(document).ready(function(){
            

            jQuery('#submitPassΝο').click(function(){
                if('{{$exam->examCheckbox}}' === ""){
                    window.location = '{{ route('exam-start', [$exam->id]) }}';
                }
                else {
                    alert('Sorry, wrong password!');
                }
            });

            jQuery('#submitPass').click(function(){
                var enter_pass = jQuery('#examPassword').val();
                if(enter_pass=='{{$exam->examCheckbox}}'){
                    window.location = '{{ route('exam-start', [$exam->id]) }}';
                }
                else {
                    alert('Sorry, wrong password!');
                }
            });
        });

      


    </script>

@endsection