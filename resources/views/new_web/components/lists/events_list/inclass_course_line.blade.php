<?php
$pubdate = $row->launch_date ? $row->launch_date :  $row->published_at;
$chmonth = date('m', strtotime($pubdate));
$month = date('F Y', strtotime($pubdate));

$isonCart = Cart::search(function ($cartItem, $rowId) use ($row) {
    return $cartItem->id === $row->id;
});
?>

@if($chmonth != $lastmonth1)
<?php $lastmonth1 = $chmonth;?>
<div class="dynamic-learning--subtitle">
    <h2>{{$month}}</h2>
</div>
@endif
<div class="dynamic-courses-wrapper <?= (!$elern) ? 'dynamic-courses-wrapper--style2' : ''; ?>">
    <div class="item">
        <div class="left">
            <?php
            if(isset($row['slugable']['slug'])){
                $slug = $row['slugable']['slug'];
            } else {
                $slug = '';
            }
            ?>
            <?php

            $info = $row->event_info();

            ?>
            <h2><a href="{{env('NEW_PAGES_LINK') . '/' .  $slug }}">{{ $row->title}}</a></h2>
            <div class="bottom">

                @include('new_web.components.event_infos',['type' => 'inclass'])

            </div>
        </div>
        <div class="right">
            <?php
            if (isset($row['ticket']->where('type','Early Bird')->first()->pivot->price) &&
                $row['ticket']->where('type','Early Bird')->first()->pivot->price > 0 &&
                $row['ticket']->where('type','Early Bird')->first()->pivot->quantity > 0 &&
                $row['ticket']->where('type','Early Bird')->first()->pivot->active) {

                $price = $row['ticket']->where('type','Early Bird')->first()->pivot->price;

            }else if(isset($row['ticket']->where('type','Special')->first()->pivot->price) &&
                $row['ticket']->where('type','Special')->first()->pivot->price > 0 &&
                $row['ticket']->where('type','Special')->first()->pivot->quantity > 0 &&
                $row['ticket']->where('type','Special')->first()->pivot->active){

                $price = $row['ticket']->where('type','Special')->first()->pivot->price;

            }else if(isset($row['ticket']->where('type','Regular')->first()->pivot->price) &&
                $row['ticket']->where('type','Regular')->first()->pivot->price > 0 &&
                $row['ticket']->where('type','Regular')->first()->pivot->quantity > 0 &&
                $row['ticket']->where('type','Regular')->first()->pivot->active){

                $price = $row['ticket']->where('type','Regular')->first()->pivot->price;
            } else {
                $price = 0;
            }
            ?>
            <?php $etstatus = 0 ?>
            <?php //dd($row['status']); ?>
            @if(isset($row['status']))
            <?php $etstatus = $row['status']; ?>
            @endif

            @if(isset($info['payment_method']) && $info['payment_method'] == 'free')
            <div class="price">free</div>
            <a href="{{ $slug }}" class="btn btn--secondary btn--md">Enroll For Free</a>
            @elseif($row->view_tpl == 'elearning_pending')
            <div class="price">Pending</div>
            <a href="{{ $slug }}" class="btn btn--secondary btn--md">Course Details</a>
            @elseif($row['status'] == 5)
            <div class="price">Course coming soon</div>
            <a href="{{ $slug }}" class="btn btn--secondary btn--md">JOIN WAITING LIST</a>
            @else
            <div class="price">from €{{$price}}</div>
            <a href="{{ $slug }}" class="btn btn--secondary btn--md">Course Details</a>
            @endif

        </div>
    </div>
</div>
