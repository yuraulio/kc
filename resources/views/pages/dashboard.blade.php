@extends('layouts.app', [
    'parentSection' => 'dashboards',
    'elementName' => 'dashboard'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Default') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboards') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Default') }}</li>
        @endcomponent
        @include('layouts.headers.cards')
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-8">
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
            </div>
            <div class="col-xl-4">
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
                                            <th scope="col" class="sort" data-sort="id">ID</th>
                                            <th scope="col" class="sort" data-sort="user">User</th>
                                            <th scope="col" class="sort" data-sort="event">Event</th>
                                            <th scope="col">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        <?php $count = 0; ?>
                                        {{--@foreach($booking as $key => $item)
                                        @if($count == 10)
                                        <?php break; ?>
                                        @endif
                                        <?php //dd($item); ?>
                                            <tr>
                                                <th>{{ $item['id'] }}</th>
                                                <th>{{ $item['user'][0]['firstname'] }} {{ $item['user'][0]['lastname'] }}</th>
                                                <th>{{ $item['subscription'][0]['event'][0]['title'] }}</th>
                                                <th>€{{ number_format($item['amount'], 2, '.', ''); }}</th>
                                            </tr>
                                            <?php $count++; ?>
                                        @endforeach--}}


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-deck">
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
                </div>
            </div>
        </div>
        <div class="row">
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
        </div>
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
            $elearningByYear = @json($elearningByYear);
            $alumniByYear = @json($alumniByYear);
            $elearningByEvent = @json($elearningByEvent);
            let pieChart1;
            let pieChart2;

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
                        }
                    }
                });

                // Save to jQuery object
                $ticketNameChart.data('chart', pieChart2);
            }

            function initTicketByEvent(ticketNameChart, $elearningByEvent, $event_id){
                let tickets = []
                let labels = []
                let sum = 0
                $.each($elearningByEvent, function(key, value) {
                    //console.log(value)
                    if(key == $event_id){
                        $.each(value, function(key1, value1) {
                            console.log(value1)
                            if(tickets[value1.ticketName] !== undefined){
                                tickets[value1.ticketName] = tickets[value1.ticketName]+1;
                            }else{
                                tickets[value1.ticketName] = sum;
                            }
                        })
                        labels = Object.keys(tickets)
                        data = Object.values(tickets)
                    }
                })

                pieChart2.data.labels = labels
                pieChart2.data.datasets[0].data = data
                pieChart2.update();

            }

            function initTicketSalesChart($ticketSalesChart, $ticketNameByYear) {
                let arr = [];
                let labels1 = [];
                let data1 = [];
                $.each($ticketNameByYear, function(key, value) {
                    if(arr[value.name] !== undefined){
                        arr[value.name] = arr[value.name] + parseInt(value.amount)
                    }else{
                        arr[value.name] = parseInt(value.amount)
                    }
                })

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
                        }
                    }
                });

                // Save to jQuery object
                $ticketSalesChart.data('chart', pieChart1);
            }

            function initTicketSalesByEvent(ticketSalesChart, $elearningByEvent, $event_id){
                let tickets = []
                let labels = []
                $.each($elearningByEvent, function(key, value) {
                    //console.log(value)
                    if(key == $event_id){
                        $.each(value, function(key1, value1) {
                            if(tickets[value1.ticketName] !== undefined){
                                tickets[value1.ticketName] = tickets[value1.ticketName] + parseInt(value1.amount)
                            }else{
                                tickets[value1.ticketName] = 0;
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

            // Init chart
            function initChart($chart, $revenueByYear) {

                let months = []

                $.each($revenueByYear, function(key, value) {
                    let amount = 0;
                    $.each(value, function(key1, value1) {
                        //console.log(value1.amount)
                        amount = amount + parseInt(value1.amount)
                    })
                    months.push(amount)
                })



                // Create chart
                ordersChart = new Chart($chart, {
                    type: 'bar',
                    data: {
                        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Income',
                            data: months,
                            backgroundColor: 'rgb(255, 99, 10)',
                        }]
                    }
                });

                // Save to jQuery object
                $chart.data('chart', ordersChart);
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
                    months.push(value.length)
                })



                // Create chart
                ordersChart1 = new Chart($elearningChart, {
                    type: 'bar',
                    data: {
                        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Income',
                            data: months,                         
                            backgroundColor: '#5603ad',
                        }]
                    }
                });

                // Save to jQuery object
                $elearningChart.data('chart', ordersChart1);
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
                    months.push(value.length)
                })

                // Create chart
                ordersChart2 = new Chart($alumniChart, {
                    type: 'bar',
                    data: {
                        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Income',
                            data: months,
                            backgroundColor: '#ffd600',                         
                        }]
                    }
                });

                // Save to jQuery object
                $alumniChart.data('chart', ordersChart2);
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
                let event_id = $(this).val()
                if(event_id != ''){
                    initTicketByEvent($ticketNameChart, $elearningByEvent, event_id)
                }else{
                    //destroy
                    pieChart2.destroy();
                    //create new chart
                    initTicketChart($ticketNameChart, $ticketNameByYear)

                }
            })


            $("#ticket-filter-by-event").change(function() {
                let event_id = $(this).val()
                if(event_id != ''){
                    initTicketSalesByEvent($ticketSalesChart, $elearningByEvent, event_id)
                }else{
                    //destroy
                    pieChart1.destroy();
                    //create new chart
                    initTicketSalesChart($ticketSalesChart, $ticketNameByYear)

                }
            })

            $("#alumni-filter-by-event").change(function() {
                let event_id = $(this).val()
                if(event_id != ''){
                    initAlumniByEvent($alumniChart,$alumniByYear,event_id)
                }else{
                    //destroy
                    ordersChart2.destroy();
                    //create new chart
                    initAlumniChart($alumniChart, $alumniByYear)

                }
            })


            $("#elearning-filter-by-event").change(function() {
                let event_id = $(this).val()
                if(event_id != ''){
                    initElearningByEvent($elearningChart,$elearningByYear,event_id)
                }else{
                    //destroy
                    ordersChart1.destroy();
                    //create new chart
                    initElearningChart($elearningChart, $elearningByYear)

                }
            })


            $( "#revenue-filter-by-event" ).change(function() {
                let event_id = $(this).val()
                if(event_id != ''){
                    initChartByEvent($chart, $revenueByYear, event_id)
                }else{
                    //destroy
                    ordersChart.destroy();
                    //create new chart
                    initChart($chart, $revenueByYear)

                }


            });

        });



    </script>
@endpush
