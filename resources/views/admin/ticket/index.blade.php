<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0">{{ __('Tickets') }}</h3>

    </div>
    @can('create', App\Model\User::class)
        <div class="col-4 text-right">
        <button data-toggle="modal" type="button" data-target="#ticketModal" class="btn btn-sm btn-primary">{{ __('Add ticket') }}</button>
        </div>
    @endcan
</div>

<div class="col-12 mt-2">
    @include('alerts.success')
    @include('alerts.errors')
</div>


<?php

   $id = isset($sections['tickets'][0]) ? $sections['tickets'][0]['id'] : '';
   $tab_title = isset($sections['tickets'][0]) ? $sections['tickets'][0]['tab_title'] : '' ;
   $title = isset($sections['tickets'][0]) ? $sections['tickets'][0]['title'] : '' ;
   $visible = isset($sections['tickets'][0]) ? $sections['tickets'][0]['visible'] : false;

?>



<div class="form-group">

   <input hidden name="sections[tickets][id]" value="{{$id}}">

   <label class="form-control-label" for="input-title">{{ __('Tab Title') }}</label>
   <input type="text" name="sections[tickets][tab_title]" class="form-control" placeholder="{{ __('Tab Title') }}" value="{{ old("sections[tickets][tab_title]", $tab_title) }}" autofocus>
   <label class="form-control-label" for="input-title">{{ __('H2 Title') }}</label>
   <input type="text" name="sections[tickets][title]" class="form-control" placeholder="{{ __('H2 Title') }}" value="{{ old("sections[tickets][title]", $title) }}" autofocus>


   <label class="form-control-label" for="input-method">{{ __('Visible') }}</label>
   <div style="margin: auto;" class="form-group">

       <label class="custom-toggle enroll-toggle visible">
           <input type="checkbox"  name="sections[tickets][visible]" @if($visible) checked @endif>
           <span class="custom-toggle-slider rounded-circle" data-label-off="no visible" data-label-on="visible"></span>
       </label>

   </div>


</div>


