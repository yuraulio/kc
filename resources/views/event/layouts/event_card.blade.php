<div class="row widget-event">
    <div class="col-xl-4 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body widget">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">
                            TICKETS
                            <i class="fas fa-ticket-alt"></i>
                        </h5>
                        <span class="h2 font-weight-bold mb-0"><span id="students_total_tickets"></span></span>
                    </div>
                </div>
                <div class="loader text-center">
                    <img class="img-responsive" src="{{ url('/theme/assets/img/ajax-loader-blue.gif') }}"
                        alt="loader">
                </div>
                <div class="row">
                    <div class="col info d-none">
                        <p class="mt-1 mb-0 text-sm">
                            <span class="mr-3 fw-bold">The count of tickets bought</span>
                        </p>
                        <hr style="margin: 5px 0px;" />
                        <div class="mt-1 mb-0 text-sm">
                            <span class="text-muted mr-3" style="white-space: nowrap;">Free:&nbsp;<span
                                    class="text-success fw-bold" id="students_free"></span></span>
                            <span class="text-muted mr-3" style="white-space: nowrap;">Early:&nbsp;<span
                                    class="text-success fw-bold" id="students_early"></span></span>
                            <hr style="margin: 5px 0px;" />
                        </div>
                        <p class="mt-0 mb-0 text-sm">
                            <span class="text-muted mr-3" style="white-space: nowrap;">Other:&nbsp;<span
                                    class="text-success fw-bold" id="other"></span></span>
                            <span class="text-muted mr-3" style="white-space: nowrap;">Students discounted:&nbsp;<span
                                    class="text-success fw-bold" id="students"></span></span>
                            <span class="text-muted mr-3" style="white-space: nowrap;">Unemployed discounted:&nbsp;<span
                                    class="text-success fw-bold" id="unemployed"></span></span>
                            <span class="text-muted mr-3" style="white-space: nowrap;">Groups discounted:&nbsp;<span
                                    class="text-success fw-bold" id="group"></span></span>
                            <span class="text-muted mr-3" style="white-space: nowrap;">Regular:&nbsp;<span
                                    class="text-success fw-bold" id="students_regular"></span></span>
                            <span class="text-muted mr-3" style="white-space: nowrap;">Alumni:&nbsp;<span
                                    class="text-success fw-bold" id="students_alumni"></span></span>
                        </p>
                        <style>
                            .fw-bold {
                                font-weight: 600;
                            }
                        </style>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($event->is_elearning_course())
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
                        <img class="img-responsive" src="{{ url('/theme/assets/img/ajax-loader-blue.gif') }}"
                            alt="loader">
                    </div>
                    <div class="row">
                        <div class="col info d-none">
                            <p class="mt-3 mb-0 text-sm">
                                <span class="text-muted mr-3">INCLASS: <span class="text-success"
                                        id="inclass-active">0</span></span>
                                <span class="text-muted mr-3">E-LEARNING: <span class="text-success"
                                        id="elearning-active">0</span></span>

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
                        <h5 class="card-title text-uppercase text-muted mb-0">
                            SALES
                            <i class="fas fa-tag"></i>
                        </h5>
                        <span class="h2 font-weight-bold mb-0"><span class="students_total_amounts"></span></span>
                    </div>
                </div>
                <div class="loader text-center">
                    <img class="img-responsive" src="{{ url('/theme/assets/img/ajax-loader-blue.gif') }}"
                        alt="loader">
                </div>
                <div class="row">
                    <div class="col info d-none">
                        <p class="mt-1 mb-0 text-sm">
                            <span class="mr-3 fw-bold">The sales revenue of this course</span>
                        </p>
                        <hr style="margin: 5px 0px;" />
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">Early:&nbsp;<span class="text-success" id="income-early"></span></span>
                            <span class="text-muted mr-3">Students discounted:&nbsp;<span class="text-success students_amounts"></span></span>
                            <span class="text-muted mr-3">Unemployed discounted:&nbsp;<span class="text-success unemployed_amounts"></span></span>
                            <span class="text-muted mr-3">Groups discounted:&nbsp;<span class="text-success group_amounts"></span></span>
                            <span class="text-muted mr-3">Other discounted:&nbsp;<span class="text-success other_amounts"></span></span>
                            <span class="text-muted mr-3">Alumni:&nbsp;<span class="text-success students_alumni_amounts"></span></span>
                            <span class="text-muted mr-3">Regular:&nbsp;<span class="text-success students_regular_amounts"></span></span>
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
                        <h5 class="card-title text-uppercase text-muted mb-0">
                            Accrued
                            <i class="fas fa-tag"></i>
                        </h5>
                        <span class="h2 font-weight-bold mb-0"> <span id="installments-total"></span></span>
                    </div>
                </div>
                <div class="loader text-center">
                    <img class="img-responsive" src="{{ url('/theme/assets/img/ajax-loader-blue.gif') }}"
                        alt="loader">
                </div>
                <div class="row">
                    <div class="col info d-none">
                        <p class="mb-0 text-sm">
                            <span class="mr-3 fw-bold">The actual and accrued revenue for this course</span>
                        </p>
                        <hr style="margin: 5px 0px;" />
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">Early: <span class="text-success" id="installments-early"></span></span>
                            <span class="text-muted mr-3">Students discounted:&nbsp;<span class="text-success" id="installments-students"></span></span>
                            <span class="text-muted mr-3">Unemployed discounted:&nbsp;<span class="text-success" id="installments-unemployed"></span></span>
                            <span class="text-muted mr-3">Groups discounted:&nbsp;<span class="text-success" id="installments-group"></span></span>
                            <span class="text-muted mr-3">Regular:&nbsp;<span class="text-success" id="installments-regular"></span></span>
                            <span class="text-muted mr-3">Alumni:&nbsp;<span class="text-success" id="installments-alumni"></span></span>
                            <span class="text-muted mr-3">Other:&nbsp;<span class="text-success" id="installments-other"></span></span>

                        </p>
                        <div class="alerts-to-show"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-12 text-right general-save-wrapper">
        <div class="save_event_btn">@include('admin.save.save', ['event' => isset($event) ? $event : null])</div>
        <div class="preview_event_btn">@include('admin.preview.preview', ['slug' => isset($slug) ? $slug : null])</div>
        <div class="save_event_btn">@include('admin.download.schedule', ['event' => isset($event) ? $event : null])</div>
    </div>
    <div class="col-12 text-right d-none seo-save-wrapper">
        <button id="submit-seo-btn" type="button"
            class="submit-btn btn btn-outline-success mt-4 custom-btn-breadcrum">{{ __('Save ') . ' ' }}</button>
    </div>
</div>
