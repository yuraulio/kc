

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('layouts.headers.auth'); ?>
        <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__('')); ?>

            <?php $__env->endSlot(); ?>
            <?php $__env->slot('filter'); ?>
                <!-- <a href="#" class="btn btn-sm btn-neutral"><?php echo e(__('Filters')); ?></a> -->
                <a class="btn btn-sm btn-neutral" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"><?php echo e(__('Filters')); ?></a>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item"><a href="<?php echo e(route('lessons.index')); ?>"><?php echo e(__('Lessons Management')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('List')); ?></li>
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
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0"><?php echo e(__('Lessons')); ?></h3>
                            </div>
                                <div class="col-4 text-right">
                                    <a href="<?php echo e(route('lessons.create')); ?>" class="btn btn-sm btn-primary"><?php echo e(__('Add Lesson')); ?></a>
                                </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <?php echo $__env->make('alerts.success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('alerts.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                    <div class="collapse" id="collapseExample">
                        <div class="container">
                            <div class="row">

                                <div class="col-sm-4 filter_col" id="filter_col2" data-column="1">
                                    <label>Categories</label>
                                    <select data-toggle="select" data-category="-1" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col2_filter">
                                    <option id="allCat" data-categoryy="" selected value="-- All --"> -- All -- </option>
                                    </select>
                                </div>

                                <div class="col-sm-4 filter_col" id="filter_col1" data-column="2">
                                    <label>Topics</label>
                                    <select data-topic="" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col1_filter">
                                    <option data-topic="" selected id="allTop" value="-- All --"> -- All -- </option>
                                    </select>
                                </div>

                                <div class="col-sm-4 filter_col" id="filter_col3" data-column="3">
                                    <label>Status</label>
                                    <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col3_filter">
                                        <option></option>
                                        <option value="Published">Published</option>
                                        <option value="Unpublished">Unpublished</option>
                                    </select>
                                </div>
                                
                                    <div class="col-sm-4 filter_col hidden" id="move_col1">
                                        <label>Move To Topic</label>
                                        <select data-topic="-1" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col1_move">
                                        <option selected id="allTop" value="-- All --"> -- All -- </option>
                                        </select>
                                        <button id="move-lesson" class="btn btn-primary" type="button"> Move </button> 
                                    </div>
                                    
                                

                            </div>
                        </div>
                    </div>

                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic31">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col"><?php echo e(__('Select')); ?></th>
                                    <th scope="col"><?php echo e(__('Status')); ?></th>
                                    <th scope="col"><?php echo e(__('Title')); ?></th>
                                    <th scope="col"><?php echo e(__('Assigned Topic')); ?></th>
                                    <th class="" scope="col"><?php echo e(__('Assigned Categories')); ?></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                             

                               

                                <?php $__currentLoopData = $lesson->topic; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                
                                
                                    <tr>
                                        <td> 
                                            <div class="input-group-prepend lesson-select">
                                                <div class="input-group-text">
                                                    <input data-lesson-id="<?php echo e($lesson->id); ?>" class="check-lesson" type="checkbox" aria-label="Checkbox for following text input">
                                                </div>
                                            </div> 
                                        </td>
                                        <td><?= ($lesson->status == 1) ? 'Published' : 'Unpublished'; ?></td>
                                        <td><a href="<?php echo e(route('lessons.edit', $lesson)); ?>"><?php echo e($lesson->title); ?></a></td>
                                        <td id="<?php echo e($lesson->category[$key]['id']); ?>-<?php echo e($topic->id); ?>-<?php echo e($lesson->id); ?>">
                                       
                                            <?php echo e($topic->title); ?>,
                                       
                                        </td>
                                        <td class="">
                                       
                                                <?php echo e($lesson->category[$key]['name']); ?>,
                                         
                                        </td>

                                        <td class="text-right">

                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" href="<?php echo e(route('lessons.edit', $lesson)); ?>"><?php echo e(__('Edit')); ?></a>
                                                    <form action="<?php echo e(route('lessons.destroy', $lesson)); ?>" method="post">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('delete'); ?>

                                                        <button type="button" class="dropdown-item" onclick="confirm('<?php echo e(__("Are you sure you want to delete this lesson?")); ?>') ? this.parentElement.submit() : ''">
                                                            <?php echo e(__('Delete')); ?>

                                                        </button>
                                                    </form>


                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php echo $__env->make('layouts.footers.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('js'); ?>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
    <script>

        var categories = <?php echo json_encode($categories, 15, 512) ?>;
        var selectedTopic = null
        var selectedCategory = null
        var count = 0
        var selectedStatus = null
        var table = $('#datatable-basic31').DataTable({
            language: {
                paginate: {
                next: '&#187;', // or '→'
                previous: '&#171;' // or '←'
                }
            }
        });

        function removeSpecial(s){
            s = s.replace(/ /g,'');
            s = s.replace(/&/g,'');
            s = s.replace(/amp;/g,'');
            return s
        }


   
            $(function() {

    $("#col2_filter").select2({
    templateResult: function(option, container) {

        if(selectedTopic != null){
            if (selectedCategory != '' && $(option.element).attr("data-category") !== undefined && removeSpecial($(option.element).attr("data-category")) != selectedCategory){
                $(container).css("display","none");
            }
            return option.text;
        }else{
            return option.text;
        }

    }
    });
    
    
    $("#col1_filter").select2({
    templateResult: function(option, container) {

        if(selectedCategory != null){
            if (selectedCategory != '' && $(option.element).attr("data-category") !== undefined && removeSpecial($(option.element).attr("data-category")) != selectedCategory){
                $(container).css("display","none");
            }
            return option.text;
        }else{
            return option.text;
        }

    }
    });
    
    $("#col1_move").select2({
    templateResult: function(option, container) {

        if(selectedCategory != null){
            if (selectedCategory != '' && $(option.element).attr("data-category") !== undefined && removeSpecial($(option.element).attr("data-category")) != selectedCategory){
                $(container).css("display","none");
            }
            return option.text;
        }else{
            return option.text;
        }

    }
    });
    
    $.each(categories, function(key, value) {
    
    let topics = value[0].topics
    let row = `<option data-categoryy="${value[0].id}" value="${key}">${key}</option>`
    $('#col2_filter').append(row)

    $.each(topics, function(key1, value1) {
        let row = `<option data-topic="${value1.id}" data-category="${key}" value="${value1.title}">${value1.title}</option>`
        $('#col1_filter').append(row)
        $('#col1_move').append(row)
    })
    })
    
    
    /////
    function searchByFilters(){
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {

            let found_from_topi = false
            let found_from_cat = false
            let found = false
            let status = data[1]
        
            //let global_search = $('.dataTables_filter input').val()
        
            // if(global_search != '' && global_search.length > 3 && selectedTopic == null && selectedCategory == null){
            //     if(data[1].includes(global_search) ){
            //         found = true
            //     }
            // }else{
            //     found = true
            // }
            
            
            
            if(selectedCategory != null){
        
        
                if(selectedCategory != '' && selectedTopic == null){
            
                    let word = data[4].split(',');
            
            
                    if(selectedCategory != "--All--"){
                    if(selectedCategory != ''){
                        if(word.length != 0){
                            $.each(word, function(key, value) {
                                if(value != ''){
                                    if(removeSpecial(value) == selectedCategory){
                                        found = true;
                                        if(selectedStatus != null){
                                            if(selectedStatus == status){
                                            
                                            }else{
                                                found = false
                                            }
                                        }
                                    
                                    }
                                }
                            })
                        
                        }
                    }else{
                        found = true
                    }
                    }else{

                    found = true
                    if(selectedStatus != null){
                        if(selectedStatus == status){
                        
                        }else{
                            found = false
                        }
                    }
                    }
            
            
                }else if(selectedCategory != null && selectedTopic != ''){
            
            
                    let cat = data[4].split(',');
                    let topi = data[3].split(',');
                    if(selectedTopic != '--All--'){
                        $.each(cat, function(key, value) {
                            if(value != ''){
                                if(removeSpecial(value) == selectedCategory){
                                    //alert('found')
                                    found_from_cat = true
                                }
                            }
                        })
                        $.each(topi, function(key, value) {
                            if(value != ''){
                                if(removeSpecial(value) == selectedTopic){
                                    //alert('found')
                                    found_from_topi = true
                                }
                            }
                        })
                
                        if(found_from_topi && found_from_cat){
                            found = true
                            if(selectedStatus != null){
                                if(selectedStatus == status){
                                    found = true
                                }else{
                                    found = false
                                }
                            }
                        
                        }
                    }else{
                        $.each(cat, function(key, value) {
                            if(value != ''){
                                if(removeSpecial(value) == selectedCategory){
                                    //alert('found')
                                    found_from_cat = true
                                }
                            }
                        })
                        if(found_from_cat){
                            found = true
                            selectedTopic = null
                        }
                
                    }
            
                }else if(selectedCategory == null && selectedTopic != null){
                let word = data[3].split(',');
            
                if(selectedTopic != '--All--'){
                    if(selectedCategory != ''){
                    
                    if(word.length != 0){
                        $.each(word, function(key, value) {
                            if(value != ''){
                                if(removeSpecial(value) == selectedTopic){
                                    //alert('found')
                                    found = true
                                    if(selectedStatus != null){
                                        if(selectedStatus == status){
                                        
                                        }else{
                                            found = false
                                        }
                                    }
                                }
                            }
                        })
                    
                    }
                    }else{
                    found = true
                    }
                }else{
                    found = true
                    if(selectedStatus != null){
                        if(selectedStatus == status){
                        
                        }else{
                            found = false
                        }
                    }
                }
            
            
            
                }
                if(found){
                    return true;
                }
                return false;
            }else{
                return true
            }
            return true
    
    
    
        }
    );
    }
    /////
    $('#col1_filter').change(function() {
    
    $("#col1_filter").data('topic',$("option:selected", this).data('topic'))
    selectedTopic = removeSpecial($(this).val())
    
    searchByFilters()
    table.draw();

    })
    
    $('#col1_move').change(function() {
    $("#col1_move").data('topic',$("option:selected", this).data('topic'))
    })
    
    $('#col2_filter').change(function() {
   
    $("#col2_filter").data('categoryy',$("option:selected", this).data('categoryy'))

    if(count != 0){
        if($(this).val() == '-- All --'){
            $("#col1_filter").val("-- All --").change();
            $("#col1_move").val("-- All --").change();
            
        }
    }
    selectedCategory = removeSpecial($(this).val())
    searchByFilters()
    table.draw();
    count = count + 1
    })
    
    $('#col3_filter').change(function() {
    selectedStatus = $(this).val()
    searchByFilters()
    table.draw();
    })

});

        



        $(document).ready(function(){
            
            $('#col2_filter').change();
        })

        $('#datatable-basic31_filter').on( 'keyup', function () {
            var table = $('#datatable-basic31').DataTable();
            
            table
                .columns( 2 )
                .search( $(this).find('input').val() )
                .draw();
        } );

        $("#move-lesson").click(function(){
            let category = $("#col2_filter").data('categoryy');
            let fromTopic = $("#col1_filter").data('topic');
            let toTopic = $("#col1_move").data('topic');
            let toTopicName = $("#col1_move").val();

            
            let lessons = [];
            $('.check-lesson').each(function(index, value) {
                if ($(this).is(':checked')) {
                    lessons.push($(this).data('lesson-id'))
                }
            });

            let data = {'lessons':lessons, "category":category,'fromTopic':fromTopic,'toTopic':toTopic}
    
            $.ajax({
                headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
   	            type: 'post',
   	            url: '<?php echo e(route("move-multiple-lessons")); ?>',
                data: data,
   	            
                success: function (data) {
                   
                    if(data['success']){
                        $.each(lessons,function(index, value){                            
                            $(`#${category}-${fromTopic}-${value}`).html( toTopicName + ',')
                            $(`#${category}-${fromTopic}-${value}`).attr("id",`${category}-${toTopic}-${value}`);
                        });
                       
                        $('#col2_filter').change();
                        $('#col1_filter').change();
                    }else{
                        let errorMessage = '';
                        $.each(data.errors,function(index, value){
                            $.each(value,function(index1, value1){
                                errorMessage += value1 + ' ';
                            });
                           
                        });

                        $(".error-message p").html(errorMessage);
                        $(".error-message").show();
                    }
                   
                }
            });

        })

    </script>

    <script>

        $(".check-lesson").click(function(){
            let clicked = false;
            $('.check-lesson').each(function(index, value) {
                if ($(this).is(':checked')) {
                    clicked = true;
                }
            });
            
            if(clicked){
                $("#move_col1").show()
            }else{
                $("#move_col1").hide()
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', [
    'title' => __('Lesson Management'),
    'parentSection' => 'laravel',
    'elementName' => 'lessons-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/lesson/index.blade.php ENDPATH**/ ?>