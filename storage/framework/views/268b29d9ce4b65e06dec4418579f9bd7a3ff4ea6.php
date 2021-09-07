

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('layouts.headers.auth'); ?>
        <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__('')); ?>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Dashboard')); ?></li>
        <?php if (isset($__componentOriginalb0ed43a6eda44b84021326772a22e85ada88d5cd)): ?>
<?php $component = $__componentOriginalb0ed43a6eda44b84021326772a22e85ada88d5cd; ?>
<?php unset($__componentOriginalb0ed43a6eda44b84021326772a22e85ada88d5cd); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
        
    <?php if (isset($__componentOriginalf553482b463f6cda44d25fdb8f98d9a83d364bb5)): ?>
<?php $component = $__componentOriginalf553482b463f6cda44d25fdb8f98d9a83d364bb5; ?>
<?php unset($__componentOriginalf553482b463f6cda44d25fdb8f98d9a83d364bb5); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users', App\Model\User::class)): ?>
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
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="h3 mb-0">Revenue report</h5>
                            </div>
                            <div class="col">
                                <select id="revenue-filter-by-event" class="custom-select custom-select-sm">
                                    <option value="" selected="selected">All</option>
                                    <?php $__currentLoopData = $revenueByEvent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"><?php echo e($item[0]['event_title']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

                                <h5 class="h3 mb-0">Elearning Users report</h5>
                            </div>
                            <div class="col">
                                <select id="elearning-filter-by-event" class="custom-select custom-select-sm">
                                    <?php //dd($revenueByEvent); ?>
                                    <option value="" selected="selected">All</option>
                                    <?php $__currentLoopData = $elearningByEvent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"><?php echo e($item[0]['event_title']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

                                <h5 class="h3 mb-0">Alumni Ticket report</h5>
                            </div>
                            <div class="col">
                                <select id="alumni-filter-by-event" class="custom-select custom-select-sm">
                                    <?php //dd($revenueByEvent); ?>
                                    <option value="" selected="selected">All</option>
                                    <?php $__currentLoopData = $alumniByEvent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"><?php echo e($item[0]['event_title']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

                                <h5 class="h3 mb-0"># of tickets / ticket type</h5>
                            </div>
                            <div class="col">
                                <select id="ticket-num-filter-by-event" class="custom-select custom-select-sm">
                                    <?php //dd($revenueByEvent); ?>
                                    <option value="" selected="selected">All</option>
                                    <?php $__currentLoopData = $alumniByEvent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"><?php echo e($item[0]['event_title']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

                                <h5 class="h3 mb-0">Sales / ticket type</h5>
                            </div>
                            <div class="col">
                                <select id="ticket-filter-by-event" class="custom-select custom-select-sm">
                                    <?php //dd($revenueByEvent); ?>
                                    <option value="" selected="selected">All</option>
                                    <?php $__currentLoopData = $elearningByEvent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"><?php echo e($item[0]['event_title']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                        <?php $__currentLoopData = $booking; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($count == 10): ?>
                                        <?php break; ?>
                                        <?php endif; ?>
                                        <?php //dd($item); ?>
                                            <tr>
                                                <th><?php echo e($item['name']); ?></th>
                                                <th><?php echo e($item['event_title']); ?></th>
                                                <th>€<?php echo e(number_format($item['amount'], 2, '.', '')); ?></th>
                                            </tr>
                                            <?php $count++; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <?php echo $__env->make('layouts.footers.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users', App\Model\User::class)): ?>
