

<div class="instructor-area instructor-profile mt-5 mb-5 ">
  <div class="avatar-wrapper">
    <div class="acc-img">
      {!! \App\Helpers\UserHelper::getUserProfileImage($user, ['width' => 230, 'height' => 230, 'id' => 'user-img', 'class' => 'profile_images_panel' ]) !!}
    </div>
    <div class="social-links">
      @if(isset($user->social_links['facebook']) && $user->social_links['facebook'] != '')
        <a target="_blank" href="{{ $user->social_links['facebook'] }}">
          <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Facebook.svg')}}" width="23" alt="Visit"></a>
      @endif
      @if(isset($user->social_links['instagram']) && $user->social_links['instagram'] != '')
        <a target="_blank" href="{{ $user->social_links['instagram'] }}">
          <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Instagram.svg')}}" width="23" alt="Visit"></a>
      @endif
      @if(isset($user->social_links['linkedin']) && $user->social_links['linkedin'] != '')
        <a target="_blank" href="{{ $user->social_links['linkedin'] }}">
          <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Linkedin.svg')}}" width="23" alt="Visit"></a>
      @endif
      @if(isset($user->social_links['twitter']) && $user->social_links['twitter'] != '')
        <a target="_blank" href="{{ $user->social_links['twitter'] }}">
          <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Twitter.svg')}}" width="23" alt="Visit"></a>
      @endif
      @if(isset($user->social_links['youtube']) && $user->social_links['youtube'] != '')
        <a target="_blank" href="{{ $user->social_links['youtube'] }}">
          <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Youtube.svg')}}" width="23" alt="Visit"></a>
      @endif
    </div>
  </div>
</div>

