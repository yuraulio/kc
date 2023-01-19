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
                    <div class="col">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">PAID: <span class="text-success" id="students_paid"></span></span>
                            <span class="text-muted mr-3">FREE: <span class="text-success" id="students_free"></span></span>
                        </p>
                        <p class="mb-0 text-sm">
                            <span class="">All students of this course.</span>
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
                        <h5 class="card-title text-uppercase text-muted mb-0">ACTUAL ACCRUED REVENUE</h5>
                        <span class="h2 font-weight-bold mb-0"> <span id="installments-total"></span></span>
                    </div>
                </div>
                <div class="loader text-center">
                    <img class="img-responsive" src="{{url('/theme/assets/img/ajax-loader-blue.gif')}}" alt="loader">
                </div>
                <div class="row">
                    <div class="col info d-none">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">EARLY: <span class="text-success" id="installments-early"></span></span>
                            <span class="text-muted mr-3">SPECIAL: <span class="text-success" id="installments-special"></span></span>
                            <span class="text-muted mr-3">REGULAR: <span class="text-success" id="installments-regular"></span></span>
                            <span class="text-muted mr-3">ALUMNI: <span class="text-success" id="installments-alumni"></span></span>
                            <span class="text-muted mr-3">SUBSCRIPTION: <span class="text-success" id="installments-subscription"></span></span>
                        </p>
                        <p class="mb-0 text-sm">
                            <span class="">All actual and accrued revenue for this course.</span>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>




</div>
