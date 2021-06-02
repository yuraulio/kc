@inject('frontHelp', 'Library\FrontendHelperLib')
@if (Request::url() != URL::to('/'))
@endif

<!-- Navigation -->
<nav class="navbar" role="navigation">
</nav>
@include('flash::message')
