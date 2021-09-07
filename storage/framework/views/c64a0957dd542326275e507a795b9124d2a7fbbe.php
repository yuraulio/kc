
<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0"><?php echo e(__('Faqs')); ?></h3>
        <p class="text-sm mb-0">
                <?php echo e(__('')); ?>

            </p>
    </div>

        

</div>

<div class="col-12 mt-2">
    <?php echo $__env->make('alerts.success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('alerts.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<div class="accordion"  id="accordionTopicMain1">

    <?php $__currentLoopData = $event->getFaqsByCategoryEvent(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $faqs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


        <div class="card">
            <div class="card-header" id="cattt_<?php echo e(\Illuminate\Support\Str::slug($key)); ?>" data-toggle="collapse" data-target="#catt_<?php echo e(\Illuminate\Support\Str::slug($key)); ?>" aria-expanded="false" >
                <h5 class="mb-0"><?php echo e($key); ?></h5>
            </div>

            <div id="catt_<?php echo e(\Illuminate\Support\Str::slug($key)); ?>" class="collapse" aria-labelledby="catt_<?php echo e(\Illuminate\Support\Str::slug($key)); ?>" data-parent="#accordionTopicMain1">
                <div class="card-body">
                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic9">
                            <thead class="thead-light">
                                <tr>
                                <th scope="col"><?php echo e(__('Title')); ?></th>
                                <th scope="col"><?php echo e(__('Operations')); ?></th>
                                </tr>
                            </thead>
                            <tbody id="faq-body" class="faq-order">
                            <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr id="faq-<?php echo e($faq['id']); ?>" data-id="<?php echo e($faq['id']); ?>" class="faq-list">
                                    <td><?php echo e($faq['question']); ?></td>


                                    <td>
                                        <?php if(!in_array($faq['id'],$eventFaqs)): ?>
                                            <button class="btn btn-primary assing" data-faq = '<?php echo e($faq["id"]); ?>' type="button">Assign</button>
                                        <?php else: ?>
                                            <button class="btn btn-primary unsing" data-faq = '<?php echo e($faq["id"]); ?>' type="button">Unsign</button>
                                        <?php endif; ?>
                                    </td>


                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>


        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<!-- Modal -->

<?php $__env->startPush('js'); ?>
    <script>
        $(document).on('shown.bs.modal', '#faqModal',function(e) {

            let modelType = "<?php echo e(addslashes ( get_class($model) )); ?>";
            let modelId = "<?php echo e($model->id); ?>";

            $('#faqFormControlSelect option').each(function(key, value) {
                        $(value).remove()
                });

                $('#faqFormControlSelect').append(`<option>-</option>`)

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '/admin/faqs/fetchAllFaqs',
                data:{'model_type':modelType,'model_id':modelId},
                success: function (data) {
                    let faq = data.faqs


                    $.each( faq, function( key, value ) {
                        row =`
                            <option value="${value.id}">${value.name}</option>
                        `
                        $('#faqFormControlSelect').append(row)
                    });
                }
            });
        })

        $(document).on('click', '#save-faq',function(e) {

                let modelType = "<?php echo e(addslashes ( get_class($model) )); ?>";
                let modelId = "<?php echo e($model->id); ?>";

                var selected_option = $('#faqFormControlSelect').val();


                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '<?php echo e(route("faqs.store_event")); ?>',
                    data: {'faqs_id':$('#faqFormControlSelect').val(), 'model_type':modelType,'model_id':modelId},
                    success: function (data) {
                        let faq = data.data

                        $('#faq-body tr').remove();

                        $.each(faq, function(key,val){

                            let newFaq =
                            `<tr id="faq-`+val.id+`">` +
                            `<td id="title-` + val.id +`">` + val.title + `</td>` +

                            `<td id="category-` + val.id +`">` + val.category[0]['name'] + `</td>` +

                            ` <td><button class="btn btn-primary assing" data-faq = '` + val.id +`' type="button">Unsign</button></td>` +
                            `

                            </tr>`;

                            $("#faq-body").append(newFaq);
                        })

                        $(".close-modal").click();
                        $("#success-message p").html(data.success);
                        $("#success-message").show();


                    }
                });

                })

                $(document).on('click', '.assing',function(e) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'get',
                        url: '/admin/faqs/assign-event/' + "<?php echo e($event->id); ?>" +'/' + $(this).data('faq'),
                        success: function (data) {

                            let faq = data.allFaqs
                            let assignedFaqs = data.eventFaqs;
                            let accordion = '';
                            let index = 0;

                            $.each(faq, function(key,val){

                                accordion +=

                                `<div class="card">
                                    <div class="card-header" id="accordion_topicc` + index +`" data-toggle="collapse" data-target="#accordion_topic` + index +`" aria-expanded="false" aria-controls="collapseOne">
                                        <h5 class="mb-0">` + key + `</h5>
                                    </div>

                                    <div id="accordion_topic` + index +`" class="collapse" aria-labelledby="accordion_topic` + index +`" data-parent="#accordionTopicMain1">
                                        <div class="card-body">
                                            <div class="table-responsive py-4">
                                                <table class="table align-items-center table-flush"  id="datatable-basic10">
                                                    <thead class="thead-light">
                                                        <tr>
                                                        <th scope="col"><?php echo e(__('Title')); ?></th>
                                                        <th scope="col"><?php echo e(__('Operations')); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="faq-body" class="faq-order">`;

                                                    $.each(val, function(key,val1){
                                                        accordion +=
                                                            `<tr id="faq-` + val1['id'] +`" data-id="` + val1['id'] +`" class="faq-list">
                                                                <td>`+ val1['question']  +`</td>`;

                                                            if(assignedFaqs.indexOf(val1.id) !== -1){
                                                                accordion += `<td>
                                                                            <button class="btn btn-primary unsing" data-faq = '` + val1.id +`' type="button">unsign</button>
                                                                             </td>`
                                                            }else{
                                                                accordion += ` <td><button class="btn btn-primary assing" data-faq = '` + val1.id +`' type="button">assign</button></td>`
                                                            }

                                                    });
                                                accordion +=`
                                                    </tr></tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>

                                </div>`

                                index += 1;

                            });

                            $("#accordionTopicMain1").empty();
                            $("#accordionTopicMain1").append(accordion)
                            faqOrder();

                            $(".assing").unbind("click");
                            $("#success-message p").html(data.success);
                            $("#success-message").show();
                        }
                    })
                });
                $(document).on('click', '.unsing',function(e) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'get',
                        url: '/admin/faqs/unsign-event/' + "<?php echo e($event->id); ?>" +'/' + $(this).data('faq'),
                        success: function (data) {
                            let faq = data.allFaqs
                            let assignedFaqs = data.eventFaqs;

                            let accordion = '';
                            let index = 0;

                            $.each(faq, function(key,val){

                                accordion +=

                                `<div class="card">
                                    <div class="card-header" id="accordion_topicc` + index +`" data-toggle="collapse" data-target="#accordion_topic` + index +`" aria-expanded="false" aria-controls="collapseOne">
                                        <h5 class="mb-0">` + key + `</h5>
                                    </div>

                                    <div id="accordion_topic` + index +`" class="collapse" aria-labelledby="accordion_topic` + index +`" data-parent="#accordionTopicMain1">
                                        <div class="card-body">
                                            <div class="table-responsive py-4">
                                                <table class="table align-items-center table-flush"  id="datatable-basic11">
                                                    <thead class="thead-light">
                                                        <tr>
                                                        <th scope="col"><?php echo e(__('Title')); ?></th>
                                                        <th scope="col"><?php echo e(__('Operations')); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="faq-body" class="faq-order">`;

                                                    $.each(val, function(key,val1){
                                                        accordion +=
                                                            `<tr id="faq-` + val1['id'] +`" data-id="` + val1['id'] +`" class="faq-list">
                                                                <td>`+ val1['question']  +`</td>`;

                                                            if(assignedFaqs.indexOf(val1.id) !== -1){
                                                                accordion += `<td>
                                                                            <button class="btn btn-primary unsing" data-faq = '` + val1.id +`' type="button">unsign</button>
                                                                             </td>`
                                                            }else{
                                                                accordion += ` <td><button class="btn btn-primary assing" data-faq = '` + val1.id +`' type="button">assign</button></td>`
                                                            }

                                                    });
                                                accordion +=`
                                                    </tr></tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>

                                </div>`

                                index += 1;

                            });

                            $("#accordionTopicMain1").empty();
                            $("#accordionTopicMain1").append(accordion)
                            faqOrder();

                            $(".unsing").unbind("click");
                            $("#success-message p").html(data.success);
                            $("#success-message").show();
                        }
                    })
                });
    </script>


