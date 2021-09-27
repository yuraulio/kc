@extends('exams.exams-layout')
@section('content')
<div class="container">

<div class="row justify-content-center">

    <div class="col-md-12">		

        <div class="card" >

            <div class="card-header">
               
                <img class="account-thumb" src="{{cdn(get_profile_image($image))}}" onerror="this.src='{{cdn('/theme/assets/images/icons/user-profile-placeholder-image.png')}}'" alt="user-profile-placeholder-image">
                <div class="pad-left-15">
                <p class="completed"> Completed: {{$end_time}}</p>
                <p class="name"> {{$first_name}} {{$last_name}} </p>      
                </div>
                 
            </div>

        </div>

        <div class = "row border-bot">
        
            {{--<div class="col-md-6" id="chartContainer" style="height: 370px; width: 100%;"></div>--}}

            
          
                    <div class="col-md-6 chart">
                        <!-- Chart wrapper -->
                        <canvas id="chart-pie" style="height: 370px; width: 100%;" class="chart-canvas"></canvas>
                    </div>
           

    

            <div class="col-md-6 success-fail-text @if($success) pass @else fail @endif">
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
                
            <p> <span class="score"> Score :</span> {{ $score }}%</p>    
            </div>
            
            </div>

        <div class="action">
            <button class="btn" type="button"  onclick="closee()" id ="closee"> close </button>
            @if($showAnswers)
            <button class="btn" type="button" onclick="viewResults()" id="view-results"> view results </button>
            <button class="btn" type="button" onclick="hideResults()" id="hide-results"> hide results </button>
            @endif

        </div>

        <div class="row ">
            @if($showAnswers)
            <div id="answers">

                    @foreach($answers as $key => $answer)
                    <div class="col-md-12">
                        <h3><div class="resulttitle">({{$key+1}}). {!! $answer['question'] !!}</div></h3>
                        @if($indicate_crt_incrt_answers)
                            <p class="result_answer {{$answer['classname']}}"> Your Answer :{!! $answer['given_answer'] !!} </p>
                        @endif

                        @if($displayCorrectAnswer)
                            <p class="result_answer"> Correct Answer : {!! $answer['correct_answer'] !!}</p>
                        @endif
                    </div>
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
      
      
        @if($success)

            const data = {
              
                labels: ['correct','wrong'],
                datasets: [{
                    data: [score,100-score],
                    backgroundColor: [
                      'green',
                      'grey',
                    
                    ],
                    borderWidth:5,
                    
                }]
            };

       
        @else
            const data = {
                labels: ['correct','wrong'],
                    datasets: [{
                    labels: ,
                    data: [score,100-score],
                    backgroundColor: [
                      'red',
                      'grey',
                    
                    ],
                    borderWidth:5,

                }]
            };
        @endif
        
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
                }
            }
        });

    }

    setTimeout("pieChart()", 1000);

</script>



<script>
    function closee(){

        window.open('', '_self', '').close();



    }
    @if($showAnswers)
        function viewResults(){

            document.getElementById("answers").style.display = "block";;
            document.getElementById("hide-results").style.display = "inline";;
            document.getElementById("view-results").style.display = "none";;
        
            //$("#answers").css({"display": "block"});

        }

        function hideResults(){

            document.getElementById("answers").style.display = "none";;
            document.getElementById("hide-results").style.display = "none";;
            document.getElementById("view-results").style.display = "inline";;
        
            //$("#answers").css({"display": "block"});

        }
    @endif
</script>
@stop

