@extends('new_admin/layouts.vertical', ["page_title"=> "Dashboard"])

@section('css')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <countdown-edit :countdown-id="{{$id}}"></countdown-edit>
        </div>
    </div>
@endsection

@section('script')
@endsection
