@extends('exams.exams-layout')
@section('content')
<div class="container">

<div class="row justify-content-center">

    <div class="col-md-12">

        {{--<div class="card" >

            <div class="card-header">

                <img class="account-thumb" src="{{cdn(get_profile_image($image))}}" onerror="this.src='{{cdn('/theme/assets/images/icons/user-circle-placeholder.svg')}}'" alt="user-circle">
                <div class="pad-left-15">
                <p class="completed"> Completed: {{$end_time}}</p>
                <p class="name"> {{$first_name}} {{$last_name}} </p>
                </div>

            </div>

        </div>--}}

        <div class = "row border-bot">

            {{--<div class="col-md-6" id="chartContainer" style="height: 370px; width: 100%;"></div>--}}



            <div class="col-md-6 chart">
                <!-- Chart wrapper -->
                <canvas id="chart-pie" style="height: 370px; width: 100%;" class="chart-canvas"></canvas>
            </div>




            <div class="col-md-6 success-fail-text">
                <h2 style="font-weight:700">Thank you for taking your exam with us.<br>Here are your results.</h2>
                <?php

                    if (isset($_GET['s'])) {

                        $shortcodes = [
                            '[student_first_name]' => $first_name,
                            '[student_last_name]' => $last_name,
                            '[student_marks]' => 100,
                        ];

                        echo $text;


                    }
                ?>

                {{-- <p> <span class="score"> Score :</span> {{ $score }}%</p> --}}

                @if(isset($_GET['t']))
                    {!! $endOfTime !!}
                @endif
            </div>

            <?php

/*
                $expirationMonth = '';
                $expirationYear = '';
                $certUrl = trim(url('/') . '/mycertificate/' . base64_encode(Auth::user()->email."--".$certificate->id));
                if($certificate->expiration_date){
                $expirationMonth = date('m',$certificate->expiration_date);
                $expirationYear = date('Y',$certificate->expiration_date);
                }

                $certiTitle = preg_replace( "/\r|\n/", " ", $certificate->certificate_title );

                if(strpos($certificate->certificate_title, '</p><p>')){
                    $certiTitle = substr_replace($certificate->certificate_title, ' ', strpos($certificate->certificate_title, '</p>'), 0);
                }else{
                    $certiTitle = $certificate->certificate_title;
                }

                $certiTitle = urlencode(htmlspecialchars_decode(strip_tags($certiTitle),ENT_QUOTES));

                */


        ?>

            <div class="col-md-6 offset-md-6 share-wrapper">
                <p>Share my results:</p>
                <div>
                    <a class="facebook-post-cert" data-certid="{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}" title="Add this certification to your Facebook profile" href="javascript:void(0)">
                        <img class="linkdein-image-add" src="{{cdn('theme/assets/images/icons/social/events/Facebook.svg')}}" alt="Facebook Add to Profile button">
                    </a>
                    <a class="twitter-post-cert" data-certid="{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}" title="Add this certification to your Twitter profile" href="javascript:void(0)">
                        <img class="linkdein-image-add" src="{{cdn('theme/assets/images/icons/social/events/Twitter.svg')}}" alt="Twitter Add to Profile button">
                    </a>
                    <a type="button" class="linkedin-post cert-post" data-certid="{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}">
                        <img class="linkdein-image-add" src="{{cdn('theme/assets/images/icons/social/events/Linkedin.svg')}}" alt="LinkedIn Add to Profile button">
                    </a>
                </div>
            </div>

            </div>
            @if($showAnswers)
            <div class="row action-wrapper">
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <h2 class="text-sm-center text-md-left text-lg-left" style="font-weight:700">Your answers summary</h2>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-6 text-sm-center text-md-right text-lg-right">

                    <button class="btn button-quaternary" type="button" onclick="toggleResults()" id="toggle-results"> SHOW/HIDE ANSWERS SUMMARY </button>

                </div>
            </div>
            @endif




        <div class="row ">
            @if($showAnswers)
            <div class="col-12" id="answers">

                    @foreach($answers as $key => $answer)
                    <div class="col-md-12">
                        <div class="row resulttitle">
                            <div class="col-1">
                                <span>{{$key+1}}</span>
                            </div>
                            <div class="col-11 question-title">
                                <h4>{!! $answer['question'] !!}</h4>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-3 col-lg-3 desc-answer">
                                            <p>Correct Answer:</p>
                                        </div>
                                        <div class="col-sm-12 col-md-9 col-lg-9 desc-question">
                                            @if($displayCorrectAnswer)
                                                <p class="result_answer">{!! $answer['correct_answer'] !!}</p>
                                            @endif
                                        </div>

                                        <div class="col-sm-12 col-md-3 col-lg-3 desc-answer">
                                            <p>Your Answer:</p>
                                        </div>
                                        <div class="col-sm-12 col-md-9 col-lg-9 desc-question">
                                            @if($indicate_crt_incrt_answers)
                                                <p class="result_answer {{$answer['classname']}}"> {!! $answer['given_answer'] !!} </p>
                                            @endif
                                        </div>



                                    </div>
                                </div>




                            </div>
                        </div>


                    </div>
                    <hr>
                    @endforeach

                </div>
            </div>
            @endif

    </div>

