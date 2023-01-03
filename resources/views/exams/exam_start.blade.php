@extends('exams.exams-layout')

@section('content')
<?php
$activeQuestion = 0;
$duration = $exam->duration;
$hours = intval($duration/60);
$minutes = $duration%60;
$seconds = 0;
?>
<script>
var EFFECT                          = 'bounceInDown';
var DURATION                        = 500;
var DIV_REFERENCE                   = jQuery(".question_div");
var MAXIMUM_QUESTIONS               = jQuery(".question_div").length;
var VISIBLE_ELEMENT                 = ".question_div:visible";
var HINTS                           = 0;
var ANSWERED                        = ' answered';
var NOT_ANSWERED                    = ' not-answered';
var ANSWER_MARKED                   = ' marked';
var NOT_VISITED                     = ' not-visited';
var TOTAL_ANSWERED                  = 0;
var TOTAL_MARKED                    = 0;
var TOTAL_NOT_VISITED               = MAXIMUM_QUESTIONS;
var TOTAL_NOT_ANSWERED              = MAXIMUM_QUESTIONS;
var HOURS                           = 0;
var MINUTES                         = 0;
var SECONDS                         = 0;
var SPENT_TIME                      = [];
var LOOP_AGAIN                      = 0;


function scrollCurrentQuestionRow(){

//var offset = $('.not-answered').offsetTop; // Contains .top and .left



// console.log($('.136').offset())
// let elem = $('.not-answered.106')


// container.animate({
//           scrollTop: elem.offset().top
//       });

const elementInScrollableDiv = document.querySelector('.active-question')
const positionFromTopOfScrollableDiv = elementInScrollableDiv.offsetTop



const scrollableDivElement = document.querySelector('.content1')

scrollableDivElement.scrollTop = positionFromTopOfScrollableDiv - 10



// const innerDivPos = document.querySelector('.active-question').offsetTop

// console.log('offset: ', innerDivPos)

// document
//     .getElementById('container1')
//     .scrollTo({ top: innerDivPos, behavior: 'smooth' })



}



// onclick of next button
function nextQues(mark) {
    if (mark === undefined) {
    mark = 0;
    }
    jQuery('.prev').removeClass('hide');
    var eJson = JSON.parse(window.examVar);
    list = $('#'+window.actQues+' input ');
    var showx = 0;
    var currQues = window.actQues;
    var finalQues = window.actQues;
    var lastQues = 0;
    answer_status = 0;
    is_marked = 0;
    var firstNonConfirm = 0;
    var allUnanswered = [];
    var allUnansweredPlus = [];


    jQuery.each(eJson, function(index, quest) {

        if(firstNonConfirm==0 && (quest.given_ans=="" || quest.mark_status==1))   {
            firstNonConfirm = quest.id;
        }
        if(showx==1 && LOOP_AGAIN ==0) {
            showSpecificQuestion(quest.id);
            showx = 0;
            currQues = quest.id;
        }
        if(quest.id==window.actQues) {
            var given_ans = "";
            var arrayName = [];
            if(list!=0) {
                list.each(function(index, value){
                    element_type = jQuery(value).attr('type');
                    switch(element_type)
                    {
                        case 'radio':
                             if(jQuery(value).prop('checked'))
                                given_ans = jQuery(value).val();
                             break;
                        case 'checkbox':
                             if(jQuery(value).prop('checked'))
                             arrayName.push(jQuery(value).val());
                             break;
                    }
                });
            }
            if(given_ans=="" && arrayName.length > 0 ) {
                //given_ans = arrayName.join("|");

            }
            if(given_ans!="") {
                quest.given_ans = given_ans;
                answer_status = 1;
                if(mark==1) {
                    quest.mark_status = 1;
                    is_marked = 1;
                }
                else {

                    quest.mark_status = 2;
                    is_marked = 2;
                }

            }else{

                answer_status = 1;
                quest.mark_status = 1;
                is_marked = 1;
            }
               // allUnansweredPlus.push(quest.id);
            showx = 1;
        }
        lastQues = quest.id;

        if(quest.given_ans=="" || quest.mark_status==1 ) {
            allUnanswered.push(quest.id);
            allUnansweredPlus.push(quest.id);
        }
    });

    window.examVar = JSON.stringify(eJson);
    updateStats();
    window.actQues = currQues;
    if(currQues==lastQues) {
      //  jQuery('.next').addClass('hide');

    } else {
      //  jQuery('.next').removeClass('hide');
    }
    if(finalQues==lastQues && LOOP_AGAIN==0 && mark!=5) {
        if(firstNonConfirm==0) {
           // alert("You have completed the exam. Please click 'I AM FINISHED WITH MY EXAM' ");
        }
        else {
            showSpecificQuestion(firstNonConfirm);
            LOOP_AGAIN = 1;
            showx = 0;
            currQues = firstNonConfirm;
            window.actQues = currQues;
        }
    }
    else if(showx==1 && mark!=5) {
        var lastInArray = 1;
        var firstUnanswered = 0;
        showx = 0;
        if(allUnanswered.length==0) {
            //  alert("You have completed the exam. Please click 'I AM FINISHED WITH MY EXAM' ");
              showSpecificQuestion(lastQues);
              currQues = lastQues;
              window.actQues = currQues;
        }
        else {
             jQuery.each(allUnansweredPlus, function(index, quest) {
         //    console.log(currQues);
              if(lastInArray==0) {
                return;
              }
              if(firstUnanswered==0) {
                firstUnanswered = quest;
              }
              if(showx==1) {
                showSpecificQuestion(quest);
                currQues = quest;
                window.actQues = currQues;
                lastInArray = 0;
              }
              if(quest==currQues) {
                showx = 1;
              }
             });
            if(lastInArray==1) {
                showSpecificQuestion(firstUnanswered);
                currQues = firstUnanswered;
                window.actQues = currQues;
            }
        }
    }
    scrollCurrentQuestionRow()
    TOTAL_NOT_ANSWERED  = jQuery(".not-answered").length;
    TOTAL_NOT_VISITED   = jQuery(".not-visited").length;
    TOTAL_MARKED = jQuery(".marked").length;
}

