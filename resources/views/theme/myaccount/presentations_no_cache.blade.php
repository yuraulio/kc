@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')

<style type="text/css">

.tree {
    min-height:20px;
    padding:0px;
    margin-bottom:20px;
    background-color:transparent;
    border:1px solid transparent;
    -webkit-border-radius:0px;
    -moz-border-radius:0px;
    border-radius:0px;
    -webkit-box-shadow: none;
    -moz-box-shadow:none;
    box-shadow:none;
}
.tree ul.rfolder {
    padding-left: 0px;
}
.tree li {
    list-style-type:none;
    margin:0;
    padding:10px 0px 0 5px;
    position:relative
}
.tree li::before, .tree li::after {
    content:'';
    left:-20px;
    position:absolute;
    right:auto
}
.tree li::before {
    border-left:1px solid #3f6fb6;
    bottom:50px;
    height:100%;
    top:0;
    width:1px
}
.tree li::after {
    border-top:1px solid #3f6fb6;
    height:20px;
    top:25px;
    width:25px
}
.tree li span {
    -moz-border-radius:0px;
    -webkit-border-radius:0px;
    border:1px solid #3f6fb6;
    border-radius:0px;
    display:inline-block;
    padding:3px 8px;
    text-decoration:none;
    width: 100%; /*calc(100% - 50px);*/
}
.tree li.parent_li>span {
    cursor:pointer
}
.tree li span.lsmod {
    border: none;
    font-size: 0.8em;
    width: calc(100% - 50px);
}
.tree>ul>li::before, .tree>ul>li::after {
    border:0
}
.tree li:last-child::before {
    height:30px
}
.tree li.parent_li>span:hover, .tree li.parent_li>span:hover+ul li span {
    background:#eee;
    /*border:1px solid #94a0b4;*/
    color:#000
}

.tree a.getdropboxlink { display: inline-block; position: absolute; right: 10px; top: 20px; }

</style>

