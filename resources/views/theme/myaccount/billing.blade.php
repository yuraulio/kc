@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
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
                                    <img src="{{ cdn('theme/assets/img/myhumber.png')}}">
                                </div>{{ $currentuser->first_name }} {{ $currentuser->last_name }}</h1>
                                 <div class="user-ids animatable fadeInUp">@if($currentuser->kc_id != '')
                                KNOWCRUNCH I.D. : {{ $currentuser->kc_id }}<br />
                                @endif
                                @if($currentuser->partner_id)
                                DEREE I.D.: {{ $currentuser->partner_id }}<br />
                                @endif</div>


                        @else
                        <h1 class="animatable fadeInDown">My account</h1>
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


                     @include('theme.myaccount.menu-mobile')
                   <!--  <p class="visible-xs" style="margin-top:20px;">
                        <button type="button" class="btn btn-green" data-toggle="offcanvas" style="margin-left:0px;"><i class="fa fa-bars"></i> Menu</button>
                    </p> -->

                @if($currentuser)


                    <div class="row">

                        <div id="student-billing-view-mode" class="col-lg-6 col-md-7 col-sm-12 col-xs-12">
                            <h3 id="billinfle">My billing info</h3>
                                <?php
                                 /*'billemail' => 'Email',
                                            'billmobile' => 'Mobile',*/
                                    $hone = [
                                            'billname' => 'First name',
                                            'billsurname' => 'Last name',
                                            'billaddress' => 'Address',
                                            'billaddressnum' => 'Street number',
                                            'billpostcode' => 'Postcode',
                                            'billcity' => 'City',
                                            'billafm' => 'Vat number'
                                        ];
                                    $inv_data = [];
                                    $htwo = [
                                            'companyname' => 'Company name',
                                            'companyprofession' => 'Profession',
                                            'companyafm' => 'Vat number',
                                            'companydoy' => 'Tax area',
                                            'companyaddress' => 'Address',
                                            'companyaddressnum' => 'Street number',
                                            'companypostcode' => 'Postcode',
                                            'companycity' => 'City'
                                        ];
                                    $rec_data = [];


                                ?>

                            <ul class="nav nav-tabs nav-justified nav-auth" role="tablist">
                                <li class="active"><a href="#invoicebill" title="Login" role="tab" data-toggle="tab">Invoice details</a></li>
                                <li class=""><a href="#receiptbill" title="Receipt" role="tab" data-toggle="tab">Receipt details</a></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="invoicebill">
                                    <a class="editbilling edit-invoice-mode" href="javascript:void(0)">@if($invoice_info != '')<i class="fa fa-pencil" aria-hidden="true"></i> edit @else <i class="fa fa-plus" aria-hidden="true"></i> add @endif </a>
                                    @if($invoice_info != '')
                                    <?php $invoice_info = json_decode($invoice_info);  ?>
                                    <ul class="dont-boost" id="static-invoice">
                                        @foreach($invoice_info as $k => $v)
                                        @if($k != 'billing' && isset($htwo[$k]))
                                        <li>
                                         <label>{{$htwo[$k]}}</label><span class="pull-right">{{{ $v }}}</span>
                                         </li>
                                         <?php $inv_data[$k] = $v; ?>
                                         @endif
                                        @endforeach
                                    </ul>
                                    @endif

                                    <div id="invoice_add_edit_mode" class="hidden">
                                        <a class="editbilling cancel-edit-invoice-mode" href="javascript:void(0)"><i class="fa fa-times" aria-hidden="true"></i> cancel</a>

                                        @foreach($htwo as $k => $v)
                                             <div class="col-xs-12">
                                                <div class="form-group">
                                                    <strong>{{$v}}: </strong>
                                                    <input type="text" class="form-control" id="{{$k}}" name="{{$k}}" placeholder="{{$v}}" value="@if(isset($inv_data[$k])){{$inv_data[$k]}}@endif" />
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="editsavebtn col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <a id="save-invoice-data" class="btn btn-green-invert" href="javascript:void(0)">SAVE</a>
                                        </div>

                                    </div>

                                </div>
                                <div class="tab-pane" id="receiptbill">
                                    <a class="editbilling edit-receipt-mode" href="javascript:void(0)">@if($receipt_info != '')<i class="fa fa-pencil" aria-hidden="true"></i> edit @else <i class="fa fa-plus" aria-hidden="true"></i> add @endif </a>
                                    @if($receipt_info != '')
                                    <?php $receipt_info = json_decode($receipt_info);  ?>
                                    <ul class="dont-boost" id="static-receipt">
                                        @foreach($receipt_info as $k => $v)
                                        @if($k != 'billing' && isset($hone[$k]))
                                        <li>
                                         <label>{{$hone[$k]}}</label><span class="pull-right">{{{ $v }}}</span>
                                         </li>
                                         <?php $rec_data[$k] = $v; ?>
                                         @endif
                                        @endforeach
                                    </ul>
                                    @endif

                                    <div id="receipt_add_edit_mode" class="hidden">
                                        <a class="editbilling cancel-edit-receipt-mode" href="javascript:void(0)"><i class="fa fa-times" aria-hidden="true"></i> cancel</a>

                                        @foreach($hone as $k => $v)
                                             <div class="col-xs-12">
                                                <div class="form-group">
                                                    <strong>{{$v}}: </strong>
                                                    <input type="text" class="form-control" id="{{$k}}" name="{{$k}}" placeholder="{{$v}}" value="@if(isset($rec_data[$k])){{$rec_data[$k]}}@endif" />
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="editsavebtn col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <a id="save-receipt-data" class="btn btn-green-invert" href="javascript:void(0)">SAVE</a>
                                        </div>
                                    </div>
                                </div> <!-- end tab receipt -->
                            </div> <!-- end tab-content -->
                        </div>
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

    $('.edit-invoice-mode').on('click', function() {
        $('#static-invoice').addClass('hidden');
        $('#invoice_add_edit_mode').removeClass('hidden');
        $('.edit-invoice-mode').addClass('hidden');
    });
    $('.cancel-edit-invoice-mode').on('click', function() {
        $('#invoice_add_edit_mode').addClass('hidden');
        $('#static-invoice').removeClass('hidden');
        $('.edit-invoice-mode').removeClass('hidden');
    });

    $('#save-invoice-data').on('click', function() {
        var invoicedata = $("#invoice_add_edit_mode :input").serialize();
        //console.log(invoicedata);
        $.ajax({ url: "myaccount/updinvbill", type: "post",
            data: invoicedata,
            success: function(data) {
                if (Number(data.status) === 1) {
                    window.location = 'myaccount/billing';
                }
                else {
                    alert('Not saved. Please try again');
                }
            }
        });
    });


    $('.edit-receipt-mode').on('click', function() {
        $('#static-receipt').addClass('hidden');
        $('#receipt_add_edit_mode').removeClass('hidden');
        $('.edit-receipt-mode').addClass('hidden');
    });
    $('.cancel-edit-receipt-mode').on('click', function() {
        $('#receipt_add_edit_mode').addClass('hidden');
        $('#static-receipt').removeClass('hidden');
        $('.edit-receipt-mode').removeClass('hidden');
    });



    $('#save-receipt-data').on('click', function() {
        var receiptdata = $("#receipt_add_edit_mode :input").serialize();
        //console.log(receiptdata);
        $.ajax({ url: "myaccount/updrecbill", type: "post",
            data: receiptdata,
            success: function(data) {
                if (Number(data.status) === 1) {

                    window.location = 'myaccount/billing';
                }
                else {
                    alert('Not saved. Please try again');
                }
            }
        });
    });






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

@stop

