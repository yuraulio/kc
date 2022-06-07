<!-- Top navbar -->
<nav class="navbar navbar-top navbar-expand border-bottom {{ $navClass ?? 'navbar-dark bg-primary' }}">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Search form -->
            <form class="navbar-search {{ $searchClass ?? 'navbar-search-light' }} form-inline mr-sm-3 search" id="navbar-search-main">
                <div class="form-group mb-0 search">
                    <div class="input-group input-group-alternative input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input id="search-user" class="form-control" placeholder="{{ __('Search') }}" type="text">
                    </div>
                    <ul class="search-list">

                    </ul>
                </div>
                <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </form>
            <!-- Navbar links -->
            <ul class="navbar-nav align-items-center ml-md-auto">
                <li class="nav-item d-xl-none">
                <!-- Sidenav toggler -->
                    <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </li>

                <li class="nav-item d-sm-none">
                    <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                        <i class="ni ni-zoom-split-in"></i>
                    </a>
                </li>


            </ul>
            <ul class="navbar-nav align-items-center ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media align-items-center">
                            <span class="avatar avatar-sm rounded-circle">
                            @if(isset(auth()->user()->image) && auth()->user()->image['name'] != '')
                                <?php
                                    $name1 = explode('.',auth()->user()->image->original_name);
                                    $path = auth()->user()->image->path.$name1[0].auth()->user()->image->ext;
                                    $path_crop = auth()->user()->image->path.$name1[0].'-crop'.auth()->user()->image->ext;
                                    $path_crop = substr_replace($path_crop, "", 0, 1);

                                    if(file_exists($path_crop)){
                                        //dd('asd');
                                        $path = asset($path_crop);
                                    }else{
                                        $path = asset($path);
                                    }
                                ?>
                                    <img src="{{ $path }}" onerror="this.src='{{cdn('/theme/assets/images/icons/user-profile-placeholder-image.png')}}'" class="rounded-circle">
                                @else
                                <img src="" alt="{{auth()->user()->firstname}}" onerror="this.src='{{cdn('/theme/assets/images/icons/user-profile-placeholder-image.png')}}'" class="rounded-circle">
                                @endif

                            </span>
                            <div class="media-body ml-2 d-none d-lg-block">
                                <span class="mb-0 text-sm  font-weight-bold">{{ auth()->user()->firstname }}</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                        </div>

                        <a href="{{ route('user.edit', auth()->user()->id) }}" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>{{ __('My account') }}</span>
                        </a>
                        <!-- <a href="javascript:void(0)" id="update-btn" class="dropdown-item">
                            <i class="fab fa-dropbox"></i>
                            <span>{{ __('Update Dropbox') }}</span>
                        </a> -->
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                            <i class="ni ni-user-run"></i>
                            <span>{{ __('Logout') }}</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

@push('js')
<script src="{{ asset('argon') }}/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
<script>
    $(document).on("click", "#update-btn", function(){

        window.swal({
            title: "Dropbox Updating...",
            text: "Please wait",
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({

        type: 'get',
        url: '/admin/dropbox/update',

        success: function (data) {
            if(data){
                window.swal({
                    title: "Finished!",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        },
        error: function() {
            //console.log(data);
        }


        })
    })

    /*$(document).on("click", "#update-feed-btn", function(){

        window.swal({
            title: "Feed Updating...",
            text: "Please wait",
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
        
        type: 'get',
        url: 'fb-google-csv',
        
        success: function (data) {
            
            window.swal({
                title: "Finished!",
                showConfirmButton: false,
                timer: 2000
            });
            
        },
        error: function() {
            //console.log(data);
        }


        })
    })*/

        $(document).on('keyup',"#search-user",function(){

            if(!$(this).val()){
                $(".search-list").empty();
                return;
            }

            $.ajax({

   			    type: 'get',
   			    url: '/admin/search-user/' + $(this).val(),

   			    success: function (data) {
                    let searchList = '';

                    $.each(data.searchData, function( index, value ) {

                        searchList += `<div>
                                            <a href="`+  value['link'] +`">
                                                <li>` + value['name'] +
                                                    `<div>` +
                                                        value['email'] +
                                                    `</div>
                                                </li>
                                            </a>
                                        </div>`;
                    })


                    $(".search-list").empty();
                    $(".search-list").append(searchList);
   			    },
   			    error: function() {
   			         //console.log(data);
   			    }


         })

        })
    </script>

@endpush