function prevQues() {
    jQuery('.next').removeClass('hide');
    var eJson = JSON.parse( window.examVar );
    var showx = 0;
    var currQues = window.actQues;
    var lastQues = 0;
    //console.log(eJson)
    eJson.reverse();
    jQuery.each(eJson, function(index, quest) {
        if(showx==1) {
            showSpecificQuestion(quest.id);
            showx = 0;
            currQues = quest.id;
        }
        if(quest.id==window.actQues) {
            showx = 1;
        }
        lastQues = quest.id;
    });
    window.actQues = currQues;
    if(currQues==lastQues) {
      //  jQuery('.prev').addClass('hide');
    } else {
      //  jQuery('.prev').removeClass('hide');
    }

    scrollCurrentQuestionRow()
}


function showSpecificQuestion(qid, changeactive) {

    if (changeactive === undefined) {
    changeactive = 0;
    }
    var eJson = JSON.parse( window.examVar );
    jQuery.each(eJson, function(index, quest) {
        lastQues = quest.id;
    });
    if(changeactive == 1)
         window.actQues = qid;

    if(qid==lastQues) {
     //   jQuery('.next').addClass('hide');

    } else {
     //   jQuery('.next').removeClass('hide');
    }
    //console.log(eJson)
    eJson.reverse();
    jQuery.each(eJson, function(index, quest) {
        lastQues = quest.id;
    });
    if(qid==lastQues) {
     //   jQuery('.prev').addClass('hide');
    } else {
      //  jQuery('.prev').removeClass('hide');
    }

    // remove active-question class

    $.each($('#pallete_list li'), function(index, value){
        if($(value).hasClass('active-question')){
            $(value).removeClass('active-question')
        }
    })


    $('.question_div').css('display', 'none');
    $('#'+qid).css('display', 'block');
    $('#pallete_list li').css('border', 'none');
    $('#pallete_list li.'+qid).css('border', 'solid 2px black');
    $('#pallete_list li.'+qid).css('border-radius', '20%');
    $('#pallete_list li').css('box-shadow', 'none');
    // $('#pallete_list li.'+qid).css('box-shadow', 'black 1px 2px 8px');
    $('#pallete_list li.'+qid).addClass('active-question')

}

function clearAnswer() {
    var eJson = JSON.parse( window.examVar );
    list = $('#'+window.actQues + ' input ');
    answer_status = 0;
    is_marked = 0;
    jQuery.each(eJson, function(index, quest) {
        if(quest.id==window.actQues) {
            var given_ans = "";
            var arrayName = [];
            if(list!=0) {
                list.each(function(index, value){
                    element_type = jQuery(value).attr('type');

                    switch(element_type)
                    {
                        case 'radio':
                             jQuery(value).prop('checked', false);
                             break;
                        case 'checkbox':
                             jQuery(value).prop('checked', false);
                             jQuery(value).attr('checked', false);
                             break;
                    }
                });
            }
            quest.given_ans = "";
            answer_status = 0;
            quest.mark_status = 0;
            is_marked = 0;
        }
        lastQues = quest.id;
    });

    window.examVar = JSON.stringify(eJson);

    updateStats();
}

