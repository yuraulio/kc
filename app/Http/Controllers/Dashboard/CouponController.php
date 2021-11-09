<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Event;
use App\Model\Coupon;

class CouponController extends Controller
{
    private function getEvents($view_tpl = null){

        if(!$view_tpl){
            $events =  Event::where('published',true)->get();
        }else{
            $events = Event::where('view_tpl',$view_tpl)->where('published',true)->get();
        }

        return $events;
    }


    public function index()
    {

        $data['coupons'] = Coupon::all();
        return view('admin/coupon/coupons_list', $data);
    }

    public function create(){

        $coupon = new Coupon;

        $data['events'] = $this->getEvents('elearning_event');
        $data['coupon'] = $coupon;
        $data['event_coupons'] = $coupon->event->pluck('id')->toArray();

        return view('admin/coupon/create', $data);

    }

    public function store(Request $request){
        

        $coupon = new Coupon;

        $coupon->code_coupon = $request->name;
        $coupon->price = $request->price;
        $coupon->percentage = $request->percentage;
        $coupon->status = $request->published == 'on' ? true : false;
        $coupon->save();

        if($request->events){
            foreach($request->events as $event){
                $coupon->event()->attach($event);
            }
        }

        return redirect('/admin/edit/coupon/'.$coupon->id);

    }

    public function edit(Coupon $coupon){

        $data['events'] = $this->getEvents('elearning_event');
        $data['coupon'] = $coupon;
        $data['event_coupons'] = $coupon->event->pluck('id')->toArray();

        return view('admin/coupon/create', $data);

    }

    public function update(Request $request, Coupon $coupon){

        if($coupon){

            $coupon->code_coupon = $request->name;

            $coupon->price = $request->price;
            $coupon->status = $request->published == 'on' ? true : false;
            $coupon->percentage = $request->percentage;

            $coupon->save();
            $coupon->event()->detach();

            if($request->events){
                foreach($request->events as $event){
                    $coupon->event()->attach($event);
                }
            }
            return redirect('admin/edit/coupon/'.$coupon->id);

        }
    }

    public function fetchAllCoupons(){
        $coupons = Coupon::all();

        return $coupons;
    }

}
