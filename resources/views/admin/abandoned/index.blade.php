@extends('layouts.app', [
    'title' => __('Abandoned Management'),
    'parentSection' => 'laravel',
    'elementName' => 'abandoned-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Examples') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('abandoned.index') }}">{{ __('Abandoned Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Abandoned') }}</h3>
                                <p class="text-sm mb-0">
                                        {{ __('This is an example of Abandoned management.') }}
                                    </p>
                            </div>
                            {{--<div class="col-4 text-right">
                                <a href="{{ route('video.create') }}" class="btn btn-sm btn-primary">{{ __('Add Video') }}</a>
                            </div>--}}
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>

                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('User') }}</th>
                                    <th scope="col">{{ __('Event') }}</th>
                                    <th scope="col">{{ __('Ticket') }}</th>
                                    <th scope="col">{{ __('Qty') }}</th>
                                    <th scope="col">{{ __('Amount') }}</th>
                                    <th scope="col">{{ __('Dates') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php //dd($abcart); ?>
                                @if(isset($list))
                                    @foreach($list as $user_id => $ucart)
                                    <?php //dd($abcart); ?>
                                        @if(isset($abcart[$user_id]->user) && isset($tickets[$ucart->id]))
                                        <?php
                                            $evdate = 'No Date';
                                            if(isset($events[$ucart->options['event']]['customFields'])) {
                                                foreach ($events[$ucart->options['event']]['customFields'] as $ckey => $cvalue) {
                                                    if ($cvalue->name == 'simple_text' && $cvalue->priority == 0) {
                                                        $evdate = $cvalue->value;
                                                        break;
                                                    }
                                                }
                                            }
                                        ?>

                                        <tr>
                                            <?php //dd($events[$ucart->options['event']]['title']); ?>
                                            <td>@if($abcart[$user_id]['user']->first() != null)<a href="mailto:{{$abcart[$user_id]['user']->first()['email']}}">{{$abcart[$user_id]['user']->first()['email']}}</a><br />{{$abcart[$user_id]['user']->first()['firstname']}} {{$abcart[$user_id]['user']->first()['lastname']}}<br /><a target="_blank" href="admin/student/{{$user_id}}"><i class="fa fa-external-link"></i></a> @endif</td>
                                            <td class="text-center">{{$events[$ucart->options['event']]['title']}} <br /> {{$evdate}}<br /><a class="small" target="_blank" href="{{$events[$ucart->options['event']]->slug}}">{{$events[$ucart->options['event']]->slug}} <i class="fa fa-external-link"></i></a></td>
                                            <td class="text-center">{{$tickets[$ucart->id]->title}}</td>
                                            <td class="text-center">{{$ucart->qty}}</td>
                                            <td class="text-right">&euro;{{$ucart->qty*$ucart->price}}</td>

                                            <td class="td_categories text-right">@if(isset($abcart[$user_id]->created_at) && $abcart[$user_id]->created_at != '') C:{{$abcart[$user_id]->created_at->format('d/m/Y H:i')}} @endif <br />U:{{$abcart[$user_id]->updated_at->format('d/m/Y H:i')}}</td>
                                            {{--<td style="text-align: center;"><span class="delete_link"><a href="javascript:void(0);" class="delete_abcart" data-dp-content-id="{{$user_id}}" data-dp-content-title="{{$ucart->name}}" title="Delete"><i class="fa fa-trash"></i></span></td>--}}


                                            <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                                    <form action="{{ route('abandoned.remove', $abcart[$user_id]->identifier) }}" method="post">
                                                        @csrf
                                                        @method('post')

                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this item?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
@endpush

@push('js')
    <script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
@endpush
