@extends('new_admin/layouts.vertical', ["page_title"=> "Dashboard"])

@section('css')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <royalties-show :data="{{$data}}"></royalties-show>
        </div>
    </div>
@endsection

@section('script')
@endsection
