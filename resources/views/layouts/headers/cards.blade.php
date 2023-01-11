<div class="row">

    <div class="col-xl-4 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">STUDENTS ACTIVE NOW</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $usersInclass + $usersElearning }}</span>


                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span class="mr-3">CLASS: {{ $usersInclass }}</span>
                    <span class="mr-3">VIDEO: {{$usersElearning}}</span>
                </p>

                <p class="mb-0 text-sm">
                    <span class="">All people who are now in a free or paid active course (class or video).</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">REGISTRATIONS ALL TIME</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $usersInclassAll + $usersElearningAll }}</span>

                    </div>

                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span class="mr-3">CLASS: {{ $usersInclassAll }}</span>
                    <span class="mr-3">VIDEO: {{ $usersElearningAll }}</span>
                </p>
                <p class="mb-0 text-sm">
                    <span class="">All people who registered in a free or paid course (class or video).</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">INSTRUCTORS</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $instructorsAll }}</span>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span class="mr-3">CLASS: {{ $instructorsInClass }}</span>
                    <span class="mr-3">VIDEO: {{ $instructorsElearning }}</span>
                </p>
                <p class="mb-0 text-sm">
                    <span class="">All instructors who are now active (class or video).</span>
                </p>
            </div>
        </div>
    </div>
</div>