function finishExam() {


    TOTAL_NOT_ANSWERED  = jQuery(".not-answered").length;
    TOTAL_NOT_VISITED   = jQuery(".not-visited").length;
    TOTAL_MARKED = jQuery(".marked").length;

    var r = confirm("Are you sure you want to finish the exam?");
    if (r == true) {



        nextQues(5);
        if(TOTAL_NOT_ANSWERED!=0 || TOTAL_NOT_VISITED != 0 || TOTAL_MARKED !=0) {
            prevQues();
            alert("You need to answer all questions to finish the exam!");
            return;
        }

        document.getElementById("ExamFinish").disabled = true;
        window.exam_finish = 1;
        var retrievedObject = window.examVar;
        var startTime = localStorage.getItem("examStart<?php echo $exam->id;?>-{{$user_id}}");
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery.ajax({
        method: "POST",
        url: "{{ route('save-data') }}",
        data: { examJson: retrievedObject, start_time: startTime, student_id: {{$user_id}}, exam_id: {{$exam->id}} },
        success: function() {
          //  alert("Exam Completed Successfully");
            <?php if($exam->indicate_crt_incrt_answers || $exam->display_crt_answers) {
            ?>
                window.location = "{{ route('exam-results', [$exam->id,'s'=>1]) }}";
            <?php
            }
            else
            {
            ?>
                window.close('fs');
            <?php
            }
            ?>
        }
        });
    } else {
    }
}

function outOfTime() {
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    var retrievedObject = window.examVar;
    var startTime = localStorage.getItem("examStart<?php echo $exam->id;?>-{{$user_id}}");

    jQuery.ajax({
    method: "POST",
    url: "{{ route('save-data') }}",
    data: { examJson: retrievedObject, start_time: startTime, student_id: {{$user_id}}, exam_id: {{$exam->id}} },
    success: function() {
       // alert("Time Over. Exam Completed Successfully");

       window.location = "{{ url('exam-results/' . $exam->id) }}?s=1&t=1";
    }
    });
}

function forceFinish() {

    jQuery("#endExamText").removeClass("hidden");
    jQuery(".container-fluid").addClass("hidden");
    nextQues(5);
    window.exam_finish = 1;
    var retrievedObject = window.examVar;
    var startTime = localStorage.getItem("examStart<?php echo $exam->id;?>-{{$user_id}}");
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.ajax({
    method: "POST",
    url: "{{ route('save-data') }}",
    data: { examJson: retrievedObject, start_time: startTime, student_id: {{$user_id}}, exam_id: {{$exam->id}} },
    success: function() {
       // alert("Time Over. Exam Completed Successfully");
            <?php if($exam->indicate_crt_incrt_answers || $exam->display_crt_answers) {
            ?>
            setTimeout(function(){  window.location = "{{ route('exam-results', [$exam->id,'s'=>1]) }}"; }, 50000);


            <?php
            }
            else
            {
            ?>
                window.close('fs');
            <?php
            }
            ?>
    }
    });
}

function updateStats() {

    //Assign the appropriate clase based on the answer type
    class_name = NOT_ANSWERED;
    if(answer_status) {

        if(is_marked == 1){
            class_name = ANSWER_MARKED;
        }else if (is_marked == 2){
            class_name = ANSWERED;
        }
    }

    //Update the palette with status

    jQuery(".question-palette .pallete-elements."+window.actQues)
    .removeClass(NOT_VISITED + NOT_ANSWERED + ANSWERED + ANSWER_MARKED)
    .addClass(class_name);


    TOTAL_NOT_ANSWERED  = jQuery(".not-answered").length;
    TOTAL_NOT_VISITED   = jQuery(".not-visited").length;
    TOTAL_MARKED        = jQuery(".marked").length;
    TOTAL_ANSWERED      = jQuery(".answered").length;

    jQuery('#palette_total_answered').html(TOTAL_ANSWERED);
    jQuery('#palette_total_marked').html(TOTAL_MARKED);
    jQuery('#palette_total_not_visited').html(TOTAL_NOT_VISITED);
    jQuery('#palette_total_not_answered').html(TOTAL_NOT_ANSWERED);

    if(TOTAL_NOT_ANSWERED==0){
        //jQuery("#ExamFinish").show();
    }

    if (typeof(Storage) !== "undefined") {
        localStorage.setItem("examStorage<?php echo $exam->id;?>-{{$user_id}}", window.examVar);
    }

}

