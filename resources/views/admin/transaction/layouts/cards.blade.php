<div id="participants_info" class="row d-none">

    <div class="card-body col-xl-3 col-md-6 total-revenue">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">REGISTRATIONS ALL TIME</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $usersInClassAll + $usersElearningAll }}</span>
                    </div>

                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span>CLASS: {{ $usersInClassAll }}</span>
                    <span class="ml-3">VIDEO: {{ $usersElearningAll }}</span>
                </p>
                <p class="mb-0 text-sm">
                    <span class="">All people who registered in a free or paid course (class or video).</span>
                </p>
            </div>
        </div>
    </div>

    <div class="card-body col-xl-3 col-md-6 total-revenue">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">REGISTRATIONS INCOME</h5>
                        <span class="h2 font-weight-bold mb-0">&euro;{{ number_format($usersInClassIncomeAll + $usersElearningIncomeAll,2,'.','.') }}</span>
                    </div>

                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span>CLASS: &euro;{{ number_format($usersInClassIncomeAll,2,'.','.') }}</span>
                    <span class="ml-3">VIDEO: &euro;{{ number_format($usersElearningIncomeAll,2,'.','.') }}</span>
                </p>
                <p class="mb-0 text-sm">
                    <span class="">All gross income from our paid courses (class or video).</span>
                </p>
            </div>
        </div>
    </div>

    <div class="card-body col-xl-3 col-md-6 total-revenue">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">TICKETS INCOME</h5>
                        <span class="h2 font-weight-bold mb-0" id="total_income"></span>
                    </div>

                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span>EARLY: <span id="early-bird"></span></span>
                    <span class="ml-3">SPECIAL: <span id="special"></span></span>
                    <span class="ml-3">REGULAR: <span id="regular"></span></span>
                </p>
                <p class="mb-0 text-sm">
                    <span class="">All income from our paid courses (class or video) by ticket type.</span>
                </p>
            </div>
        </div>
    </div>


    <!-- <div class="card-body col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                       <h5 class="card-title text-uppercase text-muted mb-0" id="count_alumni">Alumni:</h5>
                       <span id="alumni" class="h2 font-weight-bold mb-0"></span>
                    </div>
                    <div class="col-auto">
                       <button type="button" class="btn btn-sm btn-neutral mr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                       </button>
                       <div class="ticket-choices dropdown-menu dropdown-menu-right alumni-action">
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                       <h5 class="card-title text-uppercase text-muted mb-0" id="count_early-bird">Early Bird:</h5>
                       <span id="early-bird" class="h2 font-weight-bold mb-0"></span>
                    </div>
                    <div class="col-auto">
                       <button type="button" class="btn btn-sm btn-neutral mr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                       </button>
                       <div class="ticket-choices dropdown-menu dropdown-menu-right early-bird-action">
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                       <h5 class="card-title text-uppercase text-muted mb-0" id="count_regular">Regular:</h5>
                       <span id="regular" class="h2 font-weight-bold mb-0"></span>
                    </div>
                    <div class="col-auto">
                       <button type="button" class="btn btn-sm btn-neutral mr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                       </button>
                       <div class="ticket-choices dropdown-menu dropdown-menu-right regular-action">
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card-body col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                       <h5 class="card-title text-uppercase text-muted mb-0" id="count_special">Special:</h5>
                       <span id="special" class="h2 font-weight-bold mb-0"></span>
                    </div>
                    <div class="col-auto">
                       <button type="button" class="btn btn-sm btn-neutral mr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                       </button>
                       <div class="ticket-choices dropdown-menu dropdown-menu-right special-action">
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0"><div id="count_sponsored"></div> Sponsored:</h5>
                        <span id="sponsored" class="h2 font-weight-bold mb-0"></span>
                    </div>

                </div>
            </div>
        </div>
    </div> -->

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


