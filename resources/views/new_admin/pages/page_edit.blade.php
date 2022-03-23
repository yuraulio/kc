@extends('new_admin/layouts.vertical', ["page_title"=> "Dashboard"])

@section('css')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <page-edit-simple :id="{{$id}}"></page-edit-simple>
        </div>
    </div>
@endsection

@section('script')
@endsection
