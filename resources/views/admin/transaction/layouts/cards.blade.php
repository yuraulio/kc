<div id="participants_info" class="row d-none">

    <div class="card-body col-xl-4 col-md-6 total-revenue widget">
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
                            <span class="mr-3">CLASS: {{ $usersInClassAll }}</span>
                            <span class="mr-3">VIDEO: {{ $usersElearningAll }}</span>
                        </p>
                        <p class="mb-0 text-sm">
                            <span class="">All people who registered in a free or paid course (class or video).</span>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card-body col-xl-4 col-md-6 total-revenue widget">
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

    <div class="card-body col-xl-4 col-md-6 total-revenue widget">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">TICKETS INCOME</h5>
                        <span class="h2 font-weight-bold mb-0" id="total_income"></span>
                    </div>

                </div>
                <div class="row">
                    <div class="col">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="mr-3">EARLY: <span id="early-bird"></span></span>
                            <span class="mr-3">SPECIAL: <span id="special"></span></span>
                            <span class="mr-3">REGULAR: <span id="regular"></span></span>
                            <span class="mr-3">ALUMNI: <span id="alumni"></span></span>
                        </p>
                        <p class="mb-0 text-sm">
                            <span class="">All income from our paid courses (class or video) by ticket type.</span>
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
            $(`#${id}`).text('€'+newTickets[type][value]['countValue'].toLocaleString())

        })
    </script>
@endpush


