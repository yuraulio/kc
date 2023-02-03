<div class="row widget-user">

    <div class="col-xl-4 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body widget">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">ACCOUNTS CREATED</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $data['all'] }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">ACTIVE: <span class="text-success">{{ $data['active'] }}</span></span>
                            <span class="text-muted mr-3">INACTIVE: <span class="text-success">{{ $data['inactive'] }}</span></span>
                        </p>
                        <p class="mb-0 text-sm">
                            <span class="">All of the accounts created by users or admins.</span>
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
                        <h5 class="card-title text-uppercase text-muted mb-0">STUDENTS ACTIVE NOW</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $data['usersInClass'] + $data['usersElearning'] }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                <p class="mt-3 mb-0 text-sm">
                    <span class="text-muted mr-3">CLASS: <span class="text-success">{{ $data['usersInClass'] }}</span></span>
                    <span class="text-muted mr-3">VIDEO: <span class="text-success">{{ $data['usersElearning'] }}</span></span>

                </p>
                <p class="mb-0 text-sm">
                    <span class="">All people who are now in a free or paid active course (class or video).</span>
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
                        <h5 class="card-title text-uppercase text-muted mb-0">REGISTRATIONS ALL TIME</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $data['usersInClassAll'] + $data['usersElearningAll'] }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    <p class="mt-3 mb-0 text-sm">
                    <span class="text-muted mr-3">CLASS: <span class="text-success">{{ $data['usersInClassAll'] }}</span></span>
                    <span class="text-muted mr-3">VIDEO: <span class="text-success">{{ $data['usersElearningAll'] }}</span></span>

                </p>
                <p class="mb-0 text-sm">
                    <span class="">All people who registered in a free or paid course (class or video).</span>
                </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