<?php $__env->startPush('js'); ?>
    <script src="<?php echo e(asset('argon')); ?>/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/chart.js/dist/Chart.extension.js"></script>

    <script>

            //Data for charts
            $revenueByYear = <?php echo json_encode($revenueByYear, 15, 512) ?>;
            //console.log($revenueByYear);
            $elearningByYear = <?php echo json_encode($elearningByYear, 15, 512) ?>;
            $alumniByYear = <?php echo json_encode($alumniByYear, 15, 512) ?>;
            $elearningByEvent = <?php echo json_encode($elearningByEvent, 15, 512) ?>;
            let pieChart1;
            let pieChart2;
            let start_date = null;
            let end_date = null;

            let revenueByDate = null
            let elearningByDate = null
            let alumniByDate = null
            let elearningByEventDate = null
            let ticketNameByDate = null

            $ticketNameByYear = <?php echo json_encode($ticketName, 15, 512) ?>;


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

                if(start_date == null && end_date == null){
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
                                    'rgb(140, 70, 86)',
                                    'rgb(48, 80, 132)',
                                    'rgb(54, 90, 235)',
                                    'rgb(80, 205, 100)',
                                    'rgb(255, 99, 110)',
                                    'rgb(54, 130, 235)',
                                    'rgb(120, 205, 86)',
                                    'rgb(20, 90, 235)',
                                    'rgb(255, 100, 100)',
                                    'rgb(100, 99, 10)',
                                    'rgb(150, 130, 235)',
                                    'rgb(120, 205, 45)'
                                ],
                                label: 'Dataset 1'
                            }],
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: 'top',
                                display: true,
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
                                    'rgb(140, 70, 86)',
                                    'rgb(48, 80, 132)',
                                    'rgb(54, 90, 235)',
                                    'rgb(80, 205, 100)',
                                    'rgb(255, 99, 110)',
                                    'rgb(54, 130, 235)',
                                    'rgb(120, 205, 86)',
                                    'rgb(20, 90, 235)',
                                    'rgb(255, 100, 100)',
                                    'rgb(100, 99, 10)',
                                    'rgb(150, 130, 235)',
                                    'rgb(120, 205, 45)'
                                ],
                                label: 'Dataset 1'
                            }],
                        },
                        options: {
                            desplay:true,
                            responsive: true,
                            legend: {
                                position: 'top',
                                display: true,
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
                    //UPDATE CHART
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
                let count = 0;
                $.each(arr, function(key, value) {
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

                data['labels'] = new_labels;
                data['labels'].shift();
                data['sum'] = arr;
                data['sum'].shift();

                return data
            }

            // Init chart
            function initChart($chart, $revenueByYear) {

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
                                display: true,
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true
                            },
                            scales: {
                                y: {
                                    suggestedMin: 500,
                                    suggestedMax: 10000,
                                }
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
                        let num_mon = parseInt(value1.month)
                        if(value1.event_id == $event_id){
                            if(months[num_mon] !== undefined){
                                months[num_mon] = months[num_mon] + parseInt(value1.amount)
                            }else{
                                months[num_mon] = parseInt(value1.amount)
                            }

                        }
                    })
                    //months.push(sum)
                })
                data = calculate_labels(months)



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

                data = calculate_labels(months)

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
                                display: true,
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true
                            },

                            maintainAspectRatio: false
                        },
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    color: "rgba(0, 0, 0, 0)",
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    color: "rgba(0, 0, 0, 0)",
                                }
                            }]
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
                    let num_mon = parseInt(key)
                    $.each(value, function(key1, value1) {

                        if($event_id == value1.event_id){

                            if(months[num_mon] !== undefined){
                                months[num_mon] = months[num_mon] + 1
                            }else{
                                months[num_mon] = 0

                            }
                        }
                    })
                })
                data = calculate_labels(months)

                elearningChart.data().chart.data.datasets[0].data = data.sum
                ordersChart1.update();
            }

            function initAlumniChart($alumniChart, $alumniByYear) {
                let months = []
                let sum = 0

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
                                display: true,
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
                    //let sum = 0
                    $.each(value, function(key1, value1) {
                        let num_mon = parseInt(value1.month)
                        if($event_id == value1.event_id){
                            //sum++
                            months[num_mon]
                            if(months[num_mon] !== undefined){
                                months[num_mon]++

                            }else{
                                months[num_mon] = 0
                            }
                        }
                    })
                    //months.push(sum)
                })
                data = calculate_labels(months)
                alumniChart.data().chart.data.datasets[0].data = data.sum
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

            $( "#search-user" ).keyup(function() {
                if($("#search-user").val() != ''){
                    $('.search-list').addClass('search-list-css')
                }else{
                    $('.search-list').removeClass('search-list-css')
                }

            });





            $("#ticket-num-filter-by-event").change(function() {
                let event_id = $(this).val()
                if(event_id != ''){

                    if(start_date == null && end_date == null){
                        initTicketByEvent($ticketNameChart, $elearningByEvent, event_id)
                    }else{
                        initTicketByEvent($ticketNameChart, elearningByEventDate, event_id)
                    }

                }else{
                    //create new chart
                    if(start_date == null && end_date == null){
                        //destroy
                        pieChart2.destroy();
                        initTicketChart($ticketNameChart, $ticketNameByYear)
                    }else{
                        //console.log('between dates')
                        initTicketChart($ticketNameChart, ticketNameByDate)
                    }



                }
            })


            $("#ticket-filter-by-event").change(function() {
                let event_id = $(this).val()
                if(event_id != ''){


                    if(start_date == null && end_date == null){
                        initTicketSalesByEvent($ticketSalesChart, $elearningByEvent, event_id)
                    }else{
                        initTicketSalesByEvent($ticketSalesChart, elearningByEventDate, event_id)
                    }

                }else{
                    //destroy

                    //create new chart
                    if(start_date == null && end_date == null){
                        pieChart1.destroy();
                        initTicketSalesChart($ticketSalesChart, $ticketNameByYear)
                    }else{
                        initTicketSalesChart($ticketSalesChart, ticketNameByDate)
                    }


                }
            })

            $("#alumni-filter-by-event").change(function() {
                let event_id = $(this).val()
                if(event_id != ''){

                    if(start_date == null && end_date == null){
                        initAlumniByEvent($alumniChart,$alumniByYear,event_id)
                    }else{
                        initAlumniByEvent($alumniChart,alumniByDate,event_id)
                    }

                }else{
                    //destroy


                    //create new chart
                    if(start_date == null && end_date == null){
                        ordersChart2.destroy();
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

                    //create new chart
                    if(start_date == null & end_date == null){
                        ordersChart1.destroy();
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

                    //create new chart
                    if(start_date == null && end_date == null){
                        ordersChart.destroy();
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
                            alumniByEventDate = data1.alumniByEventDate
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
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php echo $__env->make('layouts.app', [
    'parentSection' => 'dashboards',
    'elementName' => 'dashboard'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/pages/dashboard.blade.php ENDPATH**/ ?>