
<div id="participants_info" class="row d-none">

    <div class="card-body col-xl-4 col-md-6 total-sales widget">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">REGISTRATIONS ALL TIME</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $total_users }}</span>
                    </div>

                </div>
                <div class="row">
                    <div class="col">
                        <p class="mt-3 mb-0 text-sm">
                        <span class="mr-3">CLASS: {{ $total_users_inclass }}</span>
                        <span class="mr-3">VIDEO: {{ $total_users_elearning }}</span>
                    </p>
                    <p class="mb-0 text-sm">
                        <span class="">All people who registered in a free or paid course (class or video).</span>
                    </p>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>

    <div class="card-body col-xl-4 col-md-6 total-sales widget">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">REGISTRATIONS INCOME</h5>
                        <span class="h2 font-weight-bold mb-0">&euro;{{ number_format($usersInClassIncomeAll + $usersElearningIncomeAll,2,',','.') }}</span>
                    </div>

                </div>
                <div class="row">
                    <div class="col">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="mr-3">CLASS: &euro;{{ number_format($usersInClassIncomeAll,2,',','.') }}</span>
                            <span class="mr-3">VIDEO: &euro;{{ number_format($usersElearningIncomeAll,2,',','.') }}</span>
                        </p>
                        <p class="mb-0 text-sm">
                            <span class="">All gross income from our paid courses (class or video).</span>
                        </p>
                    </div>

                </div>
                
            </div>
        </div>
    </div>

    <div class="card-body col-xl-4 col-md-6 total-sales widget">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">ACTUAL ACCRUED REVENUE</h5>
                        <span class="h2 font-weight-bold mb-0">&euro;{{ number_format($paid_installments_inclass + $paid_installments_elearning,2,',','.') }}</span>
                    </div>

                </div>

                <div class="row">
                    <div class="col">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="mr-3">CLASS: &euro;{{ number_format($paid_installments_inclass,2,',','.') }}</span>
                            <span class="mr-3">VIDEO: &euro;{{ number_format($paid_installments_elearning,2,',','.') }}</span>
                        </p>
                        <p class="mb-0 text-sm">
                            <span class="">All actual and accrued revenue from our paid courses (class or video).</span>
                        </p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="card-body col-xl-4 col-md-6 total-sales">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">TICKETS ACCRUED REVENUE</h5>
                        <span class="h2 font-weight-bold mb-0">&euro;{{ number_format($income['total'],2,',','.') }}</span>
                    </div>

                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span class="mr-3">EARLY: &euro;{{ number_format($income['early'],2,'.','.') }}</span>
                    <span class="mr-3">SPECIAL: &euro;{{ number_format($income['special'],2,',','.') }}</span>
                    <span class="mr-3">REGULAR: &euro;{{ number_format($income['regular'],2,',','.') }}</span>
                </p>
                <p class="mb-0 text-sm">
                    <span class="">All actual and accrued revenue from our paid courses (class or video) by ticket type.</span>
                </p>
            </div>
        </div>
    </div>





</div>


@push('js')
    <script>
        $(document).on('click','.ticket-action', function(){

            type = $(this).data('type')
            value = $(this).data('ticket');

            id = type.toLowerCase();
            id = id.replace(/ /g, '-');

            $(`#count_${id}`).text(`${type}:(${value}) ` + newTickets[type][value]['count'])
            $(`#${id}`).text('€'+newTickets[type][value]['countValue'])

        })
    </script>
@endpush


