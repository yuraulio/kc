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
@include('users.absences.absences_modal')

    <table class="table align-items-center table-flush"  id="datatable-basic-students1">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Lastname') }}</th>
                <th scope="col">{{ __('Email') }}</th>
                <th scope="col">{{ __('Mobile') }}</th>

                @if(!$isInclassCourse)
                <th scope="col">{{ __('Videos Seen') }}</th>
                <th scope="col">{{ __('Progress') }}</th>
                @endif

                @if($isInclassCourse)
                <th scope="col">{{ __('Absences') }}</th>
                @endif
                @if(!$isInclassCourse)
                <th scope="col">{{ __('Status')}}</th>
                @endif
                <th scope="col">{{ __('Last seen')}}</th>
                <th scope="col">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        <?php //dd($allTopicsByCategory);
        $i=0; ?>
            @php
            $statistics = Illuminate\Support\Facades\DB::table('event_statistics')->select('user_id','updated_at','last_seen')->where('event_id', $event->id)->get();
            @endphp
            @foreach ($eventUsers as $user)

                <tr>
                    <td><a target="_blank" href="{{ route('user.edit', $user['id']) }}">{{ $user['firstname'] }}</a></td>
                    <td>{{ $user['lastname'] }}</td>
                    <td><a href="mailto:{{ $user['email'] }}">{{ $user['email'] }}</a> </td>
                    <td>{{ $user['mobile'] }}</td>

                    @if(!$isInclassCourse)
                        <td>{{ $event->video_seen($user) }}</td>
                        <td>{{ round($event->progress($user),2)  }}%</td>
                    @endif

                    @if($isInclassCourse)
                        <td> <button class="absences btn btn-info btn-sm" style="margin-top:10px;" type="button"
                                                data-user_id="{{$user['id']}}" data-event_id="{{$event->id}}"
                                                data-toggle="modal" data-target="#absences-info">Absences</button> </td>
                    @endif

                    @if(!$isInclassCourse)
                    <td>

                        @if($user->pivot['paid'] == 1 && $user->pivot['expiration'])
                            <?php

                                $expiration_event = strtotime($user->pivot->expiration);
                                $now = strtotime(date('Y-m-d'));

                            ?>

                            @if($expiration_event >= $now)
                            <?php $i++;?>
                            <span data-status="Open" class="badge badge-dot mr-4">
                                <i class="bg-success"></i>
                            </span>
                            @else
                            <span data-status="Open" class="badge badge-dot mr-4">
                                <i class="bg-danger"></i>
                            </span>
                            @endif

                        @else
                            <span data-status="Open" class="badge badge-dot mr-4">
                                <i class="bg-danger"></i>
                            </span>
                        @endif

                    </td>
                    @endif
                    <td>
                    @foreach($statistics as $stat)
                        @if($stat->user_id == $user->id)
                            <span style="color: transparent; font-size:1px">{{ date('Ymd', strtotime($stat->last_seen)) }}</span><span>{{ date('d/m/Y', strtotime($stat->last_seen)) }}</span>
                            @break
                        @endif
                    @endforeach
                    </td>
                    <td>
                      <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                          <form action="{{ route('user.login_as', $user->id) }}" target="_blank" method="post">
                            {{ csrf_field() }}
                            <button type="button" class="dropdown-item login-as-btn">
                              Login as
                            </button>
                          </form>

                        </div>
                      </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{--dd($i)--}}




@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/sweetalert2/dist/sweetalert2.min.css">
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
    <script src="{{ asset('argon') }}/vendor/sweetalert2/dist/sweetalert2.min.js"></script>

    <script>

        $(document).ready(function() {
            var table = $('#datatable-basic-students1').DataTable({

                language: {
                    paginate: {
                    next: '&#187;', // or '→'
                    previous: '&#171;' // or '←'
                    }
                },
            });

            table.buttons().container()
                .appendTo('#datatable-basic-students1_wrapper .col-md-6:eq(0)');

            $('#datatable-basic-students1').parent().addClass('table-responsive')

            $('#datatable-basic-students1_filter').append(
                `
                <div id="export-student-button" class='export-student-waiting-button '>
                    <button title="export students to csv" class="btn btn-primary btn-sm" type="button">
                        Export students to csv
                    </button>
                </div>
                <div id="export-student-exam-button">
                    <button id="export-exam-button" class="btn btn-primary btn-sm" type="button">
                    Export students result
                    </button>
                </div>
                `
            )

            $(document).on("click","#export-student-button",function() {

                let state = 'student_list';

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('event.export-students')}}",
                    type: "POST",
                    data:{state: state, id: @json($event->id)} ,
                    success: function(data) {
                        window.location.href = '/tmp/exports/StudentsListExport.xlsx'
                    }
                });
            });

            $(document).on("click","#export-exam-button",function() {

                //let state = 'student_list';

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('event.export-students-exams')}}",
                    type: "POST",
                    data:{id: @json($event->id)} ,
                    success: function(data) {
                        window.location.href = '/tmp/exports/StudentsExamsResultsExport.xlsx'
                    }
                });
            });

          $(document).on('click', '.login-as-btn', e => {
            confirm('Are you sure you want to login as this user?') ? $(e.currentTarget).parent().submit() : '';
          });
        } );
    </script>


@endpush
