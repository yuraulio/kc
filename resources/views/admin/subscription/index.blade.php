@extends('layouts.masterv2')

{{-- Page title --}}
@section('title', 'Users')
@section('content')

<span class="admin-list-head">Events Subscriptions</span>

<!-- Modal -->
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div> -->

<div id="content_list_cont">
	<?php //dd($events); ?>

    <div class="content_list_tbl_cont">
		<table id="event_list" class="results content_list" data-dp-filter-scope="users" data-dp-filter-page="1">
			<thead>
				<tr>
					<td class="th_title">Event</td>
					<td class="th_title">Subscription</td>
					<td class="th_title">Actions</td>
				</tr>
			</thead>
			<tbody>
			<td>All</td>
			<td><input type="checkbox" class="chk_subscription checkAll" name="chk_subscription" value="0" /></td>
			@foreach($events as $key => $event)
			<tr>
				<td>{{$event['title']}}</td>
				<td><input type="checkbox" class="chk_subscription" name="chk_subscription" value="{{$key}}" <?= $event['sub'] == 1 ? 'checked' : '' ?>/></td>
				<td>
					<!-- <a href="javascript:void(0)" id="save-subs" class="btn btn-info btn-sm">View/Set</button> -->
					<!-- <button type="button" id="viewPlan" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
					View/Set
					</button> -->
					<a class="btn btn-primary btn-sm" id="viewPlan" href="admin/event/{{$key}}" data-toggle="modal" data-target="#mainModal">View/Edit</a>
				</td>
			</tr>
			@endforeach
			</tbody>
		</table>
		<div class="text-right msg">
		</div>
		<div class="text-right">
			<a class="btn" href="{{ route('subscription') }}">Cancel</a>
			<a href="javascript:void(0)" id="save-subs" class="btn btn-primary">Save</button>

		</div>
	</div>

	<div class="filter_paginator" data-dp-filter-scope="users"></div>
</div>
<!-- Modal -->
<div class="modal fade" id="mainModal" tabindex="-1" role="dialog" aria-labelledby="mainModal" aria-hidden="true">
    	<div class="modal-dialog" role="document">
    		<div class="modal-content" style="width:max-content;"></div>
    	</div>
    </div>
@include ('flashes.flash')
<script type="text/javascript">

$(".checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});



$( "#save-subs" ).click(function() {
  let events = $('.chk_subscription')
  let array = []

  Array.prototype.forEach.call(events, child => {
  if (child.checked == true){
	array.push({
		id : child['value'],
		sub : 1
	});
  }else{
	array.push({
		id : child['value'],
		sub : 0
	});
  }
});

$.ajax({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
          type: 'post',
          url: '/admin/subscription/saveSubs',
          data:{'data':array},
          success: function(data) {
                if(data){
                    //console.log('save success')
					$('.msg').append(`<p style="color:green;">Successfully Saved</p>`)
                }else{
                    $('.msg').append(`<p style="color:red;>Error</p>`)
                }
                //playVi = true;

          }
        });

});



</script>

@stop
