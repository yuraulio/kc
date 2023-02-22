<div class="row">
   <div class="col-xl-3 col-md-6">
      <div class="card card-stats">
         <!-- Card body -->
         <div class="card-body">
            <div class="row">
               <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Total customers</h5>
                  <span class="h2 font-weight-bold mb-0">{{$sumOfStudents}}</span>
               </div>
               <div class="col-auto">
                  <div class="icon icon-shape bg-orange text-white rounded-circle shadow">
                     <i class="ni ni-chart-pie-35"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-xl-3 col-md-6">
      <div class="card card-stats">
         <!-- Card body -->
         <div class="card-body">
            <div class="row">
               <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Sales</h5>
                  <span class="h2 font-weight-bold mb-0">{{$totalRevenue}}</span>
               </div>
               <div class="col-auto">
                  <div class="icon icon-shape bg-green text-white rounded-circle shadow">
                     <i class="ni ni-money-coins"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   @if(!$isInclassCourse)

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

   @endif



</div>