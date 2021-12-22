<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">TOTAL USERS</h5>
                        <span class="h2 font-weight-bold mb-0">{{$users}}</span>
                    </div>
                </div>
            </div>
           
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">TOTAL ADMINS</h5>
                        <span class="h2 font-weight-bold mb-0">{{$adminUsers}}</span>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">TOTAL INSTRUCTORS ACTIVE</h5>
                        <span class="h2 font-weight-bold mb-0">{{$instructors}}</span>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">TOTAL STUDENTS</h5>
                            <span class="h2 font-weight-bold mb-0">{{$totalsStudents}}</span>
                        </div>

                    </div>
                    <p class="mt-3 mb-0 text-sm">
                        <span class="text-nowrap">IN-CLASS COURSES: {{$usersInclass}}</span>
                    </p>
                    <p class="mt-3 mb-0 text-sm">
                        <span class="text-nowrap">E-LEARNING COURSES: {{$usersElearning}}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">TOTAL SUCCESSFUL GRADUATES</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $usersGranduates }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