function intilizetimer(hrs, mins, sec) {
   HOURS       = hrs;
   MINUTES     = mins;
   SECONDS     = sec;

   $("#hours").text(HOURS);
   if(MINUTES<10)
    $("#mins").text("0"+MINUTES);
   else
   $("#mins").text(MINUTES);
   if(SECONDS<10)
    $("#seconds").text("0"+SECONDS);
   else
    $("#seconds").text(SECONDS);
   startInterval();
   setTimeout(function(){
      jQuery("#timerdiv").addClass('text-success');
   }, 1000);
}

function startInterval() {
   timer= setInterval("tictac()", 1000);
}

function stopInterval() {
   clearInterval(timer);
}

function tictac(){

    TOTAL_NOT_VISITED   = jQuery(".not-visited").length;


    if(TOTAL_NOT_ANSWERED!=0 || TOTAL_NOT_VISITED != 0  || TOTAL_MARKED != 0){
        document.getElementById("ExamFinish").setAttribute('disabled', 'disabled');
    }else{
        document.getElementById("ExamFinish").removeAttribute('disabled');
    }

    SECONDS--;
    SPENT_TIME = jQuery('#time_spent').val();
    if(isNaN(SPENT_TIME))
        SPENT_TIME = 0;
    else
        SPENT_TIME = parseInt(SPENT_TIME)+1;
    jQuery('#time_spent').val(SPENT_TIME);
    if(SECONDS<=0)
    {
        MINUTES--;

        if(MINUTES<1)
        {
            if(HOURS==0) {
            $("#timerdiv").removeClass('text-success');
            $("#timerdiv").addClass("text-danger");
            }
        }
        if(MINUTES<0)
        {
            if(HOURS!=0) {
                MINUTES = 59;
                HOURS =  HOURS-1;
                SECONDS = 59;
                $("#mins").text(MINUTES);
                $("#hours").text(HOURS);
                return;
            }
            stopInterval();
            //forceFinish();
            outOfTime();
            return;
        }
        if(MINUTES<10)
            $("#mins").text("0"+MINUTES);
        else
            $("#mins").text(MINUTES);
    SECONDS=60;
    }
    if(MINUTES>=0) {
        if(SECONDS<10)
         $("#seconds").text("0"+SECONDS);
        else
         $("#seconds").text(SECONDS);
    }
    else
    $("#seconds").text('00');
}

