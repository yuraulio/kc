@extends('layouts.app', [
    'title' => __('Menu Management'),
    'parentSection' => 'laravel',
    'elementName' => 'menu-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Examples') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('menu.index') }}">{{ __('Menus Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('List')}}</li>
            <li class="breadcrumb-item active" aria-current="page">{{ $name }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Menus') }}</h3>
                                <p class="text-sm mb-0">
                                        {{ __('This is an example of Menu management.') }}
                                    </p>
                            </div>
                            @can('create', App\Model\User::class)
                                <div class="col-4 text-right">
                                    <button data-toggle="modal" data-target="#menuModal" class="btn btn-sm btn-primary">{{ __('Add item') }}</button>
                                </div>
                            @endcan
                        </div>
                    </div>

                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic13">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Name') }}</th>
                                    @can('manage-users', App\Model\User::class)
                                        <th scope="col"></th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody id="menu-body">
                            @if($result)
                                @foreach ($result[$name] as $key => $menu)
                                    <tr id="itemMenu_{{$menu['id']}}">
                                        <td>{{ $menu['data']['name'] }}</td>
                                        {{--<td>
                                            @if(count($menu->events) != 0)
                                                @foreach($menu->events as $event)
                                                    {{ $event->title }}
                                                @endforeach
                                            @endif
                                        </td>--}}
                                        <?php $id = $menu['data']['id']; ?>
                                        {{--<td>{{ date_format($menu['created_at'], 'Y-m-d' ) }}</td>--}}
					                        <td class="text-right">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        {{--<a class="dropdown-item" href="{{ route('menu.edit', $menu) }}">{{ __('Edit') }}</a>--}}
                                                        <a class="dropdown-item" id="remove_item" data-item-id="{{$menu['id']}}">{{ __('Delete') }}</a>

                                                        {{--<form action="{{ route('menu.destroy', $menu) }}" method="post">
                                                            @csrf
                                                            @method('delete')

                                                            <input type="hidden" name="id" value="{{ $menu['id'] }}">

                                                            <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this Menu?") }}') ? this.parentElement.submit() : ''">
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </form>--}}
                                                    </div>
                                                </div>
                                            </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="menuModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuModalLabel">{{$name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="optionFormControlMenu">Item</label>
                    <select class="form-control" id="menuItem">

                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="button" id="save-btn" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

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


        $(document).on('shown.bs.modal', '#menuModal',function(e) {
            $('#menuItem').empty()
            let menu_name = "{{ $name }}";

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '/admin/menu/fetchAllMenu',
                data:{'name': menu_name},
                success: function (data) {
                    let categories = data.data.categories
                    let types = data.data.types
                    let deliveries = data.data.deliveries

                    $.each( categories, function( key, value ) {
                    row =`
                    <option value="Category-${value.id}">${value.name}</option>
                    `
                    $('#menuItem').append(row)

                    });

                    $.each( types, function( key, value ) {
                       //console.log(key + ':' + value.title)
                    row =`
                    <option value="Type-${value.id}">${value.name}</option>
                    `
                    $('#menuItem').append(row)

                    });

                    $.each( deliveries, function( key, value ) {
                       //console.log(key + ':' + value.title)
                    row =`
                    <option value="Delivery-${value.id}">${value.name}</option>
                    `
                    $('#menuItem').append(row)

                    });
                }
            });
        })

        $(document).on('click',"#save-btn",function(){

            let menu_name = "{{ $name }}";


        var selected_option = $('#menuItem option:selected');

        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '{{route("menu.store_item")}}',
            data: {'menu':$(selected_option).val(),'name':menu_name},
            success: function (data) {
            let item = data.data.find_item;
            let menu = data.data.menu;
            let newItem =
            `<tr id=itemMenu_`+menu.id+`>` +
            `<td>`+item.name+`</td>`+
            `<td class="text-right">
                <div class="dropdown">
                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item" id="remove_item" data-item-id="`+menu.id+`">{{ __('Delete') }}</a>

                    </div>
                </div>
            </td>

            </tr>`;


            $("#menu-body").append(newItem);
            $(".close-modal").click();
            $("#success-message p").html(data.success);
            $("#success-message").show();
            // $('#newRow').empty()
            // $('#newRowEdit').empty()
            // $('#ticket-form').trigger('reset');
            $('#menuItem').empty()
                },
                error: function() {
                    //console.log(data);
                }
            });



        })

        $(document).on('click', '#remove_item',function(e) {
            let id = $(this).data('item-id')

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '{{route("menu.remove_item")}}',
                data: {'item_id':id},
                success: function (data) {


                $(`#itemMenu_${data.data}`).remove()

                }
            });
            })

      </script>
@endpush
