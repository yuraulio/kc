<div class="row widget-event">
    <div class="col-xl-4 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body widget">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">REGISTRATIONS</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $count['total'] }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                <p class="mt-3 mb-0 text-sm">
                    <span>PAID: {{ $count['total'] - $count['free'] }}</span>
                    <span class="ml-3">FREE: {{ $count['free'] }}</span>
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
                        <span class="h2 font-weight-bold mb-0">&euro; {{ number_format($income['total'],2,',','.') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="mr-3">EARLY: &euro; {{ number_format($income['early'],2,',','.') }}</span>
                            <span class="mr-3">SPECIAL: &euro; {{ number_format($income['special'],2,',','.') }}</span>
                            <span class="mr-3">REGULAR: &euro; {{ number_format($income['regular'],2,',','.') }}</span>
                            <span class="mr-3">ALUMNI: &euro; {{ number_format($income['alumni'],2,',','.') }}</span>
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
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">ACTUAL ACCRUED REVENUE</h5>
                        <span class="h2 font-weight-bold mb-0"> &euro; {{ number_format($incomeInstalments['total'],'2',',','.') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="mr-3">EARLY: &euro; {{ $incomeInstalments['early'] }}</span>
                            <span class="mr-3">SPECIAL: &euro; {{ number_format($incomeInstalments['special'],2,',','.') }}</span>
                            <span class="mr-3">REGULAR: &euro; {{ number_format($incomeInstalments['regular'],2,',','.') }}</span>
                            <span class="mr-3">ALUMNI: &euro; {{ number_format($incomeInstalments['alumni'],2,',','.') }}</span>
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
