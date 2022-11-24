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


    <div class="row">
        <div class="col-12 text-right">
            <a class="btn btn-sm btn-secondary" id="filter-student-btn" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">{{ __('Import') }}</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="collapse" id="collapseExample" style="">
                <div class="container-fluid">
                    <div class="row">

                        {{--<div class="col-12">
                            <div class="form-group">
                                <a href="{{ route('student.cvs_template') }}" class="btn btn-sm btn-primary" target="_black" type="button">Download Template</a>
                            </div>

                            <form id="uploadcsv_form">
                                <div class="form-group">
                                    <label for="exampleFormControlFile1">Upload CSV</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input name="file" type="file" class="custom-file-input" id="file"
                                            aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                        </div>
                                        <input type="hidden" value="{{ $event->id }}" name="event_id">
                                    </div>
                                </div>
                                <div class="form-group error-msg"><p></p></div>
                                <div class="form-group text-right">
                                    <button class="btn btn-sm" id="submit-file" type="button">Submit</button>
                                </div>
                            </form>
                        </div>--}}


                    </div>
                </div>
            </div>
        </div>
    </div>





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

            </tr>
        </thead>
        <tbody>
        <?php //dd($allTopicsByCategory); ?>
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

                </tr>
            @endforeach
        </tbody>
    </table>




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
                buttons: ['copy'],

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

            $('#file').on('change',function(){
                //get the file name
                var fileName = $(this).val();
                //replace the "Choose a file" label
                $(this).next('.custom-file-label').html(fileName.substring(fileName.lastIndexOf("\\") + 1));
            })


            $(document).on('click', '#submit-file', function() {

                var formData = new FormData($('#uploadcsv_form')[0]);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    data: formData,
                    processData: false,
                    contentType: false,
                    url: "{{ route('student.upload_csv') }}",
                    beforeSend: function() {
                        $('.error-msg').empty()
                        $('.error-msg').append('Wait for server response!!')

                    },
                    success: function (data) {
                        $('.error-msg').empty()

                        if(data.error){

                            Swal.close()

                            if(data.from == 'file'){
                                let msg = data.messages.file[0]
                                $('.error-msg').append(`<p>${msg}</p>`)
                            }else if(data.from == 'import'){

                                Swal.fire(
                                    'Fail to import this emails:',
                                    data.messages +' <br><a target="_black" href="/uploads/students/error_import_emails.xlsx">Download failed emails</a>',
                                    'info'
                                )
                            }else{
                                Swal.fire(
                                    'Information:',
                                    data.messages,
                                    'error'
                                )
                            }

                        }else if(!data.error){
                            Swal.fire(
                            'Notification!',
                            'Import successfully finished',
                            'success')
                        }


                    }
                })
            })

        } );
    </script>


@endpush