<script src="<?php echo e(asset('js/sortable/Sortable.js')); ?>"></script>

<script>

    $(document).ready(function(){
        faqOrder();
    })

    function faqOrder(){

        var el;


        $( ".faq-order" ).each(function( index ) {

            el = document.getElementsByClassName('faq-order')[index];
            new Sortable(el, {
                group: "words",
                handle: ".my-handle",
                draggable: ".item",
                ghostClass: "sortable-ghost",

            });

            new Sortable(el, {
                // Element dragging ended
                onEnd: function ( /**Event*/ evt) {
                    orderFaqs()
                },
            });

        });

        el = document.getElementById('accordionTopicMain1');

        new Sortable(el, {
           group: "words",
           handle: ".my-handle",
           draggable: ".item",
           ghostClass: "sortable-ghost",

        });

        new Sortable(el, {

            // Element dragging ended
            onEnd: function ( /**Event*/ evt) {

                orderFaqs()


            },
        });
    }


   function orderFaqs(){
      let faqs={}

      $( ".faq-list" ).each(function( index ) {
        faqs[$(this).data('id')] = index
      });


        $.ajax({
         type: 'POST',
         headers: {
         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
         },
         Accept: 'application/json',
         url: "<?php echo e(route ('sort-faqs', $event->id)); ?>",
         data:{'faqs':faqs},
         success: function(data) {


         }
        });
   }

   $(document).ready( function () {
      $('.faq-table').dataTable( {
          "ordering": false,
          "paging": false
      });
   });

</script>

<?php $__env->stopPush(); ?>

<?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/faq/index.blade.php ENDPATH**/ ?>