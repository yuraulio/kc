<div id="participants_info" class="row d-none">

    <div class="card-body col-xl-3 col-md-6 total-revenue">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Total Sales:</h5>
                        <span id="total" class="h2 font-weight-bold mb-0"></span>
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
    </div>
    @if(isset(total_users))
    <div class="card-body col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Total Students:</h5>
                        <span id="total_students" class="h2 font-weight-bold mb-0">{{ $total_users }}</span>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endif

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


