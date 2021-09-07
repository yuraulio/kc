



<?php $__env->startSection('content'); ?>
<?php if($exam->examCheckbox !== "" && $exam->examCheckbox): ?>
<div class="container">

        <div class="row justify-content-center">

            <div class="col-md-12">

                <div class="ib-card">


                    <div class="ib-card-body">

                        <?php if(session('status')): ?>

                            <div class="alert alert-success" role="alert">

                                <?php echo e(session('status')); ?>


                            </div>

                        <?php endif; ?>



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


    <?php else: ?>

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-12">

                <div class="ib-card">


                    <div class="ib-card-body">

                        <?php if(session('status')): ?>

                            <div class="alert alert-success" role="alert">

                                <?php echo e(session('status')); ?>


                            </div>

                        <?php endif; ?>



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


    <?php endif; ?>

    <script>

        jQuery(document).ready(function(){
            

            jQuery('#submitPassΝο').click(function(){
                if('<?php echo e($exam->examCheckbox); ?>' === ""){
                    window.location = '<?php echo e(route('exam-start', [$exam->id])); ?>';
                }
                else {
                    alert('Sorry, wrong password!');
                }
            });

            jQuery('#submitPass').click(function(){
                var enter_pass = jQuery('#examPassword').val();
                if(enter_pass=='<?php echo e($exam->examCheckbox); ?>'){
                    window.location = '<?php echo e(route('exam-start', [$exam->id])); ?>';
                }
                else {
                    alert('Sorry, wrong password!');
                }
            });
        });

      


    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('exams.exams-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/exams/exam_instructions.blade.php ENDPATH**/ ?>