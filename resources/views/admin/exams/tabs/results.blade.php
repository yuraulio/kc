<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">

        <div class="progress-wrapper">
            <div class="progress-info">
                <div class="progress-label">
                    <span>The average percentage of the exam</span>

                    <span id="avScore">{{$averageScore}}%</span>
                </div>
            </div>
            <div class="progress">
                <div id="avScoreBar" class="progress-bar bg-success" role="progressbar" aria-valuenow="60"
                     aria-valuemin="0" aria-valuemax="100" style="width: {{$averageScore}}%;"></div>
            </div>
        </div>

    </div>

    <div class="col-md-6 col-sm-6 col-xs-12">

        <div class="progress-wrapper">
            <div class="progress-info">
                <div class="progress-label">
                    <span>The average time of every participant</span>

                    <span id="avHour">{{$averageHour}}</span>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                     aria-valuemax="100" style="width: 100%;"></div>
            </div>
        </div>

    </div>
</div>
<table class="table align-items-center table-flush" id="results-table">
    <thead class="thead-light">
    <tr>
        <th scope="col">{{ __('SL NO.') }}</th>
        <th scope="col">{{ __('Name') }}</th>
        <th scope="col">{{ __('Score') }}</th>
        <th scope="col">{{ __('Percentage') }}</th>
        <th scope="col">{{ __('Start Time') }}</th>
        <th scope="col">{{ __('End Time') }}</th>
        <th scope="col">{{ __('Total Time') }}</th>
        <th scope="col">{{ __('Action') }}</th>

    </tr>
    </thead>
    <tbody id="resultsBody">
    @foreach($results as $key => $result)
    <tr>
        <td>
            {{ $key + 1 }}
        </td>
        <td>
            {{ $result['first_name'] }} {{ $result['last_name'] }}
        </td>

        <td>
            {{ $result['score'] }}
        </td>

        <td>
            {{ $result['scorePerc'] }}
        </td>

        <td>
            {{ $result['start_time'] }}
        </td>

        <td>
            {{ $result['end_time'] }}
        </td>

        <td>
            {{ $result['total_time'] }}
        </td>

        <td class="text-right">
            <div class="dropdown">
                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item" href="/admin/student-summary/{{$result['exam_id']}}/{{$result['user_id']}}"
                       target="_blank">{{ __('Show') }}</a>
                </div>
            </div>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
