<style>

</style>
<div class="row widget-dashboard">

    {{--<div class="col-xl-4 col-md-6">
            <div class="card card-stats-student">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total traffic</h5>
                            <span class="h2 font-weight-bold mb-0">350,897</span>
                        </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                            <i class="ni ni-active-40"></i>
                        </div>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                    <span class="text-nowrap">Since last month</span>
                </p>
            </div>
        </div>
    </div>--}}


    <div class="col-xl-4 col-md-6">
        <div class="card card-stats-student">

            <!-- Card body -->
            <div class="card-body widget">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">STUDENTS ACTIVE NOW</h5>
                        <span id="student_total" class="h2 font-weight-bold mb-0"></span>
                    </div>
                </div>
                <div class="loader text-center">
                    <img class="img-responsive" src="{{url('/theme/assets/img/ajax-loader-blue.gif')}}" alt="loader">
                </div>
                <div class="info d-none">
                    <p class="mt-3 mb-0 text-sm">
                        <span class="text-muted mr-3">CLASS: <span class="text-success" id="students_inclass">{{-- $usersInclass --}}</span></span>
                        <span class="text-muted mr-3">VIDEO: <span class="text-success" id="students_elearning">{{--$usersElearning--}}</span></span>
                    </p>

                    <p class="mb-0 text-sm">
                        <span class="">All people who are now in a free or paid active course (class or video).</span>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-stats-student-all">
            <div class="card-body widget">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">REGISTRATIONS ALL TIME</h5>
                        <span id="students_all" class="h2 font-weight-bold mb-0">{{-- $usersInclassAll + $usersElearningAll --}}</span>

                    </div>

                </div>
                <div class="loader text-center">
                    <img class="img-responsive" src="{{url('/theme/assets/img/ajax-loader-blue.gif')}}" alt="loader">
                </div>
                <div class="info d-none">
                    <p class="mt-3 mb-0 text-sm">
                        <span class="text-muted mr-3">CLASS: <span class="text-success" id="students_inclass_all">{{-- $usersInclassAll --}}</span></span>
                        <span class="text-muted mr-3">VIDEO: <span class="text-success" id="students_elearning_all">{{-- $usersElearningAll --}}</span></span>
                    </p>
                    <p class="mb-0 text-sm">
                        <span class="">All people who registered in a free or paid course (class or video).</span>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-stats-instructor">
            <div class="card-body widget">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">INSTRUCTORS</h5>
                        <span id="instructor_total" class="h2 font-weight-bold mb-0">{{-- $instructorsAll --}}</span>
                    </div>
                </div>
                <div class="loader text-center">
                    <img class="img-responsive" src="{{url('/theme/assets/img/ajax-loader-blue.gif')}}" alt="loader">
                </div>
                <div class="info d-none">
                    <p class="mt-3 mb-0 text-sm">
                        <span  class="text-muted mr-3">CLASS: <span class="text-success" id="instructor_inclass">{{-- $instructorsInClass --}}</span></span>
                        <span  class="text-muted mr-3">VIDEO: <span class="text-success" id="instructor_elearning">{{-- $instructorsElearning --}}</span></span>
                    </p>
                    <p class="mb-0 text-sm">
                        <span class="">All instructors who are now active (class or video).</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
