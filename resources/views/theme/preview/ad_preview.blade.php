@extends('theme.layouts.master_preview_ads')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<div id="main-content-body">

    <div id="single_post">
        <div class="container">
            @foreach ($ads as $slot => $row)
            <div class="col-lg-12">
                <h3>
                    <?php echo $row['slot'].' '.implode('x',$row['dimensions']); ?><br />
                    ID: <?php echo $row["id"]; ?>
                </h3>
                <!-- <?php echo $row['slot'].' '.implode('x',$row['dimensions']); ?> -->
                <div id='<?php echo $row['id']; ?>' style="width: <?php echo $row['dimensions'][0]; ?>px; height: <?php echo $row['dimensions'][1]; ?>px;">
                <script type='text/javascript'>
                    googletag.cmd.push(function() { googletag.display('<?php echo $row["id"]; ?>'); });
                </script>
                </div>
                <hr />
            </div>
            @endforeach
        </div>
    </div>
</div>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-564af9b7b9281517" async="async"></script>
@include('theme.preview.single_post_load_more')
@endsection
