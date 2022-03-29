@extends('new_admin/layouts.vertical', ["page_title"=> "Dashboard"])

@section('css')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <template-single :template-id="{{$id}}" :mode="'edit'"></template-single>
        </div>
    </div>
@endsection

@section('script')
@endsection
