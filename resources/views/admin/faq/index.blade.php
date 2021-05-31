
<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0">{{ __('Faqs') }}</h3>
        <p class="text-sm mb-0">
                {{ __('This is an example of Faq management.') }}
            </p>
    </div>
    @can('create', App\Model\User::class)
        <div class="col-4 text-right">
        <button data-toggle="modal" data-target="#faqModal" class="btn btn-sm btn-primary">{{ __('Assign Faqs') }}</button>
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
                <th scope="col">{{ __('Title') }}</th>
                <th scope="col">{{ __('Answer') }}</th>
                <th scope="col">{{ __('Created at') }}</th>
                @can('manage-users', App\Model\User::class)
                    <th scope="col"></th>
                @endcan
            </tr>
        </thead>
        <tbody id="faq-body">
        @if($event->faqs)
            @foreach ($event->faqs as $faq)
                <tr id="faq-{{$faq->id}}">
                    <td>{{ $faq->title }}</td>
                    <td>{{ $faq->answer }}</td>

                    <td>{{ date_format($faq->created_at, 'Y-m-d' ) }}</td>
                        {{--<td class="text-right">

                            <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" href="{{ route('faqs.edit', $faq) }}">{{ __('Edit') }}</a>
                                    <form action="{{ route('faqs.destroy', $faq) }}" method="post">
                                        @csrf
                                        @method('delete')

                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>--}}
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
<!-- Modal -->
<div class="modal fade" id="faqModal" tabindex="-1" role="dialog" aria-labelledby="faqModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="faqModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <h6 class="heading-small text-muted mb-4">{{ __('Faq information') }}</h6>
            <div class="pl-lg-4">
            <form id="faq-form" >
                <div class="pl-lg-4">
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Select Faq</label>
                        <select multiple class="form-control" id="faqFormControlSelect">
                            <option>-</option>
                        </select>
                    </div>
                </div>
            </form>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            <button type="button" id="save-faq" class="btn btn-primary">Save changes</button>
         </div>
      </div>
   </div>
</div>
@push('js')
    <script>
        $(document).on('shown.bs.modal', '#faqModal',function(e) {

            let modelType = "{{addslashes ( get_class($model) )}}";
            let modelId = "{{ $model->id }}";

            $('#faqFormControlSelect option').each(function(key, value) {
                        $(value).remove()
                });

                $('#faqFormControlSelect').append(`<option>-</option>`)

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '/admin/faqs/fetchAllFaqs',
                data:{'model_type':modelType,'model_id':modelId},
                success: function (data) {
                    let faq = data.faqs


                    $.each( faq, function( key, value ) {
                        console.log( key + ": " + value.title );
                        row =`
                            <option value="${value.id}">${value.title}</option>
                        `
                        $('#faqFormControlSelect').append(row)
                    });
                }
            });
            })

            $(document).on('click', '#save-faq',function(e) {

                let modelType = "{{addslashes ( get_class($model) )}}";
                let modelId = "{{ $model->id }}";

                var selected_option = $('#faqFormControlSelect').val();


                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{route("faqs.store_event")}}',
                    data: {'faqs_id':$('#faqFormControlSelect').val(), 'model_type':modelType,'model_id':modelId},
                    success: function (data) {
                        let faq = data.data
                        $('#faq-body tr').remove();

                        $.each(faq, function(key,val){
                            console.log(key+':'+val.title)
                            let newFaq =
                            `<tr id="faq-`+val.id+`">` +
                            `<td id="title-` + val.id +`">` + val.title + `</td>` +
                            `<td id="answer-` + val.id +`">` + val.answer + `</td>` +
                            `<td>` + val.created_at + `</td>` +
                            `

                            </tr>`;

                            $("#faq-body").append(newFaq);
                        })

                        $(".close-modal").click();
                        $("#success-message p").html(data.success);
                        $("#success-message").show();


                    }
                });

                })
    </script>


@endpush