function initializeView() {


    document.getElementById("ExamFinish").setAttribute('disabled', 'disabled');

    var eJson = JSON.parse( window.examVar );
    window.startTime = localStorage.getItem("examStart<?php echo $exam->id;?>-{{$user_id}}");
    currentTime = '<?php echo date( "Y-m-d")."T".date( "H:i:s") ?>';
    var sTime = new Date(window.startTime);
    var cTime = new Date(currentTime);
    var cSeconds = (cTime.getTime() - sTime.getTime()) / 1000;
    var sSeconds = <?php echo $exam->duration*60; ?>;
    var tSeconds = sSeconds - cSeconds;
    if(tSeconds<=0) {
        setTimeout(function(){
            forceFinish();
        }, 2000);
    }
    else {
        minutes = parseInt(tSeconds/60);
        seconds = tSeconds- minutes*60;
        hours = parseInt(minutes/60);
        minutes = minutes%60;
        intilizetimer(hours, minutes, seconds);
    }
    var firstQues = -1;
    jQuery.each(eJson, function(index, quest) {
        if(firstQues==-1)
            firstQues = quest.id;
        window.actQues = quest.id;
        window.answer_status = 0;
        window.is_marked = 0;
        list = $('#'+quest.id + ' input ');
        if(list!=0) {
            list.each(function(index, value){
                element_type = jQuery(value).attr('type');
                switch(element_type)
                {
                    case 'radio':
                            jQuery(value).prop('checked', false);
                            if(quest.given_ans!="") {
                                jQuery("input[name="+quest.id+"]" ).each(function( index ) {
                                  var currentValue = jQuery(this).attr('value');
                                  if(currentValue == quest.given_ans) {
                                    jQuery(this).prop('checked', true);
                                  }
                                });
                                //init edw
                                window.answer_status = 1;
                                window.is_marked = quest.mark_status;
                               /* if(quest.mark_status==2){
                                    window.is_marked = 2;

                                }else if(quest.mark_status==1){

                                    window.is_marked = 1;
                                }*/

                            }else{
                                if(quest.mark_status==2){
                                    window.answer_status = 1;
                                    window.is_marked = 2;

                                }else if(quest.mark_status==1){
                                    window.answer_status = 1;
                                    window.is_marked = 1;
                                }
                            }
                            break;
                    case 'checkbox':
                            jQuery(value).prop('checked', false);
                            jQuery(value).attr('checked', false);
                            if(quest.given_ans!="") {
                                var res = quest.given_ans.split("|");
                                jQuery.each( res, function( i, val ) {
                                    jQuery("input[name="+quest.id+"]" ).each(function( index ) {
                                      var currentValue = jQuery(this).attr('value');
                                      if(currentValue == val) {
                                        jQuery(this).prop('checked', true);
                                        jQuery(this).attr('checked', true);
                                      }
                                    });
                                });
                                window.answer_status = 1;
                                window.is_marked = quest.mark_status;
                                /*if(quest.mark_status==2){
                                    window.is_marked = 2;

                                }else if(quest.mark_status==1){


                                    window.is_marked = 1;
                                }*/

                            }else{
                                if(quest.mark_status==2){
                                    window.answer_status = 1;
                                    window.is_marked = 2;

                                }else if(quest.mark_status==1){
                                    window.answer_status = 1;
                                    window.is_marked = 1;
                                }
                            }
                            break;
                }
            });
        }

        updateStats();
    });

    window.actQues = firstQues;
    // hide popup modal
    jQuery("#overlay-loading").hide();
}
var examJson = `<?php echo json_encode($ex_contents); ?>`;
var startTime = '<?php echo date( "Y-m-d")."T".date( "H:i:s") ?>';
var exam_finish = 0;
if (typeof(Storage) !== "undefined") {
    // Code for localStorage/sessionStorage.
    if (localStorage.getItem("examStorage<?php echo $exam->id;?>-{{$user_id}}") === null) {
        localStorage.setItem("examStorage<?php echo $exam->id;?>-{{$user_id}}", examJson);
        localStorage.setItem("examStart<?php echo $exam->id;?>-{{$user_id}}", startTime);
        window.examVar = examJson;
        setTimeout(function(){ jQuery("#overlay-loading").hide(); }, 5000);
        <?php if($redata!=0) { ?>
            localStorage.setItem("examStart<?php echo $exam->id;?>-{{$user_id}}", '{{$redata}}');
            window.examVar = localStorage.getItem("examStorage<?php echo $exam->id;?>-{{$user_id}}");
            jQuery("#overlay-loading").show();
            jQuery(document).ready(function(){
                setTimeout(function(){ initializeView(); }, 3000);
            });
        <?php }
        else {
            ?>
            jQuery(document).ready(function(){
                setTimeout(function(){ intilizetimer({{$hours}},{{$minutes}},{{$seconds}}); }, 1500);
            });
        <?php
        }
        ?>
    }
    else {
        window.examVar = localStorage.getItem("examStorage<?php echo $exam->id;?>-{{$user_id}}");
        // show popup modal
        jQuery("#overlay-loading").show();
        setTimeout(function(){ jQuery("#overlay-loading").hide(); }, 5000);
        jQuery(document).ready(function(){
            setTimeout(function(){ initializeView(); }, 3000);
        });
    }
} else {
    setTimeout(function(){ jQuery("#overlay-loading").hide(); }, 500);
    alert("Your browser does not support latest Session Storage. Please upgrade your browser to give the exam.")
    window.examVar = examJson;
    window.close('fs');
}
window.actQues = 0;
</script>

<div id="overlay-loading" class="overlay">
    <i class="fa fa-refresh fa-spin"></i>
</div>
<div class="row justify-content-center hidden" id="endExamText" style="
    text-align: center;
">
        <div class="col-12">
            <h5>{!! $exam->end_of_time_text !!}</h5>
        </div>
    </div>
