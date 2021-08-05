                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Venues') }}</h3>
                            </div>
                                <div class="col-4 text-right">
                                    <a href="{{ route('venue.create', ['id' => $event['id']]) }}" class="btn btn-sm btn-primary">{{ __('Add venue') }}</a>
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
                                    <th scope="col">{{ __('Address') }}</th>
                                    <th scope="col">{{ __('Longitude') }}</th>
                                    <th scope="col">{{ __('Latitude') }}</th>
                                    <th scope="col">{{ __('Created at') }}</th>
                                    @can('manage-users', App\Model\User::class)
                                        <th scope="col"></th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($event->venues as $venue)
                                    <tr>
                                        <td>{{ $venue->name }}</td>
                                        <td>{{ $venue->address }}</td>
                                        <td>{{ $venue->longitude }}</td>
                                        <td>{{ $venue->latitude }}</td>


                                        <td>{{ date_format($venue->created_at, 'Y-m-d' ) }}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" href="{{ route('venue.edit', ['venue' => $venue, 'event_id'=>$event['id']]) }}">{{ __('Edit') }}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
        </div>

