@extends('exams.exams-layout')



@section('content')



<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-12">

            <div class="card">

                <div class="card-header">

                    <h3>{{ $exam->exam_name }} </h3>

                </div>

                <div style="font-size: initial" class="card-body">

                  You have already given the test earlier.

                </div>

            </div>

        </div>

    </div>

</div>



@endsection
