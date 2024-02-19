@php
$service = app(\App\Services\Transactions\TransactionParticipantsService::class);
$events = $service->getEvents();
@endphp
<div class="collapse datatable-filters-form-custom" id="collapseFilters">
  <div class="container">
    <div class="row">
      <div class="col-sm-3 filter_col" id="filter_events" data-column="1">
        <label>Event</label>
        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col1_filter">
          <option selected value> -- All -- </option>
          @foreach($events as $k => $v)
          <option value="{{$k}}">{{$v}}</option>
          @endforeach
        </select>
      </div>
      <div class="col-sm-3 filter_col" id="filter_col4" data-column="4">
        <label>Coupon</label>
        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col4_filter" placeholder="Coupon">
          <option selected value> -- All -- </option>
        </select>
      </div>

      <div class="col-sm-3 filter_col" id="filter_col8" data-column="8">
        <label>Payment Method</label>
        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col8_filter" placeholder="Payment Method">
          <option selected value> -- All -- </option>
        </select>
      </div>


      <div class="col-sm-3 filter_col" id="filter_col11" data-column="11">
        <label>Delivery</label>
        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col11_filter" placeholder="Payment Method">
          <option selected value> -- All -- </option>
        </select>
      </div>

      <div class="col-sm-3 filter_col d-none" id="filter_col12" data-column="12">
        <label>City</label>
        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col12_filter" placeholder="City">
          <option selected value> -- All -- </option>
        </select>
      </div>

      <div class="col-sm-3 filter_col" id="filter_col13" data-column="13">
        <label>Category</label>
        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col13_filter" placeholder="Category">
          <option selected value> -- All -- </option>
        </select>
      </div>


      <div class="col-sm-3 filter_col">
        <div class="form-group">
          <label>From - To</label>
          <input class="select2-css" type="text" autocomplete="off" name="daterange">
        </div>
      </div>
    </div>
  </div>
</div>
