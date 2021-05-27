<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0">{{ __('Tickets') }}</h3>
        <p class="text-sm mb-0">
                {{ __('This is an example of Ticket management.') }}
            </p>
    </div>
    @can('create', App\Model\User::class)
        <div class="col-4 text-right">
        <button data-toggle="modal" data-target="#ticketModal" class="btn btn-sm btn-primary">{{ __('Add ticket') }}</button>
        </div>
    @endcan
</div>


<div class="table-responsive py-4">
    <table class="table align-items-center table-flush"  id="datatable-basic">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('Title') }}</th>
                <th scope="col">{{ __('Subtitle') }}</th>
                <th scope="col">{{ __('Type') }}</th>
                <th scope="col">{{ __('Features') }}</th>
                <th scope="col">{{ __('Quantity') }}</th>
                <th scope="col">{{ __('Created at') }}</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($event->ticket as $ticket)
                <tr>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ $ticket->subtitle }}</td>
                    <td>{{ $ticket->type }}</td>
                    <td>{{ $ticket->features }}</td>
                    <td>{{ $ticket->pivot->quantity }}</td>

                    <td>{{ date_format($ticket->created_at, 'Y-m-d' ) }}</td>

                    <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" id="remove_ticket" data-ticket-id="{{ $ticket->id }}">{{ __('Remove') }}</a>
                            </div>
                        </div>
                    </td>
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
                    <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                    <label class="form-control-label" for="input-price">{{ __('Price') }}</label>
                    <input type="number" price="price" id="input-price" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" placeholder="{{ __('Price') }}" value="{{ old('price') }}" required autofocus>
                    @include('alerts.feedback', ['field' => 'price'])
                </div>
                <div class="form-group{{ $errors->has('quantity') ? ' has-danger' : '' }}">
                    <label class="form-control-label" for="input-quantity">{{ __('Quantity') }}</label>
                    <input type="text" name="quantity" id="input-quantity" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" placeholder="{{ __('Quantity') }}" value="{{ old('quantity') }}" autofocus>
                    @include('alerts.feedback', ['field' => 'quantity'])
                </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="ticket_save_btn" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-link close_modal ml-auto" data-dismiss="modal">Close</button>
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


      </script>
@endpush
