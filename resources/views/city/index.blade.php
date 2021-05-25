
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('City') }}</h3>
                                <p class="text-sm mb-0">
                                        {{ __('This is an example of Type management.') }}
                                    </p>
                            </div>
                            @can('create', App\Model\User::class)
                                <div class="col-4 text-right">
                                    <a href="{{ route('city.create', ['id' => $event['id']]) }}" class="btn btn-sm btn-primary">{{ __('Add City') }}</a>
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
                                    <th scope="col">{{ __('Created at') }}</th>
                                    @can('manage-users', App\Model\User::class)
                                        <th scope="col"></th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($event->city as $city)
                                    <tr>
                                        <td>{{ $city->name }}</td>
                                        <td>{{ date_format($city->created_at, 'Y-m-d' ) }}</td>
					                    @can('manage-users', App\Model\User::class)
					                        <td class="text-right">
                                                @if (auth()->user()->can('update', $user) || auth()->user()->can('delete', $user))
                                                    <div class="dropdown">
                                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                                                @can('update', $user)
                                                                    <a class="dropdown-item" href="{{ route('city.edit', $city) }}">{{ __('Edit') }}</a>
                                                                @endcan
    							                                @can('delete', $user)
        							                                <form action="{{ route('city.destroy', $city) }}" method="post">
                                                                        @csrf
                                                                        @method('delete')

                                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                                                            {{ __('Delete') }}
                                                                        </button>
                                                                    </form>
    						                                    @endcan

                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
					                    @endcan
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