</div>
<a href="#0" class="cd-top cd-is-visible cd-fade-out"><i class="fa fa-chevron-up"></i></a>

</div>

@stop

@section('scripts')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
<script>
    let chartImage = null;

    function pieChart(){


        Chart.pluginService.register({
            beforeDraw: function(chart) {
              if (chart.config.options.elements.center) {
                // Get ctx from string
                var ctx = chart.chart.ctx;

                // Get options from the center object in options
                var centerConfig = chart.config.options.elements.center;
                var fontStyle = centerConfig.fontStyle || 'Arial';
                var txt = centerConfig.text;
                var color = centerConfig.color || '#000';
                var maxFontSize = centerConfig.maxFontSize || 75;
                var sidePadding = centerConfig.sidePadding || 20;
                var sidePaddingCalculated = (sidePadding / 100) * (chart.innerRadius * 2)
                // Start with a base font of 30px
                ctx.font = "30px " + fontStyle;

                // Get the width of the string and also the width of the element minus 10 to give it 5px side padding
                var stringWidth = ctx.measureText(txt).width;
                var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

                // Find out how much the font can grow in width.
                var widthRatio = elementWidth / stringWidth;
                var newFontSize = Math.floor(30 * widthRatio);
                var elementHeight = (chart.innerRadius * 2);

                // Pick a new font size so it will not be larger than the height of label.
                var fontSizeToUse = Math.min(newFontSize, elementHeight, maxFontSize);
                var minFontSize = centerConfig.minFontSize;
                var lineHeight = centerConfig.lineHeight || 25;
                var wrapText = false;

                if (minFontSize === undefined) {
                  minFontSize = 20;
                }

                if (minFontSize && fontSizeToUse < minFontSize) {
                  fontSizeToUse = minFontSize;
                  wrapText = true;
                }

                // Set font settings to draw it correctly.
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
                var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
                ctx.font = fontSizeToUse + "px " + fontStyle;
                ctx.fillStyle = color;

                if (!wrapText) {
                  ctx.fillText(txt, centerX, centerY);
                  return;
                }

                var words = txt.split(' ');
                var line = '';
                var lines = [];

                // Break words up into multiple lines if necessary
                for (var n = 0; n < words.length; n++) {
                  var testLine = line + words[n] + ' ';
                  var metrics = ctx.measureText(testLine);
                  var testWidth = metrics.width;
                  if (testWidth > elementWidth && n > 0) {
                    lines.push(line);
                    line = words[n] + ' ';
                  } else {
                    line = testLine;
                  }
                }

                // Move the center up depending on line height and number of lines
                centerY -= (lines.length / 2) * lineHeight;

                for (var n = 0; n < lines.length; n++) {
                  ctx.fillText(lines[n], centerX, centerY);
                  centerY += lineHeight;
                }
                //Draw text in center
                ctx.fillText(line, centerX, centerY);
              }
            }
        });

        var score = <?php echo $score;?>




        const data = {

            labels: ['correct','incorrect'],
            datasets: [{
                data: [score,100-score],
                backgroundColor: [
                    'green',
                    'red',

                ],
                borderWidth:5,

            }]
        };


        var $this = document.getElementById("chart-pie").getContext("2d");
        //var $this = $("#chart-pie")
        var chart = new Chart($this, {
            type: 'doughnut',
            data,
            options: {
                elements: {
                  center: {
                    text: score+"%",
                    color: '#000000', // Default is #000000
                    fontStyle: 'Arial', // Default is Arial
                    sidePadding: 20, // Default is 20 (as a percentage)
                    minFontSize: 25, // Default is 20 (in px), set to false and text will not wrap.
                    lineHeight: 25 // Default is 25 (in px), used for when text wraps
                  }
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 30,
                        boxWidth: 10
                    }
                },
                animation: {
                    onComplete: function() {
                        chartImage = chart.toBase64Image();
                        $('#chart-pie').css('background-color', 'white')

                        // var a = document.createElement('a');
                        // a.href = chartImage;
                        // a.download = 'my_file_name.png';

                        // // Trigger the download
                        // a.click();
                    }
                }
            }
        });



    }

    setTimeout("pieChart()", 1000);

    $(document).on('click', '.facebook-post-cert', function() {
      var getUrl = window.location;
      var baseUrl = getUrl .protocol + "//" + getUrl.host;
      var pathname = getUrl.pathname

      var certificateId = $(this).attr('data-certid');


      $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          type: 'POST',
          url: "/mycertificate/save-success-chart",
          data:{
                image: chartImage,
                certificate_id: certificateId
            },


          success: function(data) {
            url = data.path
            url = url.replace('\\','/')

            if(data){
                var fbpopup = window.open(`http://www.facebook.com/sharer.php?u=${decodeURI(baseUrl)}/${decodeURI(url)}`, "pop", "width=600, height=400, scrollbars=no");
                return false;
            }

          }
      });
   })


    $(document).on('click', '.linkedin-post', function() {

        var getUrl = window.location;
        var baseUrl = getUrl .protocol + "//" + getUrl.host;
        var pathname = getUrl.pathname
        var certificateId = $(this).attr('data-certid');
        var exam = pathname.split("/").pop();

         $.ajax({
         headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
           type: 'POST',
           url: "/mycertificate/save-success-chart",
           data:{
                image: chartImage,
                certificate_id: certificateId,
                exam: exam
            },
            success: function(data) {

                let path = data.path
                let certiUrl = path.replace('\\','/')
                certiUrl = baseUrl+'/'+certiUrl
                let certiTitle = data.certiTitle
                let certiIssueYear = data.certiCreateDateY;
                let certiIssueMonth = data.certiCreateDateM;
                let certiExpMonth = data.certiExpMonth;
                let certiExpYear = data.certiExpYear;
                let certiCredential = data.certiCredential;

                if(data){

                    var fbpopup = window.open(`https://www.linkedin.com/profile/add?startTask=${certiTitle}&name=${certiTitle}&organizationId=3152129&issueYear=${certiIssueYear}
                    &issueMonth=${certiIssueMonth}&expirationYear=${certiExpYear}&expirationMonth=${certiExpMonth}&certUrl=${certiUrl}&certId=${certiCredential}`, "pop", "width=600, height=400, scrollbars=no");
                    return false;
                }
            }
        });

    })


   $(document).on('click', '.twitter-post-cert', function() {
      var getUrl = window.location;
      var baseUrl = getUrl .protocol + "//" + getUrl.host;
      var pathname = getUrl.pathname

      var certificateId = $(this).attr('data-certid');

      $.ajax({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
          type: 'POST',
          url: "/mycertificate/save-success-chart",
          data:{image: chartImage,certificate_id: certificateId},
          success: function(data) {

            url = data.path
            url = url.replace('\\','/')
            if(data){
                var fbpopup = window.open(`http://twitter.com/share?url=${decodeURI(baseUrl)}/${decodeURI(url)}`, "pop", "width=600, height=400, scrollbars=no");
                return false;
            }

          }
      });
   })



</script>



<script>
    function closee(){

        window.open('', '_self', '').close();



    }
    @if($showAnswers)
        function toggleResults(){
            $('#answers').toggle();
        }
        // function viewResults(){

        //     document.getElementById("answers").style.display = "block";;
        //     document.getElementById("hide-results").style.display = "inline";;
        //     document.getElementById("view-results").style.display = "none";;

        //     //$("#answers").css({"display": "block"});

        // }

        // function hideResults(){

        //     document.getElementById("answers").style.display = "none";;
        //     document.getElementById("hide-results").style.display = "none";;
        //     document.getElementById("view-results").style.display = "inline";;

        //     //$("#answers").css({"display": "block"});

        // }
    @endif
</script>
@stop

