<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Model\Coupon;
use App\Model\Event;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    private function getEvents()
    {
        $events = Event::where('published', true)->where('status', 0)->get();

        return $events;
    }

    public function index()
    {
        $data['coupons'] = Coupon::all();

        return view('admin/coupon/coupons_list', $data);
    }

    public function create()
    {
        $coupon = new Coupon;

        $data['events'] = $this->getEvents();
        $data['coupon'] = $coupon;
        $data['event_coupons'] = $coupon->event->pluck('id')->toArray();

        return view('admin/coupon/create', $data);
    }

    public function store(Request $request)
    {
        $coupon = new Coupon;

        $coupon->code_coupon = $request->name;
        $coupon->price = $request->price;
        $coupon->percentage = $request->percentage;
        $coupon->status = $request->published == 'on' ? true : false;
        $coupon->save();

        if ($request->events) {
            foreach ($request->events as $event) {
                $coupon->event()->attach($event);
            }
        }

        return redirect('/admin/edit/coupon/' . $coupon->id);
    }

    public function edit(Coupon $coupon)
    {
        $data['events'] = $this->getEvents();
        $data['coupon'] = $coupon;
        $data['event_coupons'] = $coupon->event->pluck('id')->toArray();

        return view('admin/coupon/create', $data);
    }

    public function update(Request $request, Coupon $coupon)
    {
        if ($coupon) {
            $coupon->code_coupon = $request->name;

            $coupon->price = $request->price;
            $coupon->status = $request->published == 'on' ? true : false;
            $coupon->percentage = $request->percentage;

            $coupon->save();
            $coupon->event()->detach();

            if ($request->events) {
                foreach ($request->events as $event) {
                    $coupon->event()->attach($event);
                }
            }

            return redirect('admin/edit/coupon/' . $coupon->id);
        }
    }

    public function fetchAllCoupons()
    {
        $coupons = Coupon::select('code_coupon')->get()->toArray();

        return $coupons;
    }
}
