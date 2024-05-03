 <!-- Create Modal -->
 <div class="modal fade" id="absences-info" tabindex="-1" role="dialog" aria-labelledby="assignUserEventLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignUserEventLabel">Absences Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="absences-modal">

                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0" >Total Presence Hours</h5>
                                        <span class="h2 font-weight-bold mb-0" id="presence-hours"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Total Absence Hours</h5>
                                        <span class="h2 font-weight-bold mb-0" id="absence-hours"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Absence(%)</h5>
                                        <span class="h2 font-weight-bold mb-0" id="absence-percent"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>


@push('js')

<script>

    $(document).on('click','.absences',function(){


        let url =`/admin/user/absences/${$(this).data('user_id')}/${$(this).data('event_id')}`


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: url,

            success: function (data) {

                if(data.success == true){
                    createAbsenceTable(data)
                }else{

                }

            }
        });

    });


    let totalHoursPresenceHours = '';
    let absClass = '';

    $("#absences-info").on('hidden.bs.modal', function (e) {

        $("#presence-hours").text(0);
        $("#absence-hours").text(0);
        $("#absence-percent").text(0 + '%');
        $(`.${absClass}`).removeClass(absClass);
        absClass = '';

        var t = $('#absences-table').DataTable();
        t.clear();
        t.destroy();
        $('#absences-table').remove();
    })


    $(document).on('click','.absence-edit',function(event){
        event.stopPropagation();  // Prevent event bubbling
      event.stopImmediatePropagation();
      $("#absences-table input").attr("readonly", true);
        $(".absence-border").removeClass('absence-border')
        $(".absence-update").addClass('hidden');

        let id = $(this).data('absence-id');

        totalHours = ($(`#absence-input-${id}`).val()).split('/')[1];
        $(`#absence-input-${id}`).attr("readonly", false);
        $(`#absence-input-${id}`).addClass('absence-border');
        $(`#absence-update-${id}`).removeClass("hidden");

    })

    $(document).on('click','.absence-update',function(event){
        event.stopPropagation();  // Prevent event bubbling
        let id = $(this).data('absence-id');

        let updatePresenceHours = $(`#absence-input-${id}`).val();
        updatePresenceHours = updatePresenceHours.split('/')[0];

        if(updatePresenceHours > totalHours){
            //alert("Presence hours cannot be greater than the total course hours.")
            alert("Wrong value")
            return;
        }


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: "{{route('update-absences')}}",
            data:{'presenceHours':updatePresenceHours, 'user_id':"{{$user['id']}}", "absence":id,'event_id': $(this).data('event-id')},
            success: function (data) {

                if(data.success == true){

                    $(`#absence-input-${id}`).attr("readonly", true);
                    $(`#absence-input-${id}`).removeClass('absence-border');
                    $(`#absence-update-${id}`).addClass('hidden');

                    createAbsenceTable(data)


                }else{

                }

            }
        });


    })

    function createAbsenceTable(data){

        absClass = data.data.class;

        let tableHtml =
                        `<table class="table align-items-center table-flush"  id="absences-table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('date') }}</th>
                                    <th scope="col">{{ __('check in') }}</th>
                                    <th scope="col">{{ __('presence hours') }}</th>
                                    <th scope="col">{{ __('absence hours') }}</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody id="absencesBody">

                            </tbody>
                        </table>
                        `

                    $("#absences-modal").append(tableHtml)

                    $("#presence-hours").text((data.data.total_user_minutes/60));
                    $("#absence-hours").text((data.data.user_minutes_absences/60));
                    $("#absence-percent").text(data.data.user_absences_percent.toFixed(2) + '%');
                    $("#absence-percent").addClass(absClass);

                    $('#absences-table').DataTable( {
                        destroy: true,
                        dom: 'lrtip',
                        bPaginate: false,
                        bLengthChange: false,
                        order: [[ 0, "asc" ]],
                        language: {
                            paginate: {
                                next: '&#187;', // or '→'
                                previous: '&#171;' // or '←'
                            }
                        },

                    });

                    var t = $('#absences-table').DataTable();
                    let totalEventHours = 0;
                    let totalUserHours = 0;
                    t.clear();
                    $.each(data.data.absences_by_date, function(key1, value1) {

                        totalEventHours = value1['event_minutes'] / 60;
                        totalUserHours = value1['user_minutes'] / 60;

                        t.row.add([

                            key1,
                            totalUserHours > 0 ? `<i class="ni ni-check-bold"></i>` : '',
                            `<input id="absence-input-${value1['id']}" value=${totalUserHours + '/' +  totalEventHours} readonly>`,
                            (totalEventHours - totalUserHours) + '/' +  totalEventHours,
                            `<button class="btn btn-info btn-sm absence-edit" data-absence-id=${value1['id']} > Edit </button>`,
                            `<button id="absence-update-${value1['id']}" class="btn btn-info btn-sm absence-update hidden" data-absence-id=${value1['id']} data-event-id=${data.data.event_id}> Update </button>`,
                        ]).draw()

                    })
    }

</script>

@endpush
