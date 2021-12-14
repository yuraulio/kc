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
        <table class="table table-flush"  id="datatable-basic99">
            <thead class="thead-light">
                <tr>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Parent') }}</th>
                    <th scope="col">{{ __('Hours') }}</th>
                    <th scope="col">{{ __('Dropbox') }}</th>
                    <th scope="col" hidden></th>
                    @can('manage-users', App\Model\User::class)
                        <th scope="col"></th>
                    @endcan
                </tr>
            </thead>
            <tbody class="categories-order">
            <?php //dd($categories); ?>
                @foreach ($categories as $category)
                    <tr>
                        <td class="category-order" data-category="{{$category->id}}" data-priority="{{$category->priority}}"><a href="{{ route('category.edit', $category) }}">{{ $category->name }}</a></td>
                        <td>{{ $category->parent }}</td>
                        <td>{{ $category->hours }}</td>
                        <td>
                        @if(count($category->dropbox) != 0)
                            <i class="ni ni-check-bold"></i>
                        @endif
                        </td>
                        <td class="hidden">{{$category->priority}}</td>
                        @can('manage-users', App\Model\User::class)
                            <td class="text-right">
                              
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ route('category.edit', $category) }}">{{ __('Edit') }}</a>
                                            
                                            <form action="{{ route('category.destroy', $category) }}" method="post">
                                                @csrf
                                                @method('delete')

                                                <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this category?") }}') ? this.parentElement.submit() : ''">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                            
                                        </div>

                                    </div>
                               
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('js')

<script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>

<script>


    $('#datatable-basic99').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "order": [[ 4, "asc" ]],
        language: {
            paginate: {
            next: '&#187;', // or '→'
            previous: '&#171;' // or '←'
            }
        }
    });

</script>


<script src="{{ asset('js/sortable/Sortable.js') }}"></script>
    <script>
        let category ;

        (function( $ ){


            categories = {};
            var el

            el = document.getElementsByClassName('categories-order')[0];


            new Sortable(el, {
                group: "words",
                handle: ".my-handle",
                draggable: ".item",
                ghostClass: "sortable-ghost",

            });

            new Sortable(el, {
            
                onStart: function (evt) {
                    initOrder()
                },

                // Element dragging ended
                onEnd: function ( /**Event*/ evt) {           
                
                    orderCategories()
                },
            });


        })( jQuery );

        function initOrder(){

            lessons = {};
            let order = 0;

           $( ".category-order" ).each(function( index ) {

                if(index == 0){
                    order = Number($(this).html());
                    //categories[$(this).data('category')] = order;
                    categories[index] = index;
                }else{
                    order += 1;
                    //categories[$(this).data('category')] = order;
                    categories[index] = index;
                }

                //console.log('index = ' + index + ' order = ' + order)


           });

       }

        function orderCategories(){
            let newOrder = {};
            
            $( ".category-order" ).each(function( index ) {
                newOrder[$(this).data('category')] = categories[index]
            });

            data = {'order':newOrder}
            //console.log(data);
            $( document ).ajaxStart(function() {
                var swal = window.swal({
                    title: "Change Order...",
                    text: "Please wait",
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            });

            $.ajax({
                type: 'POST',
                headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                Accept: 'application/json',
                url: "{{ route ('sort-categories') }}",
                data:data,
                success: function(data) {
                    if(data['success']){
                        swal.close();
                    }
                }
            });

        }

    </script>
@endpush
