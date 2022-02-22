
<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0">{{ __('Venue') }}</h3>
        <p class="text-sm mb-0">
                {{ __('This is an example of Type management.') }}
            </p>
    </div>
    @can('create', App\Model\User::class)
        <div class="col-4 text-right">
            {{--<a href="{{ route('venue.create', ['id' => $event['id']]) }}" class="btn btn-sm btn-primary">{{ __('Assign Venue') }}</a>--}}
            <button data-toggle="modal" data-target="#venueModal" class="btn btn-sm btn-primary">{{ __('Assign Venue') }}</button>
        </div>
    @endcan
</div>


<div class="col-12 mt-2">
    @include('alerts.success')
    @include('alerts.errors')
</div>

<?php 

   $id = isset($sections['location'][0]) ? $sections['location'][0]['id'] : '';
   $tab_title = isset($sections['location'][0]) ? $sections['location'][0]['tab_title'] : '' ;
   $title = isset($sections['location'][0]) ? $sections['location'][0]['title'] : '' ;
   $visible = isset($sections['location'][0]) ? $sections['location'][0]['visible'] : false ;

?> 



<div class="form-group">

   <input hidden name="sections[location][id]" value="{{$id}}"> 

   <label class="form-control-label" for="input-title">{{ __('Tab Title') }}</label>
   <input type="text" name="sections[location][tab_title]" class="form-control" placeholder="{{ __('Tab Title') }}" value="{{ old("sections[location][tab_title]", $tab_title) }}" autofocus> 
   <label class="form-control-label" for="input-title">{{ __('H2 Title') }}</label>
   <input type="text" name="sections[location][title]" class="form-control" placeholder="{{ __('H2 Title') }}" value="{{ old("sections[location][title]", $title) }}" autofocus>


   <label class="form-control-label" for="input-method">{{ __('Visible') }}</label>
   <div style="margin: auto;" class="form-group">

       <label class="custom-toggle enroll-toggle">
           <input type="checkbox"  name="sections[location][visible]" @if($visible) checked @endif>
           <span class="custom-toggle-slider rounded-circle" data-label-off="no visible" data-label-on="visible"></span>
       </label>

   </div>
                                

</div>


<div class="table-responsive py-4">
    <table class="table align-items-center table-flush"  id="datatable-basic20">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Address') }}</th>
                <th scope="col">{{ __('Longitude') }}</th>
                <th scope="col">{{ __('Latitude') }}</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody class="venue-body">
        <?php //dd($model->venues); ?>
        @if($model->venues)
            @foreach ($model->venues as $venue)
                <tr id="venue_{{$venue->id}}">
                    <td>{{ $venue->name }}</td>
                    <td>{{ $venue->address }}</td>
                    <td>{{ $venue->longitude }}</td>
                    <td>{{ $venue->latitude }}</td>
                    <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item" id="remove_venue" data-venue-id="{{ $venue->id }}">{{ __('Remove') }}</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

<!-- Modal -->

<div class="modal fade" id="venueModal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default">Assign Venue</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- <h6 class="heading-small text-muted mb-4">{{ __('Benefit information') }}</h6> -->
                <div class="pl-lg-4">
                    <form>
                    <div class="pl-lg-4">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Select Venues</label>
                            <select class="form-control" id="venueFormControlSelect">
                                <option>-</option>
                            </select>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="button" data-event-id="{{$model->id}}" id="venue_save_btn" class="btn btn-primary">Save changes</button>
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

    $(document).on('shown.bs.modal', '#venueModal',function(e) {
        let modelType = "{{addslashes ( get_class($model) )}}";
        let modelId = "{{ $model->id }}";

        $('#venueFormControlSelect option').each(function(key, value) {
                    $(value).remove()
            });

            $('#venueFormControlSelect').append(`<option>-</option>`)

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/venue/fetchAllVenues',
            data: {'model_type':modelType,'model_id':modelId},
            success: function (data) {
                let venue = data.data.venues
                let assigned = data.data.assignedVenues

                $.each( venue, function( key, value ) {
                    let found = false
                    $.each( assigned, function( key1, value1 ) {
                        if(value.id == value1.id){
                            found = true
                        }
                    })

                    if(!found){
                        row =`
                        <option value="${value.id}">${value.name}</option>
                        `
                        $('#venueFormControlSelect').append(row)
                    }
                });
            }
        });
    })

    $(document).on('click', '#remove_venue',function(e) {

        let modelType = "{{addslashes ( get_class($model) )}}";
        let modelId = "{{ $model->id }}";

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '{{route("venue.remove_event")}}',
            data: {'venue_id':$(this).data('venue-id'), 'model_type':modelType,'model_id':modelId},
            success: function (data) {
                let venue_id = data.venue_id

                $('#venue_'+venue_id).remove();

            }
        });
    })



    $(document).on('click', '#venue_save_btn',function(e) {



        let modelType = "{{addslashes ( get_class($model) )}}";
        let modelId = "{{ $model->id }}";

        var selected_option = $('#venueFormControlSelect option:selected');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '{{route("venue.store")}}',
            data: {'venue_id':$(selected_option).val(), 'model_type':modelType,'model_id':modelId},
            success: function (data) {
                let venue = data.venue


                let newVenue =
                `<tr id="venue_`+venue['id']+`">` +
                `<td>` + venue['name'] + `</td>` +
                `<td>` + venue['address'] + `</td>` +
                `<td>` + venue['longitude'] + `</td>` +
                `<td>` + venue['latitude'] + `</td>` +
                `<td class="text-right">`+
               `<div class="dropdown">
                  <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                  <a class="dropdown-item" data-venue-id="`+venue['id']+`" id="remove_venue">{{ __('Remove') }}</a>

                  </div>
               </div>
            </td>


                </tr>`;

                $(".venue-body").append(newVenue);
                $(".close-modal").click();
                $("#success-message p").html(data.success);
   	            $("#success-message").show();


            }
        });

    })

</script>
@endpush
