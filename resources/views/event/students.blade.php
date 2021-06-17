<div class="card-header">
    <div class="row align-items-center">
        <div class="col-8">
            <h3 class="mb-0">{{ __('Students') }}</h3>
            <p class="text-sm mb-0">
                    {{ __('This is an example of Students management.') }}
                </p>
        </div>
        {{--@can('create', App\Model\User::class)
            <div class="col-4 text-right">
                <a href="{{ route('topics.create_event', ['event_id' => $event['id']]) }}" class="btn btn-sm btn-primary">{{ __('Assign Topic') }}</a>
            </div>
        @endcan--}}
    </div>
</div>

<div class="col-12 mt-2">
    @include('alerts.success')
    @include('alerts.errors')
</div>
<div class="table-responsive py-4">
    <table class="table align-items-center table-flush"  id="datatable-basic">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Lastname') }}</th>
                <th scope="col">{{ __('Expiration') }}</th>

        
            </tr>
        </thead>
        <tbody>
        <?php //dd($allTopicsByCategory); ?>
            @foreach ($event->users as $user)

                <tr>
                    <td>{{ $user->firstname }}</td>
                    <td>{{ $user->lastname }}</td>
                    <td>{{ $user->expiration }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
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


@endpush
