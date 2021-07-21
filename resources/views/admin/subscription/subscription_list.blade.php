@extends('layouts.masterv2')

{{-- Page title --}}
@section('title', 'Users')
@section('content')

<span class="admin-list-head">User List Subscription</span>

<div id="content_list_cont">
	<?php //dd($events); ?>

    <div class="content_list_tbl_cont">
		<table id="user_list" class="results content_list" data-dp-filter-scope="users" data-dp-filter-page="1">
			<thead>
				<tr>
                    <td class="th_title">Id</td>
					<td class="th_title">Student</td>
                    <td class="th_title">Plan</td>
					<td class="th_title">Event Name</td>
                    <td class="th_title">Trials Sub end at</td>
                    <td class="th_title">Sub end at</td>							<!-- <td class="th_title">Actions</td> -->
				</tr>
			</thead>
			<tbody>
			@if($subscription != 0)
				@foreach($subscription as $key => $item)
					<tr>
						<td>{{$item['id']}}</td>
						<td>{{$item['student']}}</td>
						<td>{{$item['plan_name']}}</td>
						<td>{{$item['event_name']}}</td>
						<td>{{$item['trial_ends_at']}}</td>
						<td>{{$item['ends_at']}}</td>
						
					</tr>
				@endforeach
			@else
			<tr><tr>
			@endif
			
			</tbody>
		</table>
		<div class="text-right msg">
		</div>
	</div>

	<div class="filter_paginator" data-dp-filter-scope="users"></div>
</div>
<!-- Modal -->
<div class="modal fade" id="mainModal" tabindex="-1" role="dialog" aria-labelledby="mainModal" aria-hidden="true">
    	<div class="modal-dialog" role="document">
    		<div class="modal-content"></div>
    	</div>
    </div>
@include ('flashes.flash')
<script type="text/javascript">

</script>

@stop
