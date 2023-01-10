<div class="row">

    <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">COURSES</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $data['all'] }}</span>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span>ACTIVE NOW:{{ $data['active'] }}</span>
                    <span class="ml-3">COMPLETED:{{ $data['completed'] }}</span>
                </p>
                <p class="mb-0 text-sm">
                    <span class="">All courses created by admins.</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">ACTIVE COURSES</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $data['inclass'] + $data['elearning'] }}</span>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span>CLASS:{{ $data['inclass'] }}</span>
                    <span class="ml-3">VIDEO:{{ $data['elearning'] }}</span>
                </p>
                <p class="mb-0 text-sm">
                    <span class="">All courses active now on the website.</span>
                </p>
            </div>
        </div>
    </div>


</div>
