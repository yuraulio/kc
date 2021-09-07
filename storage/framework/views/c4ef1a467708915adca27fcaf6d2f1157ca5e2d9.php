<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0"><?php echo e(__('Tickets')); ?></h3>
        <p class="text-sm mb-0">
                <?php echo e(__('This is an example of Ticket management.')); ?>

            </p>
    </div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Model\User::class)): ?>
        <div class="col-4 text-right">
        <button data-toggle="modal" data-target="#ticketModal" class="btn btn-sm btn-primary"><?php echo e(__('Add ticket')); ?></button>
        </div>
    <?php endif; ?>
</div>

<div class="col-12 mt-2">
    <?php echo $__env->make('alerts.success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('alerts.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>


<div class="table-responsive py-4">
    <table class="table align-items-center table-flush ticket-table"  id="datatable-basic19">
        <thead class="thead-light">
            <tr>
                <th scope="col"><?php echo e(__('Title')); ?></th>
                <th scope="col"><?php echo e(__('Subtitle')); ?></th>
                <th scope="col"><?php echo e(__('Type')); ?></th>
                <th scope="col"><?php echo e(__('Quantity')); ?></th>
                <th scope="col"><?php echo e(__('Price')); ?></th>
                <th scope="col"><?php echo e(__('Created at')); ?></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody class="ticket-body ticket-order">
            <?php $__currentLoopData = $event->ticket; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr id="ticket_<?php echo e($ticket->id); ?>" data-id="<?php echo e($ticket->id); ?>" class="ticket-list">
                    <td><a id="edit-ticket-btn" href="#"><?php echo e($ticket->title); ?></a></td>
                    <td><?php echo e($ticket->subtitle); ?></td>
                    <td><?php echo e($ticket->type); ?></td>

                    <td id="quantity-<?php echo e($ticket->id); ?>"> <?php echo e($ticket->pivot->quantity); ?></td>
                    <td id="price-<?php echo e($ticket->id); ?>"><?php echo e($ticket->pivot->price); ?></td>

                    <td><?php echo e(date_format($ticket->created_at, 'Y-m-d' )); ?></td>
                    <td class="d-none" id="options-<?php echo e($ticket->id); ?>" ><?php echo e($ticket->pivot->options); ?></td>
                    <td class="d-none" id="features-<?php echo e($ticket->id); ?>" ><?php echo e($ticket->pivot->features); ?></td>

                    <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item edit-to-open" data-toggle="modal" data-target="#editTicketModal" data-id="<?php echo e($ticket->id); ?>" data-price="<?php echo e($ticket->price); ?>" data-quantity="<?php echo e($ticket->pivot->quantity); ?>"><?php echo e(__('Edit')); ?></a>
                                <a class="dropdown-item" id="remove_ticket" data-ticket-id="<?php echo e($ticket->id); ?>"><?php echo e(__('Delete')); ?></a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>


<div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default">Add Ticket</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- <h6 class="heading-small text-muted mb-4"><?php echo e(__('Benefit information')); ?></h6> -->
                <div class="pl-lg-4">
                <form id="ticket-form">
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Select Ticket</label>
                        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." class="form-control" id="ticketFormControlSelect">
                            <option>-</option>
                        </select>
                    </div>
                    <div class="form-group<?php echo e($errors->has('price') ? ' has-danger' : ''); ?>">
                        <label class="form-control-label" for="input-price"><?php echo e(__('Price')); ?></label>
                        <input type="number" name="price" id="input-price" class="form-control<?php echo e($errors->has('price') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Price')); ?>" value="<?php echo e(old('price')); ?>" required autofocus>
                        <?php echo $__env->make('alerts.feedback', ['field' => 'price'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="form-group<?php echo e($errors->has('quantity') ? ' has-danger' : ''); ?>">
                        <label class="form-control-label" for="input-quantity"><?php echo e(__('Quantity')); ?></label>
                        <input type="text" name="quantity" id="input-quantity" class="form-control<?php echo e($errors->has('quantity') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Quantity')); ?>" value="<?php echo e(old('quantity')); ?>" autofocus>
                        <?php echo $__env->make('alerts.feedback', ['field' => 'quantity'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="form-group<?php echo e($errors->has('features') ? ' has-danger' : ''); ?>">
                        <label class="form-control-label" for="input-features"><?php echo e(__('Features')); ?></label>
                        <input type="text" name="features[]" id="input-features" class="form-control<?php echo e($errors->has('features') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Enter feature')); ?>" value="<?php echo e(old('features')); ?>" required autofocus>


                        <div id="newRow"></div>
                        <button id="addRow" type="button" class="btn btn-info">Add Row</button>
                        <?php echo $__env->make('alerts.feedback', ['field' => 'features'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label" for="optionFormControlSelect1"><?php echo e(__('FEATURED (BLUE)')); ?></label>
                        <select class="form-control" id="option1">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label" for="optionFormControlSelect2"><?php echo e(__('DROPDOWN BOOK NOW')); ?></label>
                        <select class="form-control" id="option2">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="optionFormControlSelect3"><?php echo e(__('SHOW ONLY ON ALUMNI')); ?></label>
                        <select class="form-control" id="option3">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                        </select>
                    </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="button" id="ticket_save_btn" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editTicketModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?php echo e(__('Edit ticket')); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <h6 class="heading-small text-muted mb-4"><?php echo e(__('Ticket information')); ?></h6>
            <div class="pl-lg-4">
               <div class="form-group<?php echo e($errors->has('price') ? ' has-danger' : ''); ?>">
                  <label class="form-control-label" for="edit-price"><?php echo e(__('Price')); ?></label>
                  <input type="text" name="price" id="edit-price" class="form-control<?php echo e($errors->has('price') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Price')); ?>" value="<?php echo e(old('price')); ?>" required autofocus>
                  <?php echo $__env->make('alerts.feedback', ['field' => 'price'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
               </div>
               <div class="form-group<?php echo e($errors->has('quantity') ? ' has-danger' : ''); ?>">
                  <label class="form-control-label" for="edit-quantity"><?php echo e(__('Quantity')); ?></label>
                  <input type="text" name="quantity" id="edit-quantity1" class="form-control<?php echo e($errors->has('quantity') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Quantity')); ?>" value="<?php echo e(old('quantity')); ?>" autofocus>
                  <?php echo $__env->make('alerts.feedback', ['field' => 'quantity1'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
               </div>
               <form id="ticket-form">
               <div class="form-group<?php echo e($errors->has('features') ? ' has-danger' : ''); ?>">
                        <label class="form-control-label" for="input-features"><?php echo e(__('Features')); ?></label>
                        <input type="text" name="features_edit[]" id="input-features_edit" class="features_edit form-control<?php echo e($errors->has('features') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Enter feature')); ?>" value="<?php echo e(old('features')); ?>" required autofocus>


                        <div id="newRowEdit"></div>
                        <button id="addRowEdit" type="button" class="btn btn-info">Add Row</button>
                        <?php echo $__env->make('alerts.feedback', ['field' => 'features'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label" for="optionFormControlSelect1"><?php echo e(__('FEATURED (BLUE)')); ?></label>
                        <select class="form-control" id="option1_edit">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label" for="optionFormControlSelect2"><?php echo e(__('DROPDOWN BOOK NOW')); ?></label>
                        <select class="form-control" id="option2_edit">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="optionFormControlSelect3"><?php echo e(__('SHOW ONLY ON ALUMNI')); ?></label>
                        <select class="form-control" id="option3_edit">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                        </select>
                    </div>
               <input type="text" id="ticket-id"  value="" hidden>
               </form>
            </div>
         </div>
         <div class="modal-footer">
         <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            <button type="button" id="edit-ticket" class="btn btn-primary">Save changes</button>
         </div>
      </div>
   </div>
</div>




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
        $(document).on('click',"#edit-ticket-btn",function(){
            $(this).parent().parent().find('.edit-to-open').click()
        })
        $("#addRow").click(function () {
        var html = '';
        html += '<div id="inputFormRow">';
        html += '<div class="input-group mb-3">';
        html += '<input type="text" name="features[]" id="input-features" class="form-control m-input" placeholder="Enter feature" autocomplete="off">';
        html += '<div class="input-group-append">';
        html += '<button id="removeRow" type="button" class="btn btn-danger">Remove</button>';
        html += '</div>';
        html += '</div>';

        $('#newRow').append(html);
    });

    $("#addRowEdit").click(function () {
        var html = '';
        html += '<div id="inputFormRow">';
        html += '<div class="input-group mb-3">';
        html += '<input type="text" name="features_edit[]" id="input-features_edit" class="features_edit form-control m-input" placeholder="Enter feature" autocomplete="off">';
        html += '<div class="input-group-append">';
        html += '<button id="removeRow" type="button" class="btn btn-danger">Remove</button>';
        html += '</div>';
        html += '</div>';

        $('#newRowEdit').append(html);
    });

    // remove row
    $(document).on('click', '#removeRow', function () {
        $(this).closest('#inputFormRow').remove();
    });
    </script>


    <script>
        $( "#assignButton" ).on( "click", function(e) {
            const eventId = $(this).data("event-id");

            $.ajax({
               type:'POST',
               url:'/getmsg',
               data:'_token = <?php echo csrf_token() ?>',
               success:function(data) {
                  $("#msg").html(data.msg);
               }
            });

        });

        $(document).on('shown.bs.modal', '#ticketModal',function(e) {
            let modelType = "<?php echo e(addslashes ( get_class($model) )); ?>";
            let modelId = "<?php echo e($model->id); ?>";

            $('#newRow').empty()

            $('#ticketFormControlSelect option').each(function(key, value) {
                $(value).remove()
            });

            $('#ticketFormControlSelect').append(`<option>-</option>`)

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '/admin/ticket/fetchAllTickets',
                data: {'modelType':modelType, 'modelId':modelId},
                success: function (data) {
                    let ticket = data.data.tickets

                    $.each( ticket, function( key, value ) {
                       //console.log(key + ':' + value.title)
                    row =`
                    <option value="${value.id}">${value.title}</option>
                    `
                    $('#ticketFormControlSelect').append(row)

                    });
                }
            });
        })

        $(document).on('click',"#ticket_save_btn",function(){

            let modelType = "<?php echo e(addslashes ( get_class($model) )); ?>";
            let modelId = "<?php echo e($model->id); ?>";


            var features = $("input[name='features[]']")
              .map(function(){return $(this).val();}).get();


            var selected_option = $('#ticketFormControlSelect option:selected');

            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '<?php echo e(route("ticket.store")); ?>',
                data: {'option1':$('#option1_edit').val(),'option2':$('#option2_edit').val(),'option3':$('#option3_edit').val(),'features':features,'price':$('#input-price').val(),'quantity':$('#input-quantity').val(),'ticket_id':$(selected_option).val(),'model_type':modelType,'model_id':modelId},
                success: function (data) {

                    let ticket = data.ticket;
                    let newTicket =
                    `<tr id="ticket_`+ticket['id'] +`">` +
                    `<td id="title_ticket-` +ticket['id'] +`"><a id="edit-ticket-btn" href="#">` + ticket['title'] + `</a></td>` +
                    `<td id="subtitle_ticket` + ticket['id'] +`">` + ticket['subtitle'] + `</td>` +
                    `<td id="type_ticket-` +ticket['id'] +`">` + ticket['type'] + `</td>` +
                    `<td id="quantity-` + ticket['id'] +`">` + ticket['quantity'] + `</td>` +
                    `<td id="price-` + ticket['id'] +`">` + ticket['price'] + `</td>` +
                    `<td>` + ticket['created_at'] + `</td>` +


                    `<td class="d-none" id="options-`+ticket['id']+`" >`+ticket['options']+`</td>`+
                    `<td class="d-none" id="features-`+ticket['id']+`" >`+ticket['features']+`</td>`+


                    `<td class="text-right">
                                <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item edit-to-open" data-toggle="modal" data-target="#editTicketModal" data-id="`+ticket['id']+`" data-price="`+ticket['price']+`" data-quantity="`+ticket['quantity']+`"><?php echo e(__('Edit')); ?></a>
                                <a class="dropdown-item" id="remove_ticket" data-ticket-id="`+ticket['id']+`"><?php echo e(__('Delete')); ?></a>

                                </div>
                                </div>
                            </td>

                        </tr>`;


                    $(".ticket-body").append(newTicket);
                    $(".close-modal").click();
                    $("#success-message p").html(data.success);
                    $("#success-message").show();
                    $('#newRow').empty()
                    $('#newRowEdit').empty()
                    $('#ticket-form').trigger('reset');
                        },
                        error: function() {
                            //console.log(data);
                        }
                    });



            })

        $(document).on('click', '#remove_ticket',function(e) {

            let modelType = "<?php echo e(addslashes ( get_class($model) )); ?>";
            let modelId = "<?php echo e($model->id); ?>";

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '<?php echo e(route("ticket.remove_event")); ?>',
                data: {'ticket_id':$(this).data('ticket-id'), 'model_type':modelType,'model_id':modelId},
                success: function (data) {



                $('#ticket_'+data.ticket_id).remove()

                }
            });
        })


        $(document).on('shown.bs.modal', '#editTicketModal',function(e) {
       //e.preventDefault()

       $('#newRowEdit').empty()

   	    var link  = e.relatedTarget,
        modal    = $(this),
         id = e.relatedTarget.dataset.id
         //name = e.relatedTarget.dataset.name,
         //description =e.relatedTarget.dataset.description;
         price = $("#price-"+id).text(),
         //alert(price)
         quantity = $("#quantity-"+id).text();
         options = $("#options-"+id).text();
         features = $("#features-"+id).text();
         features = features ? JSON.parse(features) : [];
         options = options ? JSON.parse(options) : [];
         
         $.each(options, function(key,value){

             let val = 0
             if(key == 'featured'){

                 if(value == true)
                    val = 1

                $('#editTicketModal #option1_edit').val(val)
             }else if(key == 'dropdown'){

                 if(value == true)
                     val = 1

                $('#editTicketModal #option2_edit').val(val)
             }else if(key == 'alumni'){

                 if(value == true)
                     val = 1
                $('#editTicketModal #option3_edit').val(val)
             }
         })

        $.each( features, function(key, value) {
            if(key != 0){
            var html = '';
            html += '<div id="inputFormRow">';
            html += '<div class="input-group mb-3">';
            html += `<input type="text" name="features_edit[]" id="input-features_edit" class="features_edit form-control m-input" value="${value}" placeholder="Enter feature" autocomplete="off">`;
            html += '<div class="input-group-append">';
            html += '<button id="removeRow" type="button" class="btn btn-danger">Remove</button>';
            html += '</div>';
            html += '</div>';

            $('#newRowEdit').append(html);
            }else{
                $('#editTicketModal #input-features_edit').val(value)
            }
        })



      modal.find("#edit-price").val(price);
      modal.find("#edit-quantity1").val(quantity);
   	modal.find("#ticket-id").val(id)

   });

   $(document).on('click',"#edit-ticket",function(){
    let modelType = "<?php echo e(addslashes ( get_class($model) )); ?>";
    let modelId = "<?php echo e($model->id); ?>";

    var features = $(".features_edit")
              .map(function(){return $(this).val();}).get();

              //console.log(features)
              features = JSON.stringify(features)



    var selected_option = $('#editTicketModel #ticketFormControlSelect option:selected');

    $ticketId = $("#ticket-id").val()
    $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'put',
            url: '/admin/ticket/' + $ticketId,
            data: {'option1':$('#option1_edit').val(),'option2':$('#option2_edit').val(),'option3':$('#option3_edit').val(),'features':features,'price':$('#edit-price').val(),'quantity':$('#edit-quantity1').val(),'model_type':modelType,'model_id':modelId},
            success: function (data) {

        let quantity = data.data.quantity;
        let price = data.data.price;
        let ticket_id = data.data.ticket_id
        let options = data.data.options
        let features = data.data.features

        $("#quantity-"+ticket_id).html(quantity)
        $("#price-"+ticket_id).html(price)
        $("#options-"+ticket_id).html(options);
        $("#features-"+ticket_id).html(features);
        $(".close-modal").click();

        $("#success-message p").html(data.success);
        $("#success-message").show();
        $('#ticket-form').trigger('reset');

            },
            error: function() {
                //console.log(data);
            }
        });



    })


      </script>


<script src="<?php echo e(asset('js/sortable/Sortable.js')); ?>"></script>

<script>

   (function( $ ){

      var el = document.getElementsByClassName('ticket-order')[0];

      new Sortable(el, {
         group: "words",
         handle: ".my-handle",
         draggable: ".item",
         ghostClass: "sortable-ghost",

      });

      new Sortable(el, {

          // Element dragging ended
          onEnd: function ( /**Event*/ evt) {
            orderTickets()
          },
      });

   })( jQuery );

   function orderTickets(){
      let tickets={}

      $( ".ticket-list" ).each(function( index ) {
        tickets[$(this).data('id')] = index
      });


      $.ajax({
         type: 'POST',
         headers: {
         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
         },
         Accept: 'application/json',
         url: "<?php echo e(route ('sort-tickets', $event->id)); ?>",
         data:{'tickets':tickets},
         success: function(data) {


         }
      });
   }

   $(document).ready( function () {
      $('.ticket-table').dataTable( {
          "ordering": false
      });
   });

</script>

<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/ticket/index.blade.php ENDPATH**/ ?>