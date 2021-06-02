@inject('frontHelp', 'Library\FrontendHelperLib')
{{--<link rel="icon" href="{!! URL::to('/') !!}/theme/assets/img/icon/fav_icon.gif">--}}
<!--necessary stylesheets -->
<link type="text/css" rel="stylesheet" href="{!! URL::to('/') !!}/theme/assets/bootstrap/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="{!! URL::to('/') !!}/theme/assets/css/font-awesome.min.css">

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="theme/assets/js/jquery-2.1.4.min.js"></script>
<script type="text/javascript">
var routesObj = {
    baseUrl : '{{ URL::to("/") }}/'
};

$.ajaxSetup({headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
</script>
