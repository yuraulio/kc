<?php //dd(); ?>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".select_Svg_Modal-{{$template1}}">
Upload Image
</button>

<?php //dd($media); ?>

<!-- Modal -->
<div class="modal fade select_Svg_Modal-{{$template1}}" id=""  tabindex="-1" role="dialog" aria-labelledby="select_Svg_ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
            <div style="height: 600px;">
                <div id="{{$template1}}-t"></div>
            </div>
            </div>
        </div>


    </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="close_svg_modal btn btn-secondary" >Close</button>
            <button type="button" id="select-svg-{{$template1}}" class="btn btn-primary">Select</button>
        </div>
        </div>
    </div>
</div>

@push('js')

{{--<script>
from = @json($template1);

$(".close_svg_modal").click(function() {
    $('#select_Svg_Modal-'+@json($template1)).modal('hide')
})

    $( "#select-svg-"+@json($template1) ).click(function() {
        path = ''



        $.each( $('.select_Svg_Modal-'+@json($template1) + ' .fm-breadcrumb li'), function(key, value) {
            if(key != 0){
                path = path+'/'+$(value).text()
            }
        })

        name = $('.select_Svg_Modal-'+@json($template1)+ ' .table-info .fm-content-item').text()
        name = name.replace(/\s/g, '')
        ext = $('.select_Svg_Modal-'+@json($template1)+ ' .table-info td:nth-child(3)').text()
        ext = ext.replace(/\s/g, '')
        path = '/uploads'+path +'/'+name+'.'+ext

        //alert(path)

        $('#image_svg_upload-'+@json($template1)).val(path)
        $('#img-upload-'+@json($template1)).attr('src', path)

        $('.select_Svg_Modal-'+@json($template1)).modal('hide')


    });





</script>--}}

<script>
from = @json($template1);

$(".close_svg_modal").click(function() {
    $('#select_Svg_Modal-'+@json($template1)).modal('hide')
})

    $( "#select-svg-"+@json($template1) ).click(function() {
        
        path = '/uploads/';
        $("#select_ImageModal .breadcrumb.active-manager .breadcrumb-item.text-truncate span").each(function() {
            path += $(this).text() + '/' ;
        });

        
        name = $('.select_Svg_Modal-'+@json($template1)+ ' .table-info .fm-content-item').text()
        name = name.replace(/\s/g, '')
        ext = $('.select_Svg_Modal-'+@json($template1)+ ' .table-info td:nth-child(3)').text()
        ext = ext.replace(/\s/g, '')
        
        if(name == ''){
            name = $('.select_Svg_Modal-'+@json($template1)+ ' .fm-grid-item.active').attr('title');
            path += name;
        }else{
            path += name+'.'+ext;
        }
        
        

        if (path[path.length-1] === "."){
            path = path.slice(0,-1);

        }
        
        //alert(path)

        $('#image_svg_upload-'+@json($template1)).val(path)
        $('#img-upload-'+@json($template1)).attr('src', path)

        $('.select_Svg_Modal-'+@json($template1)).modal('hide')


    });





</script>


@endpush
