<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0">{{ __('Videos') }}</h3>
        <p class="text-sm mb-0">
            {{ __('This is an example of Video management.') }}
        </p>
    </div>
    <div class="col-4 text-right">
        <button data-toggle="modal" data-target="#videoModal" class="btn btn-sm btn-primary">{{ __('Assign video') }}</button>
    </div>
</div>
<div class="table-responsive py-4">
    <table class="table align-items-center table-flush video-table" id="datatable-basic-video">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('Title') }}</th>                <th scope="col"></th>
            </tr>
        </thead>
        <?php //dd($model->videos); ?>
        <tbody class="video-body summaries-order">
 
        @if($model->sectionVideos)
            @foreach ($model->sectionVideos as $video)
                <tr>
                    <td id="title-{{$video->id}}" data-id="{{$video->id}}" class="video-list"><a class="edit-btn" href="#"> {{ $video->title }} </a></td>
                    
                    <td class="text-right">
                       

                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                
                                <form action="{{ route('events.video.destroy', [$event,$video]) }}" method="post">
                                    @csrf
                                    @method('delete')

                                    <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to remove the explainer video?") }}') ? this.parentElement.submit() : ''">
                                        {{ __('Delete') }}
                                    </button>
                                </form>

                                

                            </div>
                        </div>

                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>


<!-- Modal -->

<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default">Assign Video</h6>
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
                            <label for="exampleFormControlSelect1">Select Video</label>
                            <select class="form-control" id="videoFormControlSelect">
                                <option>-</option>

                            </select>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="button" data-event-id="{{$model->id}}" id="video_save_btn" class="btn btn-primary">Save changes</button>
            </div>

        </div>
    </div>
</div>

@push('js')
<script>
    $(document).on('click', '#video_save_btn',function(e) {

        let modelType = "{{addslashes ( get_class($model) )}}";
        let modelId = "{{ $model->id }}";

        var selected_option = $('#videoFormControlSelect option:selected');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '{{route("video.store_event")}}',
            data: {'video_id':$(selected_option).val(), 'model_type':modelType,'model_id':modelId},
            success: function (data) {
                let video = data.data

                $('.video-body tr').remove();


                let newVideo =
                `<tr>` +
                `<td>` + video['title'] + `</td>` +
                `<td>` + video['url'] + `</td>` +
                `
                </tr>`;

                $(".video-body").append(newVideo);
                $(".close-modal").click();
                $("#success-message p").html(data.success);
                $("#success-message").show();


            }
        });

    })
</script>

<script>
   $(document).on('shown.bs.modal', '#videoModal',function(e) {

    $('#videoFormControlSelect option').each(function(key, value) {
        $(value).remove()
    });

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'get',
        url: '/admin/video/fetchAllVideos',
        success: function (data) {
            let videos = data.data


            $.each( videos, function( key, value ) {
                row =`
                    <option value="${value.id}">${value.title}</option>
                `
                $('#videoFormControlSelect').append(row)
            });
        }
    });

   });

</script>


@endpush


