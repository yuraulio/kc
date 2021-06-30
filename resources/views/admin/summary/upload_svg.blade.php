<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#select_Svg_Modal">
Upload Image
</button>

<!-- Modal -->
<div class="modal fade" id="select_Svg_Modal" tabindex="-1" role="dialog" aria-labelledby="select_Svg_ModalLabel" aria-hidden="true">
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
            <button type="button" id="close_svg_modal" class="btn btn-secondary" >Close</button>
            <button type="button" id="select-svg" class="btn btn-primary">Select</button>
        </div>
        </div>
    </div>
</div>

@push('js')

<script>
$("#close_svg_modal").click(function() {
    $('#select_Svg_Modal').modal('hide')
})

    $( "#select-svg" ).click(function() {
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
        console.log(path);
        $('#image_svg_upload').val(path)
        $('#svg-upload').attr('src', path);
        $('#select_Svg_Modal').modal('hide')

    });



</script>


@endpush
