<style>

</style>
<div class="row">

    <div class="col-xl-4 col-md-6">
        <div class="card card-stats-student">

            <!-- Card body -->
            <div class="card-body">
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
                        <span class="mr-3">CLASS: <span id="students_inclass">{{-- $usersInclass --}}</span></span>
                        <span class="mr-3">VIDEO: <span id="students_elearning">{{--$usersElearning--}}</span></span>
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
            <div class="card-body">
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
                        <span class="mr-3">CLASS: <span id="students_inclass_all">{{-- $usersInclassAll --}}</span></span>
                        <span class="mr-3">VIDEO: <span id="students_elearning_all">{{-- $usersElearningAll --}}</span></span>
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
            <div class="card-body">
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
                        <span  class="mr-3">CLASS: <span id="instructor_inclass">{{-- $instructorsInClass --}}</span></span>
                        <span  class="mr-3">VIDEO: <span id="instructor_elearning">{{-- $instructorsElearning --}}</span></span>
                    </p>
                    <p class="mb-0 text-sm">
                        <span class="">All instructors who are now active (class or video).</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
