<div class="col-8">
                                <h3 class="mb-0">{{ __('Benefits') }}</h3>
                                <p class="text-sm mb-0">
                                        {{ __('This is an example of Benefits management.') }}
                                    </p>
                            </div>
<div class="col-4 text-right">
                                    <a href="{{ route('benefit.create', ['id' => $model->id]) }}" class="btn btn-sm btn-primary">{{ __('Add benefit') }}</a>
                                </div>
<div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Description') }}</th>
                                    <th scope="col">{{ __('Priority') }}</th>
                                    <th scope="col">{{ __('Created at') }}</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                            <?php //dd($event->benefitable); ?>
                                @if($model->benefits)
                                    @foreach ($model->benefits as $benefit)
                                        <tr>
                                            <td>{{ $benefit->name }}</td>
                                            <td>{{ $benefit->description }}</td>
                                            <td>{{ $benefit->priority }}</td>
                                            <td>{{ date_format($benefit->created_at, 'd-m-Y' ) }}</td>
                                           
                                                <td class="text-right">
                                                    @if (auth()->user()->can('update', $user) || auth()->user()->can('delete', $user))
                                                        <div class="dropdown">
                                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                                                    @can('update', $user)
                                                                        <a class="dropdown-item" href="{{ route('benefit.edit', $benefit) }}">{{ __('Edit') }}</a>
                                                                    @endcan
                                                                    {{--@can('delete', $user)
                                                                        <form action="{{ route('benefit.destroy', $benefit) }}" method="post">
                                                                            @csrf
                                                                            @method('delete')

                                                                            <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this Ticket?") }}') ? this.parentElement.submit() : ''">
                                                                                {{ __('Delete') }}
                                                                            </button>
                                                                        </form>
                                                                    @endcan--}}

                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                           
                                        </tr>
                                    @endforeach
                                @endif
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