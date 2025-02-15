<div class="card-header">
    <div class="row align-items-center">
        <div class="col-8">
            <h3 class="mb-0">{{ __('Students') }}</h3>
        </div>
    </div>
</div>

<div class="col-12 mt-2">
    @include('alerts.success')
    @include('alerts.errors')
</div>
    <table class="table align-items-center table-flush"  id="datatable-basic-students-waiting1">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Lastname') }}</th>
                <th scope="col">{{ __('Email') }}</th>
                <th scope="col">{{ __('Mobile') }}</th>

            </tr>
        </thead>
        <tbody>
        <?php //dd($allTopicsByCategory); ?>
            @foreach ($eventWaitingUsers as $user)

                <tr>
                    <td><a target="_blank" href="{{ route('user.edit', $user['user']['id']) }}">{{ $user['user']['firstname'] }}</a></td>
                    <td>{{ $user['user']['lastname'] }}</td>
                    <td><a href="mailto:{{ $user['email'] }}">{{ $user['user']['email'] }}</a> </td>
                    <td>{{ $user['user']['mobile'] }}</td>



                </tr>
            @endforeach
        </tbody>
    </table>




@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
@endpush

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js" integrity="sha512-xQBQYt9UcgblF6aCMrwU1NkVA7HCXaSN2oq0so80KO+y68M+n64FOcqgav4igHe6D5ObBLIf68DWv+gfBowczg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-select/js/dataTables.select.min.js"></script>


    <script>

        $(document).ready(function() {
            var table = $('#datatable-basic-students-waiting1').DataTable({
                buttons: ['copy'],

                language: {
                    paginate: {
                    next: '&#187;', // or '→'
                    previous: '&#171;' // or '←'
                    }
                },
            });

            table.buttons().container()
                .appendTo('#ddatatable-basic-students-waiting1_wrapper .col-md-6:eq(0)');

            $('#datatable-basic-students-waiting1').addClass('table-responsive-sm')



            $('#datatable-basic-students-waiting1_filter').append(

                `<div id="export-student-waiting-button" class='export-student-waiting-button'>
                    <button title="export waiting list students to csv" class="btn btn-icon btn-primary" type="button">
                        <span class="btn-inner--icon"><i class="ni ni-cloud-download-95"></i></span>
                    </button>
                </div>
                `
            )

            $(document).on("click","#export-student-waiting-button",function() {
                let state = 'student_waiting_list';

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('event.export-students')}}",
                    type: "POST",
                    data:{state: state, id: @json($event->id)} ,
                    success: function(data) {
                        window.location.href = '/tmp/exports/StudentsWaitingListExport.xlsx'
                    }
                });
            });

        } );
    </script>


@endpush