<div class="container">

    <div class="row justify-content-center">


    <div class="col-12" style="margin-bottom: 2rem">
        <div id="container1" class="container1">
            <div class="header1">

            </div>
            <div class="content1">
                <ul class="question-palette" id="pallete_list">
                    <?php
                    $i = 1;
                    foreach($ex_contents as $exam_content_id => $ex_content) {
                        if($i==1)
                            $activeQuestion = $exam_content_id;
                        ?>
                        <li class="palette pallete-elements not-visited {{ $exam_content_id }}" onclick="showSpecificQuestion({{ $exam_content_id }}, 1);">
                            <span>{{ $i++ }}</span>
                        </li>
                    <?php } ?>
                </ul>

                <div id="arrow-expanded"></div>

                <div style="display: none" id="hover-palette-expand"><p>Expand</p></div>



            </div>
        </div>
        <div class="hidden" id="hover-palette-expand-arrow"><p>Expand</p></div>
    </div>
        <div class="col-12" style="margin-bottom: 2rem">

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form method="post" action="">
                <input type="hidden" name="time_spent" id="time_spent" value="0">

                            @foreach($ex_contents as $exam_content_id => $ex_content)
                            <?php $last_id = $exam_content_id; ?>
                            <div class="question_div {{$exam->id}}" name="question[{{ $exam_content_id }}]" id="{{ $exam_content_id}}" style="display:none;" value="0">
                                <div><h4>{!!$ex_content['question_title'] !!}</h4>
                                        <div class="q_description">
                                    <?php  echo $ex_content['question_description']; ?>
                                </div>
                                </div>

                                <div class="row">
                                <div class="col-md-12 questions-wrapper">
                                    <?php
                                        if($ex_content['question-type'] == 1) { //For True False Type
                                            echo '
                                                <div class="col-md-12">
                                                    <div class="form-check form-check-inline">

                                                        <label class="form-check-label checkradio" >
                                                        <input class="form-check-input " type="radio" name="'.$exam_content_id.'" value="True">  <span class="checkmark"></span>
                                                        True
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">

                                                        <label class="form-check-label checkradio" >
                                                        <input class="form-check-input " type="radio" name="'.$exam_content_id.'"  value="False">  <span class="checkmark"></span>
                                                        False
                                                        </label>
                                                    </div>
                                                </div>
                                                ';

                                        } elseif($ex_content['question-type'] == 'radio buttons') { //For Multiple Choice Type

                                            $unser_data = $ex_content['answers_keys'];

                                            $opt1 = $unser_data[0];
                                            $opt2 = $unser_data[1];
                                            $opt3 = $unser_data[2];
                                            $opt4 = $unser_data[3];

                                            echo '<div class="col-md-12">
                                                    <div class="form-check form-check-inline">
                                                    <label class="form-check-label checkradio" ><input class="form-check-input" type="radio" name="'.$exam_content_id.'"  value="'.$opt1.'"><span class="checkmark"></span>
                                                        <div class="answeralign">'
                                                            . $opt1 .
                                                        '</div></label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                    <label class="form-check-label checkradio" ><input class="form-check-input" type="radio" name="'.$exam_content_id.'"  value="'.$opt2.'"><span class="checkmark"></span>
                                                    <div class="answeralign">'
                                                            . $opt2 .
                                                        '</div></label>
                                                    </div><br/>
                                                    <div class="form-check form-check-inline">
                                                    <label class="form-check-label checkradio" ><input class="form-check-input" type="radio" name="'.$exam_content_id.'"  value="'.$opt3.'"><span class="checkmark"></span>
                                                    <div class="answeralign">'
                                                            . $opt3 .
                                                        '</div></label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                    <label class="form-check-label checkradio" ><input class="form-check-input" type="radio" name="'.$exam_content_id.'"  value="'.$opt4.'"><span class="checkmark"></span>
                                                    <div class="answeralign"> '
                                                            . $opt4 .
                                                        '</div></label>
                                                    </div>
                                                </div>
                                                ';

                                        } elseif($ex_content['question-type'] == 3) { //For Several Answer Type

                                            $unser_data = $ex_content['answers_keys'];

                                            $opt1 = $unser_data[0];
                                            $opt2 = $unser_data[1];
                                            $opt3 = $unser_data[2];
                                            $opt4 = $unser_data[3];

                                            // array_push($options, $opt1, $opt2, $opt3, $opt4);
                                            echo '<div class="col-md-12">
                                                    <div class="form-check form-check-inline">
                                                    <label class="form-check-label checklabel" ><input class="form-check-input" type="checkbox" name="'.$exam_content_id.'"  value="'.$opt1.'"><span class="checkmark"></span>
                                                    <div class="answeralign">'
                                                            . $opt1 .
                                                        '</div></label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                    <label class="form-check-label checklabel" ><input class="form-check-input" type="checkbox" name="'.$exam_content_id.'" value="'.$opt2.'"><span class="checkmark"></span>
                                                    <div class="answeralign">'
                                                            . $opt2 .
                                                        '</div></label>
                                                    </div><br/>
                                                    <div class="form-check form-check-inline">
                                                    <label class="form-check-label checklabel" ><input class="form-check-input" type="checkbox" name="'.$exam_content_id.'"  value="'.$opt3.'"><span class="checkmark"></span>
                                                    <div class="answeralign">'
                                                            . $opt3 .
                                                        '</div></label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                    <label class="form-check-label checklabel" ><input class="form-check-input" type="checkbox" name="'.$exam_content_id.'"  value="'.$opt4.'"><span class="checkmark"></span>
                                                    <div class="answeralign">'
                                                            . $opt4 .
                                                        '</div></label>
                                                    </div>
                                                </div>
                                                ';
                                        }
                                    ?>
                                </div>
                                </div>
                            </div>
                        @endforeach
                        <br/>

            </form>
        </div>



        <div class="col-12">
            <div class="row buttons-wrapper">

            <!-- col-sm-12 col-md-6 col-lg-3 -->

                            <button class=" btn btn-lg button-secondary-previous button prev" type="button"  onclick="prevQues();" style="width: 25%; min-width: fit-content;">
                            <img src="{{cdn('new_cart/images/arrow-previous-white.svg')}}" width="20px" height="12px" alt="">
                            PREVIOUS QUESTION
                            </button>

                            <button class=" btn btn-lg button clear-answer button-quinary" type="button" onclick="clearAnswer();" style="width: 20%; min-width: fit-content;">
                                CLEAR
                            </button>

                            <button class=" btn btn-lg button next button-senary" style="width: 20%; min-width: fit-content;" id="markbtn" type="button"  onclick="nextQues(1);" >
                                ANSWER LATER
                            </button>

                            <button class=" btn btn-lg button-secondary-next button next" type="button" onclick="nextQues();" style="width: 25%; min-width: fit-content;">
                                NEXT QUESTION
                                <img src="{{cdn('new_cart/images/arrow-next-white.svg')}}" width="20px" height="12px" alt="">
                            </button>



                        <!--<button class="btn btn-lg btn-danger button   finish" type="submit" onclick="finishExam();">
                        Ολοκλήρωση
                        </button>-->

            </div>
            <hr>

        </div>

        <div class="col-12">
            <div class="row justify-content-between">
                <div style="display:flex" class="col-sm-12 col-md-auto col-lg-5 mark_question_details">
                    <p class="unanswered"><span class="icon">&#9632;</span> <span class="text">unanswered</span> </p>
                    <p class="answered"><span class="icon">&#9632;</span> <span class="text">answered</span> </p>
                    <p class="answer_later"><span class="icon">&#9632;</span> <span class="text">answer later</span> </p>
                </div>
                @if(Request::segment(1) == 'exam-start')
                <div class="col-sm-12 col-md-auto col-lg-7 finish-exams text-right">
                        <button class="btn btn-lg btn-danger button finish" disabled type="submit" onclick="finishExam();" id="ExamFinish">SUBMIT YOUR EXAM</button>
                </div>
                @endif
            </div>
        </div>






                        {{--<!--
                                    <div class="card" style="margin: 0px 0px 25px 0px;">
                                        <div class="card-header text-center">
                                            <h5>Επισκόπηση</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="legends">
                                                <li class="palette answered"><span id="palette_total_answered">0</span> Απαντημένες</li>
                                                <li class="palette marked"><span id="palette_total_marked">0</span> Σημειωμένες</li>
                                                <li class="palette not-answered"><span id="palette_total_not_answered">0</span> Μη απαντημένες</li>
                                                <li class="palette not-visited"><span id="palette_total_not_visited"><?php echo $i-1; ?></span> Δεν έχουν εμφανιστεί</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        -->--}}


