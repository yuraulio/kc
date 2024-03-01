<div id="extramenu-elarning" class='col-lg-8'>

     <div id="nav-anchor"></div>
     <div id="vtab"  class="tabs ">

       <ul class="nav nav-tabs ">
         <li class="active"><a title="About" data-toggle="tab" href="#overview">Overview</a></li>
         @if($estatus == App\Model\Event::STATUS_OPEN || $estatus == App\Model\Event::STATUS_SOLDOUT)
         <li><a title="Benefit" data-toggle="tab" href="#section-benefits">Benefits</a></li>
           <li><a title="Instructors" data-toggle="tab" href="#section-instructors">Instructors</a></li>
           <li><a title="Testimonials" data-toggle="tab" href="#section-testimonials">Testimonials</a></li>
           <li><a title="FAQ" data-toggle="tab" href="#section-qnas">FAQ</a></li>
         @endif
       </ul>

      </div>

</div>
