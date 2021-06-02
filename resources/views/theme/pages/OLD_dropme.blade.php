<!-- <link type="text/css" rel="stylesheet" href="{{ cdn('theme/assets/addons/dropzone/dropzone.min.css') }}" /> -->
<div class="logo_dropzone cfieldCont custFieldUploadArea" id="logo_dropzone">
      
    <h4 class="post_side_legends">My picture</h4>
        
    <?php $custFieldValue = []; ?>

    <div id="cfMedia_0" data-dp-scope="logo_dropzone" class="fielddata" data-dp-user-id="{{$currentuser->id}}" data-dp-type="logoDropzone">
        <div id="cfMedia_logo_dropzone" class="dropzone_media">
            @if(isset($media))
		    <div class="featured_media" data-dp-media-id="{{ $media['id'] }}">
		        <div id="logoDropzone" class="custFieldMediaDrop dz-message">
                    <div class="featured_img_border">
		            <img class="dp_featured_img dz-message" src="portal-img/users/{{ $media['path'] }}/{{ $media['name'] }}{{ $media['ext'] }}" alt="cust image" />
                    </div>

		        </div>
                 <a data-dp-media-id="{{ $media['id'] }}" class="delete_media" title="Remove profile picture" href="javascript:void(0)"><i class="fa fa-times" aria-hidden="true"></i> remove</a>
            </div>
		    @else
            <div class="featured_media">
                <div id="logoDropzone" class="custFieldMediaDrop dz-message">
                    <div class="featured_img_border">
                        <i class="fa fa-user"></i><br /><!-- -circle-o -->
                        <p>Drag and Drop or<br /> click to set a profile picture</p>
                    </div>
                </div>      
            </div>
            @endif
        </div>
    </div>
</div>
