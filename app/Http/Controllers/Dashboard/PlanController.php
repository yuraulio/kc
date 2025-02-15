<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Event;
use App\Model\PaymentMethod;
use App\Model\Plan as MyPlan;
use Illuminate\Http\Request;
use Stripe\Plan;
use Stripe\Stripe;
use Stripe\StripeClient;

class PlanController extends Controller
{
    private function getEvents($view_tpl = null)
    {
        if (!$view_tpl) {
            $events = Event::where('published', true)->get();
        } else {
            $events = Event::where('view_tpl', $view_tpl)->where('published', true)->get();
        }

        return $events;
    }

    private function getCategories()
    {
        $categories = Category::has('events')->get();

        return $categories;
    }

    public function index()
    {
        //dd('fdsa');

        $data['plans'] = MyPlan::has('events')->get();

        return view('admin/subscription/plans_list', $data);
    }

    public function create()
    {
        $plan = new MyPlan;

        $data['events'] = $this->getEvents('elearning_event');
        $data['noevents'] = $this->getEvents();
        $data['categories'] = $this->getCategories();
        $data['plan'] = $plan;
        $data['event_plans'] = $plan->events()->pluck('event_id')->toArray();
        $data['category_plans'] = $plan->categories()->pluck('category_id')->toArray();
        $data['event_noplans'] = $plan->noEvents()->pluck('event_id')->toArray();

        return view('admin/subscription/create', $data);
    }

    public function store(Request $request)
    {
        //dd($request->all());
        //$skey = config('services.stripe.secret');
        //$stripe = Stripe::make($skey);

        $paymentMethod = PaymentMethod::find(2);

        $eventsAttach = [];
        foreach ($request->events as $eventId) {
            if (($event = Event::find($eventId))) {
                if (!$event->paymentMethod->first()) {
                    continue;
                }

                $eventsAttach[] = $eventId;

                //$secretKey = config('app.PAYMENT_PRODUCTION') ? $event->paymentMethod->first()->processor_options['secret_key'] : $event->paymentMethod->first()->test_processor_options['secret_key'];
                //session()->put('payment_method',$event->paymentMethod->first()->id);

                $secretKey = config('app.PAYMENT_PRODUCTION') ? $paymentMethod->processor_options['secret_key'] : $paymentMethod->test_processor_options['secret_key'];
                session()->put('payment_method', $paymentMethod->id);

                Stripe::setApiKey($secretKey);
                $id = 'plan' . time();
                $plan = Plan::create([
                    'id'                   => $id,
                    'product' => [
                        'name'             => $request->name,
                    ],
                    'amount'               => $request->price * 100,
                    'currency'             => 'EUR',
                    'interval'             => $request->interval,
                    'interval_count'       => $request->interval_count,
                    'trial_period_days'    => $request->trial,

                ]);
            }
        }

        if ($plan) {
            $planN = new MyPlan;

            $planN->name = $request->name;
            $planN->description = $request->description;
            $planN->stripe_plan = $id;
            $planN->cost = $request->price;
            $planN->interval = $request->interval;
            $planN->trial_days = $request->trial;
            $planN->interval_count = $request->interval_count;
            $planN->published = $request->published == 'on' ? true : false;
            $planN->invoice_text = $request->invoice_text;
            $planN->save();

            if ($request->events) {
                foreach ($eventsAttach as $event) {
                    $planN->events()->attach($event);
                }
            }

            if ($request->categories) {
                foreach ($request->categories as $category) {
                    $planN->categories()->attach($category);
                }
            }

            if ($request->noevents) {
                foreach ($request->noevents as $event) {
                    $planN->noEvents()->attach($event);
                }
            }

            return redirect('/admin/edit/plan/' . $planN->id);
        }
    }

    public function edit(MyPlan $plan)
    {
        $data['events'] = $this->getEvents('elearning_event');
        $data['noevents'] = $this->getEvents();
        $data['categories'] = $this->getCategories();
        $data['plan'] = $plan;
        $data['event_plans'] = $plan->events()->pluck('event_id')->toArray();
        $data['category_plans'] = $plan->categories()->pluck('category_id')->toArray();
        $data['event_noplans'] = $plan->noEvents()->pluck('event_id')->toArray();

        return view('admin/subscription/create', $data);
    }

    public function update(Request $request, MyPlan $plan)
    {
        if ($plan) {
            $plan->name = $request->name;
            $plan->description = $request->description;
            $plan->invoice_text = $request->invoice_text;
            //$plan->cost = $request->price;
            //$plan->interval = $request->interval;
            //$plan->interval_count = $request->interval_count;
            $plan->published = $request->published == 'on' ? true : false;

            $plan->save();
            $plan->events()->detach();
            $plan->categories()->detach();
            $plan->noEvents()->detach();

            if ($request->events) {
                foreach ($request->events as $event) {
                    $plan->events()->attach($event);
                }
            }

            if ($request->categories) {
                foreach ($request->categories as $category) {
                    $plan->categories()->attach($category);
                }
            }

            if ($request->noevents) {
                foreach ($request->noevents as $event) {
                    $plan->noEvents()->attach($event);
                }
            }

            return redirect('admin/edit/plan/' . $plan->id);
        }
    }

    public function removePlan(Request $request)
    {
        //$skey = config('services.stripe.secret');
        //$stripe = Stripe::make($skey);

        $plan = DB::table('plans')->where('id', $request->id)->first();

        //dd(gettype($plan->stripe_plan));

        $plan1 = $stripe->plans()->delete($plan->stripe_plan);
        //dd($plan);
        //dd('from remove');
        if (DB::table('plans')->where('id', $request->id)->delete() && $plan1) {
            return response()->json([
                'success' => true,
                'data' => $request->id,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => $request->id,
            ]);
        }
        //dd($request->id);
    }
}
