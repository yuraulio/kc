
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
                        <span class="text-muted mr-3">CLASS: <span class="text-success">{{ $total_users_inclass }}</span></span>
                        <span class="text-muted mr-3">VIDEO: <span class="text-success">{{ $total_users_elearning }}</span></span>
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
                        <span id="totalIncomeAll" class="h2 font-weight-bold mb-0"></span>
                    </div>

                </div>
                <div class="loader text-center">
                    <img class="img-responsive" src="{{url('/theme/assets/img/ajax-loader-blue.gif')}}" alt="loader">
                </div>
                <div class="row">
                    <div class="col info d-none">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">CLASS: <span class="text-success" id="income_inclassAll"></span></span>
                            <span class="text-muted mr-3">VIDEO: <span class="text-success" id="income_elearningAll"></span></span>
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
                        <span class="h2 font-weight-bold mb-0"><span class="total"></span></span>
                    </div>

                </div>
                <div class="loader text-center">
                    <img class="img-responsive" src="{{url('/theme/assets/img/ajax-loader-blue.gif')}}" alt="loader">
                </div>

                <div class="row">
                    <div class="col info d-none">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">CLASS: <span class="text-success" id="income_inclass"></span></span>
                            <span class="text-muted mr-3">VIDEO:  <span class="text-success" id="income_elearning"></span></span>
                        </p>
                        <p class="mb-0 text-sm">
                            <span class="">All actual and accrued revenue from our paid courses (class or video).</span>
                        </p>
                    </div>
                </div>

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


