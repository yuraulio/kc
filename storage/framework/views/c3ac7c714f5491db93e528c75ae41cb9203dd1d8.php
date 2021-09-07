
<?php $__env->startSection('content'); ?>

<?php $__env->startComponent('layouts.headers.auth'); ?>
<?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
<?php $__env->slot('title'); ?>
<?php echo e(__('')); ?>

<?php $__env->endSlot(); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('exams.index')); ?>"><?php echo e(__('Exams Management')); ?></a></li>
<li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Add Exam')); ?></li>
<?php if (isset($__componentOriginalb0ed43a6eda44b84021326772a22e85ada88d5cd)): ?>
<?php $component = $__componentOriginalb0ed43a6eda44b84021326772a22e85ada88d5cd; ?>
<?php unset($__componentOriginalb0ed43a6eda44b84021326772a22e85ada88d5cd); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
<?php if (isset($__componentOriginalf553482b463f6cda44d25fdb8f98d9a83d364bb5)): ?>
<?php $component = $__componentOriginalf553482b463f6cda44d25fdb8f98d9a83d364bb5; ?>
<?php unset($__componentOriginalf553482b463f6cda44d25fdb8f98d9a83d364bb5); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
<div class="container-fluid mt--6">
   <div class="nav-wrapper" style="margin-top: 65px;">
      <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
         <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Settings</a>
         </li>
         <?php if($edit): ?>
         <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#questions" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Content</a>
         </li>
         <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#results" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i> Results</a>
         </li>
         <?php endif; ?>
      </ul>
   </div>
   <div class="tab-content">
      <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
         <div class="row">
            <div class="col-xl-12 order-xl-1">
               <div class="card-body">
                  <?php if($edit): ?>
                  <form method="post" action="<?php echo e(route('exams.update',$exam->id)); ?>" autocomplete="off"
                        enctype="multipart/form-data">
                        <?php echo method_field('put'); ?>
                  <?php else: ?>
                  <form method="post" action="<?php echo e(route('exams.store')); ?>" autocomplete="off"
                        enctype="multipart/form-data">

                  <?php endif; ?>
                  <?php echo csrf_field(); ?>
                     <h6 class="heading-small text-muted mb-4"><?php echo e(__('Exam information')); ?></h6>
                     <div class="pl-lg-4">
                        <div class ="row">
                           <div class="col-md-6">
                              <div class="form-group<?php echo e($errors->has('exam_name') ? ' has-danger' : ''); ?>">
                                 <label class="form-control-label" for="input-title"><?php echo e(__('Exam Name')); ?></label>
                                 <input type="text" name="exam_name" id="input-title" class="form-control<?php echo e($errors->has('exam_name') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Exam Name')); ?>" value="<?php echo e(old('exam_name',$exam->exam_name)); ?>" required autofocus>
                                 <?php echo $__env->make('alerts.feedback', ['field' => 'title'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                              </div>
                              <div class="form-group<?php echo e($errors->has('title') ? ' has-danger' : ''); ?>">
                                 <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="indicate_crt_incrt_answers" class="custom-control-input checkboxes" id="indicate_crt_incrt_answers" checked>
                                    <label class="custom-control-label" for="indicate_crt_incrt_answers"><?php echo e(__('Indicate correct or incorrect answers')); ?></label>
                                 </div>
                                 <?php echo $__env->make('alerts.feedback', ['field' => 'indicate_crt_incrt_answers'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                              </div>
                              <div class="form-group<?php echo e($errors->has('random_questions') ? ' has-danger' : ''); ?>">
                                 <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="random_questions" class="custom-control-input checkboxes" id="random_questions" checked>
                                    <label class="custom-control-label" for="random_questions"><?php echo e(__('Randomize questions ')); ?></label>
                                 </div>
                                 <?php echo $__env->make('alerts.feedback', ['field' => 'random_questions'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                              </div>
                              <div class="form-group<?php echo e($errors->has('duration') ? ' has-danger' : ''); ?>">
                                 <label class="form-control-label" for="input-duration"><?php echo e(__('Duration')); ?></label>
                                 <input type="number" name="duration" id="input-duration" class="form-control<?php echo e($errors->has('duration') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Enter exam duration')); ?>" value="<?php echo e(old('duration',$exam->duration)); ?>" required autofocus>
                                 <?php echo $__env->make('alerts.feedback', ['field' => 'duration'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                              </div>

                              <div class="form-group">
                                  <label class="form-control-label" for="input-q_limit"><?php echo e(__('Date')); ?></label>
                                  <div class='input-group date' id='datetimepicker1'>
                                      <input name="publish_time" type='text' class="form-control" value="<?php echo e(old('publish_time',date('d-m-Y H:i',strtotime($exam->publish_time)))); ?>"/>
                                      <span class="input-group-addon input-group-append">

                                          <button class="btn btn-outline-primary" type="button" id="button-addon2">  <span class="fa fa-calendar"></span></button>
                                      </span>
                                  </div>
                              </div>
                              <div class="form-group<?php echo e($errors->has('examCheckbox') ? ' has-danger' : ''); ?>">
                                 <label class="form-control-label" for="input-examCheckbox"><?php echo e(__('Exam Password')); ?></label>
                                 <input type="text" name="examCheckbox" id="input-examCheckbox" class="form-control<?php echo e($errors->has('examCheckbox') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Exam Password')); ?>" value="<?php echo e(old('examCheckbox',$exam->examCheckbox)); ?>" autofocus>
                                 <?php echo $__env->make('alerts.feedback', ['field' => 'examCheckbox'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                              </div>
                           </div>
                           <div class="col-md-6 plan">
                              <div class="form-group<?php echo e($errors->has('event_id') ? ' has-danger' : ''); ?>">
                                 <label class="form-control-label" for="input-event_id"><?php echo e(__('Event')); ?></label>
                                 <select name="event_id" id="input-event_id" class="form-control" placeholder="<?php echo e(__('Event')); ?>">
                                    <option value="">-</option>
                                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($event->id); ?>" <?php if(old('event_id',$event_id) == $event->id): ?> selected <?php endif; ?>><?php echo e($event->title); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                 </select>
                                 <?php echo $__env->make('alerts.feedback', ['field' => 'event_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                              </div>
                              <div class="form-group<?php echo e($errors->has('display_crt_answers') ? ' has-danger' : ''); ?>">
                                 <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="display_crt_answers" class="custom-control-input checkboxes" id="display_crt_answers" checked>
                                    <label class="custom-control-label" for="display_crt_answers"><?php echo e(__('Display correct answer')); ?></label>
                                 </div>
                                 <?php echo $__env->make('alerts.feedback', ['field' => 'display_crt_answers'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                              </div>
                              <div class="form-group<?php echo e($errors->has('random_answers') ? ' has-danger' : ''); ?>">
                                 <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="random_answers" class="custom-control-input checkboxes" id="random_answers" checked>
                                    <label class="custom-control-label" for="random_answers"><?php echo e(__('Randomize answers ')); ?></label>
                                 </div>
                                 <?php echo $__env->make('alerts.feedback', ['field' => 'random_answers'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                              </div>
                              <div class="form-group<?php echo e($errors->has('examMethods') ? ' has-danger' : ''); ?>">
                                 <label class="form-control-label" for="input-examMethods"><?php echo e(__('Exam Methods')); ?></label>
                                 <select name="examMethods" id="input-examMethods" class="form-control" placeholder="<?php echo e(__('Choose Method')); ?>">
                                    <option value="">Choose Method</option>
                                    <option <?php if(old("examMethods",$exam->examMethods) == "percentage" ): ?> selected <?php endif; ?> value="percentage">Percentage</option>
                                    <option <?php if(old("examMethods",$exam->examMethods) == "point" ): ?> selected <?php endif; ?> value="point">Point</option>
                                 </select>
                                 <?php echo $__env->make('alerts.feedback', ['field' => 'examMethods'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                              </div>
                              <div class="form-group<?php echo e($errors->has('q_limit') ? ' has-danger' : ''); ?>">
                                 <label class="form-control-label" for="input-q_limit"><?php echo e(__('Qualification Limit')); ?></label>
                                 <input type="number" name="q_limit" id="input-q_limit" class="form-control<?php echo e($errors->has('q_limit') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Qualification Limit')); ?>" value="<?php echo e(old('q_limit',$exam->q_limit)); ?>" required autofocus>
                                 <?php echo $__env->make('alerts.feedback', ['field' => 'q_limit'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                              </div>
                              <label class="form-control-label" for="status"><?php echo e(__('Status')); ?></label>
                              <div class="form-group<?php echo e($errors->has('status') ? ' has-danger' : ''); ?>">

                                  <label class="custom-toggle">
                                      <input name="status" type="checkbox" <?php if($exam->status): ?> checked <?php endif; ?>>
                                      <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                  </label>
                              </div>

                           </div>

                        </div>

                        <div class="form-group<?php echo e($errors->has('intro_text') ? ' has-danger' : ''); ?>">
                           <label class="form-control-label" for="input-intro_text"><?php echo e(__('Exam Introduction Text')); ?></label>
                           <textarea name="intro_text" id="input-intro_text"  class="ckeditor form-control<?php echo e($errors->has('intro_text') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Replace this text as exam introduction text')); ?>"  required autofocus> <?php echo e(old('intro_text',$exam->intro_text)); ?> </textarea>
                           <?php echo $__env->make('alerts.feedback', ['field' => 'intro_text'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="form-group<?php echo e($errors->has('end_of_time_text') ? ' has-danger' : ''); ?>">
                           <label class="form-control-label" for="input-end_of_time_text"><?php echo e(__('Exam End of Time Text')); ?></label>
                           <textarea name="end_of_time_text" id=""  class="ckeditor form-control<?php echo e($errors->has('end_of_time_text') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Replace this text as exam end of time text')); ?>"  required autofocus> <?php echo e(old('end_of_time_text',$exam->end_of_time_text)); ?> </textarea>
                           <?php echo $__env->make('alerts.feedback', ['field' => 'end_of_time_text'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="form-group<?php echo e($errors->has('success_text') ? ' has-danger' : ''); ?>">
                           <label class="form-control-label" for="input-success_text"><?php echo e(__('Exam Success Text')); ?></label>
                           <textarea name="success_text" id="input-success_text"  class="ckeditor form-control<?php echo e($errors->has('success_text') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Replace this text as exam success text')); ?>"  required autofocus><?php echo e(old('success_text',$exam->success_text)); ?></textarea>
                           <?php echo $__env->make('alerts.feedback', ['field' => 'success_text'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="form-group<?php echo e($errors->has('failure_text') ? ' has-danger' : ''); ?>">
                           <label class="form-control-label" for="input-failure_text"><?php echo e(__('Exam Failure Text')); ?></label>
                           <textarea name="failure_text" id="input-failure_text"  class="ckeditor form-control<?php echo e($errors->has('failure_text') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Replace this text as exam success text')); ?>"  required autofocus><?php echo e(old('failure_text',$exam->failure_text)); ?></textarea>
                           <?php echo $__env->make('alerts.feedback', ['field' => 'failure_text'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="text-center">
                           <button type="submit" class="btn btn-success mt-4"><?php echo e(__('Save')); ?></button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <?php if($edit): ?>
      <div class="tab-pane fade" id="questions" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
         <div class="accordion accord_topic" id="accordionExample">
            <div class="card">
               <div class="card-header"  data-toggle="collapse" data-target="#questions-list" aria-expanded="false" >
                  <h5 class="mb-0">Questions List</h5>
               </div>
               <div id="questions-list" class="collapse" aria-labelledby="questions-list" data-parent="#accordionExample">
                  <div class="card-body">
                     <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic6">
                           <thead class="thead-light">
                              <tr>
                                 <th scope="col"><?php echo e(__('Title')); ?></th>
                                 <th scope="col"><?php echo e(__('Operations')); ?></th>
                              </tr>
                           </thead>
                           <tbody id="question-body" class="question-order">
                              <?php $__currentLoopData = (array)json_decode($exam->questions,true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr id="question-<?php echo e($key); ?>" data-id="<?php echo e($key); ?>" class="question-list">
                                 <td>
                                    <?php echo $question['question']; ?>

                                 </td>
                                 <td class="text-right">
                                    <div class="dropdown">
                                       <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <i class="fas fa-ellipsis-v"></i>
                                       </a>
                                       <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                          <a class="dropdown-item question-item" data-toggle="modal" data-target="#editModal" data-id="<?php echo e($key); ?>" data-question="<?php echo e(json_encode(json_decode($exam->questions,true)[$key])); ?>"><?php echo e(__('Edit')); ?></a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-xl-12 order-xl-1">
               <div class="card-body">
                  <h6 class="heading-small text-muted mb-4"><?php echo e(__('Add Exam Question')); ?></h6>
                  <div class="pl-lg-4">
                     <div class="form-group">
                        <label class="form-control-label" for="question"><?php echo e(__('Exam Question')); ?></label>
                        <textarea name="question" id="question"  class="ckeditor form-control"  required autofocus>  </textarea>
                        <?php echo $__env->make('alerts.feedback', ['field' => 'intro_text'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                     </div>
                     <div class="row">
                        <div class="col-6">
                           <label class="form-control-label" for="question"><?php echo e(__('Question Type')); ?></label>
                           <select id="question-types" class="form-control" name="question-type">
                              <option value="true or false"> True or False </option>
                              <option value="radio buttons"> Radio Buttons (one correct answer) </option>
                              <option value="check boxes"> Check Boxes (several correct answers) </option>
                           </select>
                        </div>
                        <div class="col-6">
                        <label class="form-control-label" for="question"><?php echo e(__('Answer Credit')); ?></label>
                           <div class="form-group">
                              <div class="input-group">
                                 <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-default"><?php echo e(__('Answer Credit')); ?></span>
                                 </div>
                                 <input id="answer-credit" type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="form-group row answer-types">
                        <div class="custom-control custom-radio mb-3">
                           <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
                           <label class="custom-control-label" for="customRadio1">Toggle this custom radio</label>
                        </div>
                        <div class="custom-control custom-radio">
                           <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
                           <label class="custom-control-label" for="customRadio2">Or toggle this other custom radio</label>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="text-center">
            <button type="button" class="btn btn-success mt-4 add-question"><?php echo e(__('Add Question')); ?></button>
         </div>
      </div>
      <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="benefitModalLabel" aria-hidden="true">
         <div id="exam-edit-modal" class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="benefitModalLabel">Modal title</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="card-body">
                     <h6 class="heading-small text-muted mb-4"><?php echo e(__('Edit Exam Question')); ?></h6>
                     <div class="pl-lg-4">
                        <div class="form-group">
                           <label class="form-control-label" for="question"><?php echo e(__('Exam Question')); ?></label>
                           <textarea name="edit-question" id="edit-question"  class="ckeditor form-control"  required autofocus>  </textarea>
                           <?php echo $__env->make('alerts.feedback', ['field' => 'intro_text'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="row">
                           <div class="col-6">
                              <label class="form-control-label" for="question"><?php echo e(__('Question Type')); ?></label>
                              <select id="edit-question-types" class="form-control" name="question-type">
                                 <option value="true or false"> True or False </option>
                                 <option value="radio buttons"> Radio Buttons (one correct answer) </option>
                                 <option value="check boxes"> Check Boxes (several correct answers) </option>
                              </select>
                           </div>
                           <div class="col-6">
                              <div class="form-group">
                                 <div class="input-group">
                                    <div class="input-group-prepend">
                                       <span class="input-group-text" id="inputGroup-sizing-default"><?php echo e(__('Answer Credit')); ?></span>
                                    </div>
                                    <input id="answer-credit" type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="form-group row edit-answer-types">

                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                  <button type="button" data-qu="0" id="update-question" class="btn btn-primary">Save changes</button>
               </div>
            </div>
         </div>
      </div>

      <div class="tab-pane fade" id="results" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
         <table class="table align-items-center table-flush"  id="results-table">
            <thead class="thead-light">
               <tr>
                  <th scope="col"><?php echo e(__('SL NO.')); ?></th>
                  <th scope="col"><?php echo e(__('Name')); ?></th>
                  <th scope="col"><?php echo e(__('Score')); ?></th>
                  <th scope="col"><?php echo e(__('Percentage')); ?></th>
                  <th scope="col"><?php echo e(__('Start Time')); ?></th>
                  <th scope="col"><?php echo e(__('End Time')); ?></th>
                  <th scope="col"><?php echo e(__('Total Time')); ?></th>
                  <th scope="col"><?php echo e(__('Action')); ?></th>

               </tr>
            </thead>
            <tbody >
               <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               <tr>
                  <td>
                     <?php echo e($key + 1); ?>

                  </td>
                  <td>
                     <?php echo e($result['first_name']); ?> <?php echo e($result['last_name']); ?>

                  </td>

                  <td>
                     <?php echo e($result['score']); ?>

                  </td>

                  <td>
                     <?php echo e($result['scorePerc']); ?>

                  </td>

                  <td>
                     <?php echo e($result['start_time']); ?>

                  </td>

                  <td>
                     <?php echo e($result['end_time']); ?>

                  </td>

                  <td>
                     <?php echo e($result['total_time']); ?>

                  </td>

                  <td class="text-right">
                     <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                           <a class="dropdown-item" href="/admin/student-summary/<?php echo e($result['exam_id']); ?>/<?php echo e($result['user_id']); ?>" target="_blank"><?php echo e(__('Show')); ?></a>
                        </div>
                     </div>
                  </td>
               </tr>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
         </table>
      </div>

      <?php endif; ?>
   </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables-datetime/datetime.min.css">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('js'); ?>
<script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo e(asset('assets/vendor/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendor/bootstrap-datetimepicker.js')); ?>"></script>
<script type="text/javascript">
    $(function () {
        $('#datetimepicker1').datetimepicker({
          icons: {
            time: "fa fa-clock",
            date: "fa fa-calendar-day",
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down",
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-screenshot',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
          },
          format: 'DD-MM-YYYY HH:mm',
        });
    });
</script>
<script src="<?php echo e(asset('js/sortable/Sortable.js')); ?>"></script>
<script>
   $(document).ready(function(){
       $(".checkboxes").each(function( ) {
           if($(this).is(':checked')){
               $(this).val(1)
           }else{
               $(this).val(0)
           }
       });
   })
   $(".checkboxes").click(function(){

       if($(this).is(':checked')){
           $(this).val(1)
       }else{
           $(this).val(0)
       }
   })
</script>
<script>
   $("#question-types").change(function(){

       if($(this).val() == 'true or false'){
           $('.answer-types').empty();
           $('.answer-types').append(`<div class="custom-control custom-radio mb-3">
                                     <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
                                     <label class="custom-control-label" for="customRadio1">True</label>
                                   </div>
                                   <div class="custom-control custom-radio">
                                     <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
                                     <label class="custom-control-label" for="customRadio2">False</label>
                                   </div>`
           )
       }else if($(this).val() == 'radio buttons'){
           $('.answer-types').empty();
           $('.answer-types').append(
               `<div class="col-6">
                   <div class="form-group">
                       <div class="input-group answers">
                         <div class="input-group-prepend">
                           <div class="input-group-text">
                             <input data-id='1' class="answer-check" type="radio" aria-label="Checkbox for following text input">
                           </div>
                         </div>
                         <input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                       </div>
                   </div>
               </div>

               <div class="col-6">
                   <div class="form-group">
                       <div class="input-group answers">
                         <div class="input-group-prepend">
                           <div class="input-group-text">
                             <input data-id='2' class="answer-check" type="radio" aria-label="Checkbox for following text input">
                           </div>
                         </div>
                         <input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                       </div>
                   </div>
               </div>

               <div class="col-6">
                   <div class="form-group">
                       <div class="input-group answers">
                         <div class="input-group-prepend">
                           <div class="input-group-text">
                             <input data-id='3' class="answer-check" type="radio" aria-label="Checkbox for following text input">
                           </div>
                         </div>
                         <input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                       </div>
                   </div>
               </div>

               <div class="col-6">
                   <div class="form-group">
                       <div class="input-group answers">
                         <div class="input-group-prepend">
                           <div class="input-group-text">
                             <input data-id='4' class="answer-check" type="radio" aria-label="Checkbox for following text input">
                           </div>
                         </div>
                         <input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                       </div>
                   </div>
               </div>`)
       }else if($(this).val() == 'check boxes'){

           $('.answer-types').empty();
           $('.answer-types').append(
               `<div class="col-6">
                   <div class="form-group">
                       <div class="input-group answers">
                         <div class="input-group-prepend">
                           <div class="input-group-text">
                             <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
                           </div>
                         </div>
                         <input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                       </div>
                   </div>
               </div>

               <div class="col-6">
                   <div class="form-group">
                       <div class="input-group answers">
                         <div class="input-group-prepend">
                           <div class="input-group-text">
                             <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
                           </div>
                         </div>
                         <input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                       </div>
                   </div>
               </div>

               <div class="col-6">
                   <div class="form-group">
                       <div class="input-group answers">
                         <div class="input-group-prepend">
                           <div class="input-group-text">
                             <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
                           </div>
                         </div>
                         <input  type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                       </div>
                   </div>
               </div>

               <div class="col-6">
                   <div class="form-group">
                       <div class="input-group answers">
                         <div class="input-group-prepend">
                           <div class="input-group-text">
                             <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
                           </div>
                         </div>
                         <input  type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                       </div>
                   </div>
               </div>`)

       }

   })


	$("#edit-question-types").change(function(){

	if($(this).val() == 'true or false'){
		 $('.edit-answer-types').empty();
		 $('.edit-answer-types').append(`<div class="custom-control custom-radio mb-3">
											<input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
											<label class="custom-control-label" for="customRadio1">True</label>
										 </div>
										 <div class="custom-control custom-radio">
											<input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
											<label class="custom-control-label" for="customRadio2">False</label>
										 </div>`
		 )
	}else if($(this).val() == 'radio buttons'){
		 $('.edit-answer-types').empty();
		 $('.edit-answer-types').append(
			  `<div class="col-12">
					<div class="form-group">
						 <div class="input-group answers">
							<div class="input-group-prepend">
							  <div class="input-group-text">
								 <input data-id='1' class="answer-check" type="radio" aria-label="Checkbox for following text input">
							  </div>
							</div>
							<textarea type="text" class="form-control answer-input" aria-label="Text input with checkbox"></textarea>
						 </div>
					</div>
			  </div>

			  <div class="col-12">
					<div class="form-group">
						 <div class="input-group answers">
							<div class="input-group-prepend">
							  <div class="input-group-text">
								 <input data-id='2' class="answer-check" type="radio" aria-label="Checkbox for following text input">
							  </div>
							</div>
							<textarea type="text" class="form-control answer-input" aria-label="Text input with checkbox"></textarea>
						 </div>
					</div>
			  </div>

			  <div class="col-12">
					<div class="form-group">
						 <div class="input-group answers">
							<div class="input-group-prepend">
							  <div class="input-group-text">
								 <input data-id='3' class="answer-check" type="radio" aria-label="Checkbox for following text input">
							  </div>
							</div>
							<textarea type="text" class="form-control answer-input" aria-label="Text input with checkbox"></textarea>
						 </div>
					</div>
			  </div>

			  <div class="col-12">
					<div class="form-group">
						 <div class="input-group answers">
							<div class="input-group-prepend">
							  <div class="input-group-text">
								 <input data-id='4' class="answer-check" type="radio" aria-label="Checkbox for following text input">
							  </div>
							</div>
							<textarea type="text" class="form-control answer-input" aria-label="Text input with checkbox"></textarea>
						 </div>
					</div>
			  </div>`)
	}else if($(this).val() == 'check boxes'){

		 $('.edit-edit-answer-types').empty();
		 $('.answer-types').append(
			  `<div class="col-6">
					<div class="form-group">
						 <div class="input-group answers">
							<div class="input-group-prepend">
							  <div class="input-group-text">
								 <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
							  </div>
							</div>
							<input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
						 </div>
					</div>
			  </div>

			  <div class="col-6">
					<div class="form-group">
						 <div class="input-group answers">
							<div class="input-group-prepend">
							  <div class="input-group-text">
								 <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
							  </div>
							</div>
							<input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
						 </div>
					</div>
			  </div>

			  <div class="col-6">
					<div class="form-group">
						 <div class="input-group answers">
							<div class="input-group-prepend">
							  <div class="input-group-text">
								 <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
							  </div>
							</div>
							<input  type="text" class="form-control answer-input" aria-label="Text input with checkbox">
						 </div>
					</div>
			  </div>

			  <div class="col-6">
					<div class="form-group">
						 <div class="input-group answers">
							<div class="input-group-prepend">
							  <div class="input-group-text">
								 <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
							  </div>
							</div>
							<input  type="text" class="form-control answer-input" aria-label="Text input with checkbox">
						 </div>
					</div>
			  </div>`)

	}

})

   $(document).on('click',".answer-check",function(){
       if($(this).attr('type') == 'radio'){
           let self = $(this);
           $("input.answer-check").each(function( ) {
               if(self.data('id') != $(this).data('id')){
                   $(this).prop('checked',false);
               }
           });
       }
   })

   <?php if($edit): ?>
      $(".add-question").click(function(){

       let question = {};


       question['question'] = CKEDITOR.instances['question'].getData();
       question['answer-credit'] = $("#answer-credit").val() ? $("#answer-credit").val() : 1;
       question['question-type'] = $("#question-types").val();

       var answer = [];
       var correctAnswers = [];
       let answerChecked = false;
       $(".answers").each(function(){

           if(!$(this).find('.answer-input').val()){
               alert('πρεπει να βαλετε ολες τις ερωτησεις')

               return;
           }


           answer.push($(this).find('.answer-input').val());
           if($(this).find('.answer-check').is(':checked')){
               answerChecked = true;
               correctAnswers.push($(this).find('.answer-input').val());
           }


       })
       if(!answerChecked){
           alert('πρεπει να επιλέξετε τη σωστη απάντηση')

           return;
       }

       question['answers'] = answer;
       question['correct_answer'] =  correctAnswers;


       $.ajax({
           type: 'POST',
           headers: {
               'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
           },
           Accept: 'application/json',
           url: "<?php echo e(route ('exam.add_question',$exam->id)); ?>",
           data:{'question':question},
           success: function(data) {
               initQuestionFields()
               var  questionsList = '';
               let questions = JSON.parse(data.questions);
               $.each(questions,function(index, value){

                  questionsList += `<tr id="question-` + index + `" data-id="` + index + `" class="question-list">` +
                     `<td>` + value['question'] + `</td>` +
                     `<td class="text-right">
                        <div class="dropdown">
                           <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fas fa-ellipsis-v"></i>
                           </a>
                           <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                              <a class="dropdown-item question-item" data-toggle="modal" data-target="#editModal" data-id="`+ index +`" data-question='`+ JSON.stringify(value) +`'><?php echo e(__('Edit')); ?></a>
                           </div>
                        </div>
                     </td>
                  </tr>`


               })

               $("#question-body").empty();
               $("#question-body").append(questionsList)
               questionOrder()
           }
       });


      })

      $(document).on('click','#update-question' ,function(){
	   //$("edit-question").click(function(){

         let question = {};

       //question['question'] = (CKEDITOR.instances['edit-question'].getData()).replace(/[&#39;]+/g, '');
       //question['question'] =  question['question'].replace(/[quot]+/g, '');
       
       question['question'] = CKEDITOR.instances['edit-question'].getData();
       question['answer-credit'] = $(".modal #answer-credit").val() ? $(".modal #answer-credit").val() : 1;
       question['question-type'] = $("#edit-question-types").val();

       var answer = [];
       var correctAnswers = [];
       let answerChecked = false;
       $(".modal .answers").each(function(){

           if(!$(this).find('.answer-input').val()){
               alert('πρεπει να βαλετε ολες τις ερωτησεις')

               return;
           }


           answer.push($(this).find('.answer-input').val());
           if($(this).find('.answer-check').is(':checked')){
               answerChecked = true;
               correctAnswers.push($(this).find('.answer-input').val());
           }


       })
       if(!answerChecked){
           alert('πρεπει να επιλέξετε τη σωστη απάντηση')

           return;
       }

       question['answers'] = answer;
       question['correct_answer'] =  correctAnswers;

       $.ajax({
           type: 'POST',
           headers: {
               'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
           },
           Accept: 'application/json',
           url: "<?php echo e(route ('exam.update-question',$exam->id)); ?>",
           data:{'question':question,'key':$("#update-question").attr('data-qu')},
           success: function(data) {
               //initQuestionFields()
               $(".close-modal").click();
           }
       });


      })

      function questionOrder(){

         var el = document.getElementById('question-body');
         new Sortable(el, {
             group: "words",
             handle: ".my-handle",
             draggable: ".item",
             ghostClass: "sortable-ghost",

         });

         new Sortable(el, {
             // Element dragging ended
             onEnd: function ( /**Event*/ evt) {
                 orderQuestions(evt)
             },
         });



      }

      function orderQuestions(evt){

         let questions = {}
         $( ".question-list" ).each(function( index ) {

             questions[index] = $(this).data('id')
             $(this).attr('data-id',index)

         });

         $( ".dropdown-item.question-item" ).each(function( index ) {

           $(this).attr('data-id',index)

         });

         $.ajax({
             type: 'POST',
             headers: {
                 'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
             },
             Accept: 'application/json',
             url: "<?php echo e(route('exam.order-questions',$exam->id)); ?>",
             data:{'questions':questions},
             success: function(data) {


             }
         });
      }

   <?php endif; ?>

   function initQuestionFields(){
       $("input.answer-check").each(function( ) {

           $(this).prop('checked',false);

       });

       $(".answer-input").each(function( ) {

           $(this).val('');

       });


       CKEDITOR.instances['question'].setData('');
       $("#answer-credit").val(1);

   }





   $(document).ready( function () {
    $('#results-table').DataTable( {
        language: {
            paginate: {
                next: '&#187;', // or '→'
                previous: '&#171;' // or '←'
            }
        }
       });

       questionOrder();
       $('#datatable-basic6').DataTable( {
           "ordering": false,
           "paging": false
       });


   });



</script>

<script>
   $(document).on('shown.bs.modal', '#editModal',function(e) {
       //e.preventDefault()


   	var link  = e.relatedTarget,
        	modal    = $(this),
         question = JSON.parse(e.relatedTarget.dataset.question);

			modal.find("#edit-question-types").val(question['question-type'])
			modal.find("#edit-question-types").change();

			modal.find("#answer-credit").val(question['answer-credit']);

			modal.find(".answer-input").each(function(index){
				$(this).val(question['answers'][index])

				if(jQuery.inArray(question['answers'][index], question['correct_answer']) !== -1){
					$(this).parent().find('.answer-check').prop('checked',true);
				}

			})

			modal.find("#update-question").attr('data-qu',e.relatedTarget.dataset.id)
      	CKEDITOR.instances['edit-question'].setData(question['question'])
   	//    modal.find("#benefit-id").val(id)

   });


</script>



<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', [
'title' => __('Exams Management'),
'parentSection' => 'laravel',
'elementName' => 'exams-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/exams/create.blade.php ENDPATH**/ ?>