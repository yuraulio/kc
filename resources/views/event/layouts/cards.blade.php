<div class="row widget-event">

    <div class="col-xl-4 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body widget">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">COURSES</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $data['all'] }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">ACTIVE NOW: <span class="text-success">{{ $data['active'] }}</span></span>
                            <span class="text-muted mr-3">COMPLETED: <span class="text-success">{{ $data['completed'] }}</span></span>
                        </p>
                        <p class="mb-0 text-sm">
                            <span class="">All courses created by admins.</span>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body widget">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">ACTIVE COURSES</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $data['inclass'] + $data['elearning'] }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">CLASS: <span class="text-success">{{ $data['inclass'] }}</span></span>
                            <span class="text-muted mr-3">VIDEO: <span class="text-success">{{ $data['elearning'] }}</span></span>
                        </p>
                        <p class="mb-0 text-sm">
                            <span class="">All courses active now on the website.</span>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>


</div>