<div class="table-responsive py-4">
    <table class="table align-items-center table-flush ticket-table"  id="datatable-basic19">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('Visible/Not Visible') }}</th>
                <th scope="col">{{ __('Title') }}</th>
                {{--<th scope="col">{{ __('Subtitle') }}</th>--}}
                <th scope="col">{{ __('Type') }}</th>
                <th scope="col">{{ __('Quantity') }}</th>
                <th scope="col">{{ __('Price') }}</th>
                {{--<th scope="col">{{ __('Created at') }}</th>--}}
                <th scope="col"></th>
                <th hidden scope="col">Public Title</th>
                <th hidden scope="col">Seats Visible</th>
            </tr>
        </thead>
        <tbody class="ticket-body ticket-order">
            @foreach ($event->ticket as $ticket)
                <tr id="ticket_{{$ticket->id}}" data-id="{{$ticket->id}}" class="ticket-list">
                    <td>
                        <div class="custom-control custom-checkbox visible-ticket">
                          <input  class="custom-control-input" type="checkbox" data-ticket="{{$ticket->id}}" data-event="{{$event->id}}" @if($ticket->pivot->active) checked @endif>
                          <label class="custom-control-label" for="">Enable</label>
                        </div>
                    </td>
                    <td><a class="admin_title_ticket_{{$ticket->id}}" id="edit-ticket-btn" href="#">{{ $ticket->title }}</a></td>
                    {{--<td>{{ $ticket->subtitle }}</td>--}}
                    <td>{{ $ticket->type }}</td>

                    <td id="quantity-{{$ticket->id}}"> {{ $ticket->pivot->quantity }}</td>
                    <td id="price-{{$ticket->id}}">{{ $ticket->pivot->price }}</td>

                    {{--<td>{{ date_format($ticket->created_at, 'Y-m-d' ) }}</td>--}}

                    <td class="d-none" id="options-{{ $ticket->id }}" >{{ json_encode($ticket->pivot->options ?? []) }}</td>
                    <td class="d-none" id="features-{{ $ticket->id }}" >{{ json_encode($ticket->pivot->features  ?? []) }}</td>

                    <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item edit-to-open" data-toggle="modal" data-target="#editTicketModal" data-id="{{$ticket->id}}" data-price="{{$ticket->price}}" data-quantity="{{$ticket->pivot->quantity}}">{{ __('Edit') }}</a>
                                {{--<a class="dropdown-item ticket-active ticket-active{{$ticket->id}}" data-ticket="{{$ticket->id}}" data-event="{{$event->id}}" data-active="{{$ticket->pivot->active ? 0 : 1}}">@if($ticket->pivot->active) <span class="ticket-span{{$ticket->id}}"> {{ __('Disable') }} </span> @else <span class="ticket-span{{$ticket->id}}"> {{ __('Enable') }} </span> @endif</a>--}}
                                <a class="dropdown-item" id="remove_ticket" data-ticket-id="{{ $ticket->id }}">{{ __('Delete') }}</a>
                            </div>
                        </div>
                    </td>

                    <td hidden id="public_title-{{$ticket->id}}">{{ $ticket->pivot->public_title }}</td>
                    <td hidden id="seats_visible-{{$ticket->id}}">{{ $ticket->pivot->seats_visible }}</td>


                </tr>
            @endforeach
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
                <!-- <h6 class="heading-small text-muted mb-4">{{ __('Benefit information') }}</h6> -->
                <div class="pl-lg-4">
                    <form id="ticket-form">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Select Ticket</label>
                            <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." class="form-control" id="ticketFormControlSelect">
                                <option>-</option>
                            </select>
                        </div>
                        <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-price">{{ __('Price') }}</label>
                            <input type="number" name="price" id="input-price" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" placeholder="{{ __('Price') }}" value="{{ old('price') }}" required autofocus>
                            @include('alerts.feedback', ['field' => 'price'])
                        </div>
                        <div class="form-group{{ $errors->has('quantity') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-quantity">{{ __('Quantity') }}</label>
                            <input type="text" name="quantity" id="input-quantity" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" placeholder="{{ __('Quantity') }}" value="{{ old('quantity') }}" autofocus>
                            @include('alerts.feedback', ['field' => 'quantity'])
                        </div>
                        <div class="form-group{{ $errors->has('features') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-features">{{ __('Features') }}</label>
                            <input type="text" name="features[]" id="input-features" class="form-control{{ $errors->has('features') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter feature') }}" value="{{ old('features') }}" required autofocus>


                            <div id="newRow"></div>
                            <button id="addRow" type="button" class="btn btn-info">Add Row</button>
                            @include('alerts.feedback', ['field' => 'features'])
                        </div>

                        <div class="form-group">
                            <label class="form-control-label" for="optionFormControlSelect1">{{ __('FEATURED (BLUE)') }}</label>
                            <select class="form-control" id="option1">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label" for="optionFormControlSelect2">{{ __('DROPDOWN BOOK NOW') }}</label>
                            <select class="form-control" id="option2">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="optionFormControlSelect3">{{ __('SHOW ONLY ON ALUMNI') }}</label>
                            <select class="form-control" id="option3">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                            </select>
                        </div>

                        <label class="form-control-label" for="input-method">{{ __('SHOW REMAINING SEATS') }}</label>
                        <div style="margin: auto;" class="form-group">

                            <label class="custom-toggle enroll-toggle visible">
                                <input type="checkbox"  name="seats_visible" id="input-show-seats-store">
                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                            </label>

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
            <h5 class="modal-title" id="exampleModalLabel">{{ __('Edit ticket') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <h6 class="heading-small text-muted mb-4">{{ __('Ticket information') }}</h6>
            <div class="pl-lg-4">

                <div class="form-group">
                  <label class="form-control-label" for="edit-price">{{ __('Title for the administration view') }}</label>
                  <input type="text" id="edit-title-ticket" class="form-control" value="" readonly>
                </div>

                <div class="form-group{{ $errors->has('public_title') ? ' has-danger' : '' }}">
                    <label class="form-control-label" for="input-title">{{ __('Public Title') }}</label>
                    <input type="text" name="public_title" id="edit-public_title" class="form-control{{ $errors->has('public_title') ? ' is-invalid' : '' }}" placeholder="{{ __('Public Title') }}" value="{{ old('public_title') }}" required autofocus>

                    @include('alerts.feedback', ['field' => 'public_title'])
                </div>
               <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="edit-price">{{ __('Price') }}</label>
                  <input type="text" name="price" id="edit-price" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" placeholder="{{ __('Price') }}" value="{{ old('price') }}" required autofocus>
                  @include('alerts.feedback', ['field' => 'price'])
               </div>
               <div class="form-group{{ $errors->has('quantity') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="edit-quantity">{{ __('Quantity') }}</label>
                  <input type="text" name="quantity" id="edit-quantity1" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" placeholder="{{ __('Quantity') }}" value="{{ old('quantity') }}" autofocus>
                  @include('alerts.feedback', ['field' => 'quantity1'])
               </div>
               <form id="ticket-form">
               <div class="form-group{{ $errors->has('features') ? ' has-danger' : '' }}">
                        <label class="form-control-label" for="input-features">{{ __('Features') }}</label>
                        <input type="text" name="features_edit[]" id="input-features_edit" class="features_edit form-control{{ $errors->has('features') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter feature') }}" value="{{ old('features') }}" required autofocus>


                        <div id="newRowEdit"></div>
                        <button id="addRowEdit" type="button" class="btn btn-info">Add Row</button>
                        @include('alerts.feedback', ['field' => 'features'])
                    </div>

                    <div class="form-group">
                        <label class="form-control-label" for="optionFormControlSelect1">{{ __('FEATURED (BLUE)') }}</label>
                        <select class="form-control" id="option1_edit">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label" for="optionFormControlSelect2">{{ __('DROPDOWN BOOK NOW') }}</label>
                        <select class="form-control" id="option2_edit">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="optionFormControlSelect3">{{ __('SHOW ONLY ON ALUMNI') }}</label>
                        <select class="form-control" id="option3_edit">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                        </select>
                    </div>

                    <label class="form-control-label" for="input-method">{{ __('SHOW REMAINING SEATS') }}</label>
                        <div style="margin: auto;" class="form-group">

                            <label class="custom-toggle enroll-toggle visible">
                                <input type="checkbox"  name="seats_visible" id="input-show-seats">
                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                            </label>

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




@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
@endpush

@push('js')
    <script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-select/js/dataTables.select.min.js"></script>

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
            let modelType = "{{addslashes ( get_class($model) )}}";
            let modelId = "{{ $model->id }}";

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

            let modelType = "{{addslashes ( get_class($model) )}}";
            let modelId = "{{ $model->id }}";


            var features = $("input[name='features[]']")
              .map(function(){return $(this).val();}).get();


            var selected_option = $('#ticketFormControlSelect option:selected');

            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '{{route("ticket.store")}}',
                data: {'option1':$('#option1_edit').val(),'option2':$('#option2_edit').val(),'option3':$('#option3_edit').val(),'features':features,'price':
                        $('#input-price').val(),'quantity':$('#input-quantity').val(),'ticket_id':$(selected_option).val(),
                        'model_type':modelType,'model_id':modelId,'seats_visible':$("#input-show-seats-store").is(':checked')},
                success: function (data) {

                    let ticket = data.ticket;
                    if(!ticket){
                        return;
                    }
                    let newTicket =
                    `<tr id="ticket_`+ticket['id'] +`">` +

                        `<td>
                            <div class="custom-control custom-checkbox visible-ticket">
                              <input  class="custom-control-input" type="checkbox" data-ticket="${ticket['id']}" data-event="{{$model->id}}"  checked >
                              <label class="custom-control-label" for="">Enable</label>
                            </div>
                        </td>
                        <td id="title_ticket-` +ticket['id'] +`"><a id="edit-ticket-btn" href="#">` + ticket['title'] + `</a></td>` +
                        `<td id="type_ticket-` +ticket['id'] +`">` + ticket['type'] + `</td>` +
                        `<td id="quantity-` + ticket['id'] +`">` + ticket['pivot']['quantity'] + `</td>` +
                        `<td id="price-` + ticket['id'] +`">` + ticket['pivot']['price'] + `</td>` +
                        `<td>` + ticket['created_at'] + `</td>` +


                        `<td class="d-none" id="options-`+ticket['id']+`" >`+ticket['options']+`</td>`+
                        `<td class="d-none" id="features-`+ticket['id']+`" >`+ticket['features']+`</td>`+


                        `<td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item edit-to-open" data-toggle="modal" data-target="#editTicketModal" data-id="`+ticket['id']+`" data-price="`+ticket['price']+`" data-quantity="`+ticket['quantity']+`" data-public_title="`+ticket['public_title']+`">{{ __('Edit') }}</a>
                                            <a class="dropdown-item" id="remove_ticket" data-ticket-id="`+ticket['id']+`">{{ __('Delete') }}</a>

                                        </div>
                                    </div>
                        </td>


                        <td hidden id="public_title-${ticket['id']}">${ ticket['pivot']['public_title'] }</td>
                        <td hidden id="seats_visible-${ticket['id']}">${ ticket['pivot']['seats_visible'] }</td>

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

            let modelType = "{{addslashes ( get_class($model) )}}";
            let modelId = "{{ $model->id }}";

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '{{route("ticket.remove_event")}}',
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
         title = $(".admin_title_ticket_"+id).text(),
         price = $("#price-"+id).text(),
         //alert(price)
         quantity = $("#quantity-"+id).text();
         public_title = $("#public_title-"+id).text();
         options = $("#options-"+id).text();
         features = $("#features-"+id).text();
         features = features ? JSON.parse(features) : [];
         options = options ? JSON.parse(options) : [];

         seatsVisible = $("#seats_visible-"+id).text() != 0 && $("#seats_visible-"+id).text() != 'false' ? true : false;
         $("#input-show-seats").prop("checked",seatsVisible);

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

        modal.find("#edit-title-ticket").val(title)
        modal.find("#edit-price").val(price);
        modal.find("#edit-quantity1").val(quantity);
   	    modal.find("#ticket-id").val(id)
        modal.find("#edit-public_title").val(public_title)

   });

   $(document).on('click',"#edit-ticket",function(){
    let modelType = "{{addslashes ( get_class($model) )}}";
    let modelId = "{{ $model->id }}";

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
            data: {'option1':$('#option1_edit').val(),'option2':$('#option2_edit').val(),'option3':$('#option3_edit').val(),
                    'features':features,'price':$('#edit-price').val(),'public_title':$('#edit-public_title').val(),'quantity':$('#edit-quantity1').val(),
                    'model_type':modelType,'model_id':modelId, 'seats_visible':$("#input-show-seats").is(':checked')},
            success: function (data) {

        let quantity = data.data.quantity;
        let price = data.data.price;
        let ticket_id = data.data.ticket_id
        let options = data.data.options
        let features = data.data.features
        let public_title = data.data.public_title
        let seats_visible = data.data.seats_visible

        $("#quantity-"+ticket_id).html(quantity)
        $("#price-"+ticket_id).html(price)
        $("#options-"+ticket_id).html(options);
        $("#features-"+ticket_id).html(features);
        $("#seats_visible-"+ticket_id).html(seats_visible);
        $("#public_title-"+ticket_id).html(public_title);
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


<script src="{{ asset('js/sortable/Sortable.js') }}"></script>

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
         url: "{{ route ('sort-tickets', $event->id) }}",
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


   {{--$('.ticket-active').click(function(){

        let ticket = $(this).data('ticket');
        let event = $(this).data('event');
        let active = $(this).data('active');

        $.ajax({
         type: 'GET',
         headers: {
         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
         },
         Accept: 'application/json',
         url: "/admin/ticket-active/" + event + "/" + ticket + "/" + active,
         success: function(data) {

            $('.ticket-span' + data.ticket).html(data.text);
            //$('.ticket-active' + data.ticket).attr("data-active", data.active)
            $('.ticket-active' + data.ticket).data("active", data.active)
         }
      });

   })--}}

    $(document).on('click', '.visible-ticket',function(){
        let ticket = $(this).find('input').data('ticket');
        let event = $(this).find('input').data('event');
        let active = $(this).find('input').is(':checked') ? 0 : 1;

        $(this).find('input').prop('checked', active);

        $.ajax({
         type: 'GET',
         headers: {
         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
         },
         Accept: 'application/json',
         url: "/admin/ticket-active/" + event + "/" + ticket + "/" + active,
         success: function(data) {

            $('.ticket-span' + data.ticket).html(data.text);
            //$('.ticket-active' + data.ticket).attr("data-active", data.active)
            $('.ticket-active' + data.ticket).data("active", data.active)
         }
      });
    })


</script>

@endpush
