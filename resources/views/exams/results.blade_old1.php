@extends('exams.exams-layout')
@section('content')
<div class="container">

<div class="row justify-content-center">

    <div class="col-md-12">

        <div class="card" >

            <div class="card-header">

                <img class="account-thumb" src="{{cdn(get_profile_image($image))}}" onerror="this.src='{{cdn('/theme/assets/images/icons/user-circle.svg')}}'" alt="user-circle">
                <div class="pad-left-15">
                <p class="completed"> Completed: {{$end_time}}</p>
                <p class="name"> {{$first_name}} {{$last_name}} </p>
                </div>

            </div>

        </div>

        <div class = "row border-bot">

            <div class="col-md-6" id="chartContainer" style="height: 370px; width: 100%;"></div>

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

@section('head_scripts')
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>

function pieChart(){


    var score = <?php echo $score;?>

    @if($success )
    var dataPoints = [
			{ y: score,color: "green" },
			{ y: 100-score,color: "grey" },

		]
    @else
    var dataPoints = [
			{ y: score,color: "red" },
			{ y: 100-score,color: "grey" },

		]
    @endif

var chart = new CanvasJS.Chart("chartContainer", {

    title: {
						fontColor: "black",
						fontSize: 50,
						horizontalAlign: "center",
						text: score+"%",
						verticalAlign: "center"
					},
	data: [{
        startAngle: 280,
		type: "doughnut",
        //type: 'pie',

        explodeOnClick: false,
		//innerRadius: 60,
		//indexLabelFontSize: 17,

		dataPoints: dataPoints
	}]
});



chart.render();

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
