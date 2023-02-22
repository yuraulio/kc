<div class="row widget-instructor">
    <div class="col-xl-4 col-md-6">
        <div class="card card-stats widget">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">INSTRUCTORS</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $data['active'] + $data['inactive'] }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">ACTIVE: <span class="text-success">{{ $data['active'] }}</span></span>
                            <span class="text-muted mr-3">INACTIVE: <span class="text-success">{{ $data['inactive'] }}</span></span>
                        </p>
                        <p class="mb-0 text-sm">
                            <span class="">All instructors active now on the website.</span>
                        </p>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-stats widget">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">INSTRUCTORS ACTIVE</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $data['active'] }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">CLASS: <span class="text-success">{{ $data['inclass'] }}</span></span>
                            <span class="text-muted mr-3">VIDEO: <span class="text-success">{{ $data['elearning'] }}</span></span>
                        </p>
                        <p class="mb-0 text-sm">
                            <span class="">Instructors active now on the website by course type.</span>
                        </p>
                    </div>
                </div>


            </div>
        </div>
    </div>

</div>
