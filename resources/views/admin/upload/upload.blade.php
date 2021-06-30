<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#select_ImageModal">
Upload Image
</button>

<?php
    if(isset($versions)){
        $versions = json_encode($versions);
    }
 ?>


<form method="post" id="upload_form" method="POST" action="{{ route('upload.versionImage', $event) }}" autocomplete="off" enctype="multipart/form-data">
    @csrf
    @method('put')

    @if($event)
        <div class="form-group">
            <img id="img-upload" src="
            <?php if($event['path'] != null) {
                echo url('uploads').$event['path'].$event['original_name'];
            }?>">
        </div>
        <input type="hidden" value="{{$event['path'].$event['original_name']}}" id="image_upload" name="image_upload">
        @isset($versions)
        <input type="hidden" value="{{$versions}}" name="versions">
        @endisset

    @else
        <div class="form-group">
            <img id="img-upload" src="">
        </div>
        <input type="hidden" value="" id="image_upload" name="image_upload">
    @endif
</form>


<!-- Modal -->
<div class="modal fade" id="select_ImageModal" tabindex="-1" role="dialog" aria-labelledby="select_ImageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @include('admin.media2.modal')
        </div>
        <div class="modal-footer">
            <button type="button" id="close" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            <button type="button" id="select-image" class="btn btn-primary">Select</button>
        </div>
        </div>
    </div>
</div>

@push('js')

<script>
    $( "#select-image" ).click(function() {
        path = ''
        $.each( $('.fm-breadcrumb li'), function(key, value) {
            if(key != 0){
                path = path+'/'+$(value).text()
            }
        })

        name = $('.table-info .fm-content-item').text()
        name = name.replace(/\s/g, '')
        ext = $('.table-info td:nth-child(3)').text()
        ext = ext.replace(/\s/g, '')
        path = path +'/'+name+'.'+ext
        $('#image_upload').val(path)
        $('#img-upload').attr('src', path);
        $(".close").click();
        $("#upload_form").submit();

    });

</script>


@endpush
