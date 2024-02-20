@php
$service = app(\App\Services\Transactions\TransactionParticipantsService::class);
$events = $service->getEvents();
$coupons = $service->getCoupons();
@endphp
<div class="collapse " id="collapseFilters">
  <div class="container">
    <form id="{{$formId}}" action="#" method="POST">
      <div class="row">
        <div class="col-sm-3 filter_col" id="filter_event_row" data-column="1">
          <label>Event</label>
          <select name="filter[event]" id="filter_event"
                  data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."
                  class="column_filter" >
            <option selected value> -- All --</option>
            @foreach($events as $k => $v)
            <option value="{{$k}}">{{$v}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-sm-3 filter_col" id="filter_coupons_row" data-column="4">
          <label>Coupon</label>
          <select name="filter[coupons]" id="filter_coupons" data-placeholder="Select coupons" multiple="multiple"
                  data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."
                  class="column_filter">
            <option></option>
            @foreach($coupons as $v)
            <option value="{{$v}}">{{$v}}</option>
            @endforeach
          </select>
        </div>

        <div class="col-sm-3 filter_col" id="filter_col8" data-column="8">
          <label>Payment Method</label>
          <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name"
                  class="column_filter" id="col8_filter" placeholder="Payment Method">
            <option selected value> -- All --</option>
          </select>
        </div>


        <div class="col-sm-3 filter_col" id="filter_col11" data-column="11">
          <label>Delivery</label>
          <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name"
                  class="column_filter" id="col11_filter" placeholder="Delivery">
            <option selected value> -- All --</option>
          </select>
        </div>
        <div class="col-sm-3 filter_col" id="filter_pricing_row" data-column="3">
          <label>Pricing</label>
          <select id="filter_pricing" name="filter[pricing]" data-toggle="select" class="column_filter">
            <option selected value> -- All --</option>
            <option value="free">Free</option>
            <option value="paid">Paid</option>
          </select>
        </div>

        <div class="col-sm-3 filter_col d-none" id="filter_col12" data-column="12">
          <label>City</label>
          <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name"
                  class="column_filter" id="col12_filter" placeholder="City">
            <option selected value> -- All --</option>
          </select>
        </div>

        <div class="col-sm-3 filter_col" id="filter_col13" data-column="13">
          <label>Category</label>
          <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name"
                  class="column_filter" id="col13_filter" placeholder="Category">
            <option selected value> -- All --</option>
          </select>
        </div>


        <div class="col-sm-3 filter_col">
          <div class="form-group">
            <label>From - To</label>
            <input class="select2-css" type="text" autocomplete="off" name="daterange">
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