<script>

    jQuery( document ).ready(function() {

        $('.time_remaining_header').removeAttr('hidden')

        window.actQues = <?php echo $activeQuestion; ?>;
        var eJson = JSON.parse( window.examVar );
        var showx = 0;
        var currQues = window.actQues;
        var firstQues = 0;
        var lastQues = 0;
        var isOnline = 1;
        jQuery.each(eJson, function(index, quest) {
            if(firstQues==0) {
                firstQues = quest.id;
            }
            lastQues = quest.id;
        });
        if(currQues==firstQues) {
            jQuery('.prev').addClass('hide');
        }
        if(currQues==lastQues) {
         //   jQuery('.next').addClass('hide');
        }
        showSpecificQuestion(<?php echo $activeQuestion; ?>);
        setInterval(function(){

            var retrievedObject = localStorage.getItem('examStorage<?php echo $exam->id;?>-{{$user_id}}');
            var startTime = localStorage.getItem("examStart<?php echo $exam->id;?>-{{$user_id}}");
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            if(navigator.onLine) {
                isOnline = 1;
            }
            else if(isOnline==1) {
                isOnline = 0;
                alert('You can continue the exam and wait until internet connection is back');
            }

            if(window.actQues=={{$last_id}}) {
                    var eJson = JSON.parse( window.examVar );
                    list = $('#'+window.actQues + ' input ');
                    var currQues = window.actQues;
                    var lastQues = 0;
                // answer_status = 0;
                  //  is_marked = 0;
                    jQuery.each(eJson, function(index, quest) {
                        if(quest.id==window.actQues) {
                            var given_ans = "";
                            var arrayName = [];
                            if(list!=0) {
                                list.each(function(index, value){
                                    element_type = jQuery(value).attr('type');
                                    switch(element_type)
                                    {
                                        case 'radio':
                                             if(jQuery(value).prop('checked'))
                                                given_ans = jQuery(value).val();
                                             break;
                                        case 'checkbox':
                                             if(jQuery(value).prop('checked'))
                                             arrayName.push(jQuery(value).val());
                                             break;
                                    }
                                });
                            }
                            if(given_ans=="" && arrayName.length > 0 ) {
                               // given_ans = arrayName.join("|");

                            }
                            if(given_ans!="") {
                                quest.given_ans = given_ans;
                                quest.mark_status = 2;
                                answer_status = 1;
                            }

                        }
                        lastQues = quest.id;
                    });
                    window.examVar = JSON.stringify(eJson);

                    updateStats();
            }

            if(window.exam_finish==0) {
                jQuery.ajax({
                method: "POST",
                url: "{{ route('sync-data') }}",
                data: { examJson: retrievedObject, start_time: startTime, student_id: {{$user_id}}, exam_id: {{$exam->id}} },
                success: function() {
                }
                });
            }
        }, 1000 * 60);
    });

    function preventScroll(e){
        e.preventDefault();
        e.stopPropagation();

        return false;
    };

