@extends('new_admin/layouts.vertical', ["page_title"=> "Dashboard"])

@section('css')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <page-edit :page-id="{{$id}}"></page-edit>
        </div>
    </div>
@endsection

@section('script')
@endsection
