<div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="background-image: url(../argon/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div style="display:block !important;" class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-md-12 {{ $class ?? '' }}">
                <h1 class="display-2 text-white">{{ $title }}</h1>
                @if (isset($description) && $description)
                    <p class="text-white mt-0 mb-5">{{ $description }}</p>
                @endif

            </div>

        </div>
        <div class="row">
            <div class="col-md-12 {{ $class ?? '' }}">
            @if(isset($partner_id) || isset($kc_id))
                <div style="color: white;">
                    @if($partner_id != '')<strong>Deree ID:</strong> {{ $partner_id }}@endif
                        <br/>

                    @if($kc_id != '')<strong>KC ID:</strong> {{ $kc_id }}@endif
                </div>
            @endif
            </div>

        </div>
    </div>
</div>
