@extends('layouts.app', [
    'title' => __('User Management'),
    'parentSection' => 'laravel',
    'elementName' => 'user-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Examples') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('topics.index') }}">{{ __('Edit Ticket Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Event') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Event Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('topics.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('user.store_ticket', $events) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Ticket information') }}</h6>
                            <div class="pl-lg-4">
                            <?php //dd($events); ?>

                                <div class="form-group{{ $errors->has('ticket_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-ticket_id">{{ __('Ticket') }}</label>
                                    <select name="ticket_id" id="input-ticket_id" class="form-control" placeholder="{{ __('Ticket') }}" required>
                                        <option value="">-</option>
                                        @foreach ($events->ticket as $ticket)
                                            <option <?php if(count($events->ticket) != 0){
                                                // if($ticket->category[0]->id == $category->id){
                                                //     echo 'selected';
                                                // }else{
                                                //     echo '';
                                                // }
                                            }
                                            ?>
                                            value="{{ $ticket->id }}" >{{ $ticket->title }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'category_id'])
                                </div>




                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