jQuery(document).ready(function(){

    document.querySelector('.content1').addEventListener('wheel', preventScroll, {passive: false});



    $(document).on('click', '.container1', function(){
        let rotate = 0

        if($('.content1').hasClass('expanded')){

        }else{
            rotate = 180;
        }

        $('#arrow-expanded').css('-webkit-transform','rotate('+rotate+'deg)');
        $('#arrow-expanded').css('-moz-transform','rotate('+rotate+'deg)');
        $('#arrow-expanded').css('transform','rotate('+rotate+'deg)');


    })

    $(document).on('click', '.container1', function(){
        $header = $(this);
        //getting the next element
        $content = $header.next();
        //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
        if($('.content1').hasClass('expanded')){
            //$('.content1').removeClass('expanded').slideUp();
            $('.content1').removeClass('expanded')

            scrollCurrentQuestionRow()


        }else{
            $('.content1').addClass('expanded').slideDown();
        }
        // $content.slideToggle(500, function () {
        //     //execute this after slideToggle is done
        //     //change text of header based on visibility of content div
        //     $header.text(function () {
        //         //change text based on condition



        //         //return $content.is(":visible") ? "Collapse" : "Expand";
        //     });
        // });
        checkIfExpanded()
    })

    function checkIfExpanded(){
        if($('.content1').hasClass('expanded')){
            $('#hover-palette-expand').css('display', 'none')

        }else{
            $('#hover-palette-expand').css('display', 'block')
        }
    }

    $( ".content1" ).on('mouseover', function(){

        checkIfExpanded()

    });






    $( ".content1" ).on('mouseleave', function(){

        $('#hover-palette-expand').css('display', 'none')
    });

    $('#pallete_list span').hover(function(){
        $('#hover-palette-expand').toggleClass('hidden')
    })

    $('#pallete_list li').click(function(e){
        e.stopPropagation();
    })

    $('#arrow-expanded').hover(function(){
        $('#hover-palette-expand-arrow').toggleClass('hidden')
        $('#hover-palette-expand').toggleClass('hidden')
    })









    jQuery("body").on("change",function(){
        mark = 0;
        var eJson = JSON.parse(window.examVar);
        list = $('#'+window.actQues+' input ');
        jQuery.each(eJson, function(index, quest) {
            if(quest.id==window.actQues) {
                var given_ans = "";
                var arrayName = [];
                if(list!=0) {
                    list.each(function(index, value){
                        element_type = jQuery(value).attr('type');
                        switch(element_type)
                        {
                            case 'radio':
                                 if(jQuery(value).prop('checked'))
                                    given_ans = jQuery(value).val();
                                 break;
                            case 'checkbox':
                                 if(jQuery(value).prop('checked'))
                                 arrayName.push(jQuery(value).val());
                                 break;
                        }
                    });
                }
                if(given_ans=="" && arrayName.length > 0 ) {
                    given_ans = arrayName.join("|");
                }
                if(given_ans!="") {
                    quest.given_ans = given_ans;
                    answer_status = 1;
                    if(mark==1) {
                        quest.mark_status = 1;
                        is_marked = 1;
                    }
                    else {
                        quest.mark_status = 2;
                        is_marked = 2;
                    }
                }else{

                    quest.mark_status = 1;
                    answer_status = 1;
                    is_marked = 1;
                }
            }
        });

        window.examVar = JSON.stringify(eJson);

        updateStats();
    });
});

</script>
@endsection

