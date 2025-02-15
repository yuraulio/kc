<div id="participants_info" class="row d-none">
    <div class="card-body col-xl-4 col-md-6 total-revenue widget">
        <div class="card">
            <div class="card-body js-statistics-registrations-total">
                <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">REGISTRATIONS ALL TIME</h5>
                      <span class="h2 font-weight-bold mb-0 js-total-users"></span>
                    </div>
                </div>
                <div class="loader text-center">
                  <img class="img-responsive" src="{{url('/theme/assets/img/ajax-loader-blue.gif')}}" alt="loader">
                </div>
                <div class="row">
                    <div class="col js-statistics-body" style="display:none">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">CLASS: <span class="text-success js-total-users-in-class"></span></span>
                            <span class="text-muted mr-3">VIDEO: <span class="text-success js-total-users-elearning"></span></span>
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
            <div class="card-body js-statistics-registrations-income">
                <div class="row">
                    <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">REGISTRATIONS INCOME</h5>
                        <span class="h2 font-weight-bold mb-0" id="total_income_by_type"></span>
                    </div>

                </div>
                <div class="loader text-center">
                    <img class="img-responsive" src="{{url('/theme/assets/img/ajax-loader-blue.gif')}}" alt="loader">
                </div>
                <div class="row">
                    <div class="col info js-statistics-body" style="display:none">
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-muted mr-3">CLASS: <span class="text-success" id="incomeInclassAll"></span></span>
                            <span class="text-muted mr-3">VIDEO: <span class="text-success" id="incomeElearningAll"></span></span>
                        </p>
                        <p class="mb-0 text-sm">
                            <span class="">All gross income from our paid courses (class or video).</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

  @if(!empty($type) && $type === 'revenue')
    <div class="card-body col-xl-4 col-md-6 js-statistics-revenues widget">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">ACTUAL ACCRUED REVENUE</h5>
              <span class="h2 font-weight-bold mb-0"><span id="incomeTotal" class="total"></span></span>
            </div>

          </div>
          <div class="loader text-center">
            <img class="img-responsive" src="{{url('/theme/assets/img/ajax-loader-blue.gif')}}" alt="loader">
          </div>

          <div class="row">
            <div class="col info js-statistics-body" style="display:none">
              <p class="mt-3 mb-0 text-sm">
                <span class="text-muted mr-3">CLASS: <span class="text-success" id="incomeInclass"></span></span>
                <span class="text-muted mr-3">VIDEO:  <span class="text-success" id="incomeElearning"></span></span>
              </p>
              <p class="mb-0 text-sm">
                <span class="">All actual and accrued revenue from our paid courses (class or video).</span>
              </p>
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


