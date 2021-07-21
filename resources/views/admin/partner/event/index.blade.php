
<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0">{{ __('Partners') }}</h3>
        <p class="text-sm mb-0">
            {{ __('This is an example of partner management. This is a minimal setup in order to get started fast.') }}
        </p>
    </div>
    <div class="col-4 text-right">
    <button data-toggle="modal" data-target="#partnerModal" class="btn btn-sm btn-primary">{{ __('Assign partner') }}</button>
    </div>
</div>

<div class="table-responsive py-4">
    <table class="table align-items-center table-flush"  id="datatable-basic">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Creation Date') }}</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody class="partner-body">
        @if($model->partners)
            @foreach ($model->partners as $partner)
                <tr id="partner-{{$partner->id}}">
                    <td>{{ $partner->name }}</td>
                    <td>{{ $partner->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" id="remove_partner" data-partner-id="{{ $partner->id }}">{{ __('Remove') }}</a>
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
<div class="modal fade" id="partnerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Create partner</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <h6 class="heading-small text-muted mb-4">{{ __('Partner information') }}</h6>
            <div class="pl-lg-4">
                <form>
                    <div class="pl-lg-4">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Select Partner</label>
                            <select class="form-control" id="partnerFormControlSelect">
                                <option>-</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            <button type="button" data-event-id="{{$model->id}}" id="save_partner_btn" class="btn btn-primary">Save changes</button>
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

        $(document).on('click', '#remove_partner',function(e) {

        let modelType = "{{addslashes ( get_class($model) )}}";
        let modelId = "{{ $model->id }}";

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '{{route("partner.remove_event")}}',
            data: {'partner_id':$(this).data('partner-id'), 'model_type':modelType,'model_id':modelId},
            success: function (data) {
                let partner_id = data.partner_id

                $('#partner-'+partner_id).remove();

            }
        });
        })


        $(document).on('click', '#save_partner_btn',function(e) {



        let modelType = "{{addslashes ( get_class($model) )}}";
        let modelId = "{{ $model->id }}";

        var selected_option = $('#partnerFormControlSelect option:selected');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '{{route("partner.store_event")}}',
            data: {'partner_id':$(selected_option).val(), 'model_type':modelType,'model_id':modelId},
            success: function (data) {
                let partner = data.partner


                let newPartner =
                `<tr id="partner-`+partner['id']+`">` +
                `<td>` + partner['name'] + `</td>` +
                `<td>` + partner['created_at'] + `</td>` +
                `<td class="text-right">`+
                `<div class="dropdown">
                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item" data-partner-id="`+partner['id']+`" id="remove_partner">{{ __('Remove') }}</a>

                    </div>
                </div>
                </td>


                </tr>`;

                $(".partner-body").append(newPartner);
                $(".close-modal").click();
                $("#success-message p").html(data.success);
                $("#success-message").show();


            }
        });

        })

        $(document).on('shown.bs.modal', '#partnerModal',function(e) {
            let modelType = "{{addslashes ( get_class($model) )}}";
            let modelId = "{{ $model->id }}";

        $('#partnerFormControlSelect option').each(function(key, value) {
                    $(value).remove()
            });

            $('#partnerFormControlSelect').append(`<option>-</option>`)

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/partner/fetchAllPartners',
            data: {'model_type':modelType,'model_id':modelId},
            success: function (data) {

                let partner = data.data.partners
                let assigned = data.data.assignedPartners


                $.each( partner, function( key, value ) {
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
                        $('#partnerFormControlSelect').append(row)
                    }

                });
            }
        });
        })

    </script>
@endpush
