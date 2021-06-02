@extends('theme.preview.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')

<link rel="stylesheet" href="/theme/assets/addons/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="/theme/assets/addons/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>

<section class="content">
@if (isset($entry))
    {!! $entry->body !!}
@endif
</section>

@endsection
