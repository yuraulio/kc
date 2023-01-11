<div class="row">

    <div class="col-xl-4 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">ACCOUNTS CREATED</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $data['all'] }}</span>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span class="mr-3">ACTIVE:{{ $data['active'] }}</span>
                    <span class="mr-3">INACTIVE:{{ $data['inactive'] }}</span>
                </p>
                <p class="mb-0 text-sm">
                    <span class="">All of the accounts created by users or admins.</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">STUDENTS ACTIVE NOW</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $data['usersInClass'] + $data['usersElearning'] }}</span>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span class="mr-3">CLASS:{{ $data['usersInClass'] }}</span>
                    <span class="mr-3">VIDEO:{{ $data['usersElearning'] }}</span>

                </p>
                <p class="mb-0 text-sm">
                    <span class="">All people who are now in a free or paid active course (class or video).</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">REGISTRATIONS ALL TIME</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $data['usersInClassAll'] + $data['usersElearningAll'] }}</span>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span class="mr-3">CLASS:{{ $data['usersInClassAll'] }}</span>
                    <span class="mr-3">VIDEO:{{ $data['usersElearningAll'] }}</span>

                </p>
                <p class="mb-0 text-sm">
                    <span class="">All people who registered in a free or paid course (class or video).</span>
                </p>
            </div>
        </div>
    </div>

</div>
