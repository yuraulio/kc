<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-8">
                <h3 class="mb-0">{{ __('Categories') }}</h3>
            </div>
            @can('create', App\Model\Role::class)
                <div class="col-4 text-right">
                    <a href="{{ route('category.create') }}" class="btn btn-sm btn-primary">{{ __('Add category') }}</a>
                </div>
            @endcan
        </div>
    </div>

    <div class="col-12 mt-2">
        @include('alerts.success')
        @include('alerts.errors')
    </div>

    <div class="table-responsive py-4">
        <table class="table table-flush"  id="datatable-basic29">
            <thead class="thead-light">
                <tr>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Parent') }}</th>
                    <th scope="col">{{ __('Hours') }}</th>
                    <th scope="col">{{ __('Dropbox') }}</th>
                    @can('manage-users', App\Model\User::class)
                        <th scope="col"></th>
                    @endcan
                </tr>
            </thead>
            <tbody>
            <?php //dd($categories); ?>
                @foreach ($categories as $category)
                    <tr>
                        <td><a href="{{ route('category.edit', $category) }}">{{ $category->name }}</a></td>
                        <td>{{ $category->parent }}</td>
                        <td>{{ $category->hours }}</td>
                        <td>
                        @if(count($category->dropbox) != 0)
                            <i class="ni ni-check-bold"></i>
                        @endif
                        </td>
                        @can('manage-users', App\Model\User::class)
                            <td class="text-right">
                                @can('update', $category)
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ route('category.edit', $category) }}">{{ __('Edit') }}</a>
                                            @can('delete', $category)
                                            <form action="{{ route('category.destroy', $category) }}" method="post">
                                                @csrf
                                                @method('delete')

                                                <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this category?") }}') ? this.parentElement.submit() : ''">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                            @endcan
                                        </div>

                                    </div>
                                @endcan
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
