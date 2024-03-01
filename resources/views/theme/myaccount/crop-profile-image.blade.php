@extends('theme.layouts.master')
@section('metas')
<title>{{ 'Crop profile image' }}</title>
@endsection

@section('content')
<main  id="" role="main">

  <section class="section-hero section-hero-small section-hero-blue-bg">
      <div class="container">
          <div class="hero-message">
              <div class="account-infos">
                  <div class="account-thumb">
                      {!! \App\Helpers\UserHelper::getUserProfileImage($user, ['width' => 43, 'height' => 43, 'id' => 'user-img-up' ]) !!}
                  </div>
                  <div class="account-hero-info">
                      <h2>{{ $currentuser['firstname'] }} {{ $currentuser['lastname'] }}</h2>
                      <ul>
                          @if($currentuser['kc_id'] != '')
                          <li>{{ $currentuser['kc_id'] }}</li>
                          @endif
                          @if($currentuser['partner_id'])
                          <li>, DR-{{ $currentuser['partner_id'] }}</li>
                          @endif
                      </ul>
                  </div>
              </div>
          </div>
      </div>
      <!-- /.section-hero -->
  </section>

  <div class="content-wrapper">
    <div class="tabs-wrapper fixed-tab-controls">
      <div class="tab-controls subscription-account">
        <div class="container">
          <a href="#" class="mobile-tabs-menu">Account</a>
          <ul class="clearfix tab-controls-list">
            <li><a href="/myaccount/#my-account">My Account</a></li>
            <li>
                @if( (isset($events) && count($events) == 0))
                <a  href="#nocourses" >My courses</a>
                @else
                <a id="myCourses" href="/myaccount/#courses">My courses</a>
                @endif
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
      <div class="row" style="margin-bottom: 20px">
          <div class="col image-version-page">
            <div class="col-12">
              <h4 style="margin-top: 25px; margin-bottom: 15px">Crop Profile Image</h4>
              <div class="card-version-image">
                <img id="profile_image" class="img-fluid" src="<?php
                  if(isset($media)) {
                      echo url($media['path'].$media['original_name']);
                  }else{
                      echo '';
                  }
                ?>" alt="Card image cap"></div>
              </div>
              <div class="col crop-wrap-btn" style="margin-top: 10px">
                <button class="btn btn--md btn--secondary crop_profile" type="button">Crop</button>
              </div>
          </div>
      </div>
  </div>

  @endsection
</main>



@section('scripts')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" integrity="sha512-hvNR0F/e2J7zPPfLC9auFe3/SE0yG4aJCOd/qxew74NN7eyiSKjr7xJJMu1Jy2wf7FXITpWS1E/RY8yzuXN7VA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js" integrity="sha512-9KkIqdfN7ipEW6B6k+Aq20PV31bjODg4AA52W+tYtAE0jE0kMx49bjJ3FgvS56wzmyfMUHbQ4Km2b7l9+Y/+Eg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ asset('argon') }}/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="{{ asset('argon') }}/vendor/sweetalert2/dist/sweetalert2.min.css">

<script>

  let $img = @json($media);
  if($img['name'] != '' && $img['details'] != null){
    image_details = JSON.parse($img['details'].split(','))
    width = image_details.width
    height = image_details.height
    x = image_details.x
    y = image_details.y
  }else{
    width = 800
    height = 800
    x = 0;
    y = 0;
  }

  const cropper = new Cropper(document.getElementById(`profile_image`), {
    aspectRatio: Number((width/height), 4),
    viewMode: 0,
    dragMode: "crop",
    responsive: true,
    autoCropArea: 0,
    restore: false,
    movable: false,
    rotatable: false,
    scalable: false,
    zoomable: false,
    cropBoxMovable: true,
    cropBoxResizable: true,
    minContainerWidth: 300,
    minContainerHeight: 300,
    data:{
      x:parseInt(x),
      y:parseInt(y),
      width: parseInt(width),
      height: parseInt(height)
    }
  });


$(".crop_profile").click(function(){

  let media = @json($media);

  let path = $(this).parent().parent().find('img').attr('src')

  path = path.split('/')
  path = '/'+path[3]+'/' + path[4]+'/' + path[5]

  $.ajax({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'post',
      url: '/admin/media/crop_profile_image',
      data: {'media_id': media.id,'path':path, 'x':cropper.getData({rounded: true}).x, 'y':cropper.getData({rounded: true}).y, 'width':cropper.getData({rounded: true}).width, 'height':cropper.getData({rounded: true}).height},
      success: function (data) {
        if(data){

          Swal.fire(
                'Good job!',
                'Successfully Cropped!',
                'success'
            )
          window.location = '/myaccount#my-account';
        }
      }
  });

});


</script>

@endsection

