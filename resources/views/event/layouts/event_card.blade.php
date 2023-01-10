<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">REGISTRATIONS</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $count['total'] }}</span>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span>PAID:{{ $count['total'] - $count['free'] }}</span>
                    <span class="ml-3">FREE:{{ $count['free'] }}</span>
                </p>
                <p class="mb-0 text-sm">
                    <span class="">All students of this course.</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">REGISTRATIONS INCOME</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $count['total'] }}</span>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <span>EARLY: {{ $income['early'] }}</span>
                    <span class="ml-3">SPECIAL: {{ $income['special'] }}</span>
                    <span class="ml-3">REGULAR: {{ $income['regular'] }}</span>
                </p>
                <p class="mb-0 text-sm">
                    <span class="">All gross income for this course.</span>
                </p>
            </div>
        </div>
    </div>


   {{--@if(!$isInclassCourse)

      <div class="col-xl-3 col-md-6">
         <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
               <div class="row">
                  <div class="col">
                     <h5 class="card-title text-uppercase text-muted mb-0">Active Customers</h5>
                     <span class="h2 font-weight-bold mb-0">{{$activeMembers}}</span>
                  </div>
                  <div class="col-auto">
                     <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                        <i class="ni ni-atom"></i>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

   @endif--}}



</div>
