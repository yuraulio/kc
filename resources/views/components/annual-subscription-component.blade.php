<div>
  @if($canBeShown)
  <div class="subscription-div">
    <div class="col12 dynamic-courses-wrapper subscription-card">
      <div class="item">
        <h2>{{ $showPlan->title }}</h2>
        <div class="bottom">
          <div class="left">
            <div class="location">
              You eligible for an annual access to our award winning e-learning course. Subscribe annually, access all updated videos and files or take exams. Stop your subscription or renew anytime you want.
            </div>
          </div>
          <div class="right subscription-button">
            @foreach($annualSubscriptionLinks as $annualSubscriptionLink)
              <a href="{{ $annualSubscriptionLink }}" class="btn btn--primary btn--lg">{{ trans('GET ANNUAL ACCESS NOW') }}</a>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