<?php $currentuser = Sentinel::getUser(); ?>
<link type="text/css" rel="stylesheet" href="{{ cdn('theme/assets/css/fileicon.css') }}" />
<link type="text/css" rel="stylesheet" href="{{ cdn('theme/assets/addons/dropzone/dropzone.min.css') }}" />
<div id="main-content-body">

    <div id="event-banner">
        <div class="page-helper"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="post-caption static-pages">
                        @if($currentuser)
                        <h1 class="animatable fadeInDown"><div class="players-img">
                                    <img src="{{ cdn('theme/assets/img/myhumber.png')}}"><!-- icon_student_grey -->
                                </div>{{ $currentuser->first_name }} {{ $currentuser->last_name }}</h1>
                                 <div class="user-ids animatable fadeInUp">@if($currentuser->kc_id != '')
                                KNOWCRUNCH I.D. : {{ $currentuser->kc_id }}<br />
                                @endif
                                @if($currentuser->partner_id)
                                DEREE I.D.: {{ $currentuser->partner_id }}<br />
                                @endif</div>

                        @else
                        <h1 class="animatable fadeInDown">Account Page</h1>
                        <h2 class="fadeInUp animated"></h2>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <?php $hasfeat = false; ?>
    </div>

    <div id="about-page" class="@if($hasfeat) content-fix @endif">
        <div class="container">
            <div class="row row-offcanvas row-offcanvas-left" >
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 side sidebar-offcanvas" id="leftCol">
                    <div class="about-sidebar clearfix">

                        <span class="hidden" id="stid">{{ $currentuser->id }}</span>

                        @include('theme.myaccount.dropme')
                        @include('theme.myaccount.menu')

                    </div>
                </div>
                <div class="col-lg-8 col-lg-offset-1 col-md-8 col-md-offset-1 col-sm-8 col-sm-offset-1 col-xs-12 col-xs-offset-0">

                    <p class="visible-xs" style="margin-top:20px;">
                        <button type="button" class="btn btn-green" data-toggle="offcanvas" style="margin-left:0px;"><i class="fa fa-bars"></i> Menu</button>
                    </p>

                @if($currentuser)

                    <div class="col-lg-12">
                        <h3 id="accdownloads">My files</h3>




                        @if(isset($events) && count($events) > 0)
                        <div class="panel-group" id="topicsaccordion" role="tablist" aria-multiselectable="true">
                            @foreach($events as $ekey => $event)




                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="theading{{ $ekey }}">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#topicsaccordion" href="#tcollapse{{ $ekey }}" aria-expanded="false" aria-controls="tcollapse{{ $ekey }}" style="font-size: 1.0em;">
                                                <!-- TOPIC TITLE HERE -->
                                                 {{ $eventnames[$event->id] }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="tcollapse{{ $ekey }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="theading{{ $ekey }}">
                                        <div class="panel-body">



                                            @if (isset($folders[$event->id][0]) && !empty($folders[$event->id][0]))
                                                <div class="tree well">
                                                    <ul class="rfolder">
                                                    @foreach($folders[$event->id][0] as $key => $folder)
                                                    <?php
                                                        $rf = strtolower($folder['dirname']);

                                                    ?>
                                                        <li>
                                                            <span><i class="icon-folder-open"></i> {{ $folder['foldername'] }}</span> {{-- <a href="">DOWN</a> --}}

                                                            @if (isset($files[$event->id][1]) && !empty($files[$event->id][1]))
                                                                <ul>
                                                                @foreach($files[$event->id][1] as $fkey => $frow)
                                                                    @if($frow['fid'] == $folder['id'])

                                                                        <li>
                                                                            <span><i class="icon-minus-sign"></i> {{ $frow['filename'] }}<br /><span class="lsmod">Last Modified: {{$frow['last_mod']}}</span>
                                                                                <a data-toggle="tooltip" data-placement="left" title="Click to download" class="getdropboxlink" data-dirname="{{ $frow['dirname'] }}" data-filename="{{ $frow['filename'] }}" href="javascript:void(0)"><div class="file-icon" data-type="{{ $frow['ext'] }}"></div></a>
                                                                            </span>

                                                                        </li>

                                                                    @endif
                                                                @endforeach


                                                                @if (isset($folders[$event->id][1]) && !empty($folders[$event->id][1]))

                                                                    @foreach($folders[$event->id][1] as $nkey => $nfolder)

                                                                        @if($nfolder['parent'] == $key)

                                                                        <li>
                                                                            <span><i class="icon-folder-open"></i> {{ $nfolder['foldername'] }}  </span>

                                                                            @if (isset($files[$event->id][2]) && !empty($files[$event->id][2]))

                                                                                <ul>
                                                                                @foreach($files[$event->id][2] as $fkey => $frow)


                                                                                    @if (strpos($frow['dirname'], $rf) !== false)

                                                                                        <li>
                                                                                            <span><i class="icon-minus-sign"></i> {{ $frow['filename'] }}<br /><span class="lsmod">Last Modified: {{$frow['last_mod']}}</span>
                                                                                                <a data-toggle="tooltip" data-placement="left" title="Click to download" class="getdropboxlink" data-dirname="{{ $frow['dirname'] }}" data-filename="{{ $frow['filename'] }}" href="javascript:void(0)"><div class="file-icon" data-type="{{ $frow['ext'] }}"></div></a>
                                                                                            </span>

                                                                                        </li>
                                                                                    @endif

                                                                                @endforeach
                                                                                </ul>
                                                                            @endif
                                                                        </li>
                                                                        @endif
                                                                    @endforeach
                                                                @endif


                                                                </ul>
                                                            @endif



                                                        </li>
                                                    @endforeach

                                                    @if (isset($files[$event->id][0]) && !empty($files[$event->id][0]))
                                                        @foreach($files[$event->id][0] as $key => $row)
                                                            <li>
                                                                <span><i class="icon-minus-sign"></i> {{ $row['filename'] }}<br /><span class="lsmod">Last Modified: {{$row['last_mod']}}</span>
                                                                    <a data-toggle="tooltip" data-placement="left" title="Click to download" class="getdropboxlink" data-dirname="{{ $row['dirname'] }}" data-filename="{{ $row['filename'] }}" href="javascript:void(0)"><div class="file-icon" data-type="{{ $row['ext'] }}"></div></a>
                                                                </span>
                                                            </li>

                                                        @endforeach
                                                    @endif

                                                    </ul>
                                                </div>
                                            @endif











                                        </div>
                                    </div>
                                </div>


                            @endforeach

                            </div><!-- topicsaccordion END -->

                        @else
                            <h4>No events</h4>
                        @endif
                    </div>

                    @endif


                </div>
            </div><!-- ROW END -->
        </div>
    </div>
</div><!-- close profile-account -->

@endsection
@section('scripts')
<script src="{{ cdn('theme/assets/js/jquery.scrollto.js') }}"></script>
<script src="{{ cdn('theme/assets/addons/dropzone/dropzone.js') }}"></script>
<script type="text/javascript">
$(window).load(function(){

var width = $(window).width();
if (width > 768) {
    var $sidebar   = $("#sidebar"),
        $window    = $(window),
        offset     = $sidebar.offset(),
        topPadding = 170;

    var ban = $('#event-banner').height();


    $window.scroll(function() {

        var maxcont = $('#about-page').height() - 280;

        var modfix = offset.top - 60;

        //console.log(offset.top);

        if ($window.scrollTop() > modfix && $window.scrollTop() <  maxcont + ban) {
            $sidebar.stop().animate({
                marginTop: $window.scrollTop() - ban + 100

            });
        } else {
            $sidebar.stop().animate({
                marginTop: 0
            });
        }
    });
}

});

</script>

<script type="text/javascript">
$(document).ready(function() {

    $("body").on("click", ".delete_media", function (event) {
        if(confirm('Do you really want to remove your profile picture?')) {
            //alert('You are very brave!');
            deleteMediaPromt($(this).attr("data-dp-media-id"));
        }
        else {
            alert('Tip! Drag and Drop or click to change your profile picture');

        }
    });

    function deleteMediaPromt(media_id) {
        //alert(media_id+' deleted');
        $.ajax({ url: "student/removeavatar", type: "post",
            data: media_id,
            success: function(data) {
                if (Number(data.status) === 1) {

                    var html = '';
                    html += '<div class="featured_img_border">';
                    html += '<i class="fa fa-user"></i><br />';
                    html += '<p>Drag and Drop or<br /> click to set a profile picture</p>';
                    html += '</div>';


                    $('#logoDropzone').html(html);
                    $('.delete_media').hide();
                    //alert('Profile picture removed.');
                }
            }
        });
    }

    $.ajaxSetup({headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    $(document).ajaxError(function(event, jqxhr, settings, exception) {
        if (exception == 'Unauthorized') {
            window.location.href = baseUrl+'/';
        }
    });

    var settingsObj = {
        maxUploadSize: "5", // in MB
        dropzoneAcceptedFiles: "image/*",
        dropzoneAcceptImage: "image/*"
    };



    var logo_dropzone = {};
    var logo_dropzoneObj = {dragenter: 0, dragleave: 0};

    function logo_dropzoneToDropzone() {
        logo_dropzone = new Dropzone(document.getElementById("logo_dropzone"), {
        url: 'admin/media_uploader/upload',
        method: "post",
        maxFilesize: settingsObj.maxUploadSize,
        paramName: "dp_fileupload",
        uploadMultiple: false,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        previewsContainer: null,
        previewTemplate: '<div id="preview-template" style="display: none;"></div>',
        createImageThumbnails: false,
        maxFiles: null,
        init: function () { },
        acceptedFiles: settingsObj.dropzoneAcceptedFiles,
        autoProcessQueue: true,
        uploadprogress: function(file, progress, bytesSent) {
            //console.log(progress);
        },
        sending: function (file, xhr, formData) {
           /* NProgress.configure({ parent: '#logo_dropzone' });
            NProgress.start();*/
            formData.append("filesize", file.size);
            formData.append("student_avatar", '1');
            formData.append("stid", $('#stid').html());
            formData.append("scope", "logo_dropzone");
        },
        complete: function (file) {
        },
        success: function (file, response, load) {
            $('#logo_media').val(response.urls.large_thumb);
            $('#logo_media_id').val(response.media_id);
            renderLogoDropzone(response, true);
        }
    });

    logo_dropzone.on("dragenter", function(event) {
        logo_dropzoneObj.dragenter++;
        logo_dropzoneObj.dragleave = 0;
        $("#logoDropzone").addClass('acceptDrop');
    });

    logo_dropzone.on("dragleave", function(file) {
        logo_dropzoneObj.dragleave++;
        logo_dropzoneObj.dragenter = 0;
        if (logo_dropzoneObj.dragleave > 1) {
            $("#logoDropzone").removeClass('acceptDrop');
        }
    });
}

 logo_dropzoneToDropzone();



function renderLogoDropzone(mediaObj, overwrite) {
    //console.log(mediaObj);
    var html = '<div class="logo_dropzone_click">';
    if (mediaObj.media_id > 0) {
        html += '<div class="featured_media" data-dp-media-id="'+mediaObj.media_id+'">';
        html += '<div id="logoDropzone" class="custFieldMediaDrop dz-message">';
        /*html += '<img class="dp_featured_img dz-message" src="'+mediaObj.urls.large_thumb+'" alt="cust image" />';*/
        html += '<div class="featured_img_border">';
        html += '<img class="dp_featured_img dz-message" src="portal-img/users/'+mediaObj.media.path+'/'+mediaObj.media.name+mediaObj.media.ext+'" alt="cust image" />';
        if (Number(mediaObj.media.type) === 1) {
            html += '<p class="mediaDesc" title="">'+mediaObj.media.name+'</p>';
        }
        html += '</div>';
        html += '</div>';
        html += '<a data-dp-media-id="'+mediaObj.media_id+'" class="delete_media" title="Remove profile picture" href="javascript:void(0)"><i class="fa fa-times" aria-hidden="true"></i> remove</a>';
        html += '</div>';
    } else {
        html += '<div class="featured_media">';
        html += '<div id="logoDropzone" class="custFieldMediaDrop dz-message">';
        html += '<div class="featured_img_border">';
        html += '<i class="fa fa-user"></i><br />';
        html += '<p>Drag and Drop or<br /> click to set a profile picture</p>';
        html += '</div>';
        html += '</div>';

        html += '</div>';
    }
    html += '</div>';

    if ((typeof overwrite !== "undefined") && (overwrite === true)) {
        //console.log(html);
        $('[data-dp-scope="logo_dropzone"] #cfMedia_logo_dropzone').html(html);
        $('[data-dp-scope="logo_dropzone"] #cfMedia_logo_dropzone').find('.logo_dropzone_click').addClass('dz-clickable');
        var dropzoneFn = 'logo_dropzoneToDropzone';
        //window[dropzoneFn]();
        return true;
    } else {
        return html;
    }
}




















    $('[data-toggle=offcanvas]').click(function() {
        $('.row-offcanvas').toggleClass('active');
    });

    $('.getdropboxlink').click(function() {

        var dir = $(this).attr('data-dirname');
        var fname = $(this).attr('data-filename');

        $.ajax({ url: '/getdropbox', type: "post",
            data: {dir: dir, fname:fname},
            success: function(data) {
              window.location.href = data;
            }
        });

    });

    /*$(".dont-boost li a").click(function(evn){
        evn.preventDefault();
        if(!$(this).parent().hasClass('disabled')) {
            $('html,body').scrollTo(this.hash, this.hash);
            $('.dont-boost li').removeClass('active');
            $(this).parent().addClass('active');
        }
    });*/

    $('.edit-mode').on('click', function() {
        $('#student-view-mode').addClass('hidden');
       // $('#student-billing-view-mode').addClass('hidden');
        $('#student-edit-mode').removeClass('hidden');
    });
    $('.cancel-edit-mode').on('click', function() {
        $('#student-edit-mode').addClass('hidden');
        $('#student-view-mode').removeClass('hidden');
        //$('#student-billing-view-mode').removeClass('hidden');
    });

});

</script>

<script type="text/javascript">
$(function () {
    $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this folder');
    $('.tree li:has(ul)').find(' > ul > li').hide('fast');
    $('.tree li.parent_li > span').on('click', function (e) {
        var children = $(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(":visible")) {
            children.hide('fast');
            $(this).attr('title', 'Expand this folder').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
        } else {
            children.show('fast');
            $(this).attr('title', 'Collapse this folder').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
        }
        e.stopPropagation();
    });
});
</script>

@stop

