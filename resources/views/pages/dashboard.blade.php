@extends('layouts.app', [
    'parentSection' => 'dashboards',
    'elementName' => 'dashboard'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item active" aria-current="page">{{ __('Dashboard') }}</li>
        @endcomponent
        @include('layouts.headers.cards')
    @endcomponent

    <div class="container-fluid mt--10">
        <div class="row">
            <div class="col">
            <div class="input-daterange datepicker row justify-content-md-center">
                <div class="col-4">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                            </div>
                            <input class="form-control" id="start_date" placeholder="Start date" type="text" value="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                            </div>
                            <input class="form-control" id="end_date" placeholder="End date" type="text" value="">
                        </div>
                    </div>
                </div>
            </div>


            </div>
        </div>
        <div class="row">
            {{--<div class="col-xl-8">
                <div class="card bg-default">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
                                <h5 class="h3 text-white mb-0">Sales value</h5>
                            </div>
                            <div class="col">
                                <ul class="nav nav-pills justify-content-end">
                                    <li class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-sales-dark" data-update='{"data":{"datasets":[{"data":[0, 20, 10, 30, 15, 40, 20, 60, 60]}]}}'
                                        data-prefix="$" data-suffix="k">
                                        <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                            <span class="d-none d-md-block">Month</span>
                                            <span class="d-md-none">M</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" data-toggle="chart" data-target="#chart-sales-dark" data-update='{"data":{"datasets":[{"data":[0, 20, 5, 25, 10, 30, 15, 40, 40]}]}}'
                                        data-prefix="$" data-suffix="k">
                                        <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                            <span class="d-none d-md-block">Week</span>
                                            <span class="d-md-none">W</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <!-- Chart wrapper -->
                            <canvas id="chart-sales-dark" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>--}}
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                                <h5 class="h3 mb-0">Revenue report</h5>
                            </div>
                            <div class="col">
                                <select id="revenue-filter-by-event" class="custom-select custom-select-sm">
                                    <?php //dd($revenueByEvent); ?>
                                    <option value="" selected="selected">All</option>
                                    @foreach($revenueByEvent as $key => $item)
                                        <option value="{{$key}}">{{$item[0]['event_title']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <canvas id="chart-bars1" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-xl-6">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                                <h5 class="h3 mb-0">Elearning Users report</h5>
                            </div>
                            <div class="col">
                                <select id="elearning-filter-by-event" class="custom-select custom-select-sm">
                                    <?php //dd($revenueByEvent); ?>
                                    <option value="" selected="selected">All</option>
                                    @foreach($elearningByEvent as $key => $item)
                                        <option value="{{$key}}">{{$item[0]['event_title']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <canvas id="elearning-bars" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                                <h5 class="h3 mb-0">Alumni Ticket report</h5>
                            </div>
                            <div class="col">
                                <select id="alumni-filter-by-event" class="custom-select custom-select-sm">
                                    <?php //dd($revenueByEvent); ?>
                                    <option value="" selected="selected">All</option>
                                    @foreach($alumniByEvent as $key => $item)
                                        <option value="{{$key}}">{{$item[0]['event_title']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <canvas id="alumni-bars" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Ticket Stats</h6>
                                <h5 class="h3 mb-0"># of tickets / ticket type</h5>
                            </div>
                            <div class="col">
                                <select id="ticket-num-filter-by-event" class="custom-select custom-select-sm">
                                    <?php //dd($revenueByEvent); ?>
                                    <option value="" selected="selected">All</option>
                                    @foreach($alumniByEvent as $key => $item)
                                        <option value="{{$key}}">{{$item[0]['event_title']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <canvas id="ticket-type-pie" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Ticket Stats</h6>
                                <h5 class="h3 mb-0">Sales / ticket type</h5>
                            </div>
                            <div class="col">
                                <select id="ticket-filter-by-event" class="custom-select custom-select-sm">
                                    <?php //dd($revenueByEvent); ?>
                                    <option value="" selected="selected">All</option>
                                    @foreach($elearningByEvent as $key => $item)
                                        <option value="{{$key}}">{{$item[0]['event_title']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <canvas id="ticket-sales-pie" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>



        </div>
        <div class="row">

            <div class="col-xl-12">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <!-- Card header -->
                            <div class="card-header border-0">
                                <h3 class="mb-0">BOOKINGS</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">User</th>
                                            <th scope="col">Event</th>
                                            <th scope="col">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        <?php $count = 0; ?>
                                        @foreach($booking as $key => $item)
                                        @if($count == 10)
                                        <?php break; ?>
                                        @endif
                                        <?php //dd($item); ?>
                                            <tr>
                                                <th>{{ $item['name'] }}</th>
                                                <th>{{ $item['event_title']}}</th>
                                                <th>€{{ number_format($item['amount'], 2, '.', ''); }}</th>
                                            </tr>
                                            <?php $count++; ?>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="card-deck">
                    <div class="card bg-gradient-default">
                        <div class="card-body">
                            <div class="mb-2">
                                <sup class="text-white">$</sup> <span class="h2 text-white">3,300</span>
                                <div class="text-light mt-2 text-sm">Your current balance</div>
                                <div>
                                    <span class="text-success font-weight-600">+ 15%</span> <span class="text-light">($250)</span>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-block btn-neutral">Add credit</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <small class="text-light">Orders: 60%</small>
                                    <div class="progress progress-xs my-2">
                                        <div class="progress-bar bg-success" style="width: 60%"></div>
                                    </div>
                                </div>
                                <div class="col"><small class="text-light">Sales: 40%</small>
                                    <div class="progress progress-xs my-2">
                                        <div class="progress-bar bg-warning" style="width: 40%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Username card -->
                    <div class="card bg-gradient-danger">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row justify-content-between align-items-center">
                                <div class="col">
                                    <img src="{{ asset('argon') }}/img/icons/cards/bitcoin.png" alt="Image placeholder" />
                                </div>
                                <div class="col-auto">
                                    <span class="badge badge-lg badge-success">Active</span>
                                </div>
                            </div>
                            <div class="my-4">
                                <span class="h6 surtitle text-light">
                            Username
                            </span>
                                <div class="h1 text-white">@johnsnow</div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <span class="h6 surtitle text-light">Name</span>
                                    <span class="d-block h3 text-white">John Snow</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>--}}
            </div>
        </div>
        {{--<div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Page visits</h3>
                            </div>
                            <div class="col text-right">
                                <a href="#!" class="btn btn-sm btn-primary">See all</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Page name</th>
                                    <th scope="col">Visitors</th>
                                    <th scope="col">Unique users</th>
                                    <th scope="col">Bounce rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        /argon/
                                    </th>
                                    <td>
                                        4,569
                                    </td>
                                    <td>
                                        340
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-up text-success mr-3"></i> 46,53%
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        /argon/index.html
                                    </th>
                                    <td>
                                        3,985
                                    </td>
                                    <td>
                                        319
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-down text-warning mr-3"></i> 46,53%
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        /argon/charts.html
                                    </th>
                                    <td>
                                        3,513
                                    </td>
                                    <td>
                                        294
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-down text-warning mr-3"></i> 36,49%
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        /argon/tables.html
                                    </th>
                                    <td>
                                        2,050
                                    </td>
                                    <td>
                                        147
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-up text-success mr-3"></i> 50,87%
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        /argon/profile.html
                                    </th>
                                    <td>
                                        1,795
                                    </td>
                                    <td>
                                        190
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-down text-danger mr-3"></i> 46,53%
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Social traffic</h3>
                            </div>
                            <div class="col text-right">
                                <a href="#!" class="btn btn-sm btn-primary">See all</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Referral</th>
                                    <th scope="col">Visitors</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        Facebook
                                    </th>
                                    <td>
                                        1,480
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">60%</span>
                                            <div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-danger" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                                        style="width: 60%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        Facebook
                                    </th>
                                    <td>
                                        5,480
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">70%</span>
                                            <div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"
                                                        style="width: 70%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        Google
                                    </th>
                                    <td>
                                        4,807
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">80%</span>
                                            <div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-primary" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"
                                                        style="width: 80%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        Instagram
                                    </th>
                                    <td>
                                        3,678
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">75%</span>
                                            <div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        twitter
                                    </th>
                                    <td>
                                        2,645
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">30%</span>
                                            <div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-warning" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"
                                                        style="width: 30%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>--}}
        <!-- Footer -->
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>

    <script>

            //Data for charts
            $revenueByYear = @json($revenueByYear);
            //console.log($revenueByYear);
            $elearningByYear = @json($elearningByYear);
            $alumniByYear = @json($alumniByYear);
            $elearningByEvent = @json($elearningByEvent);
            let pieChart1;
            let pieChart2;
            let start_date = null;
            let end_date = null;

            let revenueByDate = null
            let elearningByDate = null
            let alumniByDate = null
            let elearningByEventDate = null
            let ticketNameByDate = null

            $ticketNameByYear = @json($ticketName);


            var $ticketNameChart = $('#ticket-type-pie');
            var $ticketSalesChart = $('#ticket-sales-pie');

            // Init pie chart
            function initTicketChart($ticketNameChart, $ticketNameByYear) {

                const counts = {};
                const sampleArray = $ticketNameByYear;
                sampleArray.forEach(function (x) { counts[x.name] = (counts[x.name] || 0) + 1; });

                let labels = []
                let data = []

                $.each(counts, function(key,value) {
                    labels.push(key)
                    data.push(value)
                })

                //log(labels)
                //console.log(data)

                if(start_date == null && end_date == null){
                    console.log('new CHART')
                    pieChart2 = new Chart($ticketNameChart, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: data,
                                backgroundColor: [
                                    'rgb(255, 99, 10)',
                                    'rgb(54, 30, 20)',
                                    'rgb(255, 205, 40)',
                                    'rgb(50, 99, 132)',
                                    'rgb(54, 60, 235)',
                                    'rgb(255, 70, 86)',
                                    'rgb(255, 80, 132)',
                                    'rgb(54, 90, 235)',
                                    'rgb(255, 205, 100)',
                                    'rgb(255, 99, 110)',
                                    'rgb(54, 130, 235)',
                                    'rgb(120, 205, 86)'
                                ],
                                label: 'Dataset 1'
                            }],
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: 'top',
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true
                            },
                            maintainAspectRatio: false
                        }
                    });

                    // Save to jQuery object
                    $ticketNameChart.data('chart', pieChart2);
                }else{
                    //UPDATE CHART
                    pieChart2.data.labels = labels
                    pieChart2.data.datasets[0].data = data
                    pieChart2.update();
                }


            }

            function initTicketByEvent(ticketNameChart, $elearningByEvent, $event_id){
                let tickets = []
                let labels = []
                let sum = 0
                $.each($elearningByEvent, function(key, value) {
                    //console.log(value)
                    if($event_id != '' && key == $event_id){
                        $.each(value, function(key1, value1) {
                            //console.log(value1)
                            if(tickets[value1.ticketName] !== undefined ){
                                tickets[value1.ticketName] = tickets[value1.ticketName]+1;
                            }else{
                                tickets[value1.ticketName] = sum;
                            }
                        })
                        labels = Object.keys(tickets)
                        data = Object.values(tickets)
                        // console.log(labels)
                        // console.log(data)
                    }
                })

                if(labels.length == 0){
                    labels = ['null']
                    data = [0]
                }
                pieChart2.data.labels = labels
                pieChart2.data.datasets[0].data = data
                pieChart2.update();

            }

            function initTicketSalesChart($ticketSalesChart, $ticketNameByYear) {
                console.log('from function')
                let arr = [];
                let labels1 = [];
                let data1 = [];
                console.log($ticketNameByYear)
                $.each($ticketNameByYear, function(key, value) {
                    if(arr[value.name] !== undefined){
                        arr[value.name] = arr[value.name] + parseInt(value.amount)
                    }else{
                        arr[value.name] = parseInt(value.amount)
                    }
                })


                if(start_date == null & end_date == null){
                    pieChart1 = new Chart($ticketSalesChart, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(arr),
                            datasets: [{
                                data: Object.values(arr),
                                backgroundColor: [
                                    'rgb(255, 99, 10)',
                                    'rgb(54, 30, 20)',
                                    'rgb(255, 205, 40)',
                                    'rgb(50, 99, 132)',
                                    'rgb(54, 60, 235)',
                                    'rgb(255, 70, 86)',
                                    'rgb(255, 80, 132)',
                                    'rgb(54, 90, 235)',
                                    'rgb(255, 205, 100)',
                                    'rgb(255, 99, 110)',
                                    'rgb(54, 130, 235)',
                                    'rgb(120, 205, 86)'
                                ],
                                label: 'Dataset 1'
                            }],
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: 'top',
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true
                            },
                            maintainAspectRatio: false
                        }
                    });

                    // Save to jQuery object
                    $ticketSalesChart.data('chart', pieChart1);
                }else{
                    console.log('from update')
                    //UPDATE CHART
                    console.log(Object.keys(arr))
                    console.log(Object.values(arr))
                    pieChart1.data.labels = Object.keys(arr)
                    pieChart1.data.datasets[0].data = Object.values(arr)
                    pieChart1.update();
                }


            }

            function initTicketSalesByEvent(ticketSalesChart, data, $event_id){
                //console.log('ticket')
                //console.log(data)
                let tickets = []
                let labels = []


                $.each(data, function(key, value) {
                    //console.log(value)
                    if(key == $event_id){
                        $.each(value, function(key1, value1) {
                            if(tickets[value1.ticketName] !== undefined){
                                tickets[value1.ticketName] = tickets[value1.ticketName] + parseInt(value1.amount)
                            }else{
                                tickets[value1.ticketName] = parseInt(value1.amount);
                            }
                        })

                        labels = Object.keys(tickets)
                        data = Object.values(tickets)
                    }
                })

                pieChart1.data.labels = labels
                pieChart1.data.datasets[0].data = data
                pieChart1.update();
            }



            if ($ticketNameChart.length) {
                initTicketChart($ticketNameChart, $ticketNameByYear);
            }
            if ($ticketSalesChart.length) {
                initTicketSalesChart($ticketSalesChart, $ticketNameByYear);
            }





            //
            // Variables
            //

            var $chart = $('#chart-bars1');
            var $elearningChart = $('#elearning-bars');
            var $alumniChart = $('#alumni-bars');
            let ordersChart;
            let ordersChart1;
            let ordersChart2;




            //
            // Methods
            //

            function calculate_labels(arr){
                data = []
                new_labels = []
                labels = {1:'Jan',2:'Feb',3:'Mar',4:'Apr',5:'May',6:'Jun',7:'Jul',8:'Aug', 9:'Sep', 10:'Oct', 11:'Nov', 12:'Dec'}
                //console.log(arr)
                let count = 0;
                $.each(arr, function(key, value) {
                    //console.log(key)
                    if(value !== undefined){
                        new_labels.push(labels[key])
                    }else{
                        if(key != 0){
                            arr[new_labels.length] = 0
                        }

                        new_labels[new_labels.length] = labels[new_labels.length]

                        if(key != count){
                            arr[count] = 0
                            new_labels[count] = labels[count]
                        }
                    }
                    count++
                })

                let count1 = 1;
                for(count1; count1<=12;count1++){
                    if(new_labels[count1] === undefined){
                        new_labels[count1] = 0
                        new_labels[count1] = labels[count1]
                        arr[count1] = 0
                    }
                }


                console.log(arr)
                data['labels'] = new_labels
                data['sum'] = arr
               // delete labels[0]
                //console.log(new_labels)
                return data
            }

            // Init chart
            function initChart($chart, $revenueByYear) {
                //console.log($revenueByYear)

                let months = []

                $.each($revenueByYear, function(key, value) {
                    $.each(value, function(key1, value1) {
                        let num_mon = parseInt(value1.month)

                        if(months[num_mon] !== undefined){
                            months[num_mon] = months[num_mon] + parseInt(value1.amount)

                        }else{
                            months[num_mon] = parseInt(value1.amount)
                        }

                    })
                    //months.push(amount)
                })


                data = calculate_labels(months)
                // console.log('-------////')
                // console.log(months)
                // console.log(labels)


                if(start_date == null && end_date == null){
                    // Create chart
                    ordersChart = new Chart($chart, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Income',
                                data: data.sum,
                                backgroundColor: 'rgb(255, 99, 10)',
                            }]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: 'top',
                            },
                            maintainAspectRatio: false
                        }
                    });

                    // Save to jQuery object
                    $chart.data('chart', ordersChart);
                }else{
                    //Update chart
                    ordersChart.data.datasets[0].data = months
                    ordersChart.update();
                }


            }

            function initChartByEvent(chart, $revenueByYear, $event_id){
                let months = []

                $.each($revenueByYear, function(key, value) {
                    let sum = 0;

                    $.each(value, function(key1, value1) {
                        if(value1.event_id == $event_id){
                            sum = sum + parseInt(value1.amount)
                        }
                    })
                    months.push(sum)
                })


                chart.data().chart.data.datasets[0].data = months
                ordersChart.update();


            }

            function initElearningChart($elearningChart, $elearningByYear){

                let months = []

                $.each($elearningByYear, function(key, value) {

                    let num = parseInt(key)
                    months[num] = value.length
                    //months.push(value.length)
                })

                //console.log(months)

                data = calculate_labels(months)

                //console.log(calculate_labels(months))

                if(start_date == null && end_date == null) {
                    // Create chart
                    ordersChart1 = new Chart($elearningChart, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Users',
                                data: data.sum,
                                backgroundColor: '#5603ad',
                            }]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: 'top',
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true
                            },
                            maintainAspectRatio: false
                        }
                    });

                    // Save to jQuery object
                    $elearningChart.data('chart', ordersChart1);
                }else{
                    //Update chart
                    ordersChart1.data.datasets[0].data = months
                    ordersChart1.update();
                }


            }

            function initElearningByEvent(elearningChart, $elearningByYear, $event_id){
                let months = []

                $.each($elearningByYear, function(key, value) {
                    let sum = 0
                    $.each(value, function(key1, value1) {
                        if($event_id == value1.event_id){
                            sum++
                        }
                    })
                    months.push(sum)
                })

                elearningChart.data().chart.data.datasets[0].data = months
                ordersChart1.update();
            }

            function initAlumniChart($alumniChart, $alumniByYear) {
                let months = []

                $.each($alumniByYear, function(key, value) {
                    //months.push(value.length)
                    let num = parseInt(key)
                    months[num] = value.length
                })



                data = calculate_labels(months)

                if(start_date == null & end_date == null){
                    // Create chart
                    ordersChart2 = new Chart($alumniChart, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Alumni Tickets',
                                data: data.sum,
                                backgroundColor: '#ffd600',
                            }]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: 'top',
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true
                            },
                            maintainAspectRatio: false
                        }
                    });

                    // Save to jQuery object
                    $alumniChart.data('chart', ordersChart2);
                }else{
                    //UPDATE CHART
                    ordersChart2.data.datasets[0].data = months
                    ordersChart2.update();
                }


            }

            function initAlumniByEvent(alumniChart, $alumniByYear, $event_id){
                let months = []

                $.each($alumniByYear, function(key, value) {
                    let sum = 0
                    $.each(value, function(key1, value1) {
                        if($event_id == value1.event_id){
                            sum++
                        }
                    })
                    months.push(sum)
                })
                alumniChart.data().chart.data.datasets[0].data = months
                ordersChart2.update();
            }


            // Init chart
            if ($chart.length) {
                initChart($chart, $revenueByYear);
            }
            if ($elearningChart.length) {
                initElearningChart($elearningChart, $elearningByYear);
            }

            if ($alumniChart.length) {
                initAlumniChart($alumniChart, $alumniByYear);
            }


        $(function() {



            $("#ticket-num-filter-by-event").change(function() {
                //console.log('triggered')
                let event_id = $(this).val()
                if(event_id != ''){

                    //console.log('start:'+start_date)
                    //console.log('end:'+end_date)

                    if(start_date == null && end_date == null){
                        //console.log($elearningByEvent)
                        initTicketByEvent($ticketNameChart, $elearningByEvent, event_id)
                    }else{
                        //console.log('between dates')
                        //console.log(elearningByEventDate)
                        initTicketByEvent($ticketNameChart, elearningByDate, event_id)
                    }

                }else{
                    //destroy
                    pieChart2.destroy();


                    //create new chart
                    if(start_date == null && end_date == null){
                        initTicketChart($ticketNameChart, $ticketNameByDate)
                    }else{
                        //console.log('between dates')
                        initTicketChart($ticketNameChart, ticketNameByDate)
                    }



                }
            })


            $("#ticket-filter-by-event").change(function() {
                console.log('sum')
                let event_id = $(this).val()
                if(event_id != ''){


                    if(start_date == null && end_date == null){
                        console.log('here')
                        initTicketSalesByEvent($ticketSalesChart, $elearningByEvent, event_id)
                    }else{
                        console.log('between dates by event')
                        //PROBLEM
                        initTicketSalesByEvent($ticketSalesChart, elearningByEventDate, event_id)
                    }

                }else{
                    //destroy
                    pieChart1.destroy();
                    //create new chart

//EDWWWWW
                    if(start_date == null && end_date == null){
                        initTicketSalesChart($ticketSalesChart, $elearningByEvent)
                    }else{
                        console.log('between dates//')
                        initTicketSalesChart($ticketSalesChart, elearningByDate)
                    }


                }
            })

            $("#alumni-filter-by-event").change(function() {
                let event_id = $(this).val()
                if(event_id != ''){

                    if(start_date == null && end_date == null){
                        initAlumniByEvent($alumniChart,$alumniByYear,event_id)
                    }else{
                        //console.log('between dates')
                        initAlumniByEvent($alumniChart,alumniByDate,event_id)
                    }

                }else{
                    //destroy
                    ordersChart2.destroy();

                    //create new chart
                    if(start_date == null && end_date == null){
                        initAlumniChart($alumniChart, $alumniByYear)
                    }else{
                        initAlumniChart($alumniChart, alumniByDate)
                    }



                }
            })


            $("#elearning-filter-by-event").change(function() {
                let event_id = $(this).val()
                if(event_id != ''){

                    if(start_date == null && end_date == null){
                        initElearningByEvent($elearningChart,$elearningByYear,event_id)
                    }else{
                        initElearningByEvent($elearningChart, elearningByDate,event_id)
                    }

                }else{
                    //destroy
                    ordersChart1.destroy();
                    //create new chart
                    if(start_date == null & end_date == null){
                        initElearningChart($elearningChart, $elearningByYear)
                    }else{
                        initElearningChart($elearningChart, elearningByDate)
                    }


                }
            })


            $( "#revenue-filter-by-event" ).change(function() {
                let event_id = $(this).val()
                if(event_id != ''){

                    if(start_date == null && end_date == null) {
                        initChartByEvent($chart, $revenueByYear, event_id)
                    }else{
                        initChartByEvent($chart, revenueByDate, event_id)
                    }

                }else{
                    //destroy
                    ordersChart.destroy();
                    //create new chart
                    if(start_date == null && end_date == null){
                        initChart($chart, $revenueByYear)
                    }else{
                        initChart($chart, revenueByDate)
                    }


                }


            });


            $("#start_date").change(function(e){
                start_date = e.target.value

                //calculate_charts_by_selected_dates()

            });

            $("#end_date").change(function(e){
                end_date = e.target.value

                calculate_charts_by_selected_dates()

            });

            function calculate_charts_by_selected_dates(){
                //console.log('start_date:'+ start_date)
               // console.log('end_date:' + end_date)

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '/admin/home/fetchDashboardData',
                    data: {'start': start_date, 'end':end_date},
                    success: function (data) {
                        let data1 = data.data
                        if(data1){
                            revenueByDate = data1.revenueByDate
                            elearningByDate = data1.elearningByDate
                            alumniByDate = data1.alumniByDate
                            elearningByEventDate = data1.elearningByEventDate
                            ticketNameByDate = data1.ticketNameDate
                        }

                        initTicketChart($ticketNameChart, ticketNameByDate)
                        initAlumniChart($alumniChart, alumniByDate)
                        initElearningChart($elearningChart, elearningByDate)
                        initChart($chart, revenueByDate)
                        initTicketSalesChart($ticketSalesChart, ticketNameByDate)


                    }
                });

            }

        });





    </script>
@endpush
