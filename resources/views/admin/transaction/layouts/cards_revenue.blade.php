<div id="participants_info" class="row d-none">
 

    <div class="card-body col-xl-3 col-md-6 total-sales">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total Sales:</h5>
                        <span id="total-sales" class="h2 font-weight-bold mb-0"></span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="card-body col-xl-3 col-md-6 total-revenue">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Total Revenue:</h5>
                        <span id="total" class="h2 font-weight-bold mb-0"></span>
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


