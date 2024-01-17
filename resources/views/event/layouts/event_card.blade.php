<div class="row widget-event">
    <div class="col-xl-4 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body widget">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">REGISTRATIONS</h5>
                        <span id="student_total" class="h2 font-weight-bold mb-0"></span>
                    </div>
                </div>
                <div class="loader text-center">
                    <img class="img-responsive" src="{{url('/theme/assets/img/ajax-loader-blue.gif')}}" alt="loader">
                </div>
                <div class="row">
                    <div class="col info d-none">
                        <p class="mb-0 text-sm">
                            <span class="">All tickets & sales for this course.</span>
                        </p>
                        <style>
                            .fw-bold{
                                font-weight: 600;
                            }
                        </style>
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">EARLY:&nbsp;<span class="text-success fw-bold" id="students_early"></span>&nbsp;<span class="text-success students_early_amounts"></span></span>
                            <span class="text-muted mr-3">ALUMNI:&nbsp;<span class="text-success fw-bold" id="students_alumni"></span>&nbsp;<span class="text-success students_alumni_amounts"></span></span>
                            <span class="text-muted mr-3">FREE:&nbsp;<span class="text-success fw-bold" id="students_free"></span>&nbsp;<span class="text-success students_free_amounts"></span></span>
                            <span class="text-muted mr-3">REGULAR:&nbsp;<span class="text-success fw-bold" id="students_regular"></span>&nbsp;<span class="text-success students_regular_amounts"></span></span>
                            <span class="text-muted mr-3">SPECIAL:&nbsp;<span class="text-success fw-bold" id="students_special"></span>&nbsp;<span class="text-success students_special_amounts"></span></span>
                            <br>
                            <span class="mr-3 fw-bold">TOTAL STUDENTS:&nbsp; <span class="text-success fw-bold" id="students_total_students"></span></span>
                        </p>
                        <hr style="margin: 5px 0px;"/>
                        <p class="mt-0 mb-0 text-sm">
                            <span class="text-muted mr-3">STUDENTS:&nbsp;<span class="text-success fw-bold" id="students"></span>&nbsp;<span class="text-success students_amounts"></span></span>
                            <span class="text-muted mr-3">UNEMPLOYED:&nbsp;<span class="text-success fw-bold" id="unemployed"></span>&nbsp;<span class="text-success unemployed_amounts"></span></span>
                            <span class="text-muted mr-3">GROUP:&nbsp;<span class="text-success fw-bold" id="group"></span>&nbsp;<span class="text-success group_amounts"></span></span>
                            <br>
                            <span class="mr-3 fw-bold">TOTAL SALES:&nbsp; <span class="text-success fw-bold" id="students_total"></span>&nbsp;<span class="text-success students_total_amounts"></span></span>
                        </p>
                        <hr style="margin: 5px 0px;"/>
                        <p class="mt-0 mb-0 text-sm">
                            {{-- <span class="text-muted mr-3">PAID: <span class="text-success" id="students_paid"></span>&nbsp;<span class="text-success" id="students_paid_amounts"></span></span> --}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($event->is_elearning_course())
    <div class="col-xl-4 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body widget">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">TOTAL ACTIVE</h5>
                        <span class="h2 font-weight-bold mb-0"> <span id="active-total"></span></span>
                    </div>
                </div>
                <div class="loader text-center">
                    <img class="img-responsive" src="{{url('/theme/assets/img/ajax-loader-blue.gif')}}" alt="loader">
                </div>
                <div class="row">
                    <div class="col info d-none">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">INCLASS: <span class="text-success" id="inclass-active">0</span></span>
                            <span class="text-muted mr-3">E-LEARNING: <span class="text-success" id="elearning-active">0</span></span>

                        </p>
                        <p class="mb-0 text-sm">
                            <span class="">All active students of this course.</span>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endif

    <div class="col-xl-4 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body widget">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">REGISTRATIONS INCOME</h5>
                        <span class="h2 font-weight-bold mb-0"><span id="income-total"></span></span>
                    </div>
                </div>
                <div class="loader text-center">
                    <img class="img-responsive" src="{{url('/theme/assets/img/ajax-loader-blue.gif')}}" alt="loader">
                </div>
                <div class="row">
                    <div class="col info d-none">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">EARLY: <span class="text-success" id="income-early"></span></span>
                            <span class="text-muted mr-3">SPECIAL: <span class="text-success" id="income-special"></span></span>
                            <span class="text-muted mr-3">REGULAR: <span class="text-success" id="income-regular"></span></span>
                            <span class="text-muted mr-3">ALUMNI: <span class="text-success" id="income-alumni"></span></span>
                            <span class="text-muted mr-3">SUBSCRIPTION: <span class="text-success" id="income-subscription"></span></span>
                        </p>
                        <p class="mb-0 text-sm">
                            <span class="">All gross income for this course.</span>
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
                        <h5 class="card-title text-uppercase text-muted mb-0">ACRUED REVENUE</h5>
                        <span class="h2 font-weight-bold mb-0"> <span id="installments-total"></span></span>
                    </div>
                </div>
                <div class="loader text-center">
                    <img class="img-responsive" src="{{url('/theme/assets/img/ajax-loader-blue.gif')}}" alt="loader">
                </div>
                <div class="row">
                    <div class="col info d-none">
                        <p class="mb-0 text-sm">
                            <span class="">All actual revenue of payment plans</span>
                        </p>
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">EARLY: <span class="text-success students_early_amounts"></span></span>
                            <span class="text-muted mr-3">ALUMNI: <span class="text-success students_alumni_amounts"></span></span>
                            <br>
                            <span class="text-muted mr-3">STUDENTS: <span class="text-success students_amounts"></span></span>
                            <span class="text-muted mr-3">UNEMPLOYED: <span class="text-success unemployed_amounts"></span></span>
                            <span class="text-muted mr-3">GROUP: <span class="text-success group_amounts"></span></span>
                            <br>
                            <span class="text-muted mr-3">REGULAR: <span class="text-success students_regular_amounts"></span></span>
                            <span class="text-muted mr-3">SPECIAL: <span class="text-success students_special_amounts"></span></span>
                            {{-- <span class="text-muted mr-3">EARLY:&nbsp;<span class="text-success" id="installments-early"></span></span>
                            <span class="text-muted mr-3">SPECIAL:&nbsp;<span class="text-success" id="installments-special"></span></span>
                            <span class="text-muted mr-3">REGULAR:&nbsp;<span class="text-success" id="installments-regular"></span></span>
                            <span class="text-muted mr-3">ALUMNI:&nbsp;<span class="text-success" id="installments-alumni"></span></span>
                            <span class="text-muted mr-3">SUBSCRIPTION:&nbsp;<span class="text-success" id="installments-subscription"></span></span> --}}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-12 text-right general-save-wrapper">
        <div class="save_event_btn" >@include('admin.save.save',['event' => isset($event) ? $event : null])</div>
        <div class="preview_event_btn">@include('admin.preview.preview',['slug' => isset($slug) ? $slug : null])</div>
        <div class="save_event_btn" >@include('admin.download.schedule',['event' => isset($event) ? $event : null])</div>
    </div>
    <div class="col-12 text-right d-none seo-save-wrapper">
        <button id="submit-seo-btn" type="button" class="submit-btn btn btn-outline-success mt-4 custom-btn-breadcrum">{{ __('Save ').' ' }}</button>
    </div>
</div>

