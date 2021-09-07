
<?php $__env->startSection('content'); ?>
<div class="container">

<div class="row justify-content-center">

    <div class="col-md-12">		

        <div class="card" >

            <div class="card-header">
               
                <img class="account-thumb" src="<?php echo e(cdn(get_profile_image($image))); ?>" onerror="this.src='<?php echo e(cdn('/theme/assets/images/icons/user-profile-placeholder-image.png')); ?>'" alt="user-profile-placeholder-image">
                <div class="pad-left-15">
                <p class="completed"> Completed: <?php echo e($end_time); ?></p>
                <p class="name"> <?php echo e($first_name); ?> <?php echo e($last_name); ?> </p>      
                </div>
                 
            </div>

        </div>

        <div class = "row border-bot">
        
            <div class="col-md-6" id="chartContainer" style="height: 370px; width: 100%;"></div>

            <div class="col-md-6 success-fail-text <?php if($success): ?> pass <?php else: ?> fail <?php endif; ?>">
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
                
            <p> <span class="score"> Score :</span> <?php echo e($score); ?>%</p>    
            </div>
            
            </div>

        <div class="action">
            <button class="btn" type="button"  onclick="closee()" id ="closee"> close </button>
            <?php if($showAnswers): ?>
            <button class="btn" type="button" onclick="viewResults()" id="view-results"> view results </button>
            <button class="btn" type="button" onclick="hideResults()" id="hide-results"> hide results </button>
            <?php endif; ?>

        </div>

        <div class="row ">
            <?php if($showAnswers): ?>
            <div id="answers">

                    <?php $__currentLoopData = $answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-12">
                        <h3><div class="resulttitle">(<?php echo e($key+1); ?>). <?php echo $answer['question']; ?></div></h3>
                        <?php if($indicate_crt_incrt_answers): ?>
                            <p class="result_answer <?php echo e($answer['classname']); ?>"> Your Answer :<?php echo $answer['given_answer']; ?> </p>
                        <?php endif; ?>

                        <?php if($displayCorrectAnswer): ?>
                            <p class="result_answer"> Correct Answer : <?php echo $answer['correct_answer']; ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


            

                

                </div>
            </div>
            <?php endif; ?>

    </div>            

</div>
<a href="#0" class="cd-top cd-is-visible cd-fade-out"><i class="fa fa-chevron-up"></i></a>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('head_scripts'); ?>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>

function pieChart(){
    
        
    var score = <?php echo $score;?>
      
    <?php if($success ): ?>
    var dataPoints = [
			{ y: score,color: "green" },
			{ y: 100-score,color: "grey" },
			
		]
    <?php else: ?>
    var dataPoints = [
			{ y: score,color: "red" },
			{ y: 100-score,color: "grey" },
			
		]
    <?php endif; ?>

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
<?php if($showAnswers): ?>
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
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('exams.exams-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/exams/results.blade.php ENDPATH**/ ?>