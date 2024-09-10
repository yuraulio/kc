@extends('layouts.app', [
    'title' => __('Lesson Management'),
    'parentSection' => 'laravel',
    'elementName' => 'lessons-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('lessons.index') }}">{{ __('No Vimeo Link Lessons') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">

                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic31">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Id') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                </tr>
                            </thead>
                            <tbody class="lessons-order">
                                @foreach ($lessons as $lesson)

                                    <tr class="lesson-list">
                                        <td>{{$lesson->id}}</td>
                                        <td><a href="{{ config('app.url') }}/admin/lessons/{{$lesson->id}}/edit">{{$lesson->title}}</a></td>
                                    </tr>


                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection




