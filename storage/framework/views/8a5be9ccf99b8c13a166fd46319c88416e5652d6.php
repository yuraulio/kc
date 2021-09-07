<!-- SYLLABUS PRINT -->

<style>
    h1, h2 { font-family: Arial, Helvetica, sans-serif; }
    .patates {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        font-size: 10px;
    }
    .patates td, .patates th {
        border: 1px solid #ddd;
        padding: 6px;
    }
    tr.even { background-color: #f2f2f2 !important; }
    .patates th {
        padding-top: 8px;
        padding-bottom: 8px;
        text-align: left;
        background: #1c4176;
        color: white;
    }
    .topic-section { margin-bottom: 20px; }
    h2.topic-title { font-size: 18px; margin: 6px 0; padding: 0; }
    .topic-desc { padding: 0 0 10px 0;}
</style>


<div id="section-topics" class="event-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h1><?php echo e($content['title']); ?></h1>
                <h2><?php if(count($content['city']) > 0): ?> In <?php echo e($content['city'][0]['name']); ?><?php endif; ?>, <?php if($content->is_inclass_course()): ?> In-class <?php endif; ?></h2>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="clearfix"></div>
                            <div class="panel-group">
                                <?php //dd($eventtopics); ?>
                            <?php if(isset($eventtopics)): ?>
                            <?php $__currentLoopData = $eventtopics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php //dd($value); ?>

                                    <div class="topic-section">
                                        <div class="panel-heading" role="tab" id="theading<?php echo e($key); ?>">
                                            <h2 class="topic-title"><?php echo e($key); ?></h2>
                                        </div>
                                        <div id="tcollapse<?php echo e($key); ?>">
                                            <div class="panel-body">

                                                <div class="topic-desc"><?php echo $desc[$key]; ?></div>
                                                <!-- TOPIC LESSONS HERE -->
                                                <table class="patates">
                                                    <thead>
                                                        <tr>
                                                        <th>Date</th>
                                                        <th>Time</th>
                                                        <th>Location</th>
                                                        <th>Type</th>
                                                        <th>Lesson</th>
                                                        <th>Instructor</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php $__currentLoopData = $value['lessons']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lke => $lvalue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    
                                                         

                                                        <tr >
                                                            <td><img height="10" src="theme/assets/img/calendar.svg" alt="Date" /> <?= date( "l d M Y", strtotime($lvalue['pivot']['time_starts']) ) ?></td>
                                                            <td><?= date( "H:i", strtotime($lvalue['pivot']['time_starts']) ) ?> (<?php echo $lvalue['pivot']['duration']; ?>)</td>
                                                            <td><?php echo e($lvalue['pivot']['room']); ?></td>

                                                            <td> <?php if(count($lvalue['type']) > 0): ?> <?php echo $lvalue['type'][0]['name']; ?> <?php endif; ?></td>
                                                            <td><?php echo e($lvalue['title']); ?></td>
                                                            <td><?php echo $instructors[$lvalue['pivot']['instructor_id']][0]['title']; ?> <?php echo $instructors[$lvalue['pivot']['instructor_id']][0]['subtitle']; ?></td>
                                                        </tr>

                                                        
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                                    </tbody>
                                                </table>

                                            </div>
                                        </div><!-- END PANEL -->
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

                            </div><!-- END COL-12 -->
                        </div>
                    </div><!-- END ROW -->
                </div>
            </div>
        </div>

</div>

 <!-- TOPICS END -->
<?php /**PATH C:\laragon\www\kcversion8\resources\views/theme/event/syllabus_print.blade.php ENDPATH**/ ?>