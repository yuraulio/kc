
<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0">{{ __('City') }}</h3>
        <p class="text-sm mb-0">
                {{ __('This is an example of Type management.') }}
            </p>
    </div>
    @can('create', App\Model\User::class)
        <div class="col-4 text-right">
            <button data-toggle="modal" data-target="#cityModal" class="btn btn-sm btn-primary">{{ __('Assign City') }}</button>
        </div>
    @endcan
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
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody class="city-body">
        @if($model->city)
            @foreach ($model->city as $city)
                <tr>
                    <td id="name-{{$city->id}}">{{ $city->name }}</td>
                    {{--@can('manage-users', App\Model\User::class)
                        <td class="text-right">

                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" data-toggle="modal" data-target="#editCityModal" data-id="{{$city->id}}" data-name="{{$city->name}}">{{ __('Edit') }}</a>

                                        <a class="dropdown-item" href="{{ route('city.edit', $city) }}">{{ __('Edit') }}</a>
                                        <form action="{{ route('city.destroy', $city) }}" method="post">
                                            @csrf
                                            @method('delete')

                                            <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>

                        </td>
                    @endcan--}}
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

<!-- Modal -->

<div class="modal fade" id="cityModal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default">Assign City</h6>
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
                            <label for="exampleFormControlSelect1">Select Cities</label>
                            <select class="form-control" id="cityFormControlSelect">
                                <option>-</option>
                            </select>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" data-event-id="{{$model->id}}" id="city_save_btn" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-link close_modal ml-auto close-modal" data-dismiss="modal">Close</button>
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

    $(document).on('shown.bs.modal', '#cityModal',function(e) {

        $('#cityFormControlSelect option').each(function(key, value) {
                    $(value).remove()
            });

            $('#cityFormControlSelect').append(`<option>-</option>`)

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: '/admin/city/fetchAllCities',
            success: function (data) {
                let city = data.cities


                $.each( city, function( key, value ) {
                    console.log( key + ": " + value.name );
                    row =`
                        <option value="${value.id}">${value.name}</option>
                    `
                    $('#cityFormControlSelect').append(row)
                });
            }
        });
    })



    $(document).on('click', '#city_save_btn',function(e) {

        let modelType = "{{addslashes ( get_class($model) )}}";
        let modelId = "{{ $model->id }}";

        var selected_option = $('#cityFormControlSelect option:selected');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '{{route("city.store")}}',
            data: {'city_id':$(selected_option).val(), 'model_type':modelType,'model_id':modelId},
            success: function (data) {
                let city = data.city

                $('.city-body tr').remove();


                let newCity =
                `<tr>` +
                `<td id="name-` + city['id'] +`">` + city['name'] + `</td>` +
                `

                </tr>`;

                $(".city-body").append(newCity);
                $(".close-modal").click();
                $("#success-message p").html(data.success);
   	            $("#success-message").show();


            }
        });

    })



</script>
@endpush
