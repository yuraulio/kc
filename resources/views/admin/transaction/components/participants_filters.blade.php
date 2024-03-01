@php
$service = app(\App\Services\Transactions\TransactionParticipantsService::class);
$events = $service->getEvents(true);
$coupons = $service->getCoupons();
$paymentMethods = $service->getPaymentMethods();
$deliveries = $service->getDeliveries();
$cities = $service->getCities();
$categories = $service->getCategories();
@endphp
<div class="collapse " id="collapseFilters">
  <div class="container">
    <form id="{{$formId}}" action="#" method="POST">
      <div class="row">
        <div class="col-sm-3 filter_col" id="filter_event_row" data-column="1">
          <label for="filter_event">Event</label>
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
          <label for="filter_coupons">Coupon</label>
          <select name="filter[coupons]" id="filter_coupons" data-placeholder="Select coupons" multiple="multiple"
                  data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."
                  class="column_filter">
            <option></option>
            @foreach($coupons as $v)
            <option value="{{$v}}">{{$v}}</option>
            @endforeach
          </select>
        </div>

        <div class="col-sm-3 filter_col" id="filter_payment_method_row" data-column="8">
          <label for="filter_payment_method">Payment Method</label>
          <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."
                  id="filter_payment_method" name="filter[payment_method]"
                  class="column_filter">
            <option selected value> -- All --</option>
            @foreach($paymentMethods as $k => $v)
            <option value="{{$k}}">{{$v}}</option>
            @endforeach
          </select>
        </div>

        <div class="col-sm-3 filter_col" id="filter_delivery_row" data-column="11">
          <label for="filter_delivery">Delivery</label>
          <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."
                  id="filter_delivery" name="filter[delivery]"
                  class="column_filter">
            <option selected value> -- All --</option>
            @foreach($deliveries as $k => $v)
            <option value="{{$k}}">{{$v}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-sm-3 filter_col" id="filter_pricing_row" data-column="3">
          <label for="filter_pricing">Pricing</label>
          <select id="filter_pricing" name="filter[pricing]" data-toggle="select" class="column_filter">
            <option selected value> -- All --</option>
            <option value="free">Free</option>
            <option value="paid">Paid</option>
          </select>
        </div>
        @if($type !== 'revenues')
        <div class="col-sm-3 filter_col" id="filter_city_row" data-column="12">
          <label for="filter_city">City</label>
          <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."
                  name="filter[city]" id="filter_city"
                  class="column_filter" >
            <option selected value> -- All --</option>
            @foreach($cities as $k => $v)
            <option value="{{$k}}">{{$v}}</option>
            @endforeach
          </select>
        </div>

        <div class="col-sm-3 filter_col" id="filter_category_row" data-column="13">
          <label for="filter_category">Category</label>
          <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."
                  name="filter[category]" id="filter_category"
                  class="column_filter">
            <option selected value> -- All --</option>
            @foreach($categories as $k => $v)
            <option value="{{$k}}">{{$v}}</option>
            @endforeach
          </select>
        </div>
        @endif

        <div class="col-sm-3 filter_col" id="filter_daterange_row">
          <div class="form-group">
            <label for="filter_daterange">From - To</label>
            <input class="select2-css" type="text" autocomplete="off" id="filter_daterange" name="filter[daterange]" />
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
